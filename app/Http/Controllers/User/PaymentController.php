<?php

namespace App\Http\Controllers\User;

use Str;
use Auth;


use Cart;
use Mail;
use Session;
use Redirect;
use Exception;
use Carbon\Carbon;
use App\Models\Order;
use Razorpay\Api\Api;
use App\Models\Product;
use App\Models\Service;
use App\Models\Setting;
use App\Models\Language;
use App\Models\Schedule;


use App\Models\OrderItem;
Use Stripe;
use App\Helpers\MailHelper;
use App\Models\BankPayment;
use App\Models\Flutterwave;
use Illuminate\Http\Request;
use App\Models\EmailTemplate;
use App\Models\PaypalPayment;

use App\Models\StripePayment;


use App\Mail\OrderSuccessfully;
use App\Models\BreadcrumbImage;
use App\Models\PaymongoPayment;
use App\Models\RazorpayPayment;
use App\Models\InstamojoPayment;
use App\Models\AdditionalService;
use App\Models\PaystackAndMollie;
use Illuminate\Support\Facades\DB;
use Mollie\Laravel\Facades\Mollie;
use App\Models\AppointmentSchedule;
use App\Http\Controllers\Controller;

class PaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:web');
    }

    public function translator(){
        $front_lang = Session::get('front_lang');
        $language = Language::where('is_default', 'Yes')->first();
        if($front_lang == ''){
            $front_lang = Session::put('front_lang', $language->lang_code);
        }
        config(['app.locale' => $front_lang]);
    }


    public function bankPayment(Request $request){
        $this->translator();
        if(env('APP_MODE') == 'DEMO'){
            $notification = trans('user_validation.This Is Demo Version. You Can Not Change Anything');
            $notification=array('messege'=>$notification,'alert-type'=>'error');
            return redirect()->back()->with($notification);
        }

        $rules = [
            'tnx_info'=>'required',
        ];
        $customMessages = [
            'tnx_info.required' => trans('user_validation.Transaction is required'),
        ];
        $this->validate($request, $rules,$customMessages);

        $user=Auth::guard('web')->user();
                
        $order= new Order();
        $order->order_id=mt_rand(100000,999999);
        $order->user_id=$user->id;
        $order->name=$user->name;
        $order->email=$user->email;
        $order->phone=$user->phone;
        $order->total_amount=$request->total_amount;
        $order->payment_method='bank_acount';
        $order->payment_status='pending';
        $order->transection_id=$request->tnx_info;
        $order->order_status=0;
        $order->order_date=Carbon::now()->format('Y-m-d');
        $order->order_month=Carbon::now()->format('m');
        $order->order_year=Carbon::now()->format('Y');
        $order->cart_qty=$request->cart_qty;
        $order->save();
        $carts=Cart::content();
        

        foreach($carts as $cart){
            $product=Product::where('id', $cart->id)->first();
            $orderItem = new OrderItem();
            $orderItem->order_id=$order->id;
            $orderItem->product_id=$cart->id;
            $orderItem->author_id=$product->author_id;
            $orderItem->user_id=$user->id;
            $orderItem->product_type=$cart->options->product_type;
            $orderItem->price_type=$cart->options->price_type;
            $orderItem->variant_id=$cart->options->variant_id;
            $orderItem->variant_name=$cart->options->variant_name;
            $orderItem->price=$cart->price;
            $orderItem->qty=$cart->qty;
            $orderItem->save();
        }
        // $this->sendMailToUser($user, $order);
        Cart::destroy();
        $notification = trans('user_validation.Your order has been submited, wait for admin approval');
        $notification = array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->route('payment-success')->with($notification);
    }



}

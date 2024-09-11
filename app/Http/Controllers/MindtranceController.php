<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MindtranceController extends Controller
{
    public function checkout(Request $request)
    {
        // dd($request->all());
        $id_user = Auth::user()->id;

        $user = User::where('id', $id_user)->get();


        $orders = new Order;
        $orders->total_amount = $request->total_amount;
        $orders->cart_qty = $request->cart_qty;
        $orders->user_id = $id_user;
        $orders->name = Auth::user()->name;
        $orders->email = Auth::user()->email;
        $orders->phone = Auth::user()->phone;
        $orders->payment_status = "unpaid";
        $orders->save();

        
        // Set your Merchant Server Key
        \Midtrans\Config::$serverKey = config('midtrance.server_key');
        // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
        \Midtrans\Config::$isProduction = false;
        // Set sanitization on (default)
        \Midtrans\Config::$isSanitized = true;
        // Set 3DS transaction for credit card to true
        \Midtrans\Config::$is3ds = true;

        $params = array(
            'transaction_details' => array(
                'order_id' => $orders->id,
                'gross_amount' => $orders->total_amount,
            ),
        );
        
        foreach ($user as $u) {
            $params['customer_details'][] = array(
                'name' => $u->name,
                'email' => $u->email,
                'phone' => $u->phone,
            );
        }
        
        $order_id = $orders->id;
            // dd($order_id);
        // session(['order_id' => $orders->id]);

        $snapToken = \Midtrans\Snap::getSnapToken($params);

        // dd($snapToken);
        return view('paymid',compact('snapToken','order_id'));
    }

    public function callback(Request $request,$order_id){
        $order = Order::find($order_id);
        // dd($order_id);
        $order->payment_status = "paid";
        $order->update();
        $serverKey = config('midtrance.server_key');
        $hased = hash('sha512',$request->order_id.$request->status_code.$request->gross_amout.$serverKey);
        if ($hased == $request->signature_key){
            if($request->transaction_status == 'capture'){
                $order = Order::find($request->order_id);
                $order->update(['payment_status' => 'paid']);
            }
        }
    return redirect()->route('payment-success');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';

    // Specify the fields that are mass assignable
    protected $fillable = [
        'order_id',
        'user_id',
        'name',
        'email',
        'phone',
        'total_amount',
        'payment_method',
        'payment_status',
        'transection_id',
        'currency_icon',
        'country_code',
        'currency_code',
        'currency_rate',
        'order_status',
        'order_approval_date',
        'order_date',
        'order_month',
        'order_year',
        'cart_qty',
        'created_at',
        'updated_at'
    ];


    public function client(){
        return $this->belongsTo(User::class,'client_id')->select('id','name','email','image','phone','address');
    }

    public function provider(){
        return $this->belongsTo(User::class,'provider_id')->select('id','name','email','image','phone','address','designation','is_provider','user_name');
    }

    public function service(){
        return $this->belongsTo(Service::class,'service_id');
    }
    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }

    public function orderItems(){
        return $this->hasMany(OrderItem::class);
    }

    public function refundRequest(){
        return $this->hasOne(RefundRequest::class);
    }

    public function completeRequest(){
        return $this->hasOne(CompleteRequest::class);
    }




}


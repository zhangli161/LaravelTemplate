<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WeiXinXPay extends Model
{
	protected $table="wx_xcx_pays";
	protected $fillable=["nonce_str","sign","out_trade_no","total_fee","prepay_id"];
	public function order(){
		return$this->belongsTo(Order::class,"order_id");
	}
}

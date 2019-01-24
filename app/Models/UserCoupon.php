<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserCoupon extends Model
{
	use SoftDeletes;
	protected $fillable = ['user_id', 'coupon_id', 'expiry_date',"get_way","get_way_id"];
	
	public function user()
	{
		return $this->belongsTo(User::class, 'user_id');
	}
	
	public function coupon()
	{
		return $this->belongsTo(Coupon::class, 'coupon_id');
	}
}

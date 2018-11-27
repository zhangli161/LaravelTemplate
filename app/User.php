<?php

namespace App;

use App\Models\Cart;
use App\Models\Favorite;
use App\Models\Message;
use App\Models\UserAddress;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable,HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
	    'latest_login_time','name', 'email', 'password','openid'
    ];

//    /**
//     * The attributes that should be hidden for arrays.
//     *
//     * @var array
//     */
    protected $hidden = [
        'password', 'remember_token',
    ];
	
	public function addresses()
	{
		return $this->hasMany(UserAddress::class);
	}
	
	public function messages()
	{
		return $this->hasMany(Message::class,'to_user_id','id');
	}
	
	public function carts(){
		return $this->hasMany(Cart::class,'user_id','id');
	}
	public function favorites(){
		return $this->morphMany(Favorite::class,'item');
	}
}

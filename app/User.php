<?php

namespace App;

use App\Models\Agent;
use App\Models\Cart;
use App\Models\Favorite;
use App\Models\FootPrint;
use App\Models\Message;
use App\Models\Order;
use App\Models\UserAddress;
use App\Models\UserCoupon;
use App\Models\UserCredit;
use App\Models\UserCreditRecord;
use App\Models\UserWX;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'latest_login_time', 'name', 'email', 'password', 'openid'
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
        return $this->hasMany(Message::class, 'to_user_id', 'id');
    }


    public function carts()
    {
        return $this->hasMany(Cart::class, 'user_id', 'id');
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class, 'user_id');
    }

    public function credit()
    {
        return $this->hasOne(UserCredit::class, 'user_id')->withDefault(["credit" => 0.00]);
    }

    public function credit_records()
    {
        return $this->hasMany(UserCreditRecord::class, 'user_id');
    }

    public function coupons()
    {
        return $this->hasMany(UserCoupon::class, 'user_id');
    }

    public function foot_prints()
    {
        return $this->hasMany(FootPrint::class, 'user_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'user_id');
    }

    public function agent()
    {
        return $this->belongsTo(Agent::class, "agent_id");
    }

    public function WX()
    {
        return $this->hasOne(UserWX::class, 'user_id');
    }

    public function findForPassport($username)
    {
        return $this->where('id', $username)->first();
    }
}

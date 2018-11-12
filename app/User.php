<?php

namespace App;

use App\Models\Message;
use App\Models\User_Address;
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
        'name', 'email', 'password','openid'
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
		return $this->hasMany(User_Address::class);
	}
	
	public function messages()
	{
		return $this->hasMany(Message::class,'to_userid');
	}
}

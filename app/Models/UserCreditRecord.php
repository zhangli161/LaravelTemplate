<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class UserCreditRecord extends Model
{
	protected $fillable=['user_id','amount','balance','reason','editor','note'];
	public function user()
	{
		return $this->belongsTo(User::class, 'user_id');
	}
}

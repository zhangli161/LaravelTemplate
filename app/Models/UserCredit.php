<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserCredit extends Model
{
	protected $primaryKey='user_id';
	
	protected $fillable=['user_id','credit'];
}

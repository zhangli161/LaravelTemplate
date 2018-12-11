<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NewAdminToken extends Model
{
	use SoftDeletes;
	protected $dates = ["deleted_at"];
	protected $fillable = ['agent_apply_id', 'token'];
}

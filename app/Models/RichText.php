<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RichText extends Model
{
	public function item()
	{
		return $this->morphTo();
	}
}

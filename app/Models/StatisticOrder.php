<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StatisticOrder extends Model
{
	public function region()
	{
		return $this->belongsTo(NativePlaceRegion::class, "region_id");
	}
}

<?php
/**
 * Created by PhpStorm.
 * User: Acer
 * Date: 2018/11/21
 * Time: 9:56
 */

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\GoodsSpec;
use App\Models\GoodsSpecValue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GoodsSpecController extends Controller
{
	public function spec(Request $request)
	{
		$spec_id = $request->get('q');
		
		return GoodsSpecValue::query()->where('spec_id', $spec_id)->get(['id', DB::raw('value as text')]);
	}
	
}
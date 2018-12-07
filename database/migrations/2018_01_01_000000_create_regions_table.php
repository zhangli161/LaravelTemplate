<?php

use Cblink\Region\Region;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Lybc\PhpGB2260\GB2260;

class CreateRegionsTable extends Migration
{
	
	public function up()
	{
		Schema::create('native_place_region', function (Blueprint $table) {
			$table->integer('region_id', false, true)->comment("地区编码");
			$table->integer('parentid', false, true)->comment("父地区编码");
			$table->string('region_name', 255)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->comment("地区名称");
			$table->tinyInteger('have_children', false, true)->comment("是否有子地区");
			$table->integer('order', false, true)->comment("排序");
			$table->primary('region_id');
		});
		
		$region = new Region();
		
		$provinces = $region->getRegionsWithCode();
		
		foreach ($provinces as $province) {
			$provinceId = $province['ad_code'];
			DB::table('native_place_region')->insertGetId([
				'region_id' => $province['ad_code'],
				'parentid' => 0,
				'region_name' => $province['title'],
				'have_children' => 3 - Region::PROVINCE,
				'order' => 0
			]);
			foreach ($province['child'] as $city) {
				$cityId = $city['ad_code'];
				DB::table('native_place_region')->insertGetId([
					'region_id' => $city['ad_code'],
					'parentid' => $provinceId,
					'region_name' => $city['title'],
					'have_children' => 3 - Region::CITY,
					'order' => 0
				]);
				$areas = array_map(function ($area) use ($cityId) {
					return [
						'region_id' => $area['ad_code'],
						'parentid' => $cityId,
						'region_name' => $area['title'],
						'have_children' => 3 - Region::AREA,
						'order' => 0
					];
				}, $city['child']);
				DB::table('native_place_region')->insert($areas);
			}
		}
	}
	
	public function down()
	{
		Schema::dropIfExists('native_place_region');
	}
	
}
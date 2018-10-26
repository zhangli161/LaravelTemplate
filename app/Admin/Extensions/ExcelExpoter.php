<?php
/**
 * Created by PhpStorm.
 * User: Acer
 * 导出类，使用maatwebsite/excel:~2.1.0
 * Date: 2018/10/22
 * Time: 11:00
 */

namespace App\Admin\Extensions;

use Encore\Admin\Grid\Exporters\AbstractExporter;
use Maatwebsite\Excel\Facades\Excel;

class ExcelExpoter extends AbstractExporter
{
	public function export()
	{
		Excel::create('导出', function ($excel) {
			
			$excel->sheet('Sheetname', function ($sheet) {
				
				// 这段逻辑是从表格数据中取出需要导出的字段
				$rows = collect($this->getData())->map(function ($item) {
//					return array_only($item, ['id','avatar', '名称', 'id+姓名', '密码', '头像']);

//					return $item;
					return [$item['id'],$item['name'],$item['avatar'],$item['created_at']];
				})->prepend(["用户id","名称","头像","注册时间"]);
				
				$sheet->rows($rows);
				
			});
			
		})->export('xls');
	}
}
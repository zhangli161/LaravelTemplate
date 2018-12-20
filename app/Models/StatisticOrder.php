<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;

class StatisticOrder extends Model
{

    public function paginate()
    {
        $perPage = Request::get('per_page', 10);

        $page = Request::get('page', 1);

        $start = ($page-1)*$perPage;

        // 运行sql获取数据数组
        $sql = 'select * from ...';

        $result = DB::select($sql);

        $movies = static::hydrate($result);

        $paginator = new LengthAwarePaginator($movies, $total, $perPage);

        $paginator->setPath(url()->current());

        return $paginator;
    }
    private static function getModel()
    {
        $query = Order::query();

        if (Request::filled("date_from") && Request::filled("date_to")) {
            $query->where("completed_at", ">=", Request::get("date_from"));
            $query->where("completed_at", '<=', Request::get("date_to"));
        }
        $region_id = Request::filled("provience") ?
            Request::filled("city") ? Request::get("city") : Request::get("provience")
            : "0";//request中获取或全国
        if ($region_id != "0")
            $query->whereIn("receiver_region_id",
                NativePalceReagionManager::getChildren($region_id)->pluck('region_id')->toArray()
            );
        $model = $query->orderBy('completed_at', 'asc')->get();
        return $model;
    }

    public static function with($relations)
    {
        return new static;
    }
}

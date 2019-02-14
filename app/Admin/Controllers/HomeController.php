<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\Dashboard;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;

class HomeController extends Controller
{
    public function index(Content $content)
    {
        return $content
            ->header('首页')
            ->description('服务器信息')
            ->row(view("admin.index.title"))
            ->row(function (Row $row) {

                $row->column(12, function (Column $column) {
                    $column->append(self::environment());
                });

//                $row->column(4, function (Column $column) {
//                    $column->append(Dashboard::extensions());
//                });
//
//                $row->column(4, function (Column $column) {
//                    $column->append(Dashboard::dependencies());
//                });
            });
    }
    public static function environment()
    {
        $envs = [
            ['name' => 'PHP 版本',       'value' => 'PHP/'.PHP_VERSION],
            ['name' => 'Laravel 版本',   'value' => app()->version()],
            ['name' => 'CGI',               'value' => php_sapi_name()],
            ['name' => '计算机名',             'value' => php_uname()],
            ['name' => '服务器',            'value' => array_get($_SERVER, 'SERVER_SOFTWARE')],

            ['name' => '缓存驱动方式',      'value' => config('cache.default')],
            ['name' => 'Session驱动方式',    'value' => config('session.driver')],
            ['name' => '队列驱动方式',      'value' => config('queue.default')],

            ['name' => '时区',          'value' => config('app.timezone')],
            ['name' => '语言',            'value' => config('app.locale')],
            ['name' => '环境',               'value' => config('app.env')],
            ['name' => 'URL',               'value' => config('app.url')],
        ];

        return view('admin::dashboard.environment', compact('envs'));
    }
}

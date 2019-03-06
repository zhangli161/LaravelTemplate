<?php

use Illuminate\Routing\Router;

Admin::registerAuthRoutes();

Route::group([
    'prefix' => config('admin.route.prefix'),
    'namespace' => config('admin.route.namespace'),
    'middleware' => config('admin.route.middleware'),
], function (Router $router) {
    $router->get('/', 'HomeController@index');
    $router->resource('api', ApiController::class);
    $router->resource('users', UserController::class);
    $router->resource('regions', NativePlaceRegionController::class);
    $router->resource('user_address', UserAddressController::class);
    $router->resource('banner', BannerController::class);
    $router->resource('category', CategoryController::class);
    $router->resource('message', MessageContentController::class);
    $router->resource('message_source', MessageSourceController::class);
    $router->resource('goods', GoodsController::class);
    $router->resource('goods_sku', GoodsSKUController::class);
    $router->get('goods_skus/make_benifit', 'GoodsSKUController@benifit');
    $router->post('goods_skus/make_benifit', 'GoodsSKUController@benifit_post');

    $router->resource('postage', PostageController::class);
    $router->resource('spec', GoodsSpecController::class);
    $router->resource('benefit', GoodsBenefitController::class);
    $router->resource('richtext', RichTextController::class);
    $router->resource('coupon', CouponController::class);
    $router->resource('coupon_benefit', CouponBenefitController::class);
    $router->resource('order', OrderController::class);
//	$router->get('chart/order', 'OrderController@chart');

    $router->resource('article', ArticleController::class);

    $router->resource('agents', AgentsController::class);
    $router->resource('apply/agent', AgentApplyController::class);
    $router->any('agent/getQR/{id}', "AgentsController@getQR");
    //***********//
    $router->resource("rebate/agent", AgentRebateController::class);

    //订单统计
    $router->any('statistic/order', "StatisticOrderController@index");


    $router->any('chart/order/count', "StatisticOrderController@count_chart");
    $router->any('chart/order/payment', "StatisticOrderController@payment_chart");

    //商品统计
    $router->any('statistic/goods', "StatisticGoodsController@index");
    $router->any('chart/goods/count', "StatisticGoodsController@count_chart");
    $router->any('chart/goods/payment', "StatisticGoodsController@payment_chart");

    //退款统计
    $router->any('statistic/refund', "OrderRefundController@getGrid");

    //商品销售统计
    $router->any('statistic/good-sales', "StatisticGoodsSalesController@index");

    //财务统计
    $router->any('statistic/finance', "StatisticFinanceController@index");
    $router->any('chart/finance/income', "StatisticFinanceController@income_chart");

    //用户统计
    $router->any('statistic/user', "StatisticUserController@index");

    //优惠券统计
    $router->any('statistic/coupon', "StatisticCouponController@index");

    //代理商统计、
    $router->any('statistic/agent', "StatisticAgentController@index");
    $router->any('chart/agent/fans', "StatisticAgentController@fans_chart");
    $router->any('chart/agent/fans_cost', "StatisticAgentController@fans_cost");

    $router->get('agent', "AgentController@index");
    $router->any('agent/chart/order', "AgentController@orderChart");
    $router->any('agent/chart/fans', "AgentController@funsChart");
    $router->get('agent/cash', "AgentController@cash");
    $router->post('agent/cash', "AgentController@cash_post");
    $router->any("qr","QRController@getForm");


    $router->resource('refund/order', OrderRefundController::class);

    $router->resource('module', ModuleController::class);

    $router->get('export/order', function (\Illuminate\Http\Request $request){
        $id=$request->get("id");
//        return (new \App\Admin\Extensions\OrderExport($id))->view();
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Admin\Extensions\OrderExport($id), "$id.xlsx");
//        return view("admin.expoter.fahuodan",["order"=>$order]);
    });

    $router->put('upload',function (\Illuminate\Http\Request $request){
//       dd($request->all(),$request->allFiles()) ;
        $file = $request->file("file_data");

        $file_path = \Illuminate\Support\Facades\Storage::disk("admin")->putFile("images", $file);
        if ($file_path) {
//            return $file_path;
            return \App\Http\Helpers\ApiResponse::makeResponse(true,
                \Illuminate\Support\Facades\Storage::disk("admin")->url($file_path),
                \App\Http\Helpers\ApiResponse::SUCCESS_CODE);
        }
        return \App\Http\Helpers\ApiResponse::makeResponse(false, "存储失败", \App\Http\Helpers\ApiResponse::UNKNOW_ERROR);

    });
});

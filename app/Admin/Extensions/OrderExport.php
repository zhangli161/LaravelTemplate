<?php
/**
 * Created by PhpStorm.
 * User: ieso
 * Date: 2019/1/23
 * Time: 16:07
 */

namespace App\Admin\Extensions;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class OrderExport implements FromView
{
    private $id;
    public function __construct($id)
    {
        $this->id=$id;
    }

    public function view(): View
    {
        $order=\App\Models\Order::with("skus")->findOrFail($this->id);
        foreach ($order->skus as $order_sku){
            $order_sku->sku=\App\Components\GoodsSKUManager::getSpecValuesStr($order_sku->sku);
        }
        return view("admin.expoter.fahuodan", [
            'order' => $order
        ]);
    }

}
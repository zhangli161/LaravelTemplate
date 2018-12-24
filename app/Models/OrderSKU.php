<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderSKU extends Model
{
	protected $table='order_skus';
	protected $fillable = ['sku_id', 'sku_name', 'thumb', 'amount', 'price', 'total_price','average_price'];
}

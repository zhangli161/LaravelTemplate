<?php

namespace App\Models;

use App\User;
use Encore\Admin\Auth\Database\Administrator;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Grid\Displayers\Orderable;
use Illuminate\Database\Eloquent\Model;

class Agent extends Model
{
    protected $fillable = [
        "admin_id",
        "real_name",
        "gender",
        "telephone",
        "address",
        "province_id",
        "city_id",
        "region_id",
        "wx",
        "qq",
        "email",
        "business",
        "store",
        "status"
    ];
    protected $casts = ['store' => 'json',];//内嵌字段

    public function admin()
    {
        return $this->belongsTo(Administrator::class, 'admin_id');
    }

    public function city()
    {
        return $this->belongsTo(NativePlaceRegion::class, 'city_id');
    }

    public function province()
    {
        return $this->belongsTo(NativePlaceRegion::class, 'province_id');
    }

    public function region()
    {
        return $this->belongsTo(NativePlaceRegion::class, 'region_id');
    }

    public function users()
    {
        return $this->hasMany(User::class, "agent_id");
    }

    public function order_agent()
    {
        return $this->hasMany(OrderAgent::class, "agent_id");
    }

    public function finances()
    {
        return $this->hasMany(AgentFinance::class, "agent_id");
    }

    public function cashes(){
        return $this->hasMany(AgentCash::class,"agent_id");
    }
}

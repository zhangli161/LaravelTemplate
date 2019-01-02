<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AgentFinance extends Model
{
    protected $fillable=['income','expenditure','balance',"note"];
}

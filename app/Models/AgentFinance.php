<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AgentFinance extends Model
{
    protected $fillable=["agent_id",'income','expenditure','balance',"note"];
}

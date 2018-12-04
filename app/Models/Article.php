<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    public function category(){
    	return $this->belongsTo(Category::class,'cate_id',"id");
    }
    public function content(){
    	return $this->morphOne(RichText::class,'item');
    }
}

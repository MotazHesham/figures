<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mockup extends Model
{
    protected $table = 'mockups';

    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function subcategory(){
        return $this->belongsTo(SubCategory::class);
    }

    public function subsubcategory(){
        return $this->belongsTo(SubSubCategory::class);
    }

    public function mockupstocks(){
        return $this->hasMany(MockupStock::class);
    }
}

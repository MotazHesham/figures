<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Listing extends Model
{
    protected $table = 'listings';

    public function listing_images()
    {
        return $this->hasMany(ListingImage::class,'listing_id');
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function mockup(){
        return $this->belongsTo(Mockup::class);
    }
}

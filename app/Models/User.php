<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens; 
use App\Traits\Auditable;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable, HasApiTokens ,Auditable;

    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $fillable = [
        'name', 'email', 'password', 'address','device_token',
        'avatar_original', 'user_type', 'phone', 'store_name',
        'events', 
    ];

    /**
    * The attributes that should be hidden for arrays.
    *
    * @var array
    */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function conversations(){
        return $this->hasMany(Conversation::class,'sender_id');
    }
    public function conversations_2(){
        return $this->hasMany(Conversation::class,'receiver_id');
    }

    public function staff()
    {
        return $this->hasOne(Staff::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function social_orders()
    {
        return $this->hasMany(Order::class,'social_user_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    } 

    public function seller(){
        return $this->hasOne(Seller::class);
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }
    
    public function carts()
    {
        return $this->hasMany(Cart::class);
    }
    
    public function calenders()
    {
        return $this->hasMany(Calender::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAlert extends Model
{
    public $table = 'user_alerts';
    public $fillable = ['alert_text','alert_link','type','user_id','seen'];
}

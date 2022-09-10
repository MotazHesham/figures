<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GeneralSetting extends Model
{
    const BADGE_SELECT = [ 
        'mint', 
        'grey', 
        'warning', 
        'danger', 
        'purple', 
        'pink', 
        'success', 
        'info', 
        'default', 
        'dark', 
    ];
}

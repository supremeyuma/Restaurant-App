<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'site_name', 'logo_path', 'banner_path', 'email', 'phone', 'address',
        'facebook', 'twitter', 'instagram', 'whatsapp',
    ];
}

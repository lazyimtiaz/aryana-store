<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreSetting extends Model
{
    protected $fillable = [
        'store_name',
        'contact_email',
        'contact_phone',
        'whatsapp_number',
        'address',
        'business_hours',
        'facebook_url',
        'instagram_url',
        'tiktok_url',
        'contact_intro',
    ];
}

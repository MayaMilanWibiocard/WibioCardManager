<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DesktopSecretKeys extends Model
{
    use HasFactory;
    protected $connection = 'backend';

    protected $fillable = [
        'macAddress',
        'userEmail',
        'software',
        'company',
        'deal_id',
        'is_valid',
    ];

    protected $hidden = [
        'oneShotSecretKey',
    ];

    protected $casts = [
        'oneShotSecretKey' => 'hashed',
    ];
}

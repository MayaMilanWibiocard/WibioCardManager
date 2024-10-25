<?php

namespace App\Models\Linotp;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class LinOtpUser extends Authenticatable
{
    protected $connection = 'linotp';
    use HasFactory;

    protected $fillable = [
        'user',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
    ];
}

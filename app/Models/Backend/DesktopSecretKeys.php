<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Sales\Customer;

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

    public function Customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'company', 'company');
    }
}

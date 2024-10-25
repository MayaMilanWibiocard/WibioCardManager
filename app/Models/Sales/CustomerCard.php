<?php

namespace App\Models\Sales;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use App\Models\Backend\HardwareToken;
use App\Models\Sales\Customer;

class CustomerCard extends Model
{
    use HasFactory;
    protected $connection = 'backend';

    public function HardwareTokens(): HasMany
    {
        return $this->hasMany(HardwareToken::class, 'card_id', 'card_uid');
    }

    public function Customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'id', 'crm_product_id');
    }
}

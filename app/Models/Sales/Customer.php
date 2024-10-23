<?php

namespace App\Models\Sales;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

use Jurager\Teams\Models\Team;
use App\Models\Sales\CustomerCards;

class Customer extends Model
{
    use HasFactory;
    protected $connection = 'sales';

    protected $guarded = [
        'id',
    ];

    public function CustomerCards(): HasMany
    {
        return $this->hasMany(CustomerCards::class, 'crm_product_id', 'id');
    }

    public function Team(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'company', 'name');
    }

}

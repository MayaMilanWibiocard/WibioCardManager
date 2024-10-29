<?php

namespace App\Models\Sales;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Sales\CustomerCard;

class CrmProductDeal extends Model
{
    use HasFactory;
    public $table = 'crm_product_deal';
    protected $connection = 'sales';

    public function customerCards(): HasMany
    {
        return $this->hasMany(CustomerCard::class);
    }
}

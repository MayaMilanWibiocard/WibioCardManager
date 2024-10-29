<?php

namespace App\Models\Sales;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CrmProduct extends Model
{
    use HasFactory;

    public $table = 'crm_products';
    protected $connection = 'sales';

}

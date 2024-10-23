<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Sales\CustomerCard;

class HardwareToken extends Model
{
    use HasFactory;
    protected $hidden = ['tkey'];
    protected $fillable = [
        'company',
        'owner',
        'email',
        'token',
        'card_type',
        'card_id',
        'tkey',
        'tlen',
        'otp_type',
        'intend',
        'comment'];

    public function CustomerCard(): BelongsTo
    {
        return $this->belongsTo(CustomerCard::class, 'card_uid', 'card_id');
    }
}

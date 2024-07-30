<?php

namespace App\Models;

use App\Traits\FormatDatesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use FormatDatesTrait;

    protected $fillable = [
        'order_id',
        'amount',
        'status',
        'method',
        'transaction_id',
        'payment_details',
    ];
    use HasFactory;

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}

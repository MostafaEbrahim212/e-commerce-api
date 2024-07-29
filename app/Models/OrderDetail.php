<?php

namespace App\Models;

use App\Traits\FormatDatesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use FormatDatesTrait;

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price',
    ];
    use HasFactory;

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}

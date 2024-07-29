<?php

namespace App\Models;

use App\Traits\FormatDatesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use FormatDatesTrait;

    protected $fillable = [
        'user_id',
        'quantity',
        'total',
        'status',
    ];
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }
    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
}

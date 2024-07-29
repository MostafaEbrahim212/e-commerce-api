<?php

namespace App\Models;

use App\Traits\FormatDatesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Addresses extends Model
{
    use FormatDatesTrait;
    protected $fillable = [
        'user_id',
        'address',
        'city',
        'country',
        'postal_code',
    ];
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

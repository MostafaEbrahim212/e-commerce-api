<?php

namespace App\Models;

use App\Traits\FormatDatesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use FormatDatesTrait;
    protected $fillable = [
        'first_name',
        'last_name',
        'phone_number',
        'profile_picture',
        'cover_picture',
        'bio',
    ];
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

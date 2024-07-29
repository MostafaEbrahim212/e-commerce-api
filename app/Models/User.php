<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Traits\FormatDatesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use FormatDatesTrait;
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'status'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function profile()
    {
        return $this->hasOne(Profile::class);
    }
    public function addresses()
    {
        return $this->hasMany(Addresses::class);
    }
    public function orders()
    {
        return $this->hasMany(Order::class);
    }



    public function scopeSearch($query, $searchValue)
    {
        return $query->join('profiles', 'users.id', '=', 'profiles.user_id')
            ->when($searchValue, function ($query) use ($searchValue) {
                $search = '%' . $searchValue . '%';
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('users.name', 'like', $search)
                        ->orWhere('users.email', 'like', $search)
                        ->orWhere('profiles.phone_number', 'like', $search);
                });
            })
            ->select('users.id', 'users.name', 'users.email', 'profiles.phone_number');
    }
}

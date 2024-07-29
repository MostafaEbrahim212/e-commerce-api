<?php

namespace App\Models;

use App\Traits\FormatDatesTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use FormatDatesTrait;
    protected $fillable = [
        'name',
        'slug',
        'status',
        'description',
        'image',
    ];
    use HasFactory;

    public function products()
    {
        return $this->hasMany(Product::class);
    }
    public function getImageAttribute()
    {
        $image = $this->attributes['image'];
        if (filter_var($image, FILTER_VALIDATE_URL)) {
            return $image;
        }
        if ($image) {
            return url('/api/categories/images/' . $image);
        }
        return null;
    }
}

<?php

namespace App\Models;

use App\Traits\FormatDatesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use FormatDatesTrait;
    protected $fillable = [
        'name',
        'description',
        'price',
        'status',
        'stock',
        'image',
        'category_id',
    ];
    use HasFactory;

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function getStockAttribute($value)
    {
        return $value > 0 ? $value : 'Out of stock';
    }
    public function getImageAttribute()
    {
        $image = $this->attributes['image'];
        if (filter_var($image, FILTER_VALIDATE_URL)) {
            return $image;
        }
        if ($image) {
            return url('/api/products/images/' . $image);
        }
        return null;
    }

}

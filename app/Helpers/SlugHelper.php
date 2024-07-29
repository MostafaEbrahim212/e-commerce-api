<?php
namespace App\Helpers;

use Illuminate\Support\Str;
use App\Models\Category;

class SlugHelper
{
    public static function generateUniqueSlug($title, $model)
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $counter = 1;

        while ($model::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}

<?php
namespace App\Helpers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Models\Category;

class NotFoundHelper
{
    public static function checkNotFound($model, $message = 'Resource not found')
    {
        if (!$model) {
            return ApiResponseHelper::resData(null, $message, 404);
        }
        return null;
    }
}

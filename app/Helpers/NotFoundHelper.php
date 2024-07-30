<?php
namespace App\Helpers;
use Illuminate\Database\Eloquent\Model;
class NotFoundHelper
{
    public static function checkNotFound($model, $message = 'Resource not found')
    {
        if (!$model) {
            return ApiResponseHelper::resError(null, $message, 404);
        }
        return null;
    }
}

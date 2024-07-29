<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Validator;

class ValidationHelper
{
    public static function validateLoginRequest($request, $rules)
    {
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return [
                'success' => false,
                'errors' => $errors->toArray()
            ];
        }

        return [
            'success' => true,
            'errors' => null
        ];
    }
}

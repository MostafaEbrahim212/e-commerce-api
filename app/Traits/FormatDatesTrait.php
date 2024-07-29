<?php

namespace App\Traits;

use Carbon\Carbon;

trait FormatDatesTrait
{
    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->isoFormat('YYYY-MMMM-DD HH:mm:ss');
    }

    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->isoFormat('YYYY-MMMM-DD HH:mm:ss');
    }
}

<?php

namespace App\Helpers;

use Illuminate\Database\Eloquent\Builder;

class FilterHelper
{
    public static function applyFilter(Builder $query, array $filter): Builder
    {
        $property = $filter['property'] ?? null;
        $operation = $filter['operation'] ?? null;
        $collation = $filter['collation'] ?? null;
        $value = $filter['value'] ?? null;

        if (is_null($property) || is_null($operation)) {
            return $query; // أو رمي استثناء حسب الحاجة
        }

        switch (strtolower($operation)) {
            case 'equals':
                $query->where($property, '=', $value);
                break;
            case 'contains':
                if ($collation === 'ignorecase') {
                    $query->where($property, 'like', '%' . $value . '%');
                } else {
                    $query->where($property, 'like', '%' . $value . '%');
                }
                break;
            case 'starts_with':
                $query->where($property, 'like', $value . '%');
                break;
            case 'ends_with':
                $query->where($property, 'like', '%' . $value);
                break;
            case 'greater_than':
                $query->where($property, '>', $value);
                break;
            case 'greater_than_or_equal':
                $query->where($property, '>=', $value);
                break;
            case 'less_than':
                $query->where($property, '<', $value);
                break;
            case 'less_than_or_equal':
                $query->where($property, '<=', $value);
                break;
            case 'not_equals':
                $query->where($property, '!=', $value);
                break;
            case 'in':
                if (is_array($value)) {
                    $query->whereIn($property, $value);
                }
                break;
            case 'not_in':
                if (is_array($value)) {
                    $query->whereNotIn($property, $value);
                }
                break;
            case 'between':
                if (is_array($value) && count($value) == 2) {
                    $query->whereBetween($property, $value);
                }
                break;
            default:
                throw new \InvalidArgumentException("Invalid filter operation: $operation");
        }
        return $query;
    }
}

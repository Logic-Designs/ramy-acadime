<?php

namespace App\Helpers;

const PerPage = 15;

if (!function_exists('getPagination')) {
    function getPagination($object)
    {
        return [
            'total' => $object->total(),
            'current_page' => $object->currentPage(),
            'last_page' => $object->lastPage(),
            'per_page' => $object->perPage(),
            'from' => $object->firstItem(),
            'to' => $object->lastItem(),
            'count' => $object->count()
        ];
    }
}

if (!function_exists('paginat')) {
    function paginat($object)
    {
        $perPage = request('perPage') ?? PerPage;
        return $object->paginate($perPage);
    }
}

<?php

namespace App\Traits;

trait PaginationTrait
{

    private $perPage = 15;

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

    function paginate($object)
    {
        $perPage = request('perPage') ?? $this->perPage;
        return $object->paginate($perPage);
    }

}

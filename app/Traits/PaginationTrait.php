<?php

namespace App\Traits;

trait PaginationTrait
{

    private $perPage = 15;

    function getPagination($object)
    {
        $isSimpleMode = request()->query('simple', false);

        if ($isSimpleMode) {
            return [
                'simple_mode' => true, // Indicates that pagination is not applied
            ];
        }

        return [
            'simple_mode' => false, // Indicates that pagination is applied
            'total' => $object->total(),
            'current_page' => $object->currentPage(),
            'last_page' => $object->lastPage(),
            'per_page' => $object->perPage(),
            'from' => $object->firstItem(),
            'to' => $object->lastItem(),
            'count' => $object->count()
        ];
    }

    function paginate($object, $fields = [])
    {

        $isSimpleMode = request()->query('simple', false);

        if ($isSimpleMode) {
            return $fields != []? $object->select($fields)->get(): $object->get();
        }

        $perPage = request('perPage') ?? $this->perPage;
        return $object->paginate($perPage);
    }

}

<?php

namespace App\Traits;

use App\Models\Level;

trait LevelTrait
{
    use PaginationTrait;
    public function listLevels()
    {
        return $this->paginate(Level::query());
    }

    public function createLevel(array $data)
    {
        $level = Level::create($data);

        foreach ($data['prices'] as $priceData) {
            $level->prices()->create([
                'country_id' => $priceData['country_id'],
                'price' => $priceData['price']
            ]);
        }

        return $level;
    }

    public function updateLevel(Level $level, array $data)
    {
        $level->update($data);
        return $level;
    }

    public function deleteLevel(Level $level)
    {
        $level->delete();
    }
}

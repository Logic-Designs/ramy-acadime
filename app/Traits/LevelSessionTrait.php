<?php

namespace App\Traits;

use App\Models\LevelSession;

trait LevelSessionTrait
{
    use PaginationTrait;


    public function listLevelSessions()
    {
        return $this->paginate(LevelSession::query());
    }

    public function createLevelSession(array $data)
    {
        return LevelSession::create($data);
    }

    public function updateLevelSession(LevelSession $levelSession, array $data)
    {
        $levelSession->update($data);
        return $levelSession;
    }

    public function deleteLevelSession(LevelSession $levelSession)
    {
        $levelSession->delete();
    }
}

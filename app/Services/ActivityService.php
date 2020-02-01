<?php

namespace App\Services;

use App\Activity;
use Carbon\Carbon;
use Illuminate\Support\Facades\{DB, Log, Auth};

class ActivityService
{
    /**
     * ユーザーの活動取得
     * @return
     */
    public function fetchOwnActivities()
    {
        $user = Auth::user();
        return $user->activities()->get();
    }

    /**
     * 活動を保存する
     * 
     * @param array $data
     * @return bool
     */
    public function createActivity(array $data)
    {
        $user = Auth::user();
        $activity = $user->activities()->create($data);
        return (bool) $activity;
    }

    /**
     * ユーザーの活動を活動名から取得
     * @param string $name
     * @return Activity
     */
    public function fetchOwnActivityByName(string $name, bool $relation = true)
    {
        $user = Auth::user();
        $query = Activity::query();
        if ($relation) {
            $query->with(['posts']);
        }
        return $query->columns()->where('user_id', $user->id)->where('name', $name)->first();
    }

    public function deleteById(int $id)
    {
        $user = Auth::user();
        $activity = $user->activities()->findOrFail($id);
        return $activity->delete();
    }
}

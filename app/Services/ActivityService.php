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
        Log::info(Auth::id() . ":新規投稿:" . json_encode($data));
        $activity = $user->activities()->create($data);
        return (bool) $activity;
    }

    /**
     * ユーザーの活動を活動名から取得
     * @param string $name
     * @return Activity
     */
    public function fetchOwnActivityByName(string $name)
    {
        $user = Auth::user();
        return Activity::with(['posts'])->columns()->where('user_id', $user->id)->where('name', $name)->first();
    }

    /**
     * 継続日数増加
     * @param int $activity_id
     * @param int $day
     * @return bool
     */
    public function incrementContinuationDays(int $activity_id, int $day = 1)
    {
        Log::info(Auth::id() . ':継続日数増加:' . $activity_id);
        $result = Activity::whereActivity($activity_id)->whereincrement('continuation_days', $day);
        return (bool) $result;
    }

    /**
     * 継続日数リセット
     * @param int $activity_id
     * @return bool
     */
    public function resetContinuationDays(int $activity_id)
    {
        Log::info(Auth::id() . ':継続日数リセット:' . $activity_id);
        $result = Activity::whereActivity($activity_id)->update(['continuation_days' => 0]);
        return (bool) $result;
    }

    /**
     * 活動日数増加
     * @param int $activity_id
     * @param int $day
     * @return bool
     */
    public function incrementActivityDays(int $activity_id, int $day = 1)
    {
        Log::info(Auth::id() . ':活動日数増');
        $result = Activity::whereActivity($activity_id)->increment('activity_days', $day);
        return (bool) $result;
    }

    public function deleteById(int $id)
    {
        $user = Auth::user();
        Log::info(Auth::id() . ':活動削除');
        $activity = $user->activities()->findOrFail($id);
        return $activity->delete();
    }
}

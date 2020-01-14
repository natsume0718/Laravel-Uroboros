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
        try {
            DB::beginTransaction();
            $user = Auth::user();
            $activity = $user->activities()->create($data);
            DB::commit();
            return (bool) $activity;
        } catch (\Exception $e) {
            DB::rollback();
            Log::error($user->name . ':保存失敗');
            return false;
        }
    }

    /**
     * ユーザーの活動を活動名から取得
     * @param string $name
     * @return App\Activity
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
        try {
            DB::beginTransaction();
            $result = Activity::increment('continuation_days', $day, ['id' => $activity_id]);
            DB::commit();
            return (bool) $result;
        } catch (\Exception $e) {
            DB::rollback();
            Log::error(Auth::user()->name . ':継続日数増加失敗');
            return false;
        }
    }

    /**
     * 継続日数リセット
     * @param int $activity_id
     * @return bool
     */
    public function resetContinuationDays(int $activity_id)
    {
        try {
            DB::beginTransaction();
            $result = Activity::where('id', $activity_id)->update(['continuation_days' => 0]);
            DB::commit();
            return (bool) $result;
        } catch (\Exception $e) {
            DB::rollback();
            Log::error(Auth::user()->name . ':継続日数リセット失敗');
            return false;
        }
    }

    /**
     * 活動日数増加
     * @param int $activity_id
     * @param int $day
     * @return bool
     */
    public function incrementActivityDays(int $activity_id, int $day = 1)
    {
        try {
            DB::beginTransaction();
            $result = Activity::increment('activity_days', $day, ['id' => $activity_id]);
            DB::commit();
            return (bool) $result;
        } catch (\Exception $e) {
            DB::rollback();
            Log::error(Auth::user()->name . ':活動日数増加失敗');
            return false;
        }
    }
}

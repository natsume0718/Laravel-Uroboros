<?php

namespace App\Observers;

use App\Activity;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ActivityObserver
{
    /**
     * Handle the activity "created" event.
     *
     * @param  \App\Activity  $activity
     * @return void
     */
    public function created(Activity $activity)
    {
        $user = Auth::user()->nickname;
        Log::info('活動が作成されました', compact('activity', 'user'));
    }

    /**
     * Handle the activity "updated" event.
     *
     * @param  \App\Activity  $activity
     * @return void
     */
    public function updated(Activity $activity)
    {
        $user = Auth::user()->nickname;
        Log::info('活動が更新されました', compact('activity', 'user'));
    }

    /**
     * Handle the activity "deleted" event.
     *
     * @param  \App\Activity  $activity
     * @return void
     */
    public function deleted(Activity $activity)
    {
        $user = Auth::user()->nickname;
        Log::info('活動が削除されました', compact('activity', 'user'));
    }
}

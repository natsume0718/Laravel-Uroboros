<?php

namespace App\Observers;

use App\User;
use Illuminate\Support\Facades\Log;

class UserObserver
{
    /**
     * Handle the user "created" event.
     *
     * @param  \App\User  $user
     * @return void
     */
    public function created(User $user)
    {
        Log::info('ユーザーが新規作成されました', ['user' => $user->nickname]);
    }
}

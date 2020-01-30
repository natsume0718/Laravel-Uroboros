<?php

namespace App\Observers;

use App\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


class PostObserver
{
    /**
     * Handle the post "created" event.
     *
     * @param  \App\Post  $post
     * @return void
     */
    public function created(Post $post)
    {
        $user = Auth::user()->nickname;
        Log::info('投稿が作成されました', compact('post', 'user'));
    }

    /**
     * Handle the post "updated" event.
     *
     * @param  \App\Post  $post
     * @return void
     */
    public function updated(Post $post)
    {
        $user = Auth::user()->nickname;
        Log::info('投稿が更新されました', compact('post', 'user'));
    }

    /**
     * Handle the post "deleted" event.
     *
     * @param  \App\Post  $post
     * @return void
     */
    public function deleted(Post $post)
    {
        $user = Auth::user()->nickname;
        Log::info('投稿が削除されました', compact('post', 'user'));
    }
}

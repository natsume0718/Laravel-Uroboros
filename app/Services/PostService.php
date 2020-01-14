<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\{DB, Log, Auth};
use App\Post;

class PostService
{
    public function fetchTotalHour(int $activity_id)
    {
        return Post::totalHour($activity_id);
    }

    /**
     * 最新の投稿を取得する
     * @param int $activity_id
     * @return App\Post
     */
    public function fetchLatestPost(int $activity_id)
    {
        return Post::columns()->whereActivity($activity_id)->first();
    }

    /**
     * ツイートし投稿を保存する
     * @param int $activity_id
     * @param string $tweet_content
     * @param Carbon $hour
     * @param int $reply_to
     * @return App\Post | bool
     */
    public function createPostAndTweet(int $activity_id, string $tweet_content, Carbon $hour, int $reply_to = null)
    {
        if (!$user = Auth::user()) {
            return false;
        }
        try {
            $twitter_oauth = new TwitterService($user->token, $user->token_secret);
            // つぶやく
            $tweet = $twitter_oauth->tweet($tweet_content, $reply_to);
            if (!$tweet) {
                return false;
            }
            DB::beginTransaction();
            // 保存
            $post = Post::create([
                'content' => $tweet_content,
                'hour' => $hour->format('H:i'),
                'activity_id' => $activity_id,
                'tweet_id' => $tweet->id,
            ]);
            DB::commit();
            return $tweet && $post ? $post : false;
        } catch (\Exception $e) {
            DB::rollback();
            if ($tweet) {
                $twitter_oauth->destroyTweet($tweet->id);
            }
            Log::error('ツイート失敗' . $e->getMessage());
            return false;
        }
    }

    /**
     * 対象日の活動時間のある投稿を取得する
     * @param int $activity_id
     * @param Carbon $date
     * @return lluminate\Database\Eloquent\Collection;
     */
    public function fetchExistHourPostByDate(int $activity_id, Carbon $date)
    {
        $date = $date->toDateString();
        return Post::columns()->whereActivity($activity_id)->whereCreated($date)->existHour()->get();
    }
}

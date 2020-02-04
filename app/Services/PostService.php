<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\{DB, Log, Auth};
use App\Post;

class PostService
{
    public function fetchTotalHour(int $activity_id)
    {
        $post = new Post();
        return $post->fetchTotalHour($activity_id);
    }

    public function fetchActivePostCount(int $activity_id)
    {
        $post = new Post();
        return $post->fetchActivePostCount($activity_id);
    }

    public function fetchContinuationDayCount(int $activity_id)
    {
        $post = new Post();
        return $post->fetchContinuationDayCount($activity_id);
    }

    /**
     * 最新の投稿を取得する
     * @param int $activity_id
     * @return App\Post
     */
    public function fetchLatestPost(int $activity_id)
    {
        return Post::columns()->whereActivity($activity_id)->latest()->first();
    }

    /**
     * 投稿を取得する
     * @param int $activity_id
     * @param bool $is_exist_hour
     * @return App\Post
     */
    public function fetchAllPost(int $activity_id, bool $only_exist_hour = false)
    {
        $query = Post::columns()->whereActivity($activity_id);
        return $only_exist_hour ? $query->existHour()->get() : $query->get();
    }

    /**
     * ツイートし投稿を保存する
     * @param int $activity_id
     * @param string $tweet_content
     * @param Carbon $hour
     * @param int $reply_to
     * @return App\Post;
     */
    public function createPostAndTweet(int $activity_id, string $tweet_content, Carbon $hour, int $reply_to = null)
    {
        try {
            $user = Auth::user();
            $twitter_oauth = new TwitterService($user->token, $user->token_secret);
            // つぶやく
            $tweet = $twitter_oauth->tweet($tweet_content, $reply_to);
            if (!$tweet) {
                return null;
            }
            $post = Post::create([
                'content' => $tweet_content,
                'hour' => $hour->format('H:i'),
                'activity_id' => $activity_id,
                'tweet_id' => $tweet->id,
            ]);
            return $tweet && $post ? $post : null;
        } catch (\Exception $e) {
            $result = $twitter_oauth->destroyTweet($tweet->id);
            Log::error('保存失敗' . $e->getMessage());
            Log::notice('ツイート削除結果：' . $result);
            throw $e;
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

    public function destroyOwnPost(int $post_id)
    {
        $user = Auth::user();
        $post = $user->posts()->findOrFail($post_id);
        // ツイートも削除
        $twitter_oauth = new TwitterService($user->token, $user->token_secret);
        $twitter_oauth->destroyTweet($post->tweet_id);
        return $post->delete();
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\Post\PostStoreRequest;
use App\Services\{PostService, ActivityService};
use Carbon\Carbon;

class PostController extends Controller
{

    private $activity_service, $post_service;
    public function __construct(ActivityService $activity_service, PostService $post_service)
    {
        $this->activity_service = $activity_service;
        $this->post_service = $post_service;
    }

    public function store(string $user_name, string $activity_name, PostStoreRequest $request)
    {
        // 活動、ユーザー取得
        $activity = $this->activity_service->fetchOwnActivityByName($activity_name);

        // 最新の投稿のツイートID取得
        $latest_tweet_id = null;
        if ($request->input('is_reply')) {
            $latest_post = $this->post_service->fetchLatestPost($activity->id);
            $latest_tweet_id = $latest_post ? $latest_post->tweet_id : null;
        }
        $result = $this->post_service->createPostAndTweet($activity->id, $request->input('tweet'), new Carbon($request->input('hour')), $latest_tweet_id);
        if (!$result) {
            return redirect()->route('activity.show', [$user_name, $activity_name])->with('error', '投稿に失敗しました')->withInput();
        }
        return redirect()->route('activity.show', [$user_name, $activity_name])->with('success', '投稿しました');
    }

    public function destroy()
    { }
}

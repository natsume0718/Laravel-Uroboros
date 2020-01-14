<?php

namespace App\Services;

use Abraham\TwitterOAuth\TwitterOAuth;

class TwitterService
{
    private $twitter_oauth;

    /**
     * $token
     */
    public function __construct(string $token, string $token_secret)
    {
        $this->twitter_oauth = new TwitterOAuth(
            config('twitter.consumer_key'),
            config('twitter.consumer_secret'),
            $token,
            $token_secret
        );
    }

    /**
     * ツイートする
     * @param string $text
     * @param int $reply_to
     * @return object|null
     */
    public function tweet(string $content, int $reply_to = null)
    {
        $tweet = $this->twitter_oauth->post("statuses/update", [
            "status" => $content,
            'in_reply_to_status_id' => $reply_to,
            "tweet_mode" => "extended",
        ]);

        return !isset($tweet->errors) ? $tweet : false;
    }

    /**
     * Tweetの削除
     * @param int $id
     * @return bool
     */
    public function destroyTweet(int $id)
    {
        $tweet = $this->twitter_oauth->post("statuses/destroy", [
            "id" => $id,
        ]);
        return !isset($tweet->errors);
    }
}

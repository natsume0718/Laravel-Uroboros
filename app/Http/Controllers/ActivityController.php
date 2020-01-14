<?php

namespace App\Http\Controllers;

use App\Activity;
use App\Http\Requests\Activity\{StoreRequest, UpdateRequest};
use Illuminate\Support\Facades\DB;
use App\Services\{ActivityService, PostService};
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ActivityController extends Controller
{

	private $activity_service, $post_service;
	public function __construct(ActivityService $activity_service, PostService $post_service)
	{
		$this->activity_service = $activity_service;
		$this->post_service = $post_service;
	}

	/**
	 * 活動一覧画面
	 */
	public function index()
	{
		$activities = $this->activity_service->fetchOwnActivities();
		return view('index', compact('activities'));
	}


	/**
	 * 活動保存
	 */
	public function store(StoreRequest $request)
	{
		$result = $this->activity_service->createActivity($request->validated());
		return redirect()->back()->with($result ? 'success' : 'error', $result ? '新規追加しました' : '追加に失敗しました');
	}

	/**
	 * 活動表示
	 */
	public function show(string $user_name, string $activity_name)
	{
		$activity = $this->activity_service->fetchOwnActivityByName($activity_name);
		if (!$activity) {
			return redirect()->route('activity.index');
		}
		$total = $this->post_service->fetchTotalHour($activity->id);

		return view('show', compact('activity', 'total'));
	}


	public function update(string $user_name, string $activity_name, UpdateRequest $request)
	{
		// 活動、ユーザー取得
		$activity = $this->activity_service->fetchOwnActivityByName($activity_name);
		$user = $request->user();

		// 最新の投稿のツイートID取得
		$latest_tweet_id = null;
		if ($request->input('is_reply')) {
			$latest_post = $this->post_service->fetchLatestPost($activity->id);
			$latest_tweet_id = $latest_post ? $latest_post->tweet_id : null;
		}

		// ツイートして保存
		$post = $this->post_service->createPostAndTweet($activity->id, $request->input('tweet'), new Carbon($request->input('hour')), $latest_tweet_id);
		if (!$post) {
			return redirect()->back()->with('error', '投稿に失敗しました')->withInput();
		}

		// 投稿に活動時間がないか、同日に活動時間のある投稿があったら終了
		if (!$post->hour || count($this->post_service->fetchExistHourPostByDate($activity->id, today()))) {
			return redirect()->back()->with('success', '投稿しました');
		}
		// 昨日の投稿に活動時間があるなら、継続日数加算
		if ($this->post_service->fetchExistHourPostByDate($activity->id, Carbon::yesterday())) {
			$this->activity_service->incrementContinuationDays($activity->id);
			$this->activity_service->incrementActivityDays($activity->id);
		} else {
			$this->activity_service->resetContinuationDays($activity->id);
		}

		return redirect()->back()->with('success', '投稿しました');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  Activity $activity
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(String $user_name, Activity $activity)
	{
		DB::beginTransaction();
		try {
			$activity->delete();
			DB::commit();
		} catch (\Exception $e) {
			DB::rollback();
			Log::error($e->getMessage(), [$user_name, $activity]);
			return redirect()->back()->with('error', '活動削除に失敗しました。');
		}

		return redirect()->back()->with('success', '活動を削除しました');
	}


	/**
	 * ツイートの削除をする
	 * 
	 * @param  String $user_name
	 * @param  Activity $activity
	 * @param  String $id
	 * @return \Illuminate\Http\Response
	 */
	public function deleteTweet(String $user_name, Activity $activity, String $id)
	{
		$user = $activity->user;
		// 消すツイート取得
		$del_tweet = $activity->tweets()->where('tweet_id', $id)->first();
		if ($del_tweet) {
			$hour = $del_tweet->hour;
			DB::beginTransaction();
			try {
				//消すツイートと同日取得
				$date = $del_tweet->created_at->toDateString();
				$same_date_tweet = $activity->tweets()->whereNotIn('tweet_id', [$id])->whereDate('created_at', $date)->ExitActivityHour()->count();
				//消すツイートの前日を取得
				$prev_date = $del_tweet->created_at->subDay()->toDateString();
				$prev_tweet = $activity->tweets()->whereDate('created_at', $prev_date)->ExitActivityHour()->count();

				//同日に他の投稿がなく消すツイートに活動時間があれば活動日数減少
				if (!$same_date_tweet && $hour)
					$activity->decrement('days_of_activity', 1);

				//前日にツイートしていたら継続日数減少
				if (!$same_date_tweet && $prev_tweet)
					$activity->decrement('continuation_days', 1);

				//投稿削除
				$del_tweet->delete();
				$activity->decrement('hour', $hour);
				DB::commit();
			} catch (\Exception $e) {
				DB::rollback();
				Log::error($e->getMessage(), [$user, $del_tweet]);
				return redirect()->back()->with('error', '削除中にエラーが発生しました。再度お試しください。');
			}
			$twitter_user = new TwitterOAuth(
				config('twitter.consumer_key'),
				config('twitter.consumer_secret'),
				$user->twitter_oauth_token,
				$user->twitter_oauth_token_secret
			);
			//ツイート削除
			$tweet = $twitter_user->post("statuses/destroy", [
				"id" => $id,
			]);
			return redirect()->back()->with(isset($tweet->errors) ? 'error' : 'success', isset($tweet->errors) ? '削除に失敗しました。既にツイートが削除されています。' : '削除しました');
		}
	}
}

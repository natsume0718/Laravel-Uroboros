<?php

namespace App\Http\Controllers;

use App\Activity;
use App\Http\Requests\Activity\ActivityStoreRequest;
use Illuminate\Support\Facades\DB;
use App\Services\{ActivityService, PostService};
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
	 * @return Illuminate\View\View|Illuminate\Contracts\View\Factory
	 */
	public function index()
	{
		$activities = $this->activity_service->fetchOwnActivities();
		return view('index', compact('activities'));
	}


	/**
	 * 活動の保存
	 * @param  ActivityStoreRequest $request
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function store(ActivityStoreRequest $request)
	{
		$result = DB::transaction(function () use ($request) {
			return $this->activity_service->createActivity($request->validated());
		});
		return redirect()->back()->with($result ? 'success' : 'error', $result ? '新規追加しました' : '追加に失敗しました');
	}

	/**
	 * 活動表示と投稿の表示
	 * @param  string $user_name
	 * @param  string $activity_name
	 * @return \Illuminate\Http\RedirectResponse|Illuminate\View\View|Illuminate\Contracts\View\Factory
	 */
	public function show(string $user_name, string $activity_name)
	{
		$activity = $this->activity_service->fetchOwnActivityByName($activity_name);
		if (!$activity) {
			return redirect()->route('activity.index', $user_name);
		}
		// 投稿取得
		$posts = $activity->posts;
		// 活動時間、日数集計
		$total = $this->post_service->fetchTotalHour($activity->id);
		$active_day = $this->post_service->fetchActivePostCount($activity->id);
		$continue_days = $this->post_service->fetchContinuationDayCount($activity->id);

		return view('show', compact('activity', 'total', 'posts', 'active_day'));
	}

	/**
	 * 活動の削除
	 * @param  string $user_name
	 * @param  int $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function destroy(string $user_name, int $id)
	{
		$result = DB::transaction(function () use ($id) {
			return $this->activity_service->deleteById($id);
		});

		if (!$result) {
			return redirect()->route('activity.index', $user_name)->with('error', '活動の削除に失敗しました');
		}
		return redirect()->route('activity.index', $user_name)->with('success', '活動を削除しました');
	}


	/**
	 * ツイートの削除をする
	 * 
	 * @param  String $user_name
	 * @param  int $id
	 * @return \Illuminate\Http\Response
	 */
	public function deleteTweet(string $user_name, int $id)
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

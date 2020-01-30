<?php

namespace App\Http\Controllers;

use App\Activity;
use App\Http\Requests\Activity\ActivityStoreRequest;
use Illuminate\Support\Facades\DB;
use App\Services\{ActivityService, PostService};

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
		$continuation_days = $this->post_service->fetchContinuationDayCount($activity->id);

		return view('show', compact('activity', 'total', 'posts', 'active_day', 'continuation_days'));
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
}

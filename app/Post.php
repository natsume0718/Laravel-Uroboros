<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Activity;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Post extends Model
{
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'content',
        'hour',
        'activity_id',
        'tweet_id'
    ];

    /**
     * 活動へのリレーション
     */
    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }

    /**
     * ユーザーへのリレーション
     */
    public function user()
    {
        return $this->hasOneThrough(User::class, Activity::class);
    }

    public function scopeColumns(Builder $query)
    {
        return $query->select(['id', 'content', 'hour', 'activity_id', 'tweet_id']);
    }

    /**
     * 合計時間の算出
     */
    public function fetchTotalHour(int $activity_id)
    {
        return $this->selectRaw('SUM(TIME_TO_SEC(hour)) as total_hour')->where('activity_id', $activity_id)->first();
    }

    /**
     * 活動日数の取得
     */
    public function fetchActivePostCount(int $activity_id)
    {
        return $this
            ->where('activity_id', $activity_id)
            ->where('hour', '>', 0)
            ->selectRaw('DATE(created_at) as date')
            ->groupBy('date')
            ->get()
            ->count();
    }

    /**
     * 活動日数の取得
     */
    public function fetchContinuationDayCount(int $activity_id)
    {
        // 活動時間のある投稿を日毎に取得
        $posts = $this
            ->where('activity_id', $activity_id)
            ->where('hour', '>', 0)
            ->selectRaw('DATE(created_at) as date')
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->get();

        // 直近の投稿を取得
        $start = $posts->first() ? $posts->first()->date : null;
        // なければ継続日数0
        if (!$start) {
            return 0;
        }
        $start = new Carbon($start);
        // 取得したデータをループし、開始日から-1日していく
        $result = $posts->filter(function ($post) use ($start) {
            // 開始日からデクリメントした日付と同じものだけフィルタリングされる
            $r = $start->eq(new Carbon($post->date));
            $start->subDay();
            return $r;
        });
        return $result->count();
    }

    /**
     * 総活動時間のフォーマット
     */
    public function getTotalHourAttribute($value)
    {
        // H:i形式
        return sprintf('%02d時間%02d分', ($value / 3600), ($value / 60 % 60));
    }

    public function scopeWhereActivity($query, int $activity_id)
    {
        return $query->where('activity_id', $activity_id);
    }

    public function scopeWhereCreated($query, string $date)
    {
        return $query->whereDate('created_at', $date);
    }

    public function scopeExistHour($query)
    {
        return $query->whereTime('hour', '>', 0);
    }
}

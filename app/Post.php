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

    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }

    public function scopeColumns(Builder $query)
    {
        return $query->select(['id', 'content', 'hour', 'activity_id']);
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
        $posts = $this
            ->where('activity_id', $activity_id)
            ->where('hour', '>', 0)
            ->selectRaw('DATE(created_at) as date')
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->get();

        $start = $posts->first() ? $posts->first()->date : null;
        if (!$start) {
            return false;
        }
        $start = new Carbon($start);
        $result = $posts->filter(function ($post) use ($start) {
            $r = $start->eq(new Carbon($post->date));
            $start->subDay();
            return $r;
        });
        return $result;
    }

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

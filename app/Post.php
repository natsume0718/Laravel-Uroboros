<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Activity;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

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
    public function scopeTotalHour(Builder $query, int $activity_id)
    {
        return $query->selectRaw('SEC_TO_TIME(SUM(TIME_TO_SEC(hour))) as total_hour')->where('activity_id', $activity_id)->first();
    }

    public function getTotalHourAttribute($value)
    {
        // H:i:s(00:00:00)形式なので末尾3文字削る
        return substr($value, 0, 5);
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

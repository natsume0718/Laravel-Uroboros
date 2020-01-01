<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Activity;
use Carbon\Carbon;

class Tweet extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'content',
        'activity_id'
    ];


    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }

    public function scopeCreatedBeforeToday($query)
    {
        return $query->where('created_at', '<', new Carbon('today', 'Asia/Tokyo'));
    }

    public function scopeCreatedToday($query)
    {
        return $query->where('created_at', '>', new Carbon('today', 'Asia/Tokyo'))
            ->where('created_at', '<', new Carbon('tomorrow', 'Asia/Tokyo'));
    }

    public function scopeExitActivityHour($query)
    {
        return $query->where('hour', '>', 0);
    }
}

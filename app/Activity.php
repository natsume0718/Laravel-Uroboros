<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use App\Tweet;

class Activity extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'total',
        'continuation_days',
        'activity_days'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tweets()
    {
        return $this->hasMany(Tweet::class);
    }

    public function scopeColumns($query)
    {
        return $query->select(['id', 'name', 'total', 'continuation_days', 'activity_days', 'user_id']);
    }
}

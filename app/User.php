<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Activity;

class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;

    // protected $rememberTokenName = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'name',
        'nickname',
        'avatar',
        'token',
        'token_secret'
    ];

    /**
     * 日付を変形する属性
     *
     * @var array
     */
    protected $dates = [
        'deleted_at'
    ];

    /**
     * 活動へのリレーション
     */
    public function activities()
    {
        return $this->hasMany(Activity::class);
    }

    /**
     * 投稿へのリレーション
     */
    public function posts()
    {
        return $this->hasManyThrough(Post::class, Activity::class);
    }
}

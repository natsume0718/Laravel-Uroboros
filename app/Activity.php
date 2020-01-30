<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use App\Post;

class Activity extends Model
{

    use SoftDeletes;

    protected $dates = ['deleted_at'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function scopeColumns($query)
    {
        return $query->select(['id', 'name', 'user_id']);
    }

    public function scopeWhereActivity($query, int $id)
    {
        return $query->where('id', $id);
    }
}

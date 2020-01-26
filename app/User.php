<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    
    public function microposts()
    {
        return $this->hasMany(Micropost::class);
    }
    
    public function followings()
    {
        return $this->belongsToMany(User::class, 'user_follow', 'user_id', 'follow_id')->withTimestamps();
    }

    public function followers()
    {
        return $this->belongsToMany(User::class, 'user_follow', 'follow_id', 'user_id')->withTimestamps();
    }
    
    public function follow($userId)
    {
        // 既にフォローしているかの確認
        $exist = $this->is_following($userId);
        // 相手が自分自身ではないかの確認
        $its_me = $this->id == $userId;
    
        if ($exist || $its_me) {
            // 既にフォローしていれば何もしない
            return false;
        } else {
            // 未フォローであればフォローする
            $this->followings()->attach($userId);
            return true;
        }
    }
    
    public function unfollow($userId)
    {
        // 既にフォローしているかの確認
        $exist = $this->is_following($userId);
        // 相手が自分自身ではないかの確認
        $its_me = $this->id == $userId;
    
        if ($exist && !$its_me) {
            // 既にフォローしていればフォローを外す
            $this->followings()->detach($userId);
            return true;
        } else {
            // 未フォローであれば何もしない
            return false;
        }
    }
    
    public function is_following($userId)
    {
        return $this->followings()->where('follow_id', $userId)->exists();
    }
    
    
    
    public function favorites()
    {
        return $this->belongsToMany(User::class, 'user_micropost', 'user_id', 'micropost_id')->withTimestamps();
    }
    
    public function favorite($micropotId)
    {
        // 既にお気に入りしているかの確認
        $exist = $this->is_favorite($micropotId);
        
        if ($exist) {
            // 既にお気に入りしていれば何もしない
            return false;
        } else {
            // まだお気に入りでなければ、お気に入りにする
            $this->favorites()->attach($micropotId);
            return true;
        }
    }
    
    public function unfavorite($micropotId)
    {
        // 既にお気に入りしているかの確認
        $exist = $this->is_favorite($micropotId);
        
        if ($exist) {
            // 既にお気に入りしていればお気に入りを外す
            $this->favorites()->detach($micropotId);
            return true;
        } else {
            // まだお気に入りでなければ何もしない
            return false;
        }
    }
    
    public function is_favorite($micropotId)
    {
        return $this->favorites()->where('micropost_id', $micropotId)->exists();
    }
    
}

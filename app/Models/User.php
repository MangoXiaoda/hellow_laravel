<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

use App\Notifications\ResetPassword;

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


    // 头像
    public function gravatar($size = '100')
    {
        $hash = md5(strtotime(trim($this->attributes['email'])));

        return "http://www.gravatar.com/avatar/$hash?s=$size";
    }


    public static function boot()
    {
        parent::boot();
        // creating 用于监听模型被创建之前的事件
        static::creating(function ($user) {
            $user->activation_token = str_random(30);
        });
    }

    // 调用消息通知文件
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }


    public function statuses()
    {
        return $this->hasMany(Status::class);
    }

    // 动态获取发布的微博
    public function feed()
    {
        return $this->statuses()->orderBy('created_at', 'desc');
    }


}
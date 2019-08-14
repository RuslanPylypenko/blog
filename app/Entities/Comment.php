<?php


namespace App\Entities;


use App\User;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $table = 'article_comments';

    protected $fillable = ['text', 'article_id', 'user_id'];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id')
            ->select(['id', 'name', 'email']);
    }

    public function getUserName()
    {
        return $this->user ? $this->user->name : 'Инкогнито';
    }
}
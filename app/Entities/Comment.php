<?php


namespace App\Entities;


use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $table = 'article_comments';

    protected $fillable = ['text', 'article_id', 'user_id'];
}
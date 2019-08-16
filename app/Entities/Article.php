<?php


namespace App\Entities;


use App\User;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    const SHORT_TEXT_LENGTH = 140;

    const STATUS_ACTIVE = 1;
    const STATUS_DISABLED = 0;

    protected $table = 'articles';

    protected $fillable = ['title', 'text', 'views', 'likes', 'image', 'status', 'short_text', 'price', 'user_id'];


    public function getShortText()
    {
        $short_text = mb_substr($this->text, 0, self::SHORT_TEXT_LENGTH);
        $next_symbol = mb_substr($this->text, (self::SHORT_TEXT_LENGTH), 1);

        if($next_symbol != " "){
            $last_symbol = mb_strripos($short_text, " ");
            $short_text = mb_substr($this->text, 0, $last_symbol);
        }

        return  $short_text;
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id')->select(['id', 'name', 'email']);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'article_id');
    }
}
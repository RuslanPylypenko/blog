<?php

namespace App\Providers;

use App\Entities\Article;
use App\Entities\Comment;
use App\Repositories\ArticleRepository;
use App\Repositories\CommentRepository;
use App\Services\ArticleService;
use App\Services\CommentService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

        $this->app->bind(ArticleService::class, function () {
            return new ArticleService(new ArticleRepository(new Article()));
        });

        $this->app->bind(CommentService::class, function () {
            return new CommentService(new CommentRepository(new Comment()));
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}

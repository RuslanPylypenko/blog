<?php

namespace App\Providers;

use App\Entities\Article;
use App\Entities\Comment;
use App\Entities\PointsTransaction;
use App\Repositories\ArticleRepository;
use App\Repositories\CommentRepository;
use App\Repositories\PointsRepository;
use App\Repositories\UserRepository;
use App\Services\ArticleService;
use App\Services\CommentService;
use App\Services\PointService;
use App\User;
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

        $this->app->bind(PointService::class, function (){
            return new PointService(
                new PointsRepository(new PointsTransaction()),
                new UserRepository(new User())
            );
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

<?php

namespace App\Providers;

use App\Entities\Article;
use App\Entities\Comment;
use App\Entities\PointsTransaction;
use App\Repositories\ArticleRepository;
use App\Repositories\CommentRepository;
use App\Repositories\Feeds\RedisFeedsRepository;
use App\Repositories\OrderArticle\RedisOrderArticle;
use App\Repositories\PointsRepository;
use App\Repositories\Subscribe\RedisSubscribeRepository;
use App\Repositories\UserRepository;
use App\Services\ArticleOrderService;
use App\Services\ArticleService;
use App\Services\CommentService;
use App\Services\FeedService;
use App\Services\PointService;
use App\Services\UserService;
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
            return new ArticleService(
                new ArticleRepository(new Article()),
                new RedisOrderArticle()
            );
        });

        $this->app->bind(CommentService::class, function () {
            return new CommentService(new CommentRepository(new Comment()));
        });

        $this->app->bind(PointService::class, function () {
            return new PointService(
                new PointsRepository(new PointsTransaction()),
                new UserRepository(new User())
            );
        });

        $this->app->bind(UserService::class, function () {
            return new UserService(
                new UserRepository(new User()),
                new RedisSubscribeRepository()
            );
        });

        $this->app->bind(ArticleOrderService::class, function () {
            return new ArticleOrderService(
                new RedisOrderArticle(),
                new PointService(
                    new PointsRepository(new PointsTransaction()),
                    new UserRepository(new User())
                )
            );
        });

        $this->app->bind(FeedService::class, function () {
            return new FeedService(
                new RedisFeedsRepository(),
                new RedisSubscribeRepository(),
                new ArticleRepository(new Article())
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

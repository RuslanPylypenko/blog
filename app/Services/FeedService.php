<?php


namespace App\Services;


use App\Repositories\ArticleRepository;
use App\Repositories\Feeds\FeedsRepositoryInterface;
use App\Repositories\Subscribe\SubscribeInterface;
use App\User;

class FeedService
{
    /**
     * @var FeedsRepositoryInterface
     */
    private $feedsRepository;

    /**
     * @var SubscribeInterface
     */
    private $subscribeRepository;

    /**
     * @var ArticleRepository
     */
    private $articleRepository;

    /**
     * FeedService constructor.
     * @param FeedsRepositoryInterface $feedsRepository
     * @param SubscribeInterface $subscribeRepository
     * @param ArticleRepository $articleRepository
     */
    public function __construct(
        FeedsRepositoryInterface $feedsRepository,
        SubscribeInterface $subscribeRepository,
        ArticleRepository $articleRepository
    )
    {
        $this->feedsRepository = $feedsRepository;
        $this->subscribeRepository = $subscribeRepository;
        $this->articleRepository = $articleRepository;
    }

    /**
     * @param User $user
     * @return array
     */
    public function getArticles(User $user)
    {
        $articleIds = $this->feedsRepository->get($user->id);
        return $this->articleRepository->setFilter('id', $articleIds)->get();
    }

    /**
     * @param $articleId
     * @param User $user
     */
    public function addArticles($articleId, User $user)
    {
        $followers = $this->subscribeRepository->getFollowers($user->id);
        foreach ($followers as $follower) {
            $this->feedsRepository->save($articleId, $follower);
        }

    }
}
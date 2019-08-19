<?php


namespace App\Services;


use App\Entities\Article;
use App\Repositories\OrderArticle\OrderArticleInterface;
use App\Repositories\UserRepository;
use App\User;

class ArticleOrderService
{
    /**
     * @var OrderArticleInterface
     */
    private $orderArticleRepository;


    /**
     * @var PointService;
     */
    private $pointService;

    /**
     * ArticleOrderService constructor.
     * @param OrderArticleInterface $orderArticleRepository
     * @param PointService $pointService
     */
    public function __construct(
        OrderArticleInterface $orderArticleRepository,
        PointService $pointService
    )
    {
        $this->pointService = $pointService;
        $this->orderArticleRepository = $orderArticleRepository;
    }

    /**
     * @param Article $article
     * @param User $cUser
     * @throws \Exception
     */
    public function buyArticle(Article $article, User $cUser)
    {
        $data = ['points' => $article->price, 'message' => 'Покупка статьи'];
        $this->pointService->sendPointTransaction($article->user, $cUser, $data);

        $this->orderArticleRepository->addArticleToUser($article->id, $cUser->id);
    }

    /**
     * @param $articleId
     * @param User $cUser
     * @return bool
     */
    public function hasUserArticle($articleId, User $cUser)
    {
        return $this->orderArticleRepository->hasUserArticle($articleId, $cUser->id);
    }


    /**
     * @param $articleId
     * @return array
     */
    public function whoBuyArticle($articleId)
    {
        return $this->orderArticleRepository->whoBuyArticle($articleId);
    }

}
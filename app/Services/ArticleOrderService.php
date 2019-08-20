<?php


namespace App\Services;


use App\Entities\Article;
use App\Repositories\OrderArticle\OrderArticleInterface;
use App\User;

class ArticleOrderService
{
    /**
     * @var OrderArticleInterface
     */
    private $orderArticleRepository;

    /**
     * ArticleOrderService constructor.
     * @param OrderArticleInterface $orderArticleRepository
     */
    public function __construct(
        OrderArticleInterface $orderArticleRepository
    )
    {
        $this->orderArticleRepository = $orderArticleRepository;
    }

    /**
     * @param Article $article
     * @param User $cUser
     * @throws \Exception
     */
    public function buyArticle(Article $article, User $cUser)
    {
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
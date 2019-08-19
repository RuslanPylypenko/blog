<?php


namespace App\Repositories\OrderArticle;


interface OrderArticleInterface
{
    /**
     * @param $articleId
     * @param $userId
     */
    public function addArticleToUser($articleId, $userId);


    /**
     * @param $articleId
     * @param $userId
     * @return bool
     */
    public function hasUserArticle($articleId, $userId);

    /**
     * @param $articleId
     * @return array
     */
    public function whoBuyArticle($articleId);



}
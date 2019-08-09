<?php


namespace App\Http\Controllers\Rest;


use App\Http\Controllers\Controller;
use App\Services\ArticleService;

class ArticlesController extends Controller
{
    const ARTICLE_PER_PAGE = 9;

    /**
     * @var ArticleService
     */
    private $service;


    public function __construct(ArticleService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $articles = $this->service->get(self::ARTICLE_PER_PAGE);

        return $articles;
    }

    public function show($id = null)
    {
        $article = $this->service->getById($id);
        $this->service->addView($id);

        return $article;
    }


    public function like($id = null)
    {
        $article = $this->service->getById($id);
        $this->service->likeArticle($id);

        return $article;
    }
}
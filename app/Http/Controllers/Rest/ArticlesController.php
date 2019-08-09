<?php


namespace App\Http\Controllers\Rest;


use App\Http\Controllers\Controller;
use App\Http\Requests\StoreArticlePost;
use App\Http\Requests\UpdateArticlePost;
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

    public function create(StoreArticlePost $articlePost)
    {
        $article = [
            'title' => $articlePost->input('title'),
            'text' => $articlePost->input('text'),
        ];

        return $this->service->createArticle($article);
    }


    public function update($id, UpdateArticlePost $articlePost)
    {
        $article = [
            'title' => $articlePost->input('title'),
            'text' => $articlePost->input('text'),
        ];

        foreach ($article as $item => &$value) {
            if (!$article[$item]) unset($article[$item]);
        }

        return $this->service->updateArticle($id, $article);
    }

    public function delete($id)
    {
        $this->service->deleteArticle($id);

        return [
            'success' => true
        ];
    }


    public function like($id = null)
    {
        $article = $this->service->getById($id);
        $this->service->likeArticle($id);

        return $article;
    }


    public function disableArticle($id = null)
    {
        $this->service->deleteArticle($id);
        return [
            'success' => true
        ];
    }
}

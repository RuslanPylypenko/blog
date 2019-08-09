<?php


namespace App\Http\Controllers;


use App\Services\ArticleService;
use Illuminate\Http\Request;

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

        return view('articles/index', [
            'articles' => $articles
        ]);
    }

    public function show($id = null)
    {
        $this->service->addView($id);
        $article = $this->service->getById($id);

        return view('articles/view', [
            'article' => $article
        ]);
    }

    public function like(Request $request)
    {
        $articleId = $request->input('article');
        $this->service->likeArticle($articleId);

        return back()->withInput();
    }
}
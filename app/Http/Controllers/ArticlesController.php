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


    /**
     * ArticlesController constructor.
     * @param ArticleService $service
     */
    public function __construct(ArticleService $service)
    {
        $this->service = $service;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $sort = $request->input('sort', 'created_at');
        $dir = $request->input('dir', 'desc');

        $articles = $this->service->get(self::ARTICLE_PER_PAGE, $sort, $dir);

        return view('articles/index', [
            'articles' => $articles,
            'sort' => $sort,
            'dir' => $dir,
        ]);
    }

    /**
     * @param null $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id = null)
    {
        $this->service->addView($id);
        $article = $this->service->getByIdWithComments($id);

        return view('articles/view', [
            'article' => $article
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function like(Request $request)
    {
        $articleId = $request->input('article');
        $this->service->likeArticle($articleId);

        return back()->withInput();
    }
}
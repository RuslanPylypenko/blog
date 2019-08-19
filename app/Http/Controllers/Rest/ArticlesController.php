<?php


namespace App\Http\Controllers\Rest;


use App\Http\Controllers\Controller;
use App\Http\Requests\StoreArticlePost;
use App\Http\Requests\UpdateArticlePost;
use App\Services\ArticleOrderService;
use App\Services\ArticleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArticlesController extends Controller
{
    const ARTICLE_PER_PAGE = 9;

    /**
     * @var ArticleService
     */
    private $articleService;


    /**
     * @var ArticleOrderService
     */
    private $articleOrderService;

    /**
     * ArticlesController constructor.
     * @param ArticleService $articleService
     * @param ArticleOrderService $articleOrderService
     */
    public function __construct(
        ArticleService $articleService,
        ArticleOrderService $articleOrderService
    )
    {
        $this->articleService = $articleService;
        $this->articleOrderService = $articleOrderService;
    }

    /**
     * @param Request $request
     * @return array|mixed
     */
    public function index(Request $request)
    {
        try {
            $sort = $request->input('sort', 'date');
            $dir = $request->input('dir', 'created_at');

            $articles = $this->articleService->get(self::ARTICLE_PER_PAGE, $sort, $dir);
        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }

        return $articles;
    }

    /**
     * @param $id
     * @return \App\Entities\Article|array|mixed
     */
    public function show($id)
    {
        try {
            $userId = Auth::guest() ? null : Auth::guard()->user()->id;

            $article = $this->articleService->showArticle($id, $userId);
            $this->articleService->addView($id);
        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
        return $article;
    }


    /**
     * @param StoreArticlePost $articlePost
     * @return array|mixed
     */
    public function create(StoreArticlePost $articlePost)
    {
        try {
            $data = [
                'title' => $articlePost->input('title'),
                'text' => $articlePost->input('text'),
                'price' => $articlePost->input('price'),
                'user_id' => Auth::guard()->user()->id,
            ];
            $article = $this->articleService->createArticle($data);
            return ['success' => true, 'message' => $article];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }

    }


    /**
     * @param $id
     * @param UpdateArticlePost $articlePost
     * @return array
     */
    public function update($id, UpdateArticlePost $articlePost)
    {
        try {

            $cUser = Auth::guard()->user();
            if (!$this->articleService->hasUserAccessToArticle($cUser, $id)) {
                throw new \Exception('Вы не можете удалять статью...');
            }

            $data = [
                'title' => $articlePost->input('title'),
                'text' => $articlePost->input('text'),
                'price' => $articlePost->input('price'),
            ];

            foreach ($data as $item => &$value) {
                if (!$data[$item]) unset($data[$item]);
            }

            $this->articleService->updateArticle($id, $data);

            $article = $this->articleService->find($id);
            return ['success' => true, 'article' => $article];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }


    /**
     * @param $id
     * @return array
     */
    public function delete($id)
    {
        try {
            $cUser = Auth::guard()->user();
            if (!$this->articleService->hasUserAccessToArticle($cUser, $id)) {
                throw new \Exception('Вы не можете удалять статью...');
            }
            $this->articleService->deleteArticle($id);
        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }

        return ['success' => true];
    }


    /**
     * @param null $id
     * @return array|mixed
     */
    public function like($id = null)
    {
        try {

            $article = $this->articleService->find($id);
            $this->articleService->likeArticle($id);
        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
        return $article;
    }


    /**
     * @param null $id
     * @return array
     */
    public function disableArticle($id = null)
    {
        try {
            $cUser = Auth::guard()->user();
            if (!$this->articleService->hasUserAccessToArticle($cUser, $id)) {
                throw new \Exception('Вы не можете скрыть статью...');
            }
            $this->articleService->disableArticle($id);
            return ['success' => true];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }


    public function buy($articleId)
    {
        try {
            $cUser = Auth::guard()->user();

            if (!$this->articleService->isAvailableArticle($articleId)) {
                throw new \Exception('Вы не можете купить статью...');
            }
            if($this->articleOrderService->hasUserArticle($articleId, $cUser)){
                throw new \Exception('Вы уже купили эту статью...');
            }

            $article = $this->articleService->find($articleId);

            $this->articleOrderService->buyArticle($article, $cUser);
            return ['success' => true];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

}

<?php


namespace App\Http\Controllers\Rest;


use App\Http\Controllers\Controller;
use App\Http\Requests\StoreArticlePost;
use App\Http\Requests\UpdateArticlePost;
use App\Services\ArticleOrderService;
use App\Services\ArticleService;
use App\Services\FeedService;
use App\Services\PointService;
use App\User;
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
     * @var FeedService
     */
    private $feedService;

    /**
     * @var PointService;
     */
    private $pointService;

    /**
     * ArticlesController constructor.
     * @param ArticleService $articleService
     * @param ArticleOrderService $articleOrderService
     * @param FeedService $feedService
     * @param PointService $pointService
     */
    public function __construct(
        ArticleService $articleService,
        ArticleOrderService $articleOrderService,
        FeedService $feedService,
        PointService $pointService
    )
    {
        $this->articleService = $articleService;
        $this->articleOrderService = $articleOrderService;
        $this->feedService = $feedService;
        $this->pointService = $pointService;
    }

    /**
     * @param Request $request
     * @return array|mixed
     */
    public function index(Request $request)
    {
        $sort = $request->input('sort', 'date');
        $dir = $request->input('dir', 'created_at');

        return $this->articleService->get(self::ARTICLE_PER_PAGE, $sort, $dir);
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
            return $article;
        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }


    /**
     * @param StoreArticlePost $articlePost
     * @return array|mixed
     */
    public function create(StoreArticlePost $articlePost)
    {
        try {
            $cUser = Auth::guard()->user();
            $data = [
                'title' => $articlePost->input('title'),
                'text' => $articlePost->input('text'),
                'price' => $articlePost->input('price'),
                'user_id' => $cUser->id,
            ];
            $article = $this->articleService->createArticle($data);

            $this->feedService->addArticles($article->id, $cUser);

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
            $this->hasUserAccessToArticle($cUser, $id);

            $data = [
                'title' => $articlePost->input('title'),
                'text' => $articlePost->input('text'),
                'price' => $articlePost->input('price'),
            ];

            $this->articleService->updateArticle($id, $data);

            return ['success' => true,];
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
            $this->hasUserAccessToArticle($cUser, $id);
            $this->articleService->deleteArticle($id);
            return ['success' => true];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }


    /**
     * @param $id
     * @return array
     */
    public function like($id)
    {
        try {
            $this->articleService->likeArticle($id);
            return ['success' => true];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }


    /**
     * @param null $id
     * @return array
     */
    public function disableArticle($id = null)
    {
        try {
            $cUser = Auth::guard()->user();
            $this->hasUserAccessToArticle($cUser, $id);
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
            if ($this->articleOrderService->hasUserArticle($articleId, $cUser)) {
                throw new \Exception('Вы уже купили эту статью...');
            }

            $article = $this->articleService->find($articleId);

            $this->articleOrderService->buyArticle($article, $cUser);

            $data = ['points' => $article->price, 'message' => 'Покупка статьи'];
            $this->pointService->sendPointTransaction($article->user, $cUser, $data);

            return ['success' => true];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * @param User $cUser
     * @param $id
     * @throws \Exception
     */
    private function hasUserAccessToArticle(User $cUser, $id)
    {
        if (!$this->articleService->hasUserAccessToArticle($cUser, $id)) {
            throw new \Exception('Вы не можете выполнить это действие...');
        }
    }

}

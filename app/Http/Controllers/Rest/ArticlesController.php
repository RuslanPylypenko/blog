<?php


namespace App\Http\Controllers\Rest;


use App\Http\Controllers\Controller;
use App\Http\Requests\CreateComment;
use App\Http\Requests\StoreArticlePost;
use App\Http\Requests\UpdateArticlePost;
use App\Services\ArticleService;
use App\Services\CommentService;
use Illuminate\Http\Request;

class ArticlesController extends Controller
{
    const ARTICLE_PER_PAGE = 9;

    /**
     * @var ArticleService
     */
    private $articleService;

    /**
     * @var CommentService
     */
    private $commentService;


    public function __construct(
        ArticleService $articleService,
        CommentService $commentService
    )
    {
        $this->articleService = $articleService;
        $this->commentService = $commentService;
    }

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

    public function show($id = null)
    {
        try {
            $article = $this->articleService->getByIdWithComments($id);
            $this->articleService->addView($id);
        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
        return $article;
    }

    public function create(StoreArticlePost $articlePost)
    {
        try {
            $article = [
                'title' => $articlePost->input('title'),
                'text' => $articlePost->input('text'),
            ];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }

        return $this->articleService->createArticle($article);
    }


    public function update($id, UpdateArticlePost $articlePost)
    {

        try {
            $article = [
                'title' => $articlePost->input('title'),
                'text' => $articlePost->input('text'),
            ];

            foreach ($article as $item => &$value) {
                if (!$article[$item]) unset($article[$item]);
            }

            $this->articleService->updateArticle($id, $article);
            $article = $this->articleService->getById($id);
        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }

        return ['success' => true, 'article' => $article];
    }

    public function delete($id)
    {
        try {
            $this->articleService->deleteArticle($id);
        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }

        return [
            'success' => true
        ];
    }


    public function like($id = null)
    {
        try {
            $article = $this->articleService->getById($id);
            $this->articleService->likeArticle($id);
        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
        return $article;
    }


    public function disableArticle($id = null)
    {
        try {
            $this->articleService->disableArticle($id);
        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }

        return [
            'success' => true
        ];
    }

    public function createComment($article_id, CreateComment $request)
    {
        try {
            $article = $this->articleService->getById($article_id);

            $comment = [
                'text' => $request->input('text'),
                'user_id' => $request->input('user_id'),
                'article_id' => $article_id
            ];

            $this->commentService->createComment($comment);

        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }

        return [
            'success' => true,
            'article' => $article
        ];
    }
}

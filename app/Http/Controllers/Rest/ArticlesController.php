<?php


namespace App\Http\Controllers\Rest;


use App\Exceptions\CommentException;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateComment;
use App\Http\Requests\StoreArticlePost;
use App\Http\Requests\UpdateArticlePost;
use App\Services\ArticleService;
use App\Services\CommentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

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


    /**
     * ArticlesController constructor.
     * @param ArticleService $articleService
     * @param CommentService $commentService
     */
    public function __construct(
        ArticleService $articleService,
        CommentService $commentService
    )
    {
        $this->articleService = $articleService;
        $this->commentService = $commentService;
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
     * @param null $id
     * @return array|mixed
     */
    public function show($id = null)
    {
        try {
            $cUser = Auth::guard()->user();
            $article = $this->articleService->showArticle($id, $cUser);
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
            if(!$this->articleService->hasUserAccessToArticle($cUser, $id)){
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
            if(!$this->articleService->hasUserAccessToArticle($cUser, $id)){
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
            if(!$this->articleService->hasUserAccessToArticle($cUser, $id)){
                throw new \Exception('Вы не можете скрыть статью...');
            }
            $this->articleService->disableArticle($id);
        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }

        return [
            'success' => true
        ];
    }


    /**
     * @param $article_id
     * @param CreateComment $request
     * @return array
     */
    public function createComment($article_id, CreateComment $request)
    {
        try {

            $cUser = Auth::guard()->user();

            if(!$this->articleService->hasUserCommentArticle($cUser, $article_id)){
                throw new \Exception('Невозможно добавить комментарий...');
            }

            $comment = [
                'text' => $request->input('text'),
                'user_id' => $cUser->id,
                'article_id' => $article_id
            ];

            $this->commentService->createComment($cUser, $comment);

            return [ 'success' => true];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }


    }


    /**
     * @param $comment_id
     * @return array
     */
    public function deleteComment($comment_id)
    {
        try {
            Validator::make(['comment_id' => $comment_id], [
                'comment_id' => 'required|integer',
            ])->validate();

            $cUser = Auth::guard()->user();

            if(!$this->commentService->hasUserAccessComment($cUser, $comment_id)){
                throw new CommentException('Это не Ваш комментарий...');
            }

            $this->commentService->delete($comment_id);

        } catch (ValidationException $e) {
            return ['success' => false, 'message' => $e->errors()];
        } catch (CommentException $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }

        return [
            'success' => true,
        ];
    }
}

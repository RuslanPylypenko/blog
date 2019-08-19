<?php


namespace App\Http\Controllers\Rest;

use App\Exceptions\CommentException;
use App\Http\Requests\CreateComment;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use App\Services\CommentService;
use App\Services\ArticleService;
use Illuminate\Support\Facades\Auth;

class CommentController
{

    /**
     * @var ArticleService
     */
    private $articleService;

    /**
     * CommentController constructor.
     * @param CommentService $commentService
     * @param ArticleService $articleService
     */
    public function __construct(
        CommentService $commentService,
        ArticleService $articleService
    )
    {
        $this->commentService = $commentService;
        $this->articleService = $articleService;
    }

    /**
     * @var CommentService
     */
    private $commentService;

    /**
     * @param $article_id
     * @param CreateComment $request
     * @return array
     */
    public function create($article_id, CreateComment $request)
    {
        try {

            $cUser = Auth::guard()->user();

            if(!$this->articleService->isAvailableArticle($cUser, $article_id)){
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
    public function delete($comment_id)
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
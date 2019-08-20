<?php


namespace App\Services;


use App\Decorators\ArticleFullTextDecorator;
use App\Entities\Article;
use App\Helpers\StringHelper;
use App\Repositories\ArticleRepository;
use App\Repositories\OrderArticle\OrderArticleInterface;
use App\User;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\File;

class ArticleService
{
    /**
     * @var ArticleRepository
     */
    private $repository;

    /**
     * @var OrderArticleInterface
     */
    private $orderArticleRepository;

    /**
     * ArticleService constructor.
     * @param ArticleRepository $repository
     * @param OrderArticleInterface $orderArticleRepository
     */
    public function __construct(
        ArticleRepository $repository,
        OrderArticleInterface $orderArticleRepository
    )
    {
        $this->repository = $repository;
        $this->orderArticleRepository = $orderArticleRepository;
    }


    public function showArticle($articleId, $user_id)
    {
        $article = $this->find($articleId);

        if ($article->price > 0 && !$this->orderArticleRepository->hasUserArticle($articleId, $user_id)) {
            $decorator = new ArticleFullTextDecorator($article);
            $article = $decorator->decorate();
        }

        return $article;
    }


    /**
     * @param $articleId
     * @return mixed
     */
    public function find($articleId)
    {
        return $this->repository->find($articleId);
    }


    /**
     * @param int $perPage
     * @param string $sort
     * @param string $dir
     * @return mixed
     */
    public function get($perPage, $sort, $dir)
    {
        return $this->repository->get($perPage, $sort, $dir);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function addView($id)
    {
        $article = $this->repository->findOne(['id' => $id]);
        return $this->repository->update($id, ['views' => ($article->views + 1)]);
    }


    /**
     * @param $id
     * @return mixed
     * @throws \Exception
     */
    public function likeArticle($id)
    {
        if(!$article = $this->repository->findOne(['id' => $id])){
            throw new \Exception('Статья не найдена...');
        }
        return $this->repository->update($id, ['likes' => ($article->likes + 1)]);
    }


    /**
     * @param array $article
     * @return mixed
     */
    public function createArticle(array $article)
    {
        $faker = Faker::create();

        $image_folder = '/storage/images/';
        $filepath = public_path($image_folder);

        if (!File::exists($filepath)) {
            File::makeDirectory($filepath);
        }

        $image = $image_folder . $faker->image($filepath, 400, 300, false, false);

        $article['likes'] = 0;
        $article['views'] = 0;
        $article['image'] = $image;
        $article['short_text'] = StringHelper::getHortText($article['text'], Article::SHORT_TEXT_LENGTH);

        return $this->repository->create($article);
    }


    /**
     * @param $id
     * @param array $data
     * @return mixed
     */
    public function updateArticle($id, array $data)
    {
        foreach ($data as $item => &$value) {
            if (!$data[$item]) unset($data[$item]);
        }

        return $this->repository->update($id, $data);
    }


    /**
     * @param User $user
     * @param $articleId
     * @return bool
     */
    public function hasUserAccessToArticle(User $user, $articleId)
    {
        return $this->repository->exists(['id' => $articleId, 'user_id' => $user->id]);
    }


    /**
     * @param $articleId
     * @return mixed
     */
    public function isAvailableArticle($articleId)
    {
        return $this->repository->exists(['id' => $articleId, 'status' => 1]);
    }


    /**
     * @param $id
     * @return mixed
     * @throws \Exception
     */
    public function deleteArticle($id)
    {
        throw new \Exception('У Вас нет прав удалить статью. Ни у кого нет прав удалить статью...');
        return $this->repository->delete($id);
    }


    /**
     * @param $id
     * @return mixed
     */
    public function disableArticle($id)
    {
        return $this->repository->update($id, ['status' => Article::STATUS_DISABLED]);
    }

}

<?php


namespace App\Services;


use App\Entities\Article;
use App\Repositories\ArticleRepository;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\File;

class ArticleService
{

    /**
     * @var ArticleRepository
     */
    private $repository;

    public function __construct(ArticleRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getById($id)
    {
        return $this->repository->findOne($id);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getByIdWithComments($id)
    {
        return $this->repository->findOneWithComments($id);
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
        $article = $this->repository->findOne($id);
        return $this->repository->update($id, ['views' => ($article->views + 1)]);
    }


    /**
     * @param $id
     * @return mixed
     */
    public function likeArticle($id)
    {
        $article = $this->repository->findOne($id);
        return $this->repository->update($id, ['likes' => ($article->likes + 1)]);
    }


    /**
     * @param array $article
     * @return mixed
     */
    public function createArticle(array $article)
    {
        $faker = Faker::create();

        $filepath = public_path('storage/images');

        if (!File::exists($filepath)) {
            File::makeDirectory($filepath);
        }

        $article['likes'] = 0;
        $article['views'] = 0;
        $article['image'] = $faker->image($filepath, 400, 300, false, false);

        return $this->repository->create($article);
    }


    /**
     * @param $id
     * @param array $article
     * @return mixed
     */
    public function updateArticle($id, array $article)
    {
        return $this->repository->update($id, $article);
    }


    /**
     * @param $id
     * @return mixed
     */
    public function deleteArticle($id)
    {
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

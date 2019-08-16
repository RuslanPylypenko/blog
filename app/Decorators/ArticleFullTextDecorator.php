<?php


namespace App\Decorators;


use App\Entities\Article;

class ArticleFullTextDecorator implements DecoratorInterface
{
    /**
     * @var Article
     */
    private $article;

    public function __construct(Article $article)
    {
        $this->article = $article;
    }

    /**
     * @return Article
     */
    public function decorate()
    {
        $this->article->text = 'Чтобы прочитать полностю статью, купите ее. ' . route('articles.buy', ['id' => $this->article->id]);
        return $this->article;
    }
}
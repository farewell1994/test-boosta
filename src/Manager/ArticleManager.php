<?php

namespace App\Manager;

use App\Entity\Article;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class ArticleManager
 * @package App\Manager
 */
class ArticleManager
{
    /** @var EntityManagerInterface */
    private $manager;

    /**
     * ArticleManager constructor.
     * @param EntityManagerInterface $manager
     */
    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @param Article $article
     * @return Article
     */
    public function save(Article $article): Article
    {
        $this->manager->persist($article);
        $this->manager->flush();

        return $article;
    }

    /**
     * @param Article $article
     * @return Article
     */
    public function update(Article $article): Article
    {
        $article->setEdited(new \DateTime());
        $this->manager->flush();

        return $article;
    }

    /**
     * @param Article $article
     * @return Article
     */
    public function delete(Article $article): Article
    {
        $this->manager->remove($article);
        $this->manager->flush();

        return $article;
    }
}

<?php

namespace App\Manager;

use App\Entity\Article;
use App\Service\ExtensionChain;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class ArticleManager
 * @package App\Manager
 */
class ArticleManager
{
    /** @var EntityManagerInterface */
    private $manager;

    /** @var ExtensionChain */
    private $chain;

    /**
     * ArticleManager constructor.
     * @param EntityManagerInterface $manager
     * @param ExtensionChain $chain
     */
    public function __construct(EntityManagerInterface $manager, ExtensionChain $chain)
    {
        $this->chain = $chain;
        $this->manager = $manager;
    }

    /**
     * @param Article $article
     * @param UploadedFile|null $file
     * @return Article
     */
    public function save(Article $article, UploadedFile $file = null): Article
    {
        if ($file) {
            $article = $this->rewriteArticleFromFile($article, $file);
        }

        $this->manager->persist($article);
        $this->manager->flush();

        return $article;
    }

    /**
     * @param Article $article
     * @param UploadedFile|null $file
     * @return Article
     */
    public function update(Article $article, UploadedFile $file = null): Article
    {
        if ($file) {
            $article = $this->rewriteArticleFromFile($article, $file);
        }

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

    /**
     * @param Article $article
     * @param UploadedFile $file
     * @return Article
     */
    private function rewriteArticleFromFile(Article $article, UploadedFile $file): Article
    {
        $driver = $this->chain->getDriver($file->getClientMimeType());
        $uploadedArticle = $driver->convertContentToArticle($file->getContent());

        return $article
            ->setAuthor($uploadedArticle->getAuthor())
            ->setTitle($uploadedArticle->getTitle())
            ->setDescription($uploadedArticle->getDescription());
    }
}

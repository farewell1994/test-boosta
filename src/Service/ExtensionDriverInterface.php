<?php

namespace App\Service;

use App\Entity\Article;

/**
 * Interface ExtensionDriverInterface
 * @package App\Service
 */
interface ExtensionDriverInterface
{
    /**
     * @param $content
     * @return Article
     */
    public function convertContentToArticle($content): Article;

    /**
     * @param Article $article
     * @return bool
     */
    public function isValidContent(Article $article): bool;

    /**
     * @return array|null
     */
    public function getValidErrors(): ?array;
}

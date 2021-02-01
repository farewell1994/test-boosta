<?php

namespace App\Service;

use App\Entity\Article;

/**
 * Class JsonDriver
 * @package App\Service
 */
class JsonDriver extends BaseDriver implements ExtensionDriverInterface
{
    const JSON_MIME_TYPE = "application/json";

    /**
     * @param $content
     * @return Article
     */
    public function convertContentToArticle($content): Article
    {
        $array = json_decode($content, true);

        return (new Article())
            ->setAuthor($array["author"] ?? null)
            ->setTitle($array["title"] ?? null)
            ->setDescription($array["description"] ?? null);
    }
}

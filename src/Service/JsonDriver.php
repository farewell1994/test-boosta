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
            ->setAuthor((string) ($array["author"] ?? null))
            ->setTitle((string) ($array["title"] ?? null))
            ->setDescription((string) ($array["description"] ?? null));
    }
}

<?php

namespace App\Service;

use App\Entity\Article;

/**
 * Class CsvDriver
 * @package App\Service
 */
class CsvDriver extends BaseDriver implements ExtensionDriverInterface
{
    const CSV_MIME_TYPE = "text/csv";

    /**
     * @param $content
     * @return Article
     */
    public function convertContentToArticle($content): Article
    {
        list($author, $title, $description) = str_getcsv($content);

        return (new Article())
            ->setAuthor($author)
            ->setTitle($title)
            ->setDescription($description);
    }
}

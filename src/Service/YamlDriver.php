<?php

namespace App\Service;

use App\Entity\Article;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;

/**
 * Class YamlDriver
 * @package App\Service
 */
class YamlDriver extends BaseDriver implements ExtensionDriverInterface
{
    const YAML_MIME_TYPE = "application/x-yaml";

    /**
     * @param $content
     * @return Article
     */
    public function convertContentToArticle($content): Article
    {
        try {
            $array = Yaml::parse($content);
        } catch (ParseException $e) {
            $this->validErrors[] = "article.incorrect_yaml_structure";
            $array = [];
        }

        return (new Article())
            ->setAuthor($array["author"] ?? null)
            ->setTitle($array["title"] ?? null)
            ->setDescription($array["description"] ?? null);
    }
}

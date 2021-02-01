<?php

namespace App\Service;

use App\Entity\Article;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class YamlDriver
 * @package App\Service
 */
class YamlDriver extends BaseDriver implements ExtensionDriverInterface
{
    const YAML_MIME_TYPE = "application/x-yaml";

    /** @var TranslatorInterface */
    private $translator;

    /**
     * YamlDriver constructor.
     * @param ValidatorInterface $validator
     * @param TranslatorInterface $translator
     */
    public function __construct(ValidatorInterface $validator, TranslatorInterface $translator)
    {
        parent::__construct($validator);
        $this->translator = $translator;
    }

    /**
     * @param $content
     * @return Article
     */
    public function convertContentToArticle($content): Article
    {
        try {
            $array = Yaml::parse($content);
        } catch (ParseException $e) {
            $this->validErrors[] = $this->translator->trans("article.incorrect_yaml_structure");
            $array = [];
        }

        return (new Article())
            ->setAuthor($array["author"] ?? null)
            ->setTitle($array["title"] ?? null)
            ->setDescription($array["description"] ?? null);
    }
}

<?php

namespace App\Service;

use App\Entity\Article;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class BaseDriver
 * @package App\Service
 */
class BaseDriver
{
    /** @var ValidatorInterface */
    private $validator;

    /** @var array */
    protected $validErrors = [];

    /**
     * BaseDriver constructor.
     * @param ValidatorInterface $validator
     */
    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @param Article $article
     * @return bool
     */
    public function isValidContent(Article $article): bool
    {
        $errors = $this->validator->validate($article);

        if (!$errors->count()) {
            return true;
        }

        /** @var ConstraintViolation $error
         */
        foreach ($errors as $error) {
            $this->validErrors[] = sprintf(
                "%s (%s)",
                $error->getMessage(),
                $error->getPropertyPath()
            );
        }

        return false;
    }

    /**
     * @return array|null
     */
    public function getValidErrors(): ?array
    {
        return $this->validErrors;
    }
}

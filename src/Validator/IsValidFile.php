<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * Class IsValidFile
 * @Annotation
 * @package App\Validator
 */
class IsValidFile extends Constraint
{
    public $message = "article.not_allowed_extension";
}

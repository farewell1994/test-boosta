<?php

namespace App\Validator;

use App\Service\{
    CsvDriver, ExtensionChain, JsonDriver, YamlDriver
};
use Symfony\Component\Validator\{
    Constraint, ConstraintValidator
};
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * Class IsValidFileValidator
 * @package App\Validator
 */
class IsValidFileValidator extends ConstraintValidator
{
    /** @var ExtensionChain */
    private $chain;

    /**
     * IsValidFileValidator constructor.
     * @param ExtensionChain $chain
     */
    public function __construct(ExtensionChain $chain)
    {
        $this->chain = $chain;
    }

    /**
     * @param UploadedFile $value
     * @param Constraint $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof IsValidFile) {
            throw new UnexpectedTypeException($constraint, IsValidFile::class);
        }

        if (null === $value) {
            return;
        }

        $clientMimeType = $value->getClientMimeType();
        // usual File Constraint don't used because it read text/csv as text/plain
        $allowedMimeTypes = [CsvDriver::CSV_MIME_TYPE, JsonDriver::JSON_MIME_TYPE, YamlDriver::YAML_MIME_TYPE];

        if (!in_array($clientMimeType, $allowedMimeTypes)) {
            $this->context
                ->buildViolation("article.not_allowed_extension")
                ->addViolation();

            return;
        }

        $driver = $this->chain->getDriver($clientMimeType);
        $article = $driver->convertContentToArticle($value->getContent());
        $driver->isValidContent($article);

        if ($errors = $driver->getValidErrors()) {
            foreach ($errors as $error) {
                $this->context
                    ->buildViolation($error)
                    ->addViolation();
            }
        }
    }
}

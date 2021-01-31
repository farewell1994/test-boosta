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

    /**
     * @var ExtensionChain
     */
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
        // don't use usual File Constraint because it read text/csv as text/plain
        $allowedMimeTypes = [CsvDriver::CSV_MIME_TYPE, JsonDriver::JSON_MIME_TYPE, YamlDriver::YAML_MIME_TYPE];

        if (!in_array($clientMimeType, $allowedMimeTypes)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', "Mime type {$clientMimeType} is not allowed")
                ->addViolation();

            return;
        }

        $driver = $this->chain->getDriver($clientMimeType);
        $article = $driver->convertContentToArticle($value->getContent());

        if(!$isValidContent = $driver->isValidContent($article)) {
            foreach ($driver->getValidErrors() as $error) {
                $this->context->buildViolation($constraint->message)
                    ->setParameter('{{ value }}', $error)
                    ->addViolation();
            }
        }
    }
}

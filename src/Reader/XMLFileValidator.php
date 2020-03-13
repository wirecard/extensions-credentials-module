<?php
/**
 * Shop System Extensions:
 * - Terms of Use can be found at:
 * https://github.com/wirecard/extension-credentials-module/blob/master/_TERMS_OF_USE
 * - License can be found under:
 * https://github.com/wirecard/extension-credentials-module/blob/master/LICENSE
 */

namespace Wirecard\Credentials\Reader;

use DOMDocument;
use Exception;
use Wirecard\Credentials\Exception\InvalidXMLFormatException;

/**
 * Class XMLFileValidator
 * @package Wirecard\Credentials\Reader
 */
class XMLFileValidator implements FileValidatorInterface
{
    /** @var bool */
    private $throwError = false;

    /**
     * @param bool $throwError
     * @return XMLFileValidator
     */
    public function setThrowError($throwError)
    {
        $this->throwError = $throwError;
        return $this;
    }

    /**
     * @var string
     */
    const XML_SCHEMA_FILE_NAME = "schema.xsd";

    /**
     * @return string
     * @since 1.0.0
     */
    private function getXMLSchemaPath()
    {
        return sprintf(
            "%s/%s",
            dirname(dirname(__DIR__)),
            self::XML_SCHEMA_FILE_NAME
        );
    }

    /**
     * @param string $filePath
     * @return bool
     * @throws InvalidXMLFormatException
     * @since 1.0.0
     * @SuppressWarnings(PHPMD.UnusedLocalVariable)
     */
    public function validate($filePath)
    {
        $result = false;
        set_error_handler(function ($type, $message, $file, $line) {
            throw new InvalidXMLFormatException(
                "Error {$message} with type: {$type} was occurred in {$file} on line {$line}"
            );
        });
        try {
            $dom = new DOMDocument();
            $dom->load($filePath);
            $result = $dom->schemaValidate($this->getXMLSchemaPath());
        } catch (Exception $e) {
            if ($this->throwError) {
                throw new InvalidXMLFormatException($e->getMessage());
            }
        }
        restore_error_handler();

        return $result;
    }
}

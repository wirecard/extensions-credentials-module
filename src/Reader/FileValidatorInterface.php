<?php
/**
 * Shop System Extensions:
 * - Terms of Use can be found at:
 * https://github.com/wirecard/extension-credentials-module/blob/master/_TERMS_OF_USE
 * - License can be found under:
 * https://github.com/wirecard/extension-credentials-module/blob/master/LICENSE
 */

namespace Wirecard\Credentials\Reader;

/**
 * Interface FileValidatorInterface
 * @package Wirecard\Credentials\Reader
 */
interface FileValidatorInterface
{
    /**
     * @param string $filePath
     * @return bool
     */
    public function validate($filePath);
}

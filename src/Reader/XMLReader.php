<?php
/**
 * Shop System Extensions:
 * - Terms of Use can be found at:
 * https://github.com/wirecard/extension-credentials-module/blob/master/_TERMS_OF_USE
 * - License can be found under:
 * https://github.com/wirecard/extension-credentials-module/blob/master/LICENSE
 */

namespace Wirecard\Credentials\Reader;

use Wirecard\Credentials\PaymentMethodRegistry;
use Wirecard\Credentials\Exception\InvalidXMLFormatException;
use DOMDocument;
use DOMXPath;

/**
 * Class XMLReader
 * @package Credentials\Reader
 * @since 1.0.0
 */
class XMLReader implements ReaderInterface
{
    /**
     * @var string
     */
    private $rawXML;

    /**
     * XMLReader constructor.
     * @param string $filePath
     * @throws InvalidXMLFormatException
     * @since 1.0.0
     */
    public function __construct($filePath)
    {
        $xmlFileValidator = new XMLFileValidator();
        $xmlFileValidator
            ->setThrowError(true)
            ->validate($filePath);
        $this->rawXML = file_get_contents($filePath);
    }


    /**
     * @since 1.0.0
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function toArray()
    {
        $credentials = [];
        $domDocument = new DOMDocument();
        $domDocument->loadXML($this->rawXML);
        $xPath = new DOMXPath($domDocument);
        foreach (PaymentMethodRegistry::availablePaymentMethods() as $paymentMethod) {
            $paymentMethodXPath = $xPath->query(
                "/config/payment_methods/{$paymentMethod}"
            )->item(0);
            if (!$paymentMethodXPath) {
                continue;
            }
            /** @var \DOMNode $child */
            foreach ($paymentMethodXPath->childNodes as $child) {
                if ($child->nodeType != XML_ELEMENT_NODE) {
                    continue;
                }
                $credentials[$paymentMethod][$child->nodeName] = $child->nodeValue;
            }
        }

        return $credentials;
    }
}

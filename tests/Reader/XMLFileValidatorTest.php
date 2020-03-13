<?php
/**
 * Shop System Extensions:
 * - Terms of Use can be found at:
 * https://github.com/wirecard/extension-credentials-module/blob/master/_TERMS_OF_USE
 * - License can be found under:
 * https://github.com/wirecard/extension-credentials-module/blob/master/LICENSE
 */

namespace CredentialsReaderTest\Reader;

use Wirecard\Credentials\Exception\InvalidXMLFormatException;
use Wirecard\Credentials\Reader\XMLFileValidator;
use PHPUnit\Framework\TestCase;
use Generator;

/**
 * Class XMLReaderTest
 * @package CredentialsReaderTest\Reader
 * @coversDefaultClass \Wirecard\Credentials\Reader\XMLFileValidator
 * @since 1.0.0
 */
class XMLFileValidatorTest extends TestCase
{
    /**
     * @return Generator
     */
    public function dataProviderValid()
    {
        $mockFilePath = dirname(__FILE__) . '/Stubs';
        yield ["{$mockFilePath}/valid_xml_file.xml", true];
        yield ["{$mockFilePath}/valid_xml_empty.xml", true];
    }

    /**
     * @return Generator
     */
    public function dataProviderInvalid()
    {
        $mockFilePath = dirname(__FILE__) . '/Stubs';
        yield "invalid_xml_file_invalid_payment_method" =>
        ["{$mockFilePath}/invalid_xml_file_invalid_payment_method.xml"];
        yield "invalid_xml_file_missing_payment_name" =>
        ["{$mockFilePath}/invalid_xml_file_missing_payment_name.xml"];
        yield "invalid_xml_file_missing_required_field" =>
        ["{$mockFilePath}/invalid_xml_file_missing_required_field.xml"];
        yield "invalid_xml_file_wrong_order" =>
        ["{$mockFilePath}/invalid_xml_file_wrong_order.xml"];
    }

    /**
     * @group unit
     * @small
     * @covers ::validate
     * @dataProvider dataProviderValid
     * @param string $filePath
     * @param bool $isValid
     * @throws InvalidXMLFormatException
     */
    public function testValidateValid($filePath, $isValid)
    {
        $validator = new XMLFileValidator();
        $this->assertEquals($isValid, $validator->validate($filePath));
        $validator->setThrowError(true);
        $this->assertEquals($isValid, $validator->validate($filePath));
    }

    /**
     * @group unit
     * @small
     * @covers ::validate
     * @dataProvider dataProviderInvalid
     * @param string $filePath
     * @throws InvalidXMLFormatException
     */
    public function testInvalidXMLWithoutThrowError($filePath)
    {
        $validator = new XMLFileValidator();
        $this->assertEquals(false, $validator->validate($filePath));
    }

    /**
     * @group unit
     * @small
     * @covers ::validate
     * @dataProvider dataProviderInvalid
     * @param string $filePath
     * @throws InvalidXMLFormatException
     */
    public function testInvalidXMLWithThrowError($filePath)
    {
        $this->expectException(InvalidXMLFormatException::class);
        (new XMLFileValidator())
            ->setThrowError(true)
            ->validate($filePath);
    }
}

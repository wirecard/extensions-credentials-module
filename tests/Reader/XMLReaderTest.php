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
use Wirecard\Credentials\Reader\XMLReader;
use PHPUnit\Framework\TestCase;
use Generator;
use PHPUnit_Framework_MockObject_MockObject;

/**
 * Class XMLReaderTest
 * @package CredentialsReaderTest\Reader
 * @coversDefaultClass \Wirecard\Credentials\Reader\XMLReader
 * @since 1.0.0
 */
class XMLReaderTest extends TestCase
{
    /**
     * @return Generator
     */
    public function plainXMLDataProvider()
    {
        $mockFilePath = dirname(__FILE__) . '/Stubs';
        yield "sample_xml_raw_data" => [
            "{$mockFilePath}/valid_xml_file.xml",
            [
                "creditcard" => [
                    'merchant_account_id' => 'merchant_account_id',
                    'secret' => 'secret',
                    'base_url' => 'https://api-test.wirecard.com',
                    'http_user' => 'user',
                    'http_pass' => 'password',
                    'wpp_url' => 'https://wpp-test.wirecard.com',
                    'three_d_merchant_account_id' => 'three_d_merchant_account_id',
                    'three_d_secret' => 'three_d_secret',
                ],
                "paypal" => [
                    'merchant_account_id' => 'merchant_account_id',
                    'secret' => 'secret',
                    'base_url' => 'https://api-test.wirecard.com',
                    'http_user' => 'user',
                    'http_pass' => 'password',
                ]
            ]];
    }

    /**
     * @group unit
     * @small
     * @covers ::toArray()
     * @dataProvider plainXMLDataProvider
     * @param string $data
     * @param array $expectedResult
     * @throws InvalidXMLFormatException
     */
    public function testToArray($data, $expectedResult)
    {
        /** @var XMLReader | PHPUnit_Framework_MockObject_MockObject $reader */
        $reader = new XMLReader($data);
        $this->assertEquals($expectedResult, $reader->toArray());
    }
}

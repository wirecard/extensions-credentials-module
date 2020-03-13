<?php
/**
 * Shop System Extensions:
 * - Terms of Use can be found at:
 * https://github.com/wirecard/extension-credentials-module/blob/master/_TERMS_OF_USE
 * - License can be found under:
 * https://github.com/wirecard/extension-credentials-module/blob/master/LICENSE
 */

ini_set("display_errors", true);

$basePath = dirname(dirname(__FILE__));
require_once $basePath . "/vendor/autoload.php";

$credentialFilePath = dirname(__FILE__) . DIRECTORY_SEPARATOR . "default_credentials.xml";
try {
    $module = new Wirecard\Credentials\Credentials($credentialFilePath);
    $module->validateSource($credentialFilePath);
} catch (\Exception $e) {
    print_r($e->getMessage() . PHP_EOL);
    exit(0);
}

try {
    $paymentMethod = new Wirecard\Credentials\PaymentMethod(
        Wirecard\Credentials\PaymentMethodRegistry::TYPE_CREDIT_CARD
    );
    $config = $module->getConfigByPaymentMethod($paymentMethod);
    print_r($config->getBaseUrl() . PHP_EOL);
    print_r($config->getMerchantAccountId() . PHP_EOL);
} catch (\Exception $ex) {
    print_r($ex->getMessage() . PHP_EOL);
    exit(0);
}

try {
    foreach ($module->getConfig() as $paymentMethod => $config) {
        print_r($paymentMethod . ":" . $config->getBaseUrl() . PHP_EOL);
        print_r($paymentMethod . ":" . $config->getMerchantAccountId() . PHP_EOL);
    }
} catch (\Exception $e) {
    print_r($e->getMessage() . PHP_EOL);
    exit(0);
}

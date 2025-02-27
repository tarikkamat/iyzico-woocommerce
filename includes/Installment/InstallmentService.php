<?php

namespace Iyzico\IyzipayWoocommerce\Installment;

use Iyzico\IyzipayWoocommerce\Checkout\CheckoutSettings;
use Iyzipay\Model\InstallmentInfo;
use Iyzipay\Options;
use Iyzipay\Request\RetrieveInstallmentInfoRequest;

class InstallmentService
{
    private $checkoutSettings;

    public function __construct()
    {
        $this->checkoutSettings = new CheckoutSettings();
    }

    private function getInstallmentResponse()
    {
        $options = $this->createOptions();
        $request = $this->createReq();
        $response = InstallmentInfo::retrieve($request, $options);

        if ($response->getStatus() === "success") {
            return $response;
        }

        return null;
    }

    public function getInstallmentRates($update = false)
    {
        $installmentData = get_option('iyzico_installment_rates', array());

        if (empty($installmentData) || $update) {
            $response = $this->getInstallmentResponse();
            if ($response !== null) {
                foreach ($response->getInstallmentDetails() as $installment) {
                    foreach ($installment->getInstallmentPrices() as $prices) {
                        $installmentData[$installment->getCardFamilyName()][$prices->getInstallmentNumber()] = strval($prices->getTotalPrice() - 100);
                    }
                }
            }

            if (array_key_exists('Cardfinans', $installmentData)) {
                $installmentData['Advantage'] = $installmentData['Cardfinans'];
            }
            
            ksort($installmentData);
            update_option('iyzico_installment_rates', $installmentData);
        }

        return $installmentData;
    }

    private function createOptions(): Options
    {
        $options = new Options();
        $options->setApiKey($this->checkoutSettings->findByKey('api_key'));
        $options->setSecretKey($this->checkoutSettings->findByKey('secret_key'));
        $options->setBaseUrl($this->checkoutSettings->findByKey('api_type'));

        return $options;
    }

    private function createReq(): RetrieveInstallmentInfoRequest
    {
        $settingsLang = $this->checkoutSettings->findByKey('form_language');
        if ($settingsLang === null || strlen($settingsLang) === 0 || $settingsLang === false) {
            $language = "tr";
        } else {
            $language = strtolower($settingsLang);
        }

        $req = new RetrieveInstallmentInfoRequest();
        $req->setLocale($language);
        $req->setPrice("100.0");

        return $req;
    }
}

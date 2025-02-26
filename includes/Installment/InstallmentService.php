<?php

namespace Iyzico\IyzipayWoocommerce\Installment;

use Iyzico\IyzipayWoocommerce\Checkout\CheckoutSettings;
use Iyzipay\Model\InstallmentHtml;
use Iyzipay\Options;
use Iyzipay\Request\RetrieveInstallmentInfoRequest;

class InstallmentService
{
    private $checkoutSettings;

    public function __construct()
    {
        $this->checkoutSettings = new CheckoutSettings();
    }

    public function getInstallmentHtml(): string
    {
        $options = $this->createOptions();
        $request = $this->createReq();
        $response = InstallmentHtml::retrieve($request, $options);

        if ($response->getStatus() === "success") {
            return $response->getHtmlContent();
        }

        return "";
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
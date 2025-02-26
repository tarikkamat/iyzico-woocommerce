<?php

namespace Iyzico\IyzipayWoocommerce\Installment;

use Iyzico\IyzipayWoocommerce\Checkout\CheckoutSettings;

class InstallmentTab
{
    private $checkoutSettings;
    private $installmentService;

    public function __construct()
    {
        $this->checkoutSettings = new CheckoutSettings();
        $this->installmentService = new InstallmentService();

        if ($this->checkoutSettings->findByKey('iyzico_product_tab_enabled') === 'yes') {
            add_filter('woocommerce_product_tabs', [$this, 'addInstallmentTab']);
        }
    }

    public function addInstallmentTab($tabs)
    {
        $tabs['iyzico_installment'] = [
            'title'    => __('Installment', 'iyzico-woocommerce'),
            'priority' => 50,
            'callback' => [$this, 'installmentTabContent']
        ];

        return $tabs;
    }

    public function installmentTabContent()
    {
        echo $this->installmentService->getInstallmentHtml();
    }
} 
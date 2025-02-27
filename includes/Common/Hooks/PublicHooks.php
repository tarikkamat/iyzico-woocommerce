<?php

namespace Iyzico\IyzipayWoocommerce\Common\Hooks;

use Iyzico\IyzipayWoocommerce\Checkout\CheckoutForm;
use Iyzico\IyzipayWoocommerce\Common\Helpers\BuyerProtection;
use Iyzico\IyzipayWoocommerce\Common\Helpers\WebhookHelper;


class PublicHooks
{

    private $checkoutForm;
    private $buyerProtection;
    private $webhookHelper;

    public function __construct()
    {
        $this->checkoutForm = new CheckoutForm();
        $this->buyerProtection = new BuyerProtection();
        $this->webhookHelper = new WebhookHelper();
    }

    public function register()
    {
        add_action('rest_api_init', [$this->webhookHelper, 'addRoute']);

        add_action('woocommerce_receipt_iyzico', [$this->checkoutForm, 'load_form']);
        add_action('woocommerce_receipt_iyzico', [$this->checkoutForm, 'checkout_form']);
        add_action('woocommerce_api_request', [$this->checkoutForm, 'handle_api_request']);
        add_action('woocommerce_before_checkout_form', [$this->checkoutForm, 'display_errors'], 10);

        add_action('wp_footer', [$this->buyerProtection, 'iyzicoOverlayScriptMobileCss']);
        add_action('wp_enqueue_scripts', [$this->buyerProtection, 'enqueue_iyzico_overlay_script']);
        add_action('wp_enqueue_scripts', [$this, 'installmentLoad']);
    }

    public function installmentLoad()
    {
        wp_register_script('iyzico-installment-composite', IYZICO_PLUGIN_ASSETS_DIR_URL . '/js/iyzico-installment-composite.js');
        wp_register_style('iyzico-installment-settings', IYZICO_PLUGIN_ASSETS_DIR_URL . '/css/iyzico-installment-settings.css');
        wp_register_script('iyzico-installment-vertical-with-jq', IYZICO_PLUGIN_ASSETS_DIR_URL . '/js/iyzico-installment-vertical-with-jq.js');
        wp_register_style('iyzico-installment-vertical-with-jq', IYZICO_PLUGIN_ASSETS_DIR_URL . '/css/iyzico-installment-vertical-with-jq.css');
    }
}

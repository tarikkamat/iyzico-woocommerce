<?php

namespace Iyzico\IyzipayWoocommerce\Checkout;

use Iyzico\IyzipayWoocommerce\Common\Abstracts\Config;

class CheckoutSettings extends Config
{
    public $optionsTableKey = 'woocommerce_iyzico_settings';
    public $form_fields = [];

    public function __construct()
    {
        $webhookUrl = get_site_url() . "/wp-json/iyzico/v1/webhook/" . get_option('iyzicoWebhookUrlKey');
        $categories = get_terms([
            'taxonomy' => 'product_cat',
            'hide_empty' => false,
        ]);

        $category_options = [];
        if (!empty($categories) && !is_wp_error($categories)) {
            foreach ($categories as $category) {
                $category_options[$category->term_id] = $category->name;
            }
        }

        $installment_fields = [];
        for ($i = 1; $i <= 12; $i++) {
            $installment_fields["installment_$i"] = [
                'title' => sprintf(__('%d Installment', 'iyzico-woocommerce'), $i),
                'type' => 'checkbox',
                'label' => sprintf(__('%d Installment', 'iyzico-woocommerce'), $i),
                'default' => 'no',
                'custom_attributes' => [
                    'data-installment-row' => 'true',
                    'style' => 'margin-right: 10px;'
                ],
                'wrapper_attributes' => [
                    'style' => 'display: inline-block; margin-right: 15px;'
                ]
            ];
        }

        $this->form_fields = [
            'webhook_url' => [
                'title' => __('Webhook URL', 'iyzico-woocommerce'),
                'type' => 'title',
                'description' => $webhookUrl,
            ],
            'api_type' => [
                'title' => __('Environment', 'iyzico-woocommerce'),
                'type' => 'select',
                'required' => true,
                'default' => 'https://sandbox-api.iyzipay.com',
                'options' => [
                    'https://api.iyzipay.com' => __('Live', 'iyzico-woocommerce'),
                    'https://sandbox-api.iyzipay.com' => __('Sandbox / Test', 'iyzico-woocommerce')
                ],
            ],
            'api_key' => [
                'title' => __('API Key', 'iyzico-woocommerce'),
                'type' => 'text'
            ],
            'secret_key' => [
                'title' => __('Secret Key', 'iyzico-woocommerce'),
                'type' => 'text'
            ],
            'title' => [
                'title' => __('Payment Value', 'iyzico-woocommerce'),
                'type' => 'text',
                'description' => __('This message will show to the user during checkout.', 'iyzico-woocommerce'),
                'default' => __('Pay with Bank/Debit Card', 'iyzico-woocommerce')
            ],
            'description' => [
                'title' => __('Payment Form Description Value', 'iyzico-woocommerce'),
                'type' => 'text',
                'description' => __('This controls the description which the user sees during checkout.', 'iyzico-woocommerce'),
                'default' => __('Pay with your credit card or debit card via iyzico.', 'iyzico-woocommerce')
            ],
            'form_class' => [
                'title' => __('Payment Form Design', 'iyzico-woocommerce'),
                'type' => 'select',
                'default' => 'popup',
                'options' => [
                    'responsive' => __('Responsive', 'iyzico-woocommerce'),
                    'popup' => __('Popup', 'iyzico-woocommerce'),
                    'redirect' => __('Redirect', 'iyzico-woocommerce')
                ]
            ],
            'payment_checkout_value' => [
                'title' => __('Payment Checkout Value', 'iyzico-woocommerce'),
                'type' => 'text',
                'description' => __('This controls the description which the user sees during checkout.', 'iyzico-woocommerce'),
                'default' => __(
                    'Thank you for your order, please enter your card information in the payment form below to pay with iyzico checkout.',
                    'iyzico-woocommerce'
                ),
                'desc_tip' => true,
            ],
            'order_status' => [
                'title' => __('Order Status', 'iyzico-woocommerce'),
                'type' => 'select',
                'description' => __('Recommended, Default', 'iyzico-woocommerce'),
                'default' => 'default',
                'options' => [
                    'default' => __('Default', 'iyzico-woocommerce'),
                    'pending' => __('Pending', 'iyzico-woocommerce'),
                    'processing' => __('Processing', 'iyzico-woocommerce'),
                    'on-hold' => __('On-Hold', 'iyzico-woocommerce'),
                    'completed' => __('Completed', 'iyzico-woocommerce'),
                    'cancelled' => __('Cancelled', 'iyzico-woocommerce'),
                    'refunded' => __('Refunded', 'iyzico-woocommerce'),
                    'failed' => __('Failed', 'iyzico-woocommerce')
                ]
            ],
            'overlay_script' => [
                'title' => __('Buyer Protection - Logo', 'iyzico-woocommerce'),
                'type' => 'select',
                'required' => false,
                'default' => 'left',
                'options' => [
                    'bottomLeft' => __('Left', 'iyzico-woocommerce'),
                    'bottomRight' => __('Right', 'iyzico-woocommerce'),
                    'hide' => __('Hide', 'iyzico-woocommerce')
                ]
            ],
            'form_language' => [
                'title' => __('Payment Form Language', 'iyzico-woocommerce'),
                'type' => 'select',
                'required' => true,
                'default' => 'TR',
                'options' => [
                    'TR' => __('Turkish', 'iyzico-woocommerce'),
                    'EN' => __('English', 'iyzico-woocommerce')
                ]
            ],
            'affiliate_network' => [
                'title' => __('Affiliate Network', 'iyzico-woocommerce'),
                'type' => 'text',
                'required' => false,
                'description' => __('Payment source for agency', 'iyzico-woocommerce'),
                'default' => '',
                'custom_attributes' => ['maxlength' => 14]
            ],
            'enabled' => [
                'title' => __('Enable/Disable', 'iyzico-woocommerce'),
                'label' => __('Enable iyzico Checkout Form', 'iyzico-woocommerce'),
                'type' => 'checkbox',
                'default' => 'no'
            ],
            'request_log_enabled' => [
                'title' => __('Request Log', 'iyzico-woocommerce'),
                'label' => __('Enable request log', 'iyzico-woocommerce') . " (wp-content/plugins/iyzico-woocommerce/logs_files)",
                'type' => 'checkbox',
                'default' => 'no',
            ],
            'iyzico_product_tab_enabled' => [
                'title' => __('Installment Tab', 'iyzico-woocommerce'),
                'label' => __('Enable Installment Tab for Product Page', 'iyzico-woocommerce'),
                'type' => 'checkbox',
                'default' => 'no',
            ],
            'category_installments' => [
                'title' => __('Category-Based Installment Settings', 'iyzico-woocommerce'),
                'type' => 'title',
                'description' => __("You can customize the applicable installment options for each category. Important Note: On the payment page, if there are products from multiple different categories in the cart, the installment options will be determined based on the category offering the fewest installment options. Similarly, if a product belongs to more than one category, the installment options will be applied based on the category with the lowest number of installments.", 'iyzico-woocommerce'),
            ],
            'category_select' => [
                'title' => __('Select Category', 'iyzico-woocommerce'),
                'type' => 'select',
                'options' => $category_options,
                'class' => 'wc-enhanced-select',
            ],
            'installment_options_group' => [
                'title' => __('Installment Options', 'iyzico-woocommerce'),
                'type' => 'title',
                'description' => __('Please mark the applicable installment options for the selected category.', 'iyzico-woocommerce'),
                'wrapper_attributes' => [
                    'style' => 'margin-bottom: 0;'
                ]
            ],
        ];

        $this->form_fields = array_merge($this->form_fields, $installment_fields);
        $this->form_fields['category_installment_mapping'] = [
            'type' => 'hidden',
            'default' => '{}',
        ];

        $this->defaultSettings = [];
        foreach ($this->form_fields as $key => $field) {
            $this->defaultSettings[$key] = $field['default'] ?? '';
        }
    }

    public function getFormFields()
    {
        return $this->form_fields;
    }
}
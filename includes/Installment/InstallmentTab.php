<?php

namespace Iyzico\IyzipayWoocommerce\Installment;

use Iyzico\IyzipayWoocommerce\Checkout\CheckoutSettings;

class InstallmentTab
{
    private $checkoutSettings;
    private $installmentService;
    private $displayType;

    public function __construct()
    {
        $this->checkoutSettings = new CheckoutSettings();
        $this->installmentService = new InstallmentService();

        if ($this->checkoutSettings->findByKey('product_tab_installment_settings') != 'no') {
            $this->displayType = $this->checkoutSettings->findByKey('product_tab_installment_settings');
            add_filter('woocommerce_product_tabs', [$this, 'addInstallmentTab']);
        }
    }

    public function addInstallmentTab($tabs)
    {
        $tabs['iyzico_installment'] = [
            'title'    => __('Installment', 'iyzico-woocommerce'),
            'priority' => 16,
            'callback' => function() {
				global $product;
				$this->installmentPrepareTable( $product );
				$this->getInstallmentView(
					$this->displayType,
					array(
						'price' => wc_get_price_including_tax( $product ),
						'rates' => $this->installmentService->getInstallmentRates(),
					)
				);
			}
        ];

        return $tabs;
    }

    private function getInstallmentView( $view = '', $args = array() ) {
        if ( $view ) {
    
            if ( array_key_exists( $view, wp_styles()->registered ) ) {
                wp_enqueue_style( $view );
            }
    
            if ( array_key_exists( $view, wp_scripts()->registered ) ) {
                wp_enqueue_script( $view );
            }
    
            if ( ! empty( $args ) && is_array( $args ) ) {
                extract( $args ); // phpcs:ignore
            }
    
            include IYZICO_PLUGIN_DIR_PATH . 'views/' . $view . '.php';
        }
    }
    
    private function installmentPrepareTable( $product ) {
        if ( method_exists( $product, 'get_type' ) ) {
            $product_type = $product->get_type();
            if ( in_array( $product_type, array( 'composite', 'variable' ), true ) ) {
                wp_enqueue_script( "iyzico-installment-{$product_type}" );
            }
        }
    }
} 
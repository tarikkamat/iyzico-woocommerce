<?php

namespace Iyzico\IyzipayWoocommerce\Common\Helpers;

use Automattic\WooCommerce\Blocks\Payments\PaymentMethodRegistry;
use Automattic\WooCommerce\Utilities\FeaturesUtil;
use Iyzico\IyzipayWoocommerce\Checkout\BlocksCheckoutMethod;
use Iyzico\IyzipayWoocommerce\Pwi\BlocksPwiMethod;

/**
 * Class BlocksCheckoutSupport
 *
 * @package Iyzico\IyzipayWoocommerce\Checkout
 */
class BlocksSupport {

	/**
	 * @return void
	 */
	public static function init(): void {
		add_action( 'woocommerce_blocks_loaded', [ self::class, 'woocommerce_blocks_support' ] );

		add_action( 'before_woocommerce_init', function () {
			error_log( 'WooCommerce block support loaded.' );
		} );

		add_action( 'before_woocommerce_init', [ self::class, 'woocommerce_blocks_compatibility' ] );
	}

	/**
	 * @return void
	 */
	public static function woocommerce_blocks_support(): void {
		if ( ! class_exists( 'Automattic\WooCommerce\Blocks\Payments\Integrations\AbstractPaymentMethodType' ) ) {
			return;
		}

		add_action(
			'woocommerce_blocks_payment_method_type_registration',
			function ( PaymentMethodRegistry $payment_method_registry ) {
				$payment_method_registry->register( new BlocksCheckoutMethod );
				$payment_method_registry->register( new BlocksPwiMethod );
			}
		);
	}

	/**
	 * @return void
	 */
	public static function woocommerce_blocks_compatibility(): void {
		if ( class_exists( '\Automattic\WooCommerce\Utilities\FeaturesUtil' ) ) {
			FeaturesUtil::declare_compatibility(
				'cart_checkout_blocks',
				PLUGIN_BASEFILE,
				true
			);
		}
	}
}

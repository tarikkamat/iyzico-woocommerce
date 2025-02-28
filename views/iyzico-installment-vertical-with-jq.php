<?php
wp_localize_script(
	'iyzico-installment-vertical-with-jq',
	'iyzicoInstallmentObject',
	array(
		'price'    => $price,
		'rates'    => $rates,
		'assetUrl' => IYZICO_PLUGIN_ASSETS_DIR_URL,
		'symbol'   => get_woocommerce_currency_symbol( get_woocommerce_currency() ),
	)
);
?>
<b>Bilgilendirme:</b> Yapıkredi'nin World kredi kartlarına bankanın kısıtlaması sebebiyle en fazla iki taksit yapılabilmektedir. Diğer banka World kredi kartları için aşağıdaki taksitler geçerlidir.
<div class="iyzico-installment-container"></div>

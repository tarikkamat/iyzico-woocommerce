<div id="app">
	<div class="iyzico-installment-container">
		<table class="iyzico-installment-table">
			<tr  v-for="(data,family) in rates" :style="getColor(family)">
				<td><img :src="`${assetUrl}/images/${family}.png`" alt="Kredi Kartı Taksit" min-width="70" min-height="30"></td>
				<template  v-for="(rate , installment) in data">
				<td>
					<p>{{ calculate(rate) }} <span v-html="symbol" /></p>
					<p><small>{{installment}} x {{ calculate(rate,installment) }} <span v-html="symbol" /></small></p>
				</td>
				</template>
			</tr>			
		</table>
	</div>
</div>
<script src="https://cdn.jsdelivr.net/npm/vue@2"></script>
<script>
	new Vue({
		el:"#app",
		data:{
			price : window.iyzicoInstallmentPrice,
			rates : <?php echo json_encode( $rates ); ?>,
			assetUrl : "<?php echo esc_url( IYZICO_PLUGIN_ASSETS_DIR_URL ); ?>",
			symbol : "<?php echo get_woocommerce_currency_symbol( get_woocommerce_currency() ); ?>"
		},
		mounted(){
			setInterval(() => {
				this.price = window.iyzicoInstallmentPrice ? window.iyzicoInstallmentPrice : <?php echo $price; ?>
			},100)
		},
		methods:{
			calculate(rate, installment = 1){
				rate = parseFloat(rate)
				let price = parseFloat(this.price)
				let lastPrice = ((rate / 100 ) * price ) + price;
				let result = new Intl.NumberFormat('tr-TR', { style: 'currency', currency: 'TRY' ,minimumFractionDigits: 2}).format(lastPrice / installment).replace('₺','');
				return result
			},
			getColor(family){
				let bgColor = '#fff';
				let style = {
					color:'#333'
				}
				switch(family){
					case 'Maximum':
						bgColor = 'rgb(236, 1, 140, 0.07)'
						break;
					case 'Cardfinans':
						bgColor = 'rgb(41, 74, 164 ,0.07)'
						break;
					case 'Paraf':
						bgColor = 'rgb(3, 220, 255 ,0.07)'
						break;
					case 'World':
						bgColor = 'rgb(157, 105, 167 ,0.07)'
						break;
					case 'Axess':
						bgColor = 'rgb(254, 195, 14 ,0.07)'
						break;
					case 'Bonus':
						bgColor = 'rgb(100, 194, 90 ,0.07)'
						break;
					case 'BankkartCombo':
						bgColor = 'rgb(236, 12, 16 ,0.07)'
						break;
					case 'Advantage':
						bgColor = 'rgb(235, 114, 79 ,0.07)'
						break;
					default:
						bgColor = ''
						break;
				}

				style['background-color'] = bgColor;
				return style;
			}
		}
	})
</script>
<style>
	.iyzico-installment-container{
		width: 100%;
		display: flex;
		flex-wrap: wrap;
		justify-content: center;
		align-items: center;
	}
	.iyzico-installment-table{
		margin-top: 15px;	
		border: 1px solid #ddd;
	} 
	.iyzico-installment-table tr,td{
		text-align: center;
		border: 0 !important;
	}
	.iyzico-installment-table tr td p{
		padding: 0 !important;
		margin: 0 !important;
		font-weight: bold;
	}

	.iyzico-installment-table tr td p small{

		font-weight: lighter;
	}

	.iyzico-installment-table tr td:first-of-type{
		text-align: left;
	}
</style>

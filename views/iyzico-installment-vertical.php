<div id="app">
	<div class="iyzico-installment-container">
		<div class="iyzico-installment-container" v-for="(data,family) in rates">
					<center><img :src="`${assetUrl}/images/${family}.png`" alt="kredi kartı taksit"  min-width="70" min-height="30"></center>
			<table class="iyzico-installment-table">
				<tr :style="getColor(family)">
					<td>Taksit Sayısı</td>
					<td>Taksit Tutarı</td>
					<td>Toplam</td>
				</tr>			
				<tr  v-for="(rate , installment) in data">
					<td>{{ installment }}</td>
					<td>{{ calculate(rate , installment) }} <span v-html="symbol" /></td>
					<td>{{ calculate(rate) }} <span v-html="symbol" /></td>
				</tr>
			</table>
		</div>
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
					color:'white'
				}
				switch(family){
					case 'Maximum':
						bgColor = '#EC018C'
						break;
					case 'Cardfinans':
						bgColor = '#294AA4'
						break;
					case 'Paraf':
						bgColor = '#03DCFF'
						break;
					case 'World':
						bgColor = '#9D69A7'
						break;
					case 'Axess':
						bgColor = '#FEC30E'
						break;
					case 'Bonus':
						bgColor = '#64C25A'
						break;
					case 'BankkartCombo':
						bgColor = '#EC0C10'
						break;
					case 'Advantage':
						bgColor = '#EB724F'
						break;
						case 'SağlamKart':
						bgColor = '#006748'
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
	.iyzico-installment-container{
		width: 33%;
		padding: 10px;
		box-sizing: border-box;
	}

	.iyzico-installment-container img{
		max-height: 22px;
		object-fit: cover;
	}
	.iyzico-installment-table{
		margin-top: 15px;	
		color:#767676;
		border: 1px solid #ddd;
	}
	.iyzico-installment-table tr:nth-of-type(even){
		background-color: #eee;
	}

	.iyzico-installment-table tr,td{
		text-align: center;
		border: 0 !important;
	}

	@media (min-width: 1281px) { 
		.iyzico-installment-container {
			width: 33% !important;
		}
	}
	@media  (min-width: 1025px) and (max-width: 1280px) { 
		.iyzico-installment-container {
			width: 33% !important;
		}
	}
		@media  (min-width: 768px) and (max-width: 1024px) { 
		.iyzico-installment-container {
			width: 50% !important;
		}
	}
	@media  (min-width: 768px) and (max-width: 1024px) and (orientation: landscape) { 
		.iyzico-installment-container {
			width: 50% !important;
		}
	}
	@media  (min-width: 481px) and (max-width: 767px) { 
		.iyzico-installment-container {
			width: 100% !important;
		}
	}
	@media  (min-width: 320px) and (max-width: 480px) { 
		.iyzico-installment-container {
			width: 100% !important;
		}
	}
</style>

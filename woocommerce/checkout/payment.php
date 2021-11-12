<?php

/**
 * Checkout Payment Section
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/payment.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.5.3
 */

defined('ABSPATH') || exit;
$realizarPedidos = true;
$activarMetodoDePago = true;
$activarMetodoDePago = $activarMetodoDePago ? "" : "display: none;";
$validate = precor_userHasPaymentExpiry(get_current_user_id());
if ($validate) {
	$realizarPedidos = false;
}
?>
<!-- inicio de metodo de pago -->
<div style="<?= $activarMetodoDePago ?>">
	<?php
	if (!is_ajax()) {
		do_action('woocommerce_review_order_before_payment');
	}
	?>
	<?php
	if (!is_ajax()) {
	?>
		<h3 class="text-center " style="color: black; font-size: 16px;">Metodo de pago Actual : <b class="texto-precor-azul" id="methodPaymentTitlePrecor"></b></h3>
		<button class=' button-precor bg-precor-azul' type='button' id='btnShowModalMethodPayment'>Elegir otro Metodo de Pago</button>

	<?php }
	?>
	<?php
	if (!is_ajax()) {
	?>
		<div id="myModalMethodPayment" class="modalProducts">

			<div class="modalContainerProducts">
				<div class="modalHeaderProducts">
					<h4 style="color: white !important; margin-bottom: 0; ">Metodos de Pago</h4>
					<div>
						<button class="button-precor text-white btn-cerrar-method-payment" type="button" id="hiddeModalMethodPayment" style="background-color: #69daf5 !important; max-width: 80px; margin-bottom: 0;">Aceptar</button>
					</div>
				</div>
				<!-- <div class="modaltotalKgWoocommerce">
			<p></p>
			<p>Peso Total: kg</p>
		</div> -->

				<!-- de aqui para abajo se autogenera con la peticion ajax -->
				<div class="modalContentProducts">
				<?php } ?>
				<div id="payment" class="woocommerce-checkout-payment">

					<?php
					if (WC()->cart->needs_payment()) : ?>
						<ul class="wc_payment_methods payment_methods methods">
							<?php
							if (!empty($available_gateways)) {
								foreach ($available_gateways as $gateway) {
									wc_get_template('checkout/payment-method.php', array('gateway' => $gateway));
								}
							} else {
								echo '<li class="woocommerce-notice woocommerce-notice--info woocommerce-info">' . apply_filters('woocommerce_no_available_payment_methods_message', WC()->customer->get_billing_country() ? esc_html__('Sorry, it seems that there are no available payment methods for your state. Please contact us if you require assistance or wish to make alternate arrangements.', 'woocommerce') : esc_html__('Please fill in your details above to see available payment methods.', 'woocommerce')) . '</li>'; // @codingStandardsIgnoreLine
							}
							?>
						</ul>
					<?php endif; ?>

				</div>
				<?php
				if (!is_ajax()) {
				?>
				</div>
			</div>

		</div>
	<?php } ?>


</div>
<!-- fin de metodos de pagos -->

<?php
// este
if (!is_ajax()) {
?>

	<?php
	?>
	<div class="form-row place-order precor-botones">
		<noscript>
			<?php
			/* translators: $1 and $2 opening and closing emphasis tags respectively */
			printf(esc_html__('Since your browser does not support JavaScript, or it is disabled, please ensure you click the %1$sUpdate Totals%2$s button before placing your order. You may be charged more than the amount stated above if you fail to do so.', 'woocommerce'), '<em>', '</em>');
			?>
			<br /><button type="submit" class="button alt" name="woocommerce_checkout_update_totals" value="<?php esc_attr_e('Update totals', 'woocommerce'); ?>"><?php esc_html_e('Update totals', 'woocommerce'); ?></button>
		</noscript>

		<?php wc_get_template('checkout/terms.php'); ?>

		<?php do_action('woocommerce_review_order_before_submit'); ?>

		<?php
		if ($realizarPedidos) {
			echo apply_filters('woocommerce_order_button_html', '<button type="submit" class="button alt" name="woocommerce_checkout_place_order" id="place_order" value="' . esc_attr($order_button_text) . '" data-value="' . esc_attr($order_button_text) . '">' .
				esc_html($order_button_text) . '</button>');
			// @codingStandardsIgnoreLine 
		} else {
		?>

			<br>
			<h3 style="text-align: center;">Ud. tiene documentos de pago vencidos</h3>
		<?php } ?>

		<?php do_action('woocommerce_review_order_after_submit'); ?>

		<?php wp_nonce_field('woocommerce-process_checkout', 'woocommerce-process-checkout-nonce'); ?>
	</div>
<?php } ?>
<?php
if (!is_ajax()) {
?>
	<script>
		let shippingMethodBackup = document.querySelector('#precor_backup_shipping_method');
		let modalMethodPayment = document.querySelector('#myModalMethodPayment');
		let btnMethodPayment = document.getElementById('btnShowModalMethodPayment');
		let btnHiddeMethodPayment = document.getElementById('hiddeModalMethodPayment');

		btnHiddeMethodPayment.onclick = function() {
			modalMethodPayment.style.display = 'none';
		};
		btnMethodPayment.onclick = function() {
			// let modalMethodPayment = document.querySelector('#myModalMethodPayment');
			modalMethodPayment.style.display = 'block';
		};

		const getCheckedPaymentMethodPrecor = () => {
			// solucion rapida
			setInterval(() => {
				let inputRadioAddressPrecorList = document.querySelectorAll('.input-radio-payment-method-precor');
				inputRadioAddressPrecorList.forEach(e => {
					let addressChecked = document.querySelector(`label[for=${e.id}]`)
					if (e.checked) {
						let paymentMethodTitle = addressChecked.getAttribute("data-payment-method");
						// soluciono de caracteres
						let indexBorrar = paymentMethodTitle.indexOf("<");
						paymentMethodTitle = paymentMethodTitle.slice(0, indexBorrar != -1 ? indexBorrar : paymentMethodTitle.length);
						document.querySelector("#methodPaymentTitlePrecor").innerText = paymentMethodTitle;
					}
				})


			}, 1000);
		}
		getCheckedPaymentMethodPrecor();

		// eventos de cerrardo de modal por delegacion de evento

		// setiar la direccion de la tienda maxco
		document.querySelector("body").addEventListener("click", (e) => {
			if (e.target.id === "shipping_method_0_local_pickup10") {
				// console.log("me diste click a recojo en tienda");
				const tiendaMaxcoDireccion = "Av. República de Panamá 4965, Surquillo";
				document.querySelector("#billing_address_1").value = tiendaMaxcoDireccion;
			}

		})
	</script>
<?php } ?>
<?php
if (!is_ajax()) {
	do_action('woocommerce_review_order_after_payment');
}
?>
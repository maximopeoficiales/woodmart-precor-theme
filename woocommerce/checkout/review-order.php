<?php

/**
 * Review order table
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/review-order.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.8.0
 */

defined('ABSPATH') || exit;
$totalProducts = count(WC()->cart->get_cart());
$pesoTotalKg = 0;
foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
	// logica para agregar peso
	$_product_id = $cart_item['product_id'];
	$peso = doubleval(get_post_meta($_product_id, 'peso', true))  * $cart_item['quantity'];
	$pesoTotalKg += doubleval((is_null($peso) || $peso == "") ?   0 : $peso);
}
?>
<?php if (!is_ajax()) { ?>

	<div class="contenedor-tabla">
	<?php } ?>
	<table class="shop_table woocommerce-checkout-review-order-table">
		<thead>
			<tr>
				<th class="product-name"><?php esc_html_e('Product', 'woocommerce'); ?></th>
				<th class="product-total"></th>
				<th class="product-total" style="text-align: right;"><?php esc_html_e('Subtotal', 'woocommerce'); ?></th>
			</tr>
		</thead>

		<tbody style="display: none;">
			<?php
			do_action('woocommerce_review_order_before_cart_contents');

			do_action('woocommerce_review_order_after_cart_contents');
			?>
		</tbody>
		<tfoot>

			<tr class="cart-subtotal">
				<th><?php esc_html_e('Subtotal', 'woocommerce'); ?></th>
				<td></td>
				<td><?php wc_cart_totals_subtotal_html(); ?></td>
			</tr>

			<?php foreach (WC()->cart->get_coupons() as $code => $coupon) : ?>
				<tr class="cart-discount coupon-<?php echo esc_attr(sanitize_title($code)); ?>">
					<th><?php wc_cart_totals_coupon_label($coupon); ?></th>
					<td></td>
					<td><?php wc_cart_totals_coupon_html($coupon); ?></td>
				</tr>
			<?php endforeach; ?>

			<?php if (WC()->cart->needs_shipping() && WC()->cart->show_shipping()) : ?>

				<?php do_action('woocommerce_review_order_before_shipping'); ?>

				<?php wc_cart_totals_shipping_html(); ?>

				<?php do_action('woocommerce_review_order_after_shipping'); ?>

			<?php endif; ?>

			<?php foreach (WC()->cart->get_fees() as $fee) : ?>
				<tr class="fee">
					<th><?php echo esc_html($fee->name); ?></th>
					<td></td>
					<td><?php wc_cart_totals_fee_html($fee); ?></td>
				</tr>
			<?php endforeach; ?>

			<?php if (wc_tax_enabled() && !WC()->cart->display_prices_including_tax()) : ?>
				<?php if ('itemized' === get_option('woocommerce_tax_total_display')) : ?>
					<?php foreach (WC()->cart->get_tax_totals() as $code => $tax) : // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited 
					?>
						<tr class="tax-rate tax-rate-<?php echo esc_attr(sanitize_title($code)); ?>">
							<th><?php echo esc_html($tax->label); ?></th>
							<td></td>
							<td><?php echo wp_kses_post($tax->formatted_amount); ?></td>
						</tr>
					<?php endforeach; ?>
				<?php else : ?>
					<tr class="tax-total">
						<th><?php echo esc_html(WC()->countries->tax_or_vat()); ?></th>
						<td></td>
						<td><?php wc_cart_totals_taxes_total_html(); ?></td>
					</tr>
				<?php endif; ?>
			<?php endif; ?>

			<?php do_action('woocommerce_review_order_before_order_total'); ?>

			<tr class="order-total">
				<th><?php esc_html_e('Total', 'woocommerce'); ?></th>
				<td></td>
				<td><?php wc_cart_totals_order_total_html(); ?></td>
			</tr>

			<?php do_action('woocommerce_review_order_after_order_total'); ?>

		</tfoot>
	</table>
	<?php if (!is_ajax()) { ?>
	</div>
<?php } ?>

<?php if (!is_ajax()) { ?>
	<script>
		setInterval(() => {
			document.querySelector(".contenedor-tabla").childNodes.forEach(e => {
				if (e.className != "shop_table woocommerce-checkout-review-order-table") {
					e.remove();
				}
			})
		}, 100);
	</script>
	<!-- Aqui se duplica lo que edites arriba porque hace una peticion ajax -->
	<!-- The Modal -->
	<div id="myModalProducts" class="modalProducts">

		<div class="modalContainerProducts">
			<div class="modalHeaderProducts">
				<h4 style="color: white !important; margin-bottom: 0;">Lista de Productos</h4>
				<div>
					<button class="button-precor text-white" type="button" id="hiddeModalProducts" style="background-color: #69daf5 !important; max-width: 80px; margin-bottom: 0;">Aceptar</button>
				</div>
			</div>
			<div class="modaltotalKgWoocommerce">
				<p></p>
				<p>Peso Total: <?= number_format($pesoTotalKg, 2) ?> kg</p>
			</div>

			<div class="modaltotalWoocommerce">
				<p>Cantidad de Productos: <?= $totalProducts ?></p>
				<p class="order-total">
					<span><?php esc_html_e('Total', 'woocommerce'); ?> : <?php wc_cart_totals_order_total_html(); ?></span>
				</p>
			</div>


			<!-- de aqui para abajo se autogenera con la peticion ajax -->

			<div class="modalContentProducts">
				<table class="">
					<thead>
						<tr>
							<th class="product-name"><?php esc_html_e('Product', 'woocommerce'); ?></th>
							<th class="product-name precor-display-none-sm">UND</th>
							<th class="product-name">Peso Total</th>
							<th class="product-name precor-display-none-sm">PZAS</th>
							<th class="product-name precor-display-none-sm">PAQ</th>
							<th class="product-total" style="text-align: right;"><?php esc_html_e('Subtotal', 'woocommerce'); ?></th>
						</tr>
					</thead>
				<?php } ?>
				<tbody>
					<?php
					foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
						// logica para agregar peso
						$_product_id = $cart_item['product_id'];
						$und = get_post_meta($_product_id, 'und', true);
						$paq = get_post_meta($_product_id, 'unxpaq', true) == "" ? "" : get_post_meta($_product_id, 'unxpaq', true);
						$pzas = 0;
						if ($paq == "") {
							$pzas = "";
						} else {
							$pzas = $cart_item['quantity'] / $paq;
						}
						$peso = doubleval(get_post_meta($_product_id, 'peso', true))  * $cart_item['quantity'];
						// $pesoTotalKg += doubleval((is_null($peso) || $peso == "") ?   0 : $peso);
						// // 
						$_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);

						if ($_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters('woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key)) {
					?>
							<tr class="<?php echo esc_attr(apply_filters('woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key)); ?>">
								<td class="product-name">
									<?php echo apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key) . '&nbsp;'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
									?>
									<?php echo apply_filters('woocommerce_checkout_cart_item_quantity', ' <strong class="product-quantity">' . sprintf('&times;&nbsp;%s', $cart_item['quantity']) . '</strong>', $cart_item, $cart_item_key); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
									?>
									<?php echo wc_get_formatted_cart_item_data($cart_item); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
									?>
								</td>

								<td class="product-total precor-display-none-sm">
									<?= $und ?>
								</td>
								<!-- aqui va el peso -->
								<td class="product-total ">
									<?= number_format(floatval($peso), 2)  ?>
								</td>
								

								<td class="product-total precor-display-none-sm">
									<?= $pzas ?>
								</td>
								<td class="product-total precor-display-none-sm">
									<?= $paq ?>
								</td>
								<td class="product-total" style="text-align: right;">
									<?php echo apply_filters('woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal($_product, $cart_item['quantity']), $cart_item, $cart_item_key); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
									?>
								</td>
							</tr>
					<?php
						}
					}
					?>
				</tbody>
				<tfoot>
					<?php $numColumnas = 5; ?>
					<tr class="cart-subtotal">
						<th><?php esc_html_e('Subtotal', 'woocommerce'); ?></th>
						<td colspan="<?= $numColumnas ?>" style="text-align: right;"><?php wc_cart_totals_subtotal_html(); ?></td>
					</tr>

					<?php foreach (WC()->cart->get_coupons() as $code => $coupon) : ?>
						<tr class="cart-discount coupon-<?php echo esc_attr(sanitize_title($code)); ?>">
							<th><?php wc_cart_totals_coupon_label($coupon); ?></th>
							<td></td>
							<td><?php wc_cart_totals_coupon_html($coupon); ?></td>
						</tr>
					<?php endforeach; ?>

					<?php if (WC()->cart->needs_shipping() && WC()->cart->show_shipping()) : ?>

						<?php do_action('woocommerce_review_order_before_shipping'); ?>

						<?php wc_cart_totals_shipping_html(); ?>

						<?php do_action('woocommerce_review_order_after_shipping'); ?>

					<?php endif; ?>

					<?php foreach (WC()->cart->get_fees() as $fee) : ?>
						<tr class="fee">
							<th><?php echo esc_html($fee->name); ?></th>
							<td></td>
							<td><?php wc_cart_totals_fee_html($fee); ?></td>
						</tr>
					<?php endforeach; ?>

					<?php if (wc_tax_enabled() && !WC()->cart->display_prices_including_tax()) : ?>
						<?php if ('itemized' === get_option('woocommerce_tax_total_display')) : ?>
							<?php foreach (WC()->cart->get_tax_totals() as $code => $tax) : // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited 
							?>
								<tr class="tax-rate tax-rate-<?php echo esc_attr(sanitize_title($code)); ?>">
									<th><?php echo esc_html($tax->label); ?></th>
									<td colspan="<?= $numColumnas ?>" style="text-align: right;"><?php echo wp_kses_post($tax->formatted_amount); ?></td>
								</tr>
							<?php endforeach; ?>
						<?php else : ?>
							<tr class="tax-total">
								<th><?php echo esc_html(WC()->countries->tax_or_vat()); ?></th>
								<td></td>
								<td><?php wc_cart_totals_taxes_total_html(); ?></td>
							</tr>
						<?php endif; ?>
					<?php endif; ?>


					<tr class="order-total">
						<th><?php esc_html_e('Total', 'woocommerce'); ?></th>
						<td colspan="<?= $numColumnas ?>" style="text-align: right;"><?php wc_cart_totals_order_total_html(); ?></td>
					</tr>


				</tfoot>

				<?php if (!is_ajax()) { ?>


				</table>
			</div>
		</div>
	</div>
<?php } ?>
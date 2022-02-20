<?php

/**
 * Pay for order form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-pay.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.4.0
 */

defined('ABSPATH') || exit;
// solucion a bug de descuento
if ($order->get_discount_total() == "0.01") {
	$order->set_discount_total(0);
	$order->save();
}
$totals = $order->get_order_item_totals(); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
// validacion de stock
$hasStockProducts = true;
$productNotHackStock = "";
if (count($order->get_items()) > 0) {
	foreach ($order->get_items() as $item_id => $item) :
		try {
			$sku = wc_get_product(intval($item->get_product_id()))->get_sku();
			$stock = intval($item->get_quantity());
			$response = precor_getStockBySkuAndIdSoc($sku, precor_getPrecorID());
			if ($response->status) {
				if (546546546 < intval($response->stock)) {
					$hasStockProducts = true;
				} else {
					$hasStockProducts = false;
					$productNotHackStock = $item->get_name();
					break;
				}
			}
		} catch (\Throwable $th) {
			echo $th;
		}
	endforeach;
}
?>
<?php if ($hasStockProducts) { ?>
	<form id="order_review" method="post">

		<table class="shop_table">
			<thead>
				<tr>
					<th class="product-name"><?php esc_html_e('Product', 'woocommerce'); ?></th>
					<th class="product-quantity"><?php esc_html_e('Qty', 'woocommerce'); ?></th>
					<th class="product-total"><?php esc_html_e('Totals', 'woocommerce'); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php if (count($order->get_items()) > 0) : ?>
					<?php foreach ($order->get_items() as $item_id => $item) : ?>
						<?php
						if (!apply_filters('woocommerce_order_item_visible', true, $item)) {
							continue;
						}

						?>

						<tr class="<?php echo esc_attr(apply_filters('woocommerce_order_item_class', 'order_item', $item, $order)); ?>">
							<td class="product-name">
								<?php
								echo apply_filters('woocommerce_order_item_name', esc_html($item->get_name()), $item, false); // @codingStandardsIgnoreLine

								do_action('woocommerce_order_item_meta_start', $item_id, $item, $order, false);

								wc_display_item_meta($item);

								do_action('woocommerce_order_item_meta_end', $item_id, $item, $order, false);
								?>
							</td>
							<td class="product-quantity"><?php echo apply_filters('woocommerce_order_item_quantity_html', ' <strong class="product-quantity">' . sprintf('&times;&nbsp;%s', esc_html($item->get_quantity())) . '</strong>', $item); ?></td><?php // @codingStandardsIgnoreLine 
																																																															?>
							<td class="product-subtotal"><?php echo $order->get_formatted_line_subtotal($item); ?></td><?php // @codingStandardsIgnoreLine 
																														?>
						</tr>
					<?php endforeach; ?>
				<?php endif; ?>
			</tbody>
			<tfoot>
				<?php if ($totals) : ?>
					<?php foreach ($totals as $total) :
						if ($total["value"] != "YITH Request a Quote") {

					?>
							<tr>
								<th scope="row" colspan="2"><?php echo $total['label']; ?></th><?php // @codingStandardsIgnoreLine 
																								?>
								<td class="product-total"><?php echo $total['value']; ?></td><?php // @codingStandardsIgnoreLine 
																								?>
							</tr>
					<?php }
					endforeach; ?>
				<?php endif;
				?>

			</tfoot>
		</table>
		<!-- boton de cambio a dolares -->
		<?php
		precor_show_button_change_currency($order);
		?>
		<?php
		$validate = precor_userHasPaymentExpiry(get_current_user_id());

		if (!$validate) {
			# code...
		?>
			<div id="payment">

				<?php if ($order->needs_payment()) : ?>
					<ul class="wc_payment_methods payment_methods methods">

						<?php
						if (!empty($available_gateways)) {
							foreach ($available_gateways as $gateway) {
								wc_get_template('checkout/payment-method.php', array('gateway' => $gateway));
							}
						} else {
							echo '<li class="woocommerce-notice woocommerce-notice--info woocommerce-info">' . apply_filters('woocommerce_no_available_payment_methods_message', esc_html__('Sorry, it seems that there are no available payment methods for your location. Please contact us if you require assistance or wish to make alternate arrangements.', 'woocommerce')) . '</li>'; // @codingStandardsIgnoreLine
						}
						?>
					</ul>
				<?php endif; ?>
				<div class="form-row">
					<input type="hidden" name="woocommerce_pay" value="1" />

					<?php wc_get_template('checkout/terms.php'); ?>

					<?php do_action('woocommerce_pay_order_before_submit'); ?>

					<?php echo apply_filters('woocommerce_pay_order_button_html', '<button type="submit" class="button alt" id="place_order" value="' . esc_attr($order_button_text) . '" data-value="' . esc_attr($order_button_text) . '">' . esc_html($order_button_text) . '</button>'); // @codingStandardsIgnoreLine 
					?>

					<?php do_action('woocommerce_pay_order_after_submit'); ?>

					<?php wp_nonce_field('woocommerce-pay', 'woocommerce-pay-nonce'); ?>
				</div>
			</div>

		<?php } else {
		?>
			<br>
			<h1 style="text-align: center;">Ud. tiene documentos de pago vencidos</h1>
		<?php
		} ?>
	</form>
<?php } else {  ?>
	<h4><?= get_option('precor_text_no_hay_stock') ? str_replace("@nombre_producto", $productNotHackStock, get_option('precor_text_no_hay_stock')) : "No hay stock para $productNotHackStock, por favor contacte con su ejecutivo de ventas."; ?></h1>
	<?php } ?>
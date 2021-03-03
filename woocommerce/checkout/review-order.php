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
?>
<style>
	.woocommerce-checkout-review-order-table {
		position: relative;
	}

	.woocommerce-checkout-review-order-table thead {
		position: sticky;
		top: 0;
	}
</style>

<table class="shop_table woocommerce-checkout-review-order-table">
	<thead>
		<tr>
			<th class="product-name"><?php esc_html_e('Product', 'woocommerce'); ?></th>
			<th class="product-name">Peso</th>
			<th class="product-total"><?php esc_html_e('Subtotal', 'woocommerce'); ?></th>
		</tr>
	</thead>
	<tbody style="display: none;">
		<?php
		do_action('woocommerce_review_order_before_cart_contents');

		foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
			// logica para agregar peso
			$_product_id = $cart_item['product_id'];
			$peso = get_post_meta($_product_id, 'peso', true);
			$pesoTotalKg += doubleval((is_null($peso) || $peso == "") ?   0 : $peso);
			// 
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
					<!-- aqui va el peso -->
					<td class="product-total">
						<?= number_format(floatval($peso), 2)  ?>
					</td>
					<td class="product-total">
						<?php echo apply_filters('woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal($_product, $cart_item['quantity']), $cart_item, $cart_item_key); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
						?>
					</td>
				</tr>
		<?php
			}
		}

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
<style>
	/* The Modal (background) */
	.modalProducts {
		display: none;
		/* Hidden by default */
		position: fixed;
		/* Stay in place */
		z-index: 1;
		/* Sit on top */
		left: 0;
		top: 0;
		width: 100%;
		/* Full width */
		height: 100vh;
		/* Full height */
		overflow: auto;
		/* Enable scroll if needed */
		background-color: rgb(0, 0, 0);
		/* Fallback color */
		background-color: rgba(0, 0, 0, 0.4);
		animation-name: animatetop;
		animation-duration: 0.4s;
		padding-top: 2rem;
		overflow: hidden;
		/* Black w/ opacity */
	}

	/* Modal Content/Box */
	.modalContainerProducts {
		background-color: #fefefe;
		margin: 15% auto;
		/* 15% from the top and centered */
		width: 90%;
		border-radius: 13px;
		/* Could be more or less, depending on screen size */
	}

	/* The Close Button */
	.closeModalProducts {
		color: #aaa;
		font-size: 28px;
	}

	.closeModalProducts:hover,
	.closeModalProducts:focus {
		color: #fefefe;
		text-decoration: none;
		cursor: pointer;
		font-weight: bold;

	}

	@keyframes animatetop {
		from {
			top: -300px;
			opacity: 0
		}

		to {
			top: 0;
			opacity: 1
		}
	}

	.modalHeaderProducts {
		padding: .5rem 1rem;
		display: flex;
		justify-content: space-between;
		align-items: center;
		background-color: #00396E;
		color: white;
	}

	.modalContentProducts {
		padding: 1rem;
		max-height: 400px;
		overflow-y: auto;
	}

	.modalContentProducts table tbody {
		display: table-row-group !important;
		overflow-y: auto !important;
	}



	.modaltotalWoocommerce {
		display: flex;
		justify-content: space-between;
		align-items: center;
		padding: .5rem 1.5rem;
		padding-bottom: 0;
		font-size: 14px !important;
	}

	.modaltotalWoocommerce span strong span {
		font-size: 14px !important;

	}

	.modaltotalKgWoocommerce {
		display: flex;
		justify-content: space-between;
		padding: .5rem 1.5rem;
		padding-bottom: 0;
		font-size: 14px !important;
	}

	@media(min-width: 1140px) {
		.modalProducts {
			padding-top: 0rem;
		}

		.modalContainerProducts {
			margin: 10% auto;
			max-height: 600px;
		}

		.modaltotalWoocommerce {
			font-size: 18px !important;
		}

		.modaltotalWoocommerce span strong span {
			font-size: 18px !important;

		}

		.modaltotalKgWoocommerce {
			font-size: 18px !important;
		}
	}
</style>
<!-- Aqui se duplica lo que edites arriba porque hace una peticion ajax -->
<!-- The Modal -->
<div id="myModalProducts" class="modalProducts">

	<div class="modalContainerProducts">
		<div class="modalHeaderProducts">
			<h4 style="color: white; margin-bottom: 0;">Lista de Productos</h4>
			<span class="closeModalProducts">&times;</span>
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
			<table class="shop_table woocommerce-checkout-review-order-table">
			</table>
		</div>
	</div>
</div>
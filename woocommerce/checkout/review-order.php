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

<?php
if (!is_ajax()) {
?>
	<style>
		loader {
			color: #5cdb43;
			font-size: 90px;
			text-indent: -9999em;
			overflow: hidden;
			width: 1em;
			height: 1em;
			border-radius: 50%;
			margin: 72px auto;
			position: relative;
			-webkit-transform: translateZ(0);
			-ms-transform: translateZ(0);
			transform: translateZ(0);
			-webkit-animation: load6 1.7s infinite ease, round 1.7s infinite ease;
			animation: load6 1.7s infinite ease, round 1.7s infinite ease;
		}

		@-webkit-keyframes load6 {
			0% {
				box-shadow: 0 -0.83em 0 -0.4em, 0 -0.83em 0 -0.42em, 0 -0.83em 0 -0.44em, 0 -0.83em 0 -0.46em, 0 -0.83em 0 -0.477em;
			}

			5%,
			95% {
				box-shadow: 0 -0.83em 0 -0.4em, 0 -0.83em 0 -0.42em, 0 -0.83em 0 -0.44em, 0 -0.83em 0 -0.46em, 0 -0.83em 0 -0.477em;
			}

			10%,
			59% {
				box-shadow: 0 -0.83em 0 -0.4em, -0.087em -0.825em 0 -0.42em, -0.173em -0.812em 0 -0.44em, -0.256em -0.789em 0 -0.46em, -0.297em -0.775em 0 -0.477em;
			}

			20% {
				box-shadow: 0 -0.83em 0 -0.4em, -0.338em -0.758em 0 -0.42em, -0.555em -0.617em 0 -0.44em, -0.671em -0.488em 0 -0.46em, -0.749em -0.34em 0 -0.477em;
			}

			38% {
				box-shadow: 0 -0.83em 0 -0.4em, -0.377em -0.74em 0 -0.42em, -0.645em -0.522em 0 -0.44em, -0.775em -0.297em 0 -0.46em, -0.82em -0.09em 0 -0.477em;
			}

			100% {
				box-shadow: 0 -0.83em 0 -0.4em, 0 -0.83em 0 -0.42em, 0 -0.83em 0 -0.44em, 0 -0.83em 0 -0.46em, 0 -0.83em 0 -0.477em;
			}
		}

		@keyframes load6 {
			0% {
				box-shadow: 0 -0.83em 0 -0.4em, 0 -0.83em 0 -0.42em, 0 -0.83em 0 -0.44em, 0 -0.83em 0 -0.46em, 0 -0.83em 0 -0.477em;
			}

			5%,
			95% {
				box-shadow: 0 -0.83em 0 -0.4em, 0 -0.83em 0 -0.42em, 0 -0.83em 0 -0.44em, 0 -0.83em 0 -0.46em, 0 -0.83em 0 -0.477em;
			}

			10%,
			59% {
				box-shadow: 0 -0.83em 0 -0.4em, -0.087em -0.825em 0 -0.42em, -0.173em -0.812em 0 -0.44em, -0.256em -0.789em 0 -0.46em, -0.297em -0.775em 0 -0.477em;
			}

			20% {
				box-shadow: 0 -0.83em 0 -0.4em, -0.338em -0.758em 0 -0.42em, -0.555em -0.617em 0 -0.44em, -0.671em -0.488em 0 -0.46em, -0.749em -0.34em 0 -0.477em;
			}

			38% {
				box-shadow: 0 -0.83em 0 -0.4em, -0.377em -0.74em 0 -0.42em, -0.645em -0.522em 0 -0.44em, -0.775em -0.297em 0 -0.46em, -0.82em -0.09em 0 -0.477em;
			}

			100% {
				box-shadow: 0 -0.83em 0 -0.4em, 0 -0.83em 0 -0.42em, 0 -0.83em 0 -0.44em, 0 -0.83em 0 -0.46em, 0 -0.83em 0 -0.477em;
			}
		}

		@-webkit-keyframes round {
			0% {
				-webkit-transform: rotate(0deg);
				transform: rotate(0deg);
			}

			100% {
				-webkit-transform: rotate(360deg);
				transform: rotate(360deg);
			}
		}

		@keyframes round {
			0% {
				-webkit-transform: rotate(0deg);
				transform: rotate(0deg);
			}

			100% {
				-webkit-transform: rotate(360deg);
				transform: rotate(360deg);
			}
		}
	</style>
<?php
	echo '  <div id="myModalProducts" class="modalProducts">
	<div class="modalContainerProducts">
		 <div class="modalHeaderProducts">
			  <h4 style="color: white !important; margin-bottom: 0;">Lista de Productos</h4>
			  <div>
				   <button class="button-precor text-white" type="button" id="hiddeModalProducts" style="background-color: #69daf5 !important; max-width: 80px; margin-bottom: 0;">Aceptar</button>
			  </div>
		 </div>';
	echo ' <div class="text-center my-2" id="spinner_modal" style="margin: 20px 10px;">
		<h3>Cargando....</h3>
		<div class="loader">Loading...</div>
		<br/>
   </div>';

	precor_generate_modal_products();
	echo "       </div>
	</div>";
}
?>
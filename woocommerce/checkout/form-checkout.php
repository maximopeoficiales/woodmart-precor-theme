<?php

/**
 * Checkout Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-checkout.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.5.0
 */

if (!defined('ABSPATH')) {
	exit;
}

//WC 3.5.0
if (function_exists('WC') && version_compare(WC()->version, '3.5.0', '<')) {
	wc_print_notices();
}

do_action('woocommerce_before_checkout_form', $checkout);

// If checkout registration is disabled and not logged in, the user cannot checkout.
if (!$checkout->is_registration_enabled() && $checkout->is_registration_required() && !is_user_logged_in()) {
	echo esc_html(apply_filters('woocommerce_checkout_must_be_logged_in_message', __('You must be logged in to checkout.', 'woocommerce')));
	return;
}

// filter hook for include new pages inside the payment method
$get_checkout_url = apply_filters('woocommerce_get_checkout_url', wc_get_checkout_url()); ?>

<form name="checkout" method="post" class="checkout woocommerce-checkout row" action="<?php echo esc_url($get_checkout_url); ?>" enctype="multipart/form-data">

	<div class="col-12 col-md-5 col-lg-6">

		<?php if ($checkout->get_checkout_fields()) : ?>

			<?php do_action('woocommerce_checkout_before_customer_details'); ?>

			<div class="row" id="customer_details">
				<div class="col-12">
					<?php do_action('woocommerce_checkout_billing'); ?>
				</div>


			</div>

			<?php do_action('woocommerce_checkout_after_customer_details'); ?>

		<?php endif; ?>

	</div>

	<div class="col-12 col-md-7 col-lg-6">
		<div class="col-12 " style="margin-bottom: 50px;">
			<?php do_action('woocommerce_checkout_shipping'); ?>
		</div>
		<div class="checkout-order-review">
			<h3 id="order_review_heading"><?php esc_html_e('Your order', 'woocommerce'); ?></h3>

			<?php do_action('woocommerce_checkout_before_order_review'); ?>

			<div id="order_review" class="woocommerce-checkout-review-order">
				<?php do_action('woocommerce_checkout_order_review'); ?>
			</div>

			<?php do_action('woocommerce_checkout_after_order_review'); ?>

		</div>
	</div>

</form>

<?php do_action('woocommerce_after_checkout_form', $checkout); ?>
<?php


?>

<script>
	try {

		window.addEventListener("DOMContentLoaded", async () => {
			const getDataUserIsSap = async () => {
				var myHeaders = new Headers();
				myHeaders.append("Content-Type", "application/json");

				var raw = JSON.stringify({
					"user_id": <?php echo get_current_user_id(); ?>
				});

				var requestOptions = {
					method: 'POST',
					headers: myHeaders,
					body: raw,
					redirect: 'follow'
				};

				let result = await (await (fetch("<?php echo get_site_url(); ?>/wp-json/precor_prfx/v1/get_data_user_sap", requestOptions))).json();
				return result;
			}

			let result = await getDataUserIsSap();
			console.log(result);
			if (result.status == 200) {
				document.getElementById("shipping_company").value = result.data.ruc ?? "";
				document.getElementById("shipping_first_name").value = result.data.nomb ?? "";
				document.getElementById("direccion_fiscal").value = result.data.drcfisc ?? "";
				if (document.querySelector(".shipping_address").style.display == "none") {
					document.querySelector("#ship-to-different-address-checkbox").click();
				}
			}
		});
	} catch (error) {

	}
</script>
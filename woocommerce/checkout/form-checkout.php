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
			// console.log(result);
			if (result.status == 200) {
				document.getElementById("shipping_company").value = result.data.ruc ?? "";
				document.getElementById("shipping_first_name").value = result.data.nomb ?? "";
				document.getElementById("direccion_fiscal").value = result.data.drcfisc ?? "";
				if (document.querySelector(".shipping_address").style.display == "none") {
					document.querySelector("#ship-to-different-address-checkbox").click();
				}
			}

			const postCodeAvaliable = [15866, 15865, 15861, 15856, 15851, 15846, 15842, 15841, 15837, 15836, 15831, 15829, 15828, 15824, 15823, 15822, 15818, 15817, 15816, 15812, 15811, 15809, 15806, 15804, 15803, 15801, 15594, 15593, 15498, 15494, 15491, 15487, 15483, 15479, 15476, 15472, 15468, 15464, 15461, 15457, 15453, 15449, 15446, 15442, 15438, 15434, 15431, 15427, 15423, 15419, 15416, 15412, 15408, 15408, 15404, 15401, 15333, 15332, 15331, 15328, 15327, 15326, 15324, 15324, 15321, 15320, 15319, 15318, 15316, 15314, 15313, 15312, 15311, 15307, 15306, 15304, 15302, 15301, 15123, 15122, 15121, 15121, 15118, 15117, 15116, 15113, 15112, 15109, 15108, 15106, 15103, 15102, 15101, 15096, 15094, 15093, 15088, 15087, 15086, 15084, 15083, 15082, 15081, 15079, 15076, 15074, 15073, 15072, 15067, 15066, 15064, 15063, 15058, 15057, 15056, 15054, 15049, 15048, 15047, 15046, 15039, 15038, 15037, 15036, 15036, 15034, 15033, 15026, 15024, 15023, 15022, 15021, 15019, 15018, 15012, 15011, 15009, 15008, 15007, 15006, 15004, 15003, 15001, 07076, 07071, 07066, 07061, 07056, 07051, 07046, 07041, 07036, 07031, 07021, 07016, 07011, 07006, 07001];

			setInterval(() => {
				let postalCode = document.getElementById("billing_postcode").value;
				if (postalCode) {
					const shippingDelivery = document.querySelector("#shipping_method li #shipping_method_0_flat_rate54");
					const localPickup = document.querySelector("#shipping_method_0_local_pickup56");

					if (postCodeAvaliable.includes(parseInt(postalCode))) {
						shippingDelivery.parentElement.style.display = "block";

					} else {
						shippingDelivery.parentElement.style.display = "none";
						localPickup.click();
					}

				}
			}, 1000);
		});
	} catch (error) {}
</script>
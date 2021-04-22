<?php

/**
 * Customer completed order email
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/customer-completed-order.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates\Emails
 * @version 3.7.0
 */

if (!defined('ABSPATH')) {
	exit;
}

/*
 * @hooked WC_Emails::email_header() Output the email header
 */
do_action('woocommerce_email_header', $email_heading, $email); ?>

<!-- imagen y titulo de compra realizada -->
<?php
precor_get_image_header_custom("Recurso 3.jpg");
?>
<h1 class="precor-title-email">¡Tu compra ha sido realizada!</h1>

<?php /* translators: %s: Customer first name */ ?>

<div class="precor-color-texto">
	<p><strong><?= $order->get_billing_first_name() ?></strong>, estas son tus compras realizadas:</p>
</div>
<!-- <p><?php printf(esc_html__('Hi %s,', 'woocommerce'), esc_html($order->get_billing_first_name())); ?></p> -->

<p><?php esc_html_e('We have finished processing your order.', 'woocommerce'); ?></p>
<?php

/*
 * @hooked WC_Emails::order_details() Shows the order details table.
 * @hooked WC_Structured_Data::generate_order_data() Generates structured data.
 * @hooked WC_Structured_Data::output_structured_data() Outputs structured data.
 * @since 2.5.0
 */
do_action('woocommerce_email_order_details', $order, $sent_to_admin, $plain_text, $email);

/*
 * @hooked WC_Emails::order_meta() Shows order meta data.
 */
do_action('woocommerce_email_order_meta', $order, $sent_to_admin, $plain_text, $email);

/*
 * @hooked WC_Emails::customer_details() Shows customer details
 * @hooked WC_Emails::email_address() Shows email address
 */

// do_action('woocommerce_email_customer_details', $order, $sent_to_admin, $plain_text, $email);

/**
 * Show user-defined additional content - this is set in each email's settings.
 */
// GRACIAS POR TU COMPRA
// if ($additional_content) {
// 	echo wp_kses_post(wpautop(wptexturize($additional_content)));
// }

/*
 * @hooked WC_Emails::email_footer() Output the email footer
 */
?>
<p><strong>Gracias por tu compra.</strong></p>
<div class="precor-color-texto">
	<p>Tu pedido será entregado en un plazo desde 24 a 48 horas. El equipo de programación de Precor se contactará con usted para coordinar el día y hora exacta de entrega.</p>
</div>
<?php
precor_buscas_algun_producto();
?>


<?php
do_action('woocommerce_email_footer', $email);
?>
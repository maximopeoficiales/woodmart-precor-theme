<?php

/**
 * Customer note email
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/customer-note.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates\Emails
 * @version 3.7.0
 */

if (!defined('ABSPATH')) {
	exit;
}
// obtendre id del usuario

/*
 * @hooked WC_Emails::email_header() Output the email header
 */
do_action('woocommerce_email_header', $email_heading, $email);
//  imagen de relleno 
precor_get_image_header_custom("Recurso 8.jpg");
?>


<h1 class="precor-title-email">¡Nota importante!</h1>

<?php /* translators: %s: Customer first name */ ?>
<p><?php printf(esc_html__('Hi %s,', 'woocommerce'), esc_html($order->get_billing_first_name())); ?></p>
<p><?php esc_html_e('The following note has been added to your order:', 'woocommerce'); ?></p>

<blockquote><?php echo wpautop(wptexturize(make_clickable($customer_note))); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
			?></blockquote>

<p><?php esc_html_e('As a reminder, here are your order details:', 'woocommerce'); ?></p>

<?php

/*
 * @hooked WC_Emails::order_details() Shows the order details table.
 * @hooked WC_Structured_Data::generate_order_data() Generates structured data.
 * @hooked WC_Structured_Data::output_structured_data() Outputs structured data.
 * @since 2.5.0
 */
// esta es la tabla de detalles del pedido
do_action('woocommerce_email_order_details', $order, $sent_to_admin, $plain_text, $email);

/*
 * @hooked WC_Emails::order_meta() Shows order meta data.
 */
do_action('woocommerce_email_order_meta', $order, $sent_to_admin, $plain_text, $email);

$type_order = $order->get_created_via() == "ywraq" ? "Cotizacion" : "Pedido";


precor_create_button_custom("#003b71", precor_get_link_order_quote_byOrder($order), "Ver $type_order aqui");
if ($order->get_created_via() == "ywraq") {
	// Se agregara contacto con ejecutivo solo se mostrara si es una cotizacion
	precor_contact_ejecutivo_byUserid($order->get_customer_id());
}



/*
 * @hooked WC_Emails::customer_details() Shows customer details
 * @hooked WC_Emails::email_address() Shows email address
 */
// Esto es el detalle de facturacion
// do_action( 'woocommerce_email_customer_details', $order, $sent_to_admin, $plain_text, $email );

/**
 * Show user-defined additional content - this is set in each email's settings.
 */
// ESTO ES EL GRACIAS POR LEERLO
// if ($additional_content) {
// 	echo wp_kses_post(wpautop(wptexturize($additional_content)));
// }

/*
 * @hooked WC_Emails::email_footer() Output the email footer
 */
do_action('woocommerce_email_footer', $email);

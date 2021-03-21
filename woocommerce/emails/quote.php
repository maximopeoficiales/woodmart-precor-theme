<?php

/**
 * This file belongs to the YIT Plugin Framework.
 *
 * This source file is subject to the GNU GENERAL PUBLIC LICENSE (GPL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.txt
 */

/**
 * HTML Template Email Send Quote
 *
 * @since   1.0.0
 * @author  YITH
 * @version 2.2.7
 * @package YITH Woocommerce Request A Quote
 *
 * @var $raq_data array
 * @var $email_heading array
 * @var $email string
 * @var $email_description string
 * @var $email_title string
 */

do_action('woocommerce_email_header', $email_heading, $email);


$order_id = yit_get_prop($order, 'id', true);
$exdata   = yit_get_prop($order, '_ywcm_request_expire', true);
// $user_name         = yit_get_prop($order, 'ywraq_customer_name', true);

if (function_exists('wc_format_datetime')) {
	$order_date = wc_format_datetime($order->get_date_created());
	$exdata     = new WC_DateTime($exdata, new DateTimeZone('UTC'));
	$exp_date   = wc_format_datetime($exdata);
} else {
	$date_format = isset($raq_data['lang']) ? ywraq_get_date_format($raq_data['lang']) : wc_date_format();
	$order_date  = date_i18n($date_format, strtotime(yit_get_prop($order, 'date_created', true)));
	$exp_date    = date_i18n($date_format, strtotime($exdata));
}

precor_get_image_header_custom("Recurso 4.jpg");
?>



<h1 class="precor-title-email">¡La solicitud de presupuesto a sido contestada!</h1>

<h2><?php printf(('%1$s %2$s %3$s'), apply_filters('wpml_translate_single_string', esc_html($email_title), 'admin_texts_woocommerce_ywraq_send_quote_reminder_settings', '[woocommerce_ywraq_send_quote_reminder_settings]email-title', $raq_data['lang']), esc_html(__('in', 'yith-woocommerce-request-a-quote')), esc_html($raq_data['order-number'])); // phpcs:ignore 
	?></h2>

<p><?php echo apply_filters('wpml_translate_single_string', wp_kses_post($email_description), 'admin_texts_woocommerce_ywraq_send_quote_settings', '[woocommerce_ywraq_send_quote_settings]email-description', $raq_data['lang']); // phpcs:ignore 
	?></p>

<?php if (get_option('ywraq_hide_table_is_pdf_attachment') === 'no' || get_option('ywraq_hide_table_is_pdf_attachment', '') === '') : ?>
	<p><strong><?php esc_html_e('Request date', 'yith-woocommerce-request-a-quote'); ?></strong>: <?php echo esc_html($order_date); ?></p>
	<?php if ('' !== $raq_data['expiration_data']) : ?>
		<p><strong><?php esc_html_e('Expiration date', 'yith-woocommerce-request-a-quote'); ?></strong>: <?php echo esc_html($exp_date); ?></p>
	<?php endif ?>

	<?php if (!empty($raq_data['admin_message'])) : ?>
		<p><?php echo wp_kses_post($raq_data['admin_message']); ?></p>
	<?php endif ?>

	<?php
	wc_get_template(
		'emails/quote-table.php',
		array(
			'order' => $order,
		),
		'',
		YITH_YWRAQ_TEMPLATE_PATH . '/'
	);
	?>
	<p></p>
<?php endif ?>
<p>
	<?php if (get_option('ywraq_show_accept_link') !== 'no') : ?>
		<?php precor_create_button_custom("#32CC52", esc_url(ywraq_get_accepted_quote_page($order)), ywraq_get_label('accept')) ?>
	<?php
	endif;

	echo (get_option('ywraq_show_accept_link') !== 'no' && get_option('ywraq_show_reject_link') !== 'no') ? '  ' : '';
	if (get_option('ywraq_show_reject_link') !== 'no') :
		precor_create_button_custom("#D91023", esc_url(ywraq_get_rejected_quote_page($order)), ywraq_get_label('reject'));
	?>
	<?php endif; ?>
</p>

<?php
$after_list = yit_get_prop($order, '_ywraq_request_response_after', true);
if ('' !== $after_list) :
?>
	<p><?php echo esc_html(apply_filters('ywraq_quote_after_list', nl2br($after_list), $order_id)); ?></p>
<?php endif; ?>



<?php
do_action('woocommerce_email_footer', $email);
?>
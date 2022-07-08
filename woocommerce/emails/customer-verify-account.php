<?php
/**
 * Customer verify account email
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/customer-verify-account.php.
 *
 * @author  YITH
 * @package YITH WooCommerce Customize My Account Page
 * @version 2.5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

?>

<?php do_action( 'woocommerce_email_header', $email_heading, $email );
precor_get_image_header_custom("Recurso 1.jpg");
?>
<h1 class="precor-title-email">¡Verifique su Cuenta!</h1>

<?php /* translators: %s Customer first name */ ?>
	<p><?php printf( esc_html__( 'Hola %s,', 'yith-woocommerce-customize-myaccount-page' ), esc_html( stripslashes( $customer->user_login ) ) ); ?></p>
<?php /* translators: %1$s: Site title */ ?>
	<p><?php printf( __( 'Gracias por crear una cuenta en %1$s.', 'yith-woocommerce-customize-myaccount-page' ), esc_html( $blogname ) ); ?></p>
<?php /* translators: %1$s: My Account link */ ?>
	<p><?php printf( __( 'Para completar el proceso de registro, debe verificar el correo electrónico de su cuenta haciendo clic en este enlace: <br>%1$s', 'yith-woocommerce-customize-myaccount-page' ), make_clickable( esc_url( $verify_url ) ) ); ?></p><?php // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped ?>
	<p><?php esc_html_e( 'Esperamos verte pronto.', 'yith-woocommerce-customize-myaccount-page' ); ?></p>
<?php /* translators: %1$s: Site title */ ?>
	<p><?php printf( __( 'Saludos, <br>%1$s ', 'yith-woocommerce-customize-myaccount-page' ), esc_html( $blogname ) ); ?></p>
<?php
do_action( 'woocommerce_email_footer', $email );

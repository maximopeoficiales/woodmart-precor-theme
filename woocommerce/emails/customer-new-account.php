<?php

/**
 * Customer new account email
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/customer-new-account.php.
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

defined('ABSPATH') || exit; ?>

<?php
$img = get_option('woocommerce_email_header_image');
$img = precor_url_domain_replace($img);
do_action('woocommerce_email_header', $email_heading, $email);
precor_get_image_header_custom("Recurso 1.jpg");
?>


<h1 class="precor-title-email">¡Bienvenido "<?= esc_html($user_login) ?>"!</h1>
<div class="precor-color-texto">
	<?php /* translators: %s: Customer username */ ?>
	<!-- <p><?php printf(esc_html__('Hi %s,', 'woocommerce'), esc_html($user_login)); ?></p> -->
	<?php /* translators: %1$s: Site title, %2$s: Username, %3$s: My account link */ ?>
	<!-- <p><?php printf(esc_html__('Thanks for creating an account on %1$s. Your username is %2$s. You can access your account area to view orders, change your password, and more at: %3$s', 'woocommerce'), esc_html($blogname), '<strong>' . esc_html($user_login) . '</strong>', ""); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
			//  make_clickable(esc_url(wc_get_page_permalink('myaccount')))
			?></p> -->

	<p>
		Tu registro ha sido realizado con exito, ahora podras acceder a la plataforma, pero primero cambie su contraseña.
	</p>

	<?php
	$user = get_user_by('login', $user_login);

	$adt_rp_key = get_password_reset_key($user);
	$user_login = $user->user_login;
	$link_reset_password = network_site_url("wp-login.php?action=rp&key=$adt_rp_key&login=" . rawurlencode($user_login), 'login');

	?>
	<!-- <h2><?php _e('Your account is now active!'); ?></h2> -->

	<?php if ('yes' === get_option('woocommerce_registration_generate_password') && $password_generated) : ?>
		<?php /* translators: %s: Auto generated password */ ?>
		<p><?php printf(esc_html__('Your password has been automatically generated: %s', 'woocommerce'), '<strong>' . esc_html($user_pass) . '</strong>'); ?></p>
	<?php endif;

	// se imprira el boton
	precor_create_button_custom("#003b71", esc_url($link_reset_password), "Cambie su contraseña aqui");
	?>




</div>

</div>
<?php
/**
 * Show user-defined additional content - this is set in each email's settings.
 */
// if ($additional_content) {
// 	echo wp_kses_post(wpautop(wptexturize($additional_content)));
// }

do_action('woocommerce_email_footer', $email);

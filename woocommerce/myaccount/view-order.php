<?php

/**
 * View Order
 *
 * Shows the details of a particular order on the account page.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/view-order.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.0.0
 */

defined('ABSPATH') || exit;

$notes = $order->get_customer_order_notes();
?>
<p>
    <?php
    printf(
        /* translators: 1: order number 2: order date 3: order status */
        esc_html__('El pedido %1$s se realizó el %2$s y está actualmente %3$s.'),
        '<mark class="order-number">' . get_post_meta($order->get_order_number(), "id_ped", true) . ' (#' . $order->get_order_number() . ')</mark>',
        '<mark class="order-date">' . precor_get_fecha_correcta($order) . '</mark>', // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        '<mark class="order-status">' . wc_get_order_status_name($order->get_status()) . '</mark>' // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    );
    ?>
</p>


<?php
// try {
// solucion rapido de eliminacion de nota que viene del webservice
if ($notes) {
    foreach ($notes as $index => $note) {
        if (strpos($note->comment_content, "ha sido mejorada")) {
            unset($notes[$index]);
        }
    }
}
// } catch (\Throwable $th) {
//     echo $th;
// }
?>
<?php if ($notes) { ?>
    <h2><?php esc_html_e('Order updates', 'woocommerce'); ?></h2>
    <ol class="woocommerce-OrderUpdates commentlist notes">
        <?php foreach ($notes as $note) {

        ?>
            <li class="woocommerce-OrderUpdate comment note">
                <div class="woocommerce-OrderUpdate-inner comment_container">
                    <div class="woocommerce-OrderUpdate-text comment-text">
                        <p class="woocommerce-OrderUpdate-meta meta"><?php echo date_i18n(esc_html__('l jS \o\f F Y, h:ia', 'woocommerce'), strtotime($note->comment_date)); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
                                                                        ?></p>
                        <div class="woocommerce-OrderUpdate-description description">
                            <?php echo wpautop(wptexturize($note->comment_content)); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
                            ?>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="clear"></div>
                </div>
            </li>
        <?php }; ?>
    </ol>
<?php }; ?>

<?php do_action('woocommerce_view_order', $order_id); ?>
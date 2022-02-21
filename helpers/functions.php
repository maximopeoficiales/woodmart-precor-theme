<?php

use Barn2\Plugin\WC_Product_Table\Data\Abstract_Product_Data,
     Barn2\Plugin\WC_Product_Table\Table_Args;

if (!class_exists('Barn2\Plugin\WC_Product_Table\Data\Abstract_Product_Data')) {
     return;
}

/**
 * Handles data retrieval for the 'role_price' column in the product table.
 */
class Product_Table_Price_Role_Column extends Abstract_Product_Data
{

     public function getPriceByRole($role)
     {
          $price = number_format(floatval($this->product->get_price()), 2);

          if (!empty($role)) {
               $priceRolJson = get_post_meta(intval($this->product->get_id()), "_role_base_price_$role")[0];
               if (!empty($priceRolJson)) {
                    $priceRol = maybe_unserialize($priceRolJson);
                    $price = number_format(floatval($priceRol["discount_value"]), 2);
                    if (empty($price)) {
                         $price = number_format(floatval($this->product->get_price()), 2);
                    }
               }
               return '<span class="woocs_price_code""><span class="woocommerce-Price-amount amount"><bdi>' . $price . '<span class="woocommerce-Price-currencySymbol">$</span></bdi></span> <small class="woocommerce-price-suffix">Sin IGV</small></span>';
          }

          return '<span class="woocs_price_code""><span class="woocommerce-Price-amount amount"><bdi>' . $price . '<span class="woocommerce-Price-currencySymbol">$</span></bdi></span> <small class="woocommerce-price-suffix">Sin IGV</small></span>';
     }
     public function get_data()
     {
          $user = get_userdata(get_current_user_id());
          $user_roles    = $user->roles;
          if (in_array('a1', $user_roles, true)) {
               return $this->getPriceByRole("a1");
          } else if (in_array('a2', $user_roles, true)) {
               return $this->getPriceByRole("a2");
          } else if (in_array('a3', $user_roles, true)) {
               return $this->getPriceByRole("a3");
          } else if (in_array('a4', $user_roles, true)) {
               return $this->getPriceByRole("a4");
          } else {
               return $this->getPriceByRole(null);
          }
     }
}
// add_action("init", function () {

// Get all the user roles as an array.
// $user = get_userdata(get_current_user_id());
// $user_roles    = $user->roles;
// print_r($user_roles[1]);
// // if (in_array('a1', $user_roles, true)) {
//      // Do something.
//      echo 'YES, User is a subscriber';
// }
// });
function precor_getSubtotalWithRoleProduct()
{
     $valueWithIGV = floatval(WC()->cart->get_total(""));
     $valueWithNotIgv = $valueWithIGV / (1.18);
     $value = '<strong>' . WC()->cart->get_total() . '</strong> ';
     $value = '<strong>' . number_format($valueWithNotIgv, 2) . '</strong> ';

     // If prices are tax inclusive, show taxes here.
     if (wc_tax_enabled() && WC()->cart->display_prices_including_tax()) {
          $tax_string_array = array();
          $cart_tax_totals  = WC()->cart->get_tax_totals();

          if (get_option('woocommerce_tax_total_display') === 'itemized') {
               foreach ($cart_tax_totals as $code => $tax) {
                    $tax_string_array[] = sprintf('%s %s', $tax->formatted_amount, $tax->label);
               }
          } elseif (!empty($cart_tax_totals)) {
               $tax_string_array[] = sprintf('%s %s', wc_price(WC()->cart->get_taxes_total(true, true)), WC()->countries->tax_or_vat());
          }

          if (!empty($tax_string_array)) {
               $taxable_address = WC()->customer->get_taxable_address();
               if (WC()->customer->is_customer_outside_base() && !WC()->customer->has_calculated_shipping()) {
                    $country = WC()->countries->estimated_for_prefix($taxable_address[0]) . WC()->countries->countries[$taxable_address[0]];
                    /* translators: 1: tax amount 2: country name */
                    $tax_text = wp_kses_post(sprintf(__('(includes %1$s estimated for %2$s)', 'woocommerce'), implode(', ', $tax_string_array), $country));
               } else {
                    /* translators: %s: tax amount */
                    $tax_text = wp_kses_post(sprintf(__('(includes %s)', 'woocommerce'), implode(', ', $tax_string_array)));
               }

               $value .= '<small class="includes_tax">' . $tax_text . '</small>';
          }
     }
     return $value;
}

function precor_get_subtotal_mini_cart($subtotal, $simbol = "$")
{
     echo '<p class="woocommerce-mini-cart__total total">
     <strong>Subtotal:</strong> <span class="woocs_special_price_code"><span class="woocommerce-Price-amount amount"><bdi>' . $subtotal . '<span class="woocommerce-Price-currencySymbol">' . $simbol . '</span></bdi></span></span></p>';
}

// Functions Adicionales del tema
//cambio el nombre del archivo que llega al correo
add_filter('wpo_wcpdf_filename', 'wpo_wcpdf_custom_filename', 10, 4);
function wpo_wcpdf_custom_filename($filename, $template_type, $order_ids, $context)
{
     // prepend your shopname to the file
     $order_id = $order_ids[0];
     $order = wc_get_order($order_id);
     $IDSAP = get_post_meta($order_id, "id_ped", true);
     $IDSAP = $IDSAP != "" ? "$IDSAP-" : "";
     $type_order = $order->get_created_via() == "ywraq" ? "Cotizacion" : "Pedido";


     $invoice_string = _n('invoice', 'invoices', count($order_ids), 'woocommerce-pdf-invoices-packing-slips');
     $new_prefix = "$type_order-$IDSAP";
     $new_filename = str_replace($invoice_string, $new_prefix, $filename);

     return $new_filename;
}

// cambia el texto del boton Ver Factura
add_filter('wpo_wcpdf_myaccount_button_text', 'wpo_wcpdf_myaccount_button_text', 10, 1);
function wpo_wcpdf_myaccount_button_text($button_text)
{
     return 'Ver PDF'; // your preferred button text
}

// Agregar Contenido debajo la tabla de productos 
add_filter('woocommerce_review_order_before_cart_contents', 'action_woocommerce_review_order_before_cart_contents', 10, 0);

//agrego boton de mostrar productos al checkout
add_action('woocommerce_checkout_order_review', 'hookNewContentInOrderReview', 15);
function hookNewContentInOrderReview()
{

     echo "<button class=' button-precor bg-precor-azul' type='button' id='btnShowModalProducts'>Ver Productos</button>";
     echo "
      <script>
      let modal = document.querySelectorAll('#myModalProducts')[0];
      let btn = document.getElementById('btnShowModalProducts');
      let span = document.getElementById('hiddeModalProducts');
      btn.onclick = function() {
           modal.style.display = 'block';
      }
      span.onclick = function() {
           modal.style.display = 'none';
      }
      window.onclick = function(event) {
           if (event.target == modal) {
                modal.style.display = 'none';
           }
      } 
 </script>
      ";
     //  <h4 class='text-center'>Elige tu forma de pago</h4>
}

// obtengo categoria del producto por idProduct

function getCategoryNameByIdProduct($idProduct): string
{
     $product = wc_get_product($idProduct);
     $category_id = $product->get_category_ids()[0];
     $term = get_term_by("id", $category_id, "product_cat", "ARRAY_A");
     return $term["name"];
}

// shortcode
// Add Shortcode
function precor_pfrx_custom_addshorcode()
{
     add_shortcode('prflx_format', function ($atts) {
          $field_id = $atts["field_id"];
          $valor = do_shortcode("[prflxtrflds_field field_id=$field_id]");
          return number_format($valor, 2);
     });
     // shortcode para mostrar el balance de credito siempre en dolares
     add_shortcode('precor_get_balance_credit', function ($atts) {
          if (class_exists('Wallet')) {
               $valor2 = Wallet::get_balance(get_current_user_id());

               $format = number_format(floatval($valor2), 2);

               return "<b>$ " . $format . "</b>";
          } else {
               return do_shortcode("[fsww_balance]");
          }
     });
}
function precor_pfrx_addshorcode()
{
     add_shortcode('prflxtrflds_get_value', function ($atts) {
          // Attributes
          $user_id = get_current_user_id();

          $prflxtrflds_name = $atts["name"];

          global $wpdb;
          $sql = "SELECT wpufd.user_value  FROM wp_prflxtrflds_fields_id wpfi JOIN
          wp_prflxtrflds_user_field_data wpufd  on wpufd.field_id = wpfi.field_id 
          WHERE wpfi.field_name =%s
          AND  wpufd.user_id =$user_id LIMIT 1";
          $results = $wpdb->get_results($wpdb->prepare($sql, $prflxtrflds_name));
          $wpdb->flush();
          $prflxtrflds_value = $results[0]->user_value == null ? "" : $results[0]->user_value;
          return $prflxtrflds_value;
     });
}
function precor_status_wallet()
{
     add_shortcode('get_status_wallet', function () {
          // Attributes
          $user_id = get_current_user_id();
          // solo cuando es inactivo te muestra el mesange de desbloqueado
          global $wpdb;
          $sql = "SELECT IF(wf.status= 'unlocked','Activo',CONCAT('Inactivo ',wf.lock_message)) as status FROM wp_fswcwallet wf WHERE wf.user_id = $user_id LIMIT 1";
          $results = $wpdb->get_results($wpdb->prepare($sql));
          $wpdb->flush();
          $status = $results[0]->status == null ? "" : $results[0]->status;
          return $status;
     });
}
add_action('init', 'precor_pfrx_addshorcode');
add_action('init', 'precor_pfrx_custom_addshorcode');
add_action('init', 'precor_shortcode_get_currency');
add_action('init', 'precor_status_wallet');

// obtengo valor de un profile extra field por $user_id,$name_key
function precor_getPRFXValueByUserID($user_id, $name_key): string
{
     global $wpdb;
     $idProfileExtraField = 0;
     $results = $wpdb->get_results(
          "SELECT field_id,field_name FROM wp_prflxtrflds_fields_id"
     );
     $wpdb->flush();
     foreach ($results as $value) {
          if ($value->field_name == strval($name_key)) {
               $idProfileExtraField = $value->field_id;
          }
     }
     $results2 = $wpdb->get_results(
          "SELECT user_value FROM wp_prflxtrflds_user_field_data WHERE user_id=$user_id AND field_id=$idProfileExtraField"
     );
     return $results2[0]->user_value != null
          ? strval($results2[0]->user_value)
          : "";
}
// functions que reemplaza localhost por dominio
function precor_url_domain_replace($url): string
{
     $domain = "tiendaenlinea.precor.pe";
     if (strpos($url, "https") === false) {
          $url = str_replace("http", "https", $url);
     }
     return str_replace("localhost:8080", $domain, $url);
}
// obtiene direccion completa de una imagen
function precor_get_image_url_helper($name): string
{
     return precor_url_domain_replace(get_template_directory_uri()) . "/helpers/imgs/$name";
}

// obtengo imagen y sobre encima el logo de la empresa
function precor_get_image_header_custom($nameImgDebajo): void
{
     $imgLogo = get_option('woocommerce_email_header_image');
     $imgLogo = precor_url_domain_replace(is_null($imgLogo) ? "https://alpha.sam.gov/assets/img/logo-not-found.png" : $imgLogo);
     echo '
     <div style="position: relative;">
          <img src="' . precor_get_image_url_helper($nameImgDebajo) . '" alt="logo-empresa" class="img-precor-email">
     </div>
     ';
     // <img src="' . $imgLogo . '" alt="" class="img-logo-sobrepuesto">

}
// crea un boton custom
function precor_create_button_custom($bgcolor, $link, $text, $button = true): void
{
     if ($button) {
          echo '
          <table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor=”' . $bgcolor . '”>
          <tr>
               <a href="' . $link . '" class="" style="color: white; !important;  display: block !important;text-decoration: none;padding-top: 18px !important;padding-bottom: 18px !important;margin-top: 10px !important;margin-bottom: 10px !important;border-radius: 10px !important;text-align: center !important;font-weight: 600; !important;width:100% !important;">
                    ' . $text . '
               </a>
          </tr>
          </table>
          ';
     } else {
          echo '
               <a href="' . $link . '" class="" style="color: ' . $bgcolor . '; !important;  display: block !important;text-decoration: none;padding-top: 18px !important;padding-bottom: 18px !important;margin-top: 10px !important;margin-bottom: 10px !important;border-radius: 10px !important;text-align: center !important;font-weight: 600; !important;width:100% !important;">
                    ' . $text . '
               </a>
          ';
     }
}

// imprime un template con el nombre , telefono , email del ejecutivo
function precor_contact_ejecutivo_byUserid($user_id): void
{
     $nombreEjecutivo = precor_getPRFXValueByUserID(
          $user_id,
          "nombeje"
     );
     $telefonoEjecutivo = precor_getPRFXValueByUserID(
          $user_id,
          "telf_eje"
     );
     $emailEjecutivo = precor_getPRFXValueByUserID(
          $user_id,
          "email_eje"
     );
     echo      '
     <h1 class="precor-title-email" style="text-align: justify;">¿Deseas comunicarte con tu ejecutivo de ventas?</h1>
     <div class="precor-color-texto">
          <p>Contacta a tu ejecutivo de ventas <strong class="precor-text-email">' . $nombreEjecutivo . '</strong>, através del <strong class="precor-text-email">' . $telefonoEjecutivo . '</strong> o mediante el correo <strong class="precor-text-email">' . $emailEjecutivo . '</strong>.</p>
     </div>
     ';
}

function precor_get_link_order_quote_byOrder(WC_Order $order): string
{
     $order_id = $order->get_id();
     $mail_options      = get_option('woocommerce_ywraq_email_settings');
     $page_detail_admin = 'editor' === $mail_options['quote_detail_link'];

     $order = wc_get_order($order_id);
     $url = $order->get_created_via() == "ywraq" ?  esc_url(YITH_YWRAQ_Order_Request()->get_view_order_url($order_id, $page_detail_admin)) : $order->get_view_order_url();
     return $url;
}

function precor_buscas_algun_producto()
{
     echo '
     <h1 class="precor-title-email">¿Buscando algún producto?</h1>
     <div class="precor-color-texto">
          <p>Nuestra amplia lista de productos espera por ti. Haz <strong><a href="' . precor_get_catalog_products() . '" class="precor-text-email">click aquí</a></strong> para dirigirte a nuestro catalogo</p>
     </div>
     ';
}
function precor_get_catalog_products(): string
{
     return get_site_url() . "/mi-cuenta/catalogo-de-productos";
}

add_filter('woocommerce_account_menu_items', 'precor_remove_my_account_links');
function precor_remove_my_account_links($menu_links)
{

     // unset($menu_links['edit-address']); // Addresses
     unset($menu_links['dashboard']); // Remove Dashboard
     unset($menu_links['payment-methods']); // Remove Payment Methods
     unset($menu_links['orders']); // Remove Orders
     unset($menu_links['downloads']); // Disable Downloads
     unset($menu_links['edit-account']); // Remove Account details tab
     unset($menu_links['edit-address']); // Remove Account details tab
     unset($menu_links['customer-logout']); // Remove Logout link
     // $menu_links['dashboard'] = "Mi Cuenta";

     $new = array(
          'catalogoProductos' => 'Catalogo de Productos',
          "misDatos" => "Mis Datos",
          "miCredito" => "Mi Credito",
          "misDirecciones" => "Mis Direcciones",
          "misCotizaciones" => "Mis Cotizaciones",
          "misPedidos" => "Mis Pedidos",
     );

     // or in case you need 2 links
     // $new = array( 'link1' => 'Link 1', 'link2' => 'Link 2' );

     // array_slice() is good when you want to add an element between the other ones
     $menu_links = array_slice($menu_links, 0, 1, true)
          + $new
          + array_slice($menu_links, 1, NULL, true);


     return $menu_links;
}

add_filter('woocommerce_get_endpoint_url', 'precor_hook_endpoint', 10, 4);
function precor_hook_endpoint($url, $endpoint, $value, $permalink)
{
     switch ($endpoint) {
          case 'catalogoProductos':
               $url = site_url("mi-cuenta/catalogo-de-productos");
               break;
          case 'misDatos':
               $url = site_url("mi-cuenta/mis-datos");
               break;
          case 'misDirecciones':
               $url = site_url("mi-cuenta/mis-direcciones");
               break;
          case 'misPedidos':
               $url = site_url("mi-cuenta/mis-pedidos");
               break;
          case 'misCotizaciones':
               $url = site_url("mi-cuenta/ver-cotizacion");
               break;
          case 'miCredito':
               $url = site_url("mi-cuenta/mi-credito");
               break;
          default:
               break;
     }


     return $url;
}

// añadire css cuando no este logueado el usuario
function precor_style_header_noLogin()
{
     // global $wp;
     // $current_url = home_url(add_query_arg(array(), $wp->request));
     if (
          shortcode_exists("woocommerce_my_account") &&
          get_current_user_id() == 0 && !strpos($_SERVER['REQUEST_URI'], "/mi-cuenta")
     ) {
          wp_enqueue_style('login-styles', get_template_directory_uri() . '/helpers/css/login-style.css', array());
          // print_r( 
          //      );
     }
}

add_action('wp_head', 'precor_style_header_noLogin');

function precor_getIDSAPbyOrderID($order_id): string
{
     $IDSAP = get_post_meta($order_id, "id_ped", true);
     $format = "";
     if ($IDSAP != "") {
          $format = $IDSAP . " (#" . $order_id . ")";
     } else {
          $format = "#" . $order_id;
     }
     return $format;
}



// se agrego endpoint para actualizar rate en woocommerce currency
add_action('rest_api_init', function () {
     register_rest_route('webservices_precor/v1', '/update_currency_rate/', array(
          'methods' => 'POST',
          'callback' => 'precor_update_currency_rate',
          'args' => array(),
     ));
});

function precor_update_currency_rate(WP_REST_Request $request)
{
     //obtengo el id_country del parametros
     $rate = $request->get_json_params()["rate"];
     $user = $request->get_json_params()["user"];
     $pass = $request->get_json_params()["pass"];
     // $ubigeos = query_getUbigeo($id_country);
     if ($user == "PRECOR" && $pass == "PRECOR2") {
          if (empty($rate)) {
               return new WP_Error('rate_empty', "Por favor rellene el tipo de cambio", array('status' => 404));
          } else {
               $current = get_option('woocs');
               $current['PEN']['rate'] = $rate;
               update_option('woocs', $current);

               return ["code" => "updated_rate", "message" => "El tipo de cambio se ha actualizado", "data" => ["status" => 200]];
          }
     } else {
          return new WP_Error('not_authentication', "Por favor rellene el tipo de cambio", array('status' => 404));
     }
}
// Obtiene fecha Correcta respecto al timezone
function precor_get_fecha_correcta($order)
{
     return $order->get_date_created()->date("d/m/Y g:i A");
}
// boton de cambio de moneda en order pay
function precor_show_button_change_currency($order = null): void
{

     global $WOOCS;
     global $wp;
     $currency = $WOOCS->current_currency;
     $isDolar = $currency == "USD" ? true : false;
     $urlPagina = home_url($wp->request) . "?";
     $moneda = !$isDolar ? "USD" : "PEN";
     $_GET["currency"] = $moneda;
     $params = http_build_query($_GET);
     $enlace = $urlPagina . $params;
     $tipoDeCambio = get_option('woocs')['PEN']['rate'];
     $precioAprox = !$isDolar ? number_format($order->total / $tipoDeCambio, 2) : number_format($order->total * $tipoDeCambio, 2);
     $textoAprox = $isDolar ?  "S/. $precioAprox aprox" : $precioAprox . "$ aprox";

?>
     <style>
          .btn-precor-change-currency {
               cursor: pointer;
               background-color: #00396E;
               color: white;
               padding: 10px;
               font-weight: bold;
               margin-bottom: 10px;
               border-radius: 10px;
          }

          .btn-precor-change-currency:hover {
               color: white;
               background-color: #FFC107;
          }
     </style>
     <!-- Boton de cambio de moneda -->
     <div class="d-flex flex-row-reverse align-items-center">
          <div class="d-flex">
               <h4 style="margin-bottom: 0; margin-right: 10px;"><?= $textoAprox ?> </h4>

               <a class="button alt btn-precor-change-currency" style="padding: 8px; display: none;" href="<?= $enlace ?>">
                    <i class="fa fa-money" style="margin-right: 5px;"></i><?= !$isDolar ? "Convertir a USD" : "Convertir a PEN" ?></a>
          </div>
     </div>

<?php
} ?>
<?php
//cuando no sea checkout que siempre sea dolares
// add_filter('wp_head', function () {
//      if (!is_checkout()) {
//           global $WOOCS;
//           $WOOCS->set_currency('USD');
//      }
// });

//cuando sea checkout siempre la moneda sera dolar
add_filter('wp_head', function () {
     if (is_checkout()) {
          global $wp;
          global $WOOCS;
          if (isset($wp->query_vars['order-pay']) && absint($wp->query_vars['order-pay']) > 0) {
               $order_id = absint($wp->query_vars['order-pay']); // The order ID
               $order = wc_get_order($order_id); // Get the WC_Order Object instance
               if ($order) {
                    if ($_GET["currency"] != "") {
                         // print_r("me estoy ejecutando");
                         $moneda = $_GET["currency"];
                         $WOOCS->recalculate_order($order_id, $moneda);
                         update_post_meta($order_id, '_order_currency', $moneda);
                         // $order->set_currency($moneda);
                         // $order->save();
                    }
               }
          }
     }
});

// shorcode obtiene el tipo de cambio por moneda

add_shortcode('precor_get_type_rate_currency', function ($atts) {
     $currency = $atts["currency"] ?? "PEN";
     $typeRate = get_option('woocs')[$currency]['rate'];
     return $typeRate;
});


// filtro para excluir metodos de pago donde tenga tal moneda
add_filter('woocommerce_available_payment_gateways', 'woocs_filter_gateways', 1);
function woocs_filter_gateways($gateway_list)
{
     $exclude = array(
          'Mi crédito PRECOR' => array('PEN'),
          'ScotiaBank' => array('PEN'),
          'BBVA' => array('PEN'),
          'Pago con tarjeta de crédito' => array('PEN'),
     );
     // recibe el metodo de pago , y los exclude
     foreach ($gateway_list as $key => $gateway) {
          if (precor_verifyExcludesCurrency($gateway->title, $exclude)) {
               unset($gateway_list[$key]);
          }
     }
     return $gateway_list;
}
function precor_verifyExcludesCurrency($methodTitle, $exclude): bool
{
     global $WOOCS;
     foreach ($exclude as $key => $currency) {
          if ($methodTitle == $key && in_array($WOOCS->current_currency, $currency)) {
               return true;
          }
     }
     return false;
}



add_filter('wc_product_table_custom_table_data_precor_price', function ($obj, WC_Product $product, Table_Args $table_args) {
     return new Product_Table_Price_Role_Column($product);
}, 10, 3);

function precor_userHasPaymentExpiry($user_id): bool
{
     global $wpdb;
     $sql = "SELECT *  FROM wp_fswcwallet wa
          WHERE wa.lock_message = 'Documentos de Pagos Vencidos'
          AND  wa.user_id = $user_id LIMIT 1";
     $results = $wpdb->get_results($wpdb->prepare($sql));
     $wpdb->flush();
     return count($results) > 0 ? true : false;
}




add_filter('gettext', 'change_some_woocommerce_strings', 10, 3);
add_filter('ngettext', 'change_some_woocommerce_strings', 10, 3);
function change_some_woocommerce_strings($translate_text, $original_text, $domain)
{
     if (
          stripos($original_text, 'is') !== false || stripos($original_text, 'Out of stock') !== false || stripos($original_text, 'Please add or decrease items to continue') !== false || stripos($original_text, 'must be bought in groups of') !== false || stripos($original_text, 'The minimum order quantity for') !== false
          ||  stripos($original_text, 'please increase the quantity in your cart') !== false
          ||  stripos($original_text, 'Processing') !== false
          ||  stripos($original_text, 'Completed') !== false
          ||  stripos($original_text, 'Failed') !== false

     ) {
          $translate_text = str_ireplace(
               array("is", 'Out of stock', 'Please add or decrease items to continue', "The minimum order quantity for", "must be bought in groups of", "please increase the quantity in your cart", "Processing", "Completed", "Failed"),
               array("es", 'Sin Stock', 'Agregue o disminuya elementos para continuar', "La cantidad mínima de pedido para", "deben comprarse en grupos de", "Por favor aumente la cantidad en su carrito", "Procesando", "Completado", "Fallo"),
               $original_text
          );
     }




     return $translate_text;
}

function precor_translateStatus($quote, $statusCode = null): string
{

     $status = str_replace("ywraq-", "", $quote->status);
     $spanish = "";
     switch ($status) {
          case 'completed':
               $spanish = "completado";
               break;
          case 'pending':
               $spanish = "pendiente";
               break;
          case 'processing':
               $spanish = "procesando";
               break;
          case 'on-hold':
               $spanish = "en espera";
               break;
          case 'rejected':
               $spanish = "rechazado";
               break;
          case 'accepted':
               $spanish = "aceptado";
               break;
          case 'expired':
               $spanish = "vencido";
               break;
          case 'cancelled':
               $spanish = "cancelado";
               break;
          case 'failed':
               $spanish = "fallado";
               break;
          default:
               $spanish = $status;
               break;
     }
     // si es recaudacion
     if ($statusCode == 4) {
          $spanish = "recaudacion";
          // pendiente
     } else if ($statusCode == 1) {
          // caso especial cuando es pendiente buscas en el metadata si es aceptado
          foreach ($quote->meta_data as $m) {
               // esto solo pasa cuando es aceptado se guarda en el metadata
               if ($m->key == "ywraq_raq_status") {
                    if ($m->value == "accepted") {
                         $spanish = "aceptado";
                         // break;
                    }
                    if ($m->value == "expired") {
                         $spanish = "vencido";
                         // break;
                    }
               }
          }
          // llega correctamente entonces trae el codigo correcto
     } else if ($statusCode == 2) {
          foreach ($quote->meta_data as $m) {
               // esto solo pasa cuando es aceptado se guarda en el metadata
               if ($m->key == "ywraq_raq_status") {
                    if ($m->value == "accepted") {
                         $spanish = "aceptado";
                         // break;
                    }
                    if ($m->value == "expired") {
                         $spanish = "vencido";
                         // break;
                    }
               }
          }
     } else if ($statusCode == 5) {

          // correcion para correcto funcionamiento del webservices
          $spanish = "completado";
     } else if ($statusCode == 7) {
          $spanish = "pago procesando";
     } else if ($statusCode == 8) {
          $spanish = "pago procesado";
     } else if ($statusCode == 6) {
          $spanish = "pago rechazado";
     }



     return $spanish;
}
function precor_getStatusCode($quote, $id_soc)

{
     $status = $quote->status;
     $paymentMethodTitle = $quote->payment_method_title;
     // $status = $quote->status;
     // data
     $pendiente = [
          "pending", "ywraq-pending", "processing", "on-hold",
          // "ywraq-rejected",
          "ywraq-accepted"
     ];
     $vencido = ["ywraq-expired", "cancelled", "failed"];
     $statusCode = 0;
     $transactionId = null;

     // evaludacion de estado simple
     switch ($status) {
          case 'ywraq-accepted':
               $statusCode = 2;
               break;
          case 'ywraq-rejected':
               $statusCode = 3;
               break;
          case 'completed':
               $statusCode = 5;
               break;
     }

     foreach ($pendiente as $v2) {
          if ($v2 == $status) {
               $statusCode = 1;
               break;
          }
     }
     foreach ($vencido as $v3) {
          if ($v3 == $status) {
               $statusCode = 6;
               break;
          }
     }
     foreach ($quote->meta_data as $m) {
          // esto solo pasa cuando es aceptado se guarda en el metadata
          if ($m->key == "Transaction ID") {
               $transactionId = intval($m->value);
               break;
          }
     }
     // esta en pendiente
     if ($statusCode == 1) {

          // caso especial en woo esta como pending pero en el metdata esta como aceptado
          foreach ($quote->meta_data as $m) {
               // esto solo pasa cuando es aceptado se guarda en el metadata
               if ($m->key == "ywraq_raq_status") {
                    if ($m->value == "accepted") {
                         $statusCode = 2;
                         break;
                    }
               }
          }
          // si el estatus es aceptado y elegia tales metodos de pago es recaudacion
          if ($statusCode == 2) {
               if (
                    $paymentMethodTitle == "BBVA" ||
                    $paymentMethodTitle == "BBVA $" ||
                    $paymentMethodTitle == "BCP" ||
                    $paymentMethodTitle == "BCP $" ||
                    $paymentMethodTitle == "ScotiaBank" ||

                    $paymentMethodTitle == "BCP S/." ||
                    $paymentMethodTitle == "BBVA S/."
               ) {
                    $statusCode = 4;
               }
          }
     }

     // si es maxco no existe aceptado
     if (precor_isMaxco($id_soc)) {
          if (
               $paymentMethodTitle == "BBVA" ||
               $paymentMethodTitle == "BBVA $" || $paymentMethodTitle == "BCP" ||
               $paymentMethodTitle == "BCP $" ||
               $paymentMethodTitle == "ScotiaBank" ||
               $paymentMethodTitle == "BCP S/." ||
               $paymentMethodTitle == "BBVA S/."
          ) {
               $statusCode = 4;
          }
     }

     if ($paymentMethodTitle == "Mi crédito PRECOR") {
          $statusCode = 5;
     }

     // cuando es tarjeta de credito
     if ($paymentMethodTitle == "Pago con tarjeta de crédito") {
          // nuevos codigo de estado cuando es tarjeta de credito
          if ($status == "failed" || $status == "refunded" || $status == "rejected") {
               $statusCode = 6;
          } else
        if ($status == "pending") {
               $statusCode = 7;
          } else if ($status == "processing") {
               // $statusCode = 7;
               $statusCode = 8;
          } else if ($status == "completed") {
               $statusCode = 8;
          } else {
               $statusCode = 9;
          }
          // // cuando se pago con tarjeta de credito pero no tiene transaction id significa que fue fallida  la transaccion
          // if ($status == "pending" && $transactionId === null) {
          //     $statusCode = 6;
          // }
     }

     return $statusCode;
}

function precor_isMaxco($id_soc)
{
     if ($id_soc == "EM01") {
          return true;
     } else if ($id_soc == "MA01") {
          return true;
     } else {
          return false;
     }
}

function precor_isPrecor($id_soc)
{
     if ($id_soc == "PR01") {
          return true;
     } else {
          return false;
     }
}


function precor_EvaluateBadgeSpanish($status)
{
     $badgeColor = "";
     switch ($status) {
          case 'completado':
               $badgeColor = "badge-precor-success";
               break;
          case 'pago procesado':
               $badgeColor = "badge-precor-success";
               break;
          case 'cancelado':
               $badgeColor = "badge-precor-danger";
               break;
          case 'refunded':
               $badgeColor = "badge-precor-danger";
               break;
          case 'fallido':
               $badgeColor = "badge-precor-danger";
               break;
          case 'procesando':
               $badgeColor = "badge-precor-success";
               break;
          case 'pendiente':
               $badgeColor = "badge-precor-primary";
               break;
          case 'pago procesando':
               $badgeColor = "badge-precor-primary";
               break;
          case 'en espera':
               $badgeColor = "badge-precor-info";
               break;
               // quote status
          case 'recaudacion':
               $badgeColor = "badge-precor-info";
               break;
          case 'pago rechazado':
               $badgeColor = "badge-precor-danger";
               break;
          case 'vencido':
               $badgeColor = "badge-precor-danger";
               break;
          case 'rechazado':
               $badgeColor = "badge-precor-danger";
               break;
          default:
               $badgeColor = "badge-precor-dark";
               break;
     }
     return $badgeColor;
}
function precor_getPrecorID()
{
     return "PR01";
}
function precor_getMaxcoID()
{
     return "EM01";
}

// add_action('woocommerce_new_order', 'precor_sendEmailNewOrder');
// add_action('woocommerce_resume_order', 'hpWooNewOrder');
function precor_sendEmailNewOrder($id_order)
{
     function get_custom_email_html($order_id, WC_Order $order, $heading = false, $mailer)
     {
          // $template = "emails/admin-new-order.php";
          $template = "emails/request-quote.php";
          return wc_get_template_html($template, array(
               'raq_data'         => [
                    "order_id" => $order_id,
                    "raq_content" => $order->get_items()
               ],
               'order_id'         => $order_id,
               'email_description' => "",
               'email_heading' => $heading,
               'sent_to_admin' => false,
               'plain_text'    => false,
               'email'         => $mailer
          ));
     }
     $order =  wc_get_order($id_order);
     $isQuote = false;
     if (strval($order->payment_method) ==  "yith-request-a-quote") {
          $isQuote = true;
     } else {
          $isQuote = false;
     }
     // if (!$isQuote) {
     // load the mailer class
     $mailer = WC()->mailer();
     $emailEjecutivo = precor_getPRFXValueByUserID(
          $order->get_customer_id(),
          "email_eje"
     );

     //format the email
     $recipient = $emailEjecutivo;
     $subject = __("Un cliente ha hecho un nuevo pedido", 'theme_name');
     $content = get_custom_email_html($id_order, $order, $subject, $mailer);
     $headers = "Content-Type: text/html\r\n";
     //send the email through wordpress
     $mailer->send($recipient, $subject, $content, $headers);
     // }
}

<?php
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
function precor_create_button_custom($bgcolor, $link, $text): void
{
     echo '
 <table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor=”' . $bgcolor . '”>
 <tr>
      <a href="' . $link . '" class="" style="color: white; !important;  display: block !important;text-decoration: none;padding-top: 18px !important;padding-bottom: 18px !important;margin-top: 10px !important;margin-bottom: 10px !important;border-radius: 10px !important;text-align: center !important;font-weight: 600; !important;width:100% !important;">
           ' . $text . '
      </a>
 </tr>
</table>
 ';
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


/**
 * script para igresar el chatbot de wolkvox
 */

/* Inline script printed out in the header */
add_action('wp_head', 'tutsplus_add_script_wp_head');
function tutsplus_add_script_wp_head()
{
?>
     <script id="prodId" type="text/javascript" src="https://chat01.ipdialbox.com/chat/?prodId=cG1wLXRlbGV2ZW50YXM=" async>
     </script>
<?php
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
add_filter('wp_head', function () {
     if (!is_checkout()) {
          global $WOOCS;
          if ($WOOCS) {
               $WOOCS->set_currency('USD');
          }
     }
});

// cuando sea checkout siempre la moneda sera dolar
add_filter('wp_head', function () {
     if (is_checkout()) {
          global $wp;
          global $WOOCS;
          if ($WOOCS) {
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
     }
});

// shorcode obtiene el tipo de cambio por moneda

add_shortcode('precor_get_type_rate_currency', function ($atts) {
     $currency = $atts["currency"] ?? "PEN";
     $typeRate = get_option('woocs')[$currency]['rate'];
     return $typeRate;
});

// function precor_db_remove_new_site_notification_email($blog_id, $user_id, $password, $title, $meta)
// {
//      return false;
// }
// add_filter('wpmu_welcome_notification', 'precor_db_remove_new_site_notification_email');

// filtro para modificar algunos campos en checkout fields de woocommerce
function custom_override_checkout_fields($fields)
{
     // modifico los atributos de los checkout fields
     $fields['shipping']['shipping_company']['maxlength'] = '20';
     $fields['shipping']['shipping_company']['placeholder'] = 'Ingrese su RUC';
     $fields['shipping']['shipping_first_name']['maxlength'] = '150';
     $fields['shipping']['shipping_first_name']['placeholder'] = 'Ingrese su Razon social';
     $fields['shipping']['direccion_fiscal']['placeholder'] = 'Ingrese su Direccion Fiscal';
     $fields['shipping']['direccion_fiscal']['maxlength'] = '200';
     return $fields;
} // End custom_override_checkout_fields()

add_filter('woocommerce_checkout_fields', 'custom_override_checkout_fields', 10);


// function maximum_api_filter_custom($query_params)
// {
//      $query_params['per_page']["maximum"] = 100000;
//      return $query_params;
// }

// add_filter('rest_product_collection_params', 'maximum_api_filter_custom');
// add_action('woocommerce_resume_order', 'hpWooNewOrder');
add_action('woocommerce_new_order', 'maxcoDescuentosOrder');
function maxcoDescuentosOrder($id_order)
{
     $woo = wc_get_order($id_order);
     $arrayProducts = [];
     foreach ($woo->get_items() as $product) {
          $productReal = wc_get_product(intval($product["product_id"]));
          $precioProductoReal = number_format($productReal->get_price(), 2);
          $precioProductoVendido = number_format($product["total"] / intval($product["quantity"]), 2);
          $cantidadProductoVendido = intval($product["quantity"]);

          // precios totales
          $precioTotalDelProductoOriginal = $precioProductoReal * $cantidadProductoVendido;
          $precioTotalDelProductoVendido = $precioProductoVendido * $cantidadProductoVendido;

          // descuento total
          $descuentoTotal = number_format($precioTotalDelProductoOriginal - $precioTotalDelProductoVendido, 2);

          // porcentaje de descuento
          $porcentajeDescuento = number_format(($descuentoTotal * 100) / $precioTotalDelProductoOriginal, 2);

          array_push($arrayProducts, [
               "product_id" => $productReal->get_id(),
               "sku" => $productReal->get_sku(),
               "name" => $productReal->get_name(),
               "sale_price" => $precioProductoReal,
               "sale_price_order" => $precioProductoVendido,
               "quantity" => $cantidadProductoVendido,
               "total_original" => $precioTotalDelProductoOriginal,
               "total_sale" => $precioTotalDelProductoVendido,
               "discount" => $descuentoTotal,
               "percentage_discount" => $porcentajeDescuento
          ]);
          // echo $product[""];
     }
     $serializado = (maybe_serialize($arrayProducts));
     add_post_meta($id_order, "descuentos_precor", $serializado);
}

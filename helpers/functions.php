<?php
// nuevas funciones
add_filter('woocommerce_admin_order_preview_get_order_details', 'admin_order_preview_add_custom_billing_data', 10, 2);
function admin_order_preview_add_custom_billing_data($data, $order)
{
     $precorData = precor_getStatusSelectorWithStatusSpanish($order);
     $statusSelector = $precorData['selector'];
     $statusSpanish = $precorData['statusSpanish'];

     $data["status"] = $statusSelector;
     $data["status_name"] = $statusSpanish;

     return $data;
}
// Agrego columna para agregar el estado representativo
add_filter('manage_edit-shop_order_columns', 'custom_shop_order_column', 20);
function custom_shop_order_column($columns)
{
     $reordered_columns = array();

     // Inserting columns to a specific location
     foreach ($columns as $key => $column) {
          $reordered_columns[$key] = $column;
          if ($key ==  'order_status') {
               // Inserting after "Status" column
               $reordered_columns['order_status_precor'] = __('Estado', 'Estado');
          }
     }
     unset($reordered_columns["order_status"]);
     return $reordered_columns;
}

// Cambio el color y texto respecto al estado de precor SAP 
add_action('manage_shop_order_posts_custom_column', 'custom_orders_list_column_content', 20, 2);
function custom_orders_list_column_content($column, $post_id)
{

     $order = new WC_Order($post_id);
     $data = precor_getStatusSelectorWithStatusSpanish($order);
     $statusSelector = $data['selector'];
     $statusSpanish = $data['statusSpanish'];
     switch ($column) {
          case 'order_status_precor':
               echo '<mark class="order-status status-' . $statusSelector . ' tips"><span>' . $statusSpanish . '</span></mark>';
               break;
     }
}
function precor_getStatusSelectorWithStatusSpanish($order)
{
     $statusCodePrecor = precor_getStatusCode($order, precor_getPrecorID());
     $statusSpanish = precor_translateStatus($order, $statusCodePrecor, true);
     $statusSelector =  $order->status;
     if (strtolower($statusSpanish) == "completado") {
          $statusSelector = "completed";
     }
     return ["selector" => $statusSelector, "statusSpanish" => $statusSpanish];
}
function precor_translateStatus($quote, $statusCode = null, $capitalize = false): string
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
          case 'new':
               $spanish = "nuevo";
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
     } else if ($status == "completed") {
          $spanish = "completado";
     }

     if ($spanish == "pago procesado") {
          $spanish = "completado";
     }


     return $capitalize ? ucwords($spanish) : $spanish;
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

// verifica que un usuario este con los metodos de pago activos
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


function getCategoryNameByIdProduct($idProduct): string
{
     $product = wc_get_product($idProduct);
     $category_id = $product->get_category_ids()[0];
     $term = get_term_by("id", $category_id, "product_cat", "ARRAY_A");
     return $term["name"];
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
          ||  stripos($original_text, 'Regester') !== false
          ||  stripos($original_text, 'Login') !== false

     ) {
          $translate_text = str_ireplace(
               array("is", 'Out of stock', 'Please add or decrease items to continue', "The minimum order quantity for", "must be bought in groups of", "please increase the quantity in your cart", "Processing", "Completed", "Failed", "Regester", "Login"),
               array("es", 'Sin Stock', 'Agregue o disminuya elementos para continuar', "La cantidad mínima de pedido para", "deben comprarse en grupos de", "Por favor aumente la cantidad en su carrito", "Procesando", "Completado", "Fallo", "Registrarse", "Iniciar Sesion"),
               $original_text
          );
     }





     return $translate_text;
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
               <a href="' . $link . '" style="color: black; !important;  display: block !important;text-decoration: none;padding-top: 18px !important;padding-bottom: 18px !important;margin-top: 10px !important;margin-bottom: 10px !important;text-align: center !important;font-weight: 600; !important;width:100% !important;">
                    ' . $text . '
               </a>
          ';
     }
}

// imprime un template con el nombre , telefono , email del ejecutivo
function precor_contact_ejecutivo_byUserid($user_id): void
{
     // $nombreEjecutivo = precor_getPRFXValueByUserID(
     //      $user_id,
     //      "nombeje"
     // );
     // $telefonoEjecutivo = precor_getPRFXValueByUserID(
     //      $user_id,
     //      "telf_eje"
     // );
     // $emailEjecutivo = precor_getPRFXValueByUserID(
     //      $user_id,
     //      "email_eje"
     // );
     echo      '
     <h1 class="precor-title-email" style="text-align: justify;">¿Deseas comunicarte con tu ejecutivo de ventas?</h1>
     <div class="precor-color-texto">
          <p>Contáctalo a través de la Central Administrativa <strong class="precor-text-email">
          (551) 705-4040 </strong>o <strong class="precor-text-email">servicioalcliente@maxco.com.pe </strong></p>
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

     unset($menu_links['edit-address']); // Addresses
     unset($menu_links['dashboard']); // Remove Dashboard
     unset($menu_links['payment-methods']); // Remove Payment Methods
     unset($menu_links['orders']); // Remove Orders
     unset($menu_links['downloads']); // Disable Downloads
     unset($menu_links['edit-account']); // Remove Account details tab
     unset($menu_links['edit-address']); // Remove Account details tab
     unset($menu_links['customer-logout']); // Remove Logout link
     $menu_links['dashboard'] = "Mi Cuenta";

     $new = array(
          'catalogoProductos' => 'Catalogo de Productos',
          "misDatos" => "Mis Datos",
          "miCredito" => "Mi Credito",
          //"misDirecciones" => "Mis Direcciones",
          //"misCotizaciones" => "Mis Cotizaciones",
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
               $url = site_url("mi-cuenta/tienda");
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

     register_rest_route('precor_prfx/v1', '/get_data_user_sap/', array(
          'methods' => 'POST',
          'callback' => 'precor_get_data_user_sap_api',
          'args' => array(),
     ));
});
// endpoint para obtener data del usuario sap

function precor_get_data_user_sap_api(WP_REST_Request $request)
{
     $user_id = $request->get_json_params()["user_id"];
     if (precor_isUserCreatedSap($user_id)) {
          $ruc = precor_getPRFXValueByUserID($user_id, "nrdoc");
          $drcfisc = precor_getPRFXValueByUserID($user_id, "drcfisc");
          $nomb = precor_getPRFXValueByUserID($user_id, "nomb");

          return ["status" => 200, "code" => "precor_get_data_user_sap_api", "message" => "Es usuario de sap", "data" => [
               "ruc" => $ruc,
               "drcfisc" => $drcfisc,
               "nomb" => $nomb,
          ]];
     } else {
          return ["status" => 400, "code" => "precor_get_data_user_sap_api", "message" => "No es usuario de sap", "data" => []];
     }
}

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
}
?>
<?php
//cuando no sea checkout que siempre sea dolares
add_filter('wp-head', function () {
     $url = home_url($_SERVER['REQUEST_URI']);
     // print_r($url);
     $isCheckout = strpos($url, "finalizar-compra");
     // print_r($isCheckout ? "si soy" : "no soy");
     // si no es checkout
     if (!$isCheckout) {
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


// filtro para excluir metodos de pago donde tenga tal moneda
add_filter('woocommerce_available_payment_gateways', 'woocs_filter_gateways', 1);
function woocs_filter_gateways($gateway_list)
{
     $exclude = array(
          'Mi crédito PRECOR' => array('PEN'),
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
function precor_isUserCreatedSap($user_id): bool
{
     // Attributes

     // solo cuando es inactivo te muestra el mesange de desbloqueado
     global $wpdb;
     $sql = "SELECT wf.balance FROM wp_fswcwallet wf WHERE wf.user_id = $user_id LIMIT 1";
     $results = $wpdb->get_results($wpdb->prepare($sql));
     $wpdb->flush();
     $isUserSap = $results[0]->balance == null ? false : true;
     // print_r($results);
     return $isUserSap;
}
add_action('woocommerce_checkout_order_processed', 'maxcoDescuentosOrder');
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

add_action("wp_ajax_nopriv_precor_modal_products", "precor_ajax_modal_products");
add_action("wp_ajax_precor_modal_products", "precor_ajax_modal_products");
function precor_ajax_modal_products()
{
     try {
          ob_start();
          precor_generate_modal_products();
          $HTMLoutput = ob_get_contents();
          ob_end_clean();
          header('Content-Type: text/json');
          die(json_encode(["data" => html_entity_decode($HTMLoutput)]));
     } catch (\Throwable $th) {
          return $th;
     }
}



//agrego boton de mostrar productos al checkout con una peticion ajax
add_action('woocommerce_checkout_order_review', 'hookNewContentInOrderReview', 15);
function hookNewContentInOrderReview()
{
     $urlWpAjax = admin_url("admin-ajax.php");
?>

     <button class=' button-precor bg-precor-azul' type='button' id='btnShowModalProducts'>Ver Productos</button>
     <script>
          window.addEventListener('DOMContentLoaded', (event) => {
               document.querySelector('#btnShowModalProducts').addEventListener('click', async () => {
                    document.querySelector("#content_modal_products_ajax").innerHTML = ""
                    document.querySelector('#myModalProducts').style.display = 'block';
                    document.querySelector('#spinner_modal').style.display = 'block';
                    await getModalProducts();
               });
               document.querySelector('#hiddeModalProducts').addEventListener('click', () => {
                    document.querySelector('#myModalProducts').style.display = 'none';
               });
               // funcion obtenedora
               const getModalProducts = async () => {
                    let data = await (await fetch("<?= $urlWpAjax  ?>?action=precor_modal_products")).json();
                    document.querySelector("#content_modal_products_ajax").innerHTML = ""
                    document.querySelector('#spinner_modal').style.display = 'none';
                    document.querySelector("#content_modal_products_ajax").innerHTML = data.data;
               }

          });
     </script>
<?php } ?>
<?php




function precor_generate_modal_products()
{
     $totalProducts = count(WC()->cart->get_cart());
     $pesoTotalKg = 0;
     foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
          // logica para agregar peso
          $_product_id = $cart_item['product_id'];
          $peso = doubleval(get_post_meta($_product_id, 'peso', true))  * $cart_item['quantity'];
          $pesoTotalKg += doubleval((is_null($peso) || $peso == "") ?   0 : $peso);
     }
?>


     <div class="" id="content_modal_products_ajax">
          <div class="modaltotalKgWoocommerce">
               <p></p>
               <p>Peso Total: <?= number_format($pesoTotalKg, 2) ?> kg</p>
          </div>

          <div class="modaltotalWoocommerce">
               <p>Cantidad de Productos: <?= $totalProducts ?></p>
               <p class="order-total">
                    <span><?php esc_html_e('Total', 'woocommerce'); ?> : <?php wc_cart_totals_order_total_html(); ?></span>
               </p>
          </div>


          <!-- de aqui para abajo se autogenera con la peticion ajax -->

          <div class="modalContentProducts">
               <table class="">
                    <thead>
                         <tr>
                              <th class="product-name"><?php esc_html_e('Product', 'woocommerce'); ?></th>
                              <th class="product-name precor-display-none-sm">UND</th>
                              <th class="product-name">Peso Total</th>
                              <th class="product-name">Cantidad</th>
                              <!-- <th class="product-name precor-display-none-sm">PAQ</th>
							<th class="product-name precor-display-none-sm">PZAS</th> -->
                              <th class="product-total" style="text-align: right;"><?php esc_html_e('Subtotal', 'woocommerce'); ?></th>
                         </tr>
                    </thead>
                    <tbody>
                         <?php
                         foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
                              // logica para agregar peso
                              $_product_id = $cart_item['product_id'];
                              $und = get_post_meta($_product_id, 'und', true);
                              $paq = get_post_meta($_product_id, 'unxpaq', true) == "" ? "" : get_post_meta($_product_id, 'unxpaq', true);
                              $pzas = 0;
                              if ($paq == "") {
                                   $pzas = "";
                              } else {
                                   $pzas = $cart_item['quantity'] / $paq;
                              }
                              $peso = doubleval(get_post_meta($_product_id, 'peso', true))  * $cart_item['quantity'];
                              // $pesoTotalKg += doubleval((is_null($peso) || $peso == "") ?   0 : $peso);
                              // // 
                              $_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);

                              if ($_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters('woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key)) {
                         ?>
                                   <tr class="<?php echo esc_attr(apply_filters('woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key)); ?>">
                                        <td class="product-name">
                                             <?php echo apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key) . '&nbsp;'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
                                             ?>
                                             <?php echo apply_filters('woocommerce_checkout_cart_item_quantity', ' <strong class="product-quantity">' . sprintf('&times;&nbsp;%s', $cart_item['quantity']) . '</strong>', $cart_item, $cart_item_key); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
                                             ?>
                                             <?php echo wc_get_formatted_cart_item_data($cart_item); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
                                             ?>
                                        </td>

                                        <td class="product-total precor-display-none-sm">
                                             <?= $und ?>
                                        </td>
                                        <!-- aqui va el peso -->
                                        <td class="product-total ">
                                             <?= number_format(floatval($peso), 2)  ?>
                                        </td>
                                        <td class="product-total ">
                                             <?= $cart_item['quantity']  ?>
                                        </td>


                                        <!-- <td class="product-total precor-display-none-sm">
									<?= $pzas ?>
								</td>
								<td class="product-total precor-display-none-sm">
									<?= $paq ?>
								</td> -->
                                        <td class="product-total" style="text-align: right;">
                                             <?php echo apply_filters('woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal($_product, $cart_item['quantity']), $cart_item, $cart_item_key); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
                                             ?>
                                        </td>
                                   </tr>
                         <?php
                              }
                         }
                         ?>
                    </tbody>
                    <tfoot>
                         <?php $numColumnas = 5; ?>
                         <tr class="cart-subtotal">
                              <th><?php esc_html_e('Subtotal', 'woocommerce'); ?></th>
                              <td colspan="<?= $numColumnas ?>" style="text-align: right;"><?php wc_cart_totals_subtotal_html(); ?></td>
                         </tr>

                         <?php foreach (WC()->cart->get_coupons() as $code => $coupon) : ?>
                              <tr class="cart-discount coupon-<?php echo esc_attr(sanitize_title($code)); ?>">
                                   <th><?php wc_cart_totals_coupon_label($coupon); ?></th>
                                   <td></td>
                                   <td><?php wc_cart_totals_coupon_html($coupon); ?></td>
                              </tr>
                         <?php endforeach; ?>



                         <tr class="order-total">
                              <th><?php esc_html_e('Total', 'woocommerce'); ?></th>
                              <td colspan="<?= $numColumnas ?>" style="text-align: right;"><?php wc_cart_totals_order_total_html(); ?></td>
                         </tr>


                    </tfoot>
               </table>
          </div>
     </div>

<?php } ?>
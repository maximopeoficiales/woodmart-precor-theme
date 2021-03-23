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
     if (shortcode_exists("woocommerce_my_account") && get_current_user_id() == 0) {
          wp_enqueue_style('login-styles', get_template_directory_uri() . '/helpers/css/login-style.css', array());
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

<?php 
// Functions Adicionales del tema
 //cambio el nombre del archivo que llega al correo
 add_filter('wpo_wcpdf_filename', 'wpo_wcpdf_custom_filename', 10, 4);
 function wpo_wcpdf_custom_filename($filename, $template_type, $order_ids, $context)
 {
      // prepend your shopname to the file
      $invoice_string = _n('invoice', 'invoices', count($order_ids), 'woocommerce-pdf-invoices-packing-slips');
      $new_prefix = "Pedido-";
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
 
      echo "<button class='button' type='button' style='    border-radius: 1.3rem;
      width: 100%;
      padding: .8rem;
      color: white;
      background: #00396E;
      margin-bottom: 1rem;' id='btnShowModalProducts'>Ver Productos</button>";
      echo "
      <script>
      let modal = document.querySelectorAll('#myModalProducts')[0];
      let btn = document.getElementById('btnShowModalProducts');
      let span = document.getElementsByClassName('closeModalProducts')[0];
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
 }

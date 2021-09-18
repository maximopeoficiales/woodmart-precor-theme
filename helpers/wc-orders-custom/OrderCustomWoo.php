<?php

class OrderCustomWoo
{
   private $id_order;
   private $order;
   public function __construct($id_order)
   {
      $this->id_order = $id_order;
      $this->order = wc_get_order($this->id_order);
   }
   public function getOrderData(): array
   {
      $order_items = $this->order->get_items();
      $items_count = count($order_items); // Get order items count
      $items_data = []; // Initializing
      // Loop through order items
      foreach ($order_items as $item) {
         // obtengo producto
         $product = wc_get_product($item->get_product_id());
         $variation_id = $item->get_variation_id();
         $product_id =
            $variation_id > 0 ? $variation_id : $item->get_product_id();
         // Set specific data for each item in the array
         $und = null;
         $undValue = null;
         $unxpaq = null;
         foreach ($product->get_meta_data() as $mt) {
            if ($mt->key == "und") {
               if ($mt->value != "kg") {
                  $und = $mt->value;
               }
            }
            if ($mt->key == "und_value") {
               $undValue = $mt->value;
            }
            if ($mt->key == "unxpaq") {
               $unxpaq = doubleval($mt->value);
            }
         }
         $items_data[] = [
            "product_id" => $product_id,
            "variation_id" => $variation_id,
            "name" => $item->get_name(),
            "quantity" => intval($item->get_quantity()),
            "subtotal" => $item->get_subtotal(),
            "total" => $item->get_total(),
            "subtotal_tax" => $item->get_subtotal_tax(),
            "und" => $und,
            "und_value" => $undValue,
            "unxpaq" => $unxpaq,
            "meta_data" => $product->get_meta_data(),
            "sku" => $product->get_sku(),
            "weight" => $product->get_weight(),
            "price" => $product->get_price(),
            // 'parent_name'    => $product->get_parent_name(),
         ];
      }
      return [
         "id" => $this->id_order,
         "parent_id" => $this->order->get_parent_id(),
         "order_key" => $this->order->get_order_key(),
         "created_via" => $this->order->get_created_via(),
         "version" => $this->order->get_version(),
         "status" => $this->order->get_status(),
         "currency" => $this->order->get_currency(),
         "date_created" => wc_format_datetime($this->order->get_date_created()),
         "date_modified" => wc_format_datetime(
            $this->order->get_date_modified()
         ),
         "discount_total" => $this->order->get_discount_total(),
         "discount_tax" => $this->order->get_discount_tax(),
         "shipping_total" => $this->order->get_shipping_total(),
         "shipping_tax" => $this->order->get_shipping_tax(),
         "cart_tax" => $this->order->get_cart_tax(),
         "total" => $this->order->get_total(),
         "total_tax" => $this->order->get_total_tax(),
         "prices_include_tax" => $this->order->get_prices_include_tax(),
         "customer_id" => $this->order->get_customer_id(),
        "customer_id_cli" => $this->getPRFXValueByUserID(
            $this->order->get_customer_id(),
            "id_cli"
         ),
         "customer_nombeje" => $this->getPRFXValueByUserID(
            $this->order->get_customer_id(),
            "nombeje"
         ),
         "customer_telfeje" => $this->getPRFXValueByUserID(
            $this->order->get_customer_id(),
            "telf_eje"
         ),
         "customer_emaileje" => $this->getPRFXValueByUserID(
            $this->order->get_customer_id(),
            "email_eje"
         ),

         "customer_drcfisc" => $this->getPRFXValueByUserID(
            $this->order->get_customer_id(),
            "drcfisc"
         ),
         "customer_ip_address" => $this->order->get_customer_ip_address(),
         "customer_user_agent" => $this->order->get_customer_user_agent(),
         "customer_note" => $this->order->get_customer_note(),
         "billing" => [
            "first_name" => $this->order->get_billing_first_name(),
            "last_name" => $this->order->get_billing_last_name(),
            "company" => $this->order->get_billing_company(),
            "email" => $this->order->get_billing_email(),
            "address_1" => $this->order->get_billing_address_1(),
            "address_2" => $this->order->get_billing_address_2(),
            "city" => $this->order->get_billing_city(),
            "state" => $this->order->get_billing_state(),
            "post_code" => $this->order->get_billing_postcode(),
            "country" => $this->order->get_billing_country(),
            "phone" => $this->order->get_billing_phone(),
         ],
         "shipping" => [
            "first_name" => $this->order->get_shipping_first_name(),
            "last_name" => $this->order->get_shipping_last_name(),
            "company" => $this->order->get_shipping_company(),
            "address_1" => $this->order->get_shipping_address_1(),
            "address_2" => $this->order->get_shipping_address_2(),
            "city" => $this->order->get_shipping_city(),
            "state" => $this->order->get_shipping_state(),
            "post_code" => $this->order->get_shipping_postcode(),
            "country" => $this->order->get_shipping_country(),
         ],
         "payment_method" => $this->order->get_payment_method(),
         "payment_method_title" => $this->order->get_payment_method_title(),
         "transaction_id" => $this->order->get_transaction_id(),
         "date_paid" => $this->order->get_date_paid(),
         "date_completed" => $this->order->get_date_completed(),
         "cart_hash" => $this->order->get_cart_hash(),
         "meta_data" => $this->order->get_meta_data(),
         "line_items" => $items_data, // <=== HERE
         "total_items" => $items_count,
         "tax_lines" => $this->order->get_taxes(),
         "fee_lines" => $this->order->get_fees(),
         "coupon_lines" => $this->order->get_coupons(),
         "refunds" => $this->order->get_refunds(),
      ];
   }
   public function getOrder(): WC_Order
   {
      return $this->order;
   }

   private function getPRFXValueByUserID($user_id, $name_key): string
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
}

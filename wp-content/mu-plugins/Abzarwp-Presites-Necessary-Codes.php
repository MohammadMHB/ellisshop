<?php
add_action( 'plugins_loaded', function () {
add_action( 'jet-engine/register-macros', function(){ 
	
    if ( ! function_exists( 'WC' ) ) {
        return;
    }
  
    class Products_On_Sale_Macro extends \Jet_Engine_Base_Macros {

        public function macros_tag() {
            return 'wc_products_on_sale';
        }

        public function macros_name() {
            return 'WC Products on Sale';
        }

        public function macros_args() {
            return array();
        }

        public function macros_callback( $args = array() ) {

            $on_sale = array_merge( array( 0 ), wc_get_product_ids_on_sale() );
			
            return implode( ',', $on_sale );
				
        }
    }

    new Products_On_Sale_Macro();
    
} );

function display_product_discount_percentage() { 
    // Ensure WooCommerce is active 
    if ( ! class_exists( 'WooCommerce' ) ) { 
        return 'WooCommerce is not active'; 
    } 
 
global $post; 
 
// Get the product ID from the global post object 
$product_id = $post->ID; 
 
// Get the product object 
$product = wc_get_product( $product_id ); 
 
if ( ! $product ) { 
    return false; // Invalid product ID 
} 
 
$discount_percentage = 0; 
 
if ( $product->is_type( 'variable' ) ) { 
    // For variable products, find the min and max discount among variations 
    $min_discount = PHP_INT_MAX; 
    $max_discount = 0; 
    foreach ( $product->get_children() as $variation_id ) { 
        $variation = wc_get_product( $variation_id ); 
        $regular_price = (float) $variation->get_regular_price(); 
        $sale_price = (float) $variation->get_sale_price(); 
 
        if ( $regular_price > 0 && $sale_price > 0 && $regular_price > $sale_price ) { 
            $discount = ( ( $regular_price - $sale_price ) / $regular_price ) * 100; 
            if ( $discount < $min_discount ) { 
                $min_discount = $discount; 
            } 
            if ( $discount > $max_discount ) { 
                $max_discount = $discount; 
            } 
        } 
    } 
    if ( $min_discount == PHP_INT_MAX ) { 
        return false; // No discount available 
    } else { 
        return round( $min_discount ) . '% - ' . round( $max_discount ) . '%'; 
    } 
} elseif ( $product->is_type( 'grouped' ) ) { 
    // For grouped products, find the min and max discount among grouped items 
    $min_discount = PHP_INT_MAX; 
    $max_discount = 0; 
    foreach ( $product->get_children() as $child_id ) { 
        $child_product = wc_get_product( $child_id ); 
        $regular_price = (float) $child_product->get_regular_price(); 
        $sale_price = (float) $child_product->get_sale_price(); 
 
        if ( $regular_price > 0 && $sale_price > 0 && $regular_price > $sale_price ) { 
            $discount = ( ( $regular_price - $sale_price ) / $regular_price ) * 100; 
            if ( $discount < $min_discount ) { 
                $min_discount = $discount; 
            } 
            if ( $discount > $max_discount ) { 
                $max_discount = $discount; 
            } 
        } 
    } 
    if ( $min_discount == PHP_INT_MAX ) { 
        return false; // No discount available 
    } else { 
        return round( $min_discount ) . '% - ' . round( $max_discount ) . '%'; 
    } 
} else { 
    // For simple products 
    $regular_price = (float) $product->get_regular_price(); 
    $sale_price = (float) $product->get_sale_price(); 
 
    if ( $regular_price > 0 && $sale_price > 0 && $regular_price > $sale_price ) { 
        $discount_percentage = ( ( $regular_price - $sale_price ) / $regular_price ) * 100; 
    } 
 
    if ( $discount_percentage > 0 ) { 
        return round( $discount_percentage ) . '%'; 
    } else { 
        return false; // No discount available 
    } 
} 
} 
 
// Register the shortcode 
add_shortcode( 'discount_percentage', 'display_product_discount_percentage' );


function get_product_sale_end_date() {
    global $product;

    if (!$product) {
        return '';
    }

    $sale_end_dates = [];

    // For simple products
    if ($product->is_type('simple') && $product->is_on_sale()) {
        $sale_end_date = $product->get_date_on_sale_to();
        if ($sale_end_date) {
            $sale_end_dates[] = $sale_end_date->getTimestamp();
        }
    }

    // For variable products
    if ($product->is_type('variable')) {
        $available_variations = $product->get_available_variations();
        foreach ($available_variations as $variation) {
            $variation_id = $variation['variation_id'];
            $variation_product = wc_get_product($variation_id);

            if ($variation_product->is_on_sale()) {
                $variation_sale_end_date = $variation_product->get_date_on_sale_to();
                if ($variation_sale_end_date) {
                    $sale_end_dates[] = $variation_sale_end_date->getTimestamp();
                }
            }
        }
    }

    if (!empty($sale_end_dates)) {
        // Get the earliest sale end date
        $earliest_sale_end_date = min($sale_end_dates);
        return date('Y-m-d H:i:s', $earliest_sale_end_date);
    }

    return '';
}
add_shortcode('sale_end_date', 'get_product_sale_end_date');


}, 9999 );
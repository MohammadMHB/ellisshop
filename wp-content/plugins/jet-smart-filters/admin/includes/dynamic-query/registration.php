<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die();
}

if ( ! class_exists( 'Jet_Smart_Filters_Admin_Dynamic_Query_Registration' ) ) {
	/**
	 * Jet Smart Filters Admin Dynamic Query Registration class
	 */
	class Jet_Smart_Filters_Admin_Dynamic_Query_Registration {

		public $manager;
		
		public function __construct() {
			// Init dynamic query data
			require jet_smart_filters()->plugin_path( 'admin/includes/dynamic-query/manager.php' );
			$this->manager = new Jet_Smart_Filters_Admin_Dynamic_Query_Manager();

			// Insert queries to localized data
			add_filter( 'jet-smart-filters/admin/localized-data', array( $this, 'insert_queries' ), -999 );

			// Woocommerce
			if ( class_exists( 'WooCommerce' ) ) {
				$this->add_woocommerce_items();
			}
		}

		public function insert_queries( $data ) {

			if ( ! $this->manager->list || ! isset( $data['filter_settings']['settings_block']['settings']['_query_var'] ) ) {
				return $data;
			}

			$queries = isset( $data['filter_settings']['settings_block']['settings']['_query_var']['options'] )
				? $data['filter_settings']['settings_block']['settings']['_query_var']['options']
				: [];

			foreach ( $this->manager->list as $query_item ) {
				$new_option = array(
					'value' => $query_item->get_name(),
					'label' => $query_item->get_label(),
				);
				
				$item_extra_args = $query_item->get_extra_args();
				if ( $item_extra_args ){
					$new_option['fields'] = $item_extra_args;
				}

				$item_delimiter = $query_item->get_delimiter();
				if ( $item_delimiter ){
					$new_option['separator'] = $item_delimiter;
				}

				array_push( $queries, $new_option );
			}

			// Add options for Query Variable
			$data['filter_settings']['settings_block']['settings']['_query_var']['options'] = $queries ;

			// Add options for Custom Query Variable
			$data['filter_settings']['settings_block']['settings']['_custom_query_var']['options'] = $queries ;

			return $data;
		}

		public function add_woocommerce_items() {
    $woocommerce_items_list = array(
        '_price'                 => __('WooCommerce: _price - filter by product price', 'jet-smart-filters'),
        '_wc_average_rating'     => __('WooCommerce: _wc_average_rating - filter by product rating', 'jet-smart-filters'),
        'total_sales'            => __('WooCommerce: total_sales - filter by sales count', 'jet-smart-filters'),
        '_weight'                => __('WooCommerce: _weight - product weight', 'jet-smart-filters'),
        '_length'                => __('WooCommerce: _length - product length', 'jet-smart-filters'),
        '_width'                 => __('WooCommerce: _width - product width', 'jet-smart-filters'),
        '_height'                => __('WooCommerce: _height - product height', 'jet-smart-filters'),
        '_sale_price_dates_from' => __('WooCommerce: _sale_price_dates_from - filter by product sale start date', 'jet-smart-filters'),
        '_sale_price_dates_to'   => __('WooCommerce: _sale_price_dates_to - filter by product sale end date', 'jet-smart-filters')
    );

    $this->manager->register_items($woocommerce_items_list);
}

	}
}
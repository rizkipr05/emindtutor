<?php

/**
 * Validate WC Integrations
 */

defined( 'ABSPATH' ) or die( 'I feel very small...' );

class WPWFW_Integrations {

	private static $_instance;
    private static $active_plugins;
	public static $supported_integrations;
	public static $active_integrations;
	public static $meta_field_variables;

    public function __construct() {

        self::$active_plugins = (array) get_option( 'active_plugins', array() );

        if ( is_multisite() ) {
			self::$active_plugins = array_merge( self::$active_plugins, get_site_option( 'active_sitewide_plugins', array() ) );
		}

		self::$supported_integrations = array(
			'cart_abandon' => array( 
				'woo-cart-abandonment-recovery' => 'woo-cart-abandonment-recovery/woo-cart-abandonment-recovery.php'
			),
			'courier' => (isset(WPWFW_CONFIG_OVERRIDE['WPWFW_SUPPORTED_COURIER']) && !empty(WPWFW_CONFIG_OVERRIDE['WPWFW_SUPPORTED_COURIER']) ) ? WPWFW_CONFIG_OVERRIDE['WPWFW_SUPPORTED_COURIER'] : array(
				'ninja-van-woocommerce' => 'ninja-van-woocommerce/ninja-van-woocommerce.php',
				'dhl-for-woocommerece-ecs-master'	=> 'dhl-for-woocommerece-ecs-master/pr-dhl-woocommerce.php',
				'pos-for-woocommerce' => 'pos-for-woocommerce/posmalaysia.php',
				'dhl-ecommerce-apac' => 'dhl-ecommerce-apac/dhl-ecommerce-apac.php',
				'jt-express' => 'jt-express/jnt.php',
				'mygdex-for-woocommerce' => 'mygdex-for-woocommerce/gdex.php',
				'mygdex-prime-for-woocommerce' => 'mygdex-prime-for-woocommerce/gdex-prime.php',
				'delyvax' => 'delyvax/delyvax.php',
				'bizapp-for-woocommerce' => 'bizapp-for-woocommerce/bizapp-woocommerce.php',
				'EasyParcel-WooCommerce-Plugin-V1.1.3' => 'EasyParcel-WooCommerce-Plugin-V1.1.3/easyparcel.php',
				'woo-advanced-shipment-tracking' => 'woo-advanced-shipment-tracking/woocommerce-advanced-shipment-tracking.php',
				'flash-express-malaysia' => 'flash-express-malaysia/flash-express-malaysia.php',
			),
		);

		self::$active_integrations = (array) $this->get_active_integrations();

		self::$meta_field_variables = (array) $this->get_supported_meta_field_variables();

    }

	public static function get_instance() {

        if ( null === self::$_instance ) {
            self::$_instance = new self();
        }

        return self::$_instance;
        
    }

    public function is_woocommerce_active() {

		if ( class_exists( 'WooCommerce' ) ) return true;

		if ( ! function_exists( 'is_checkout' ) ) return false;

		return in_array( 'woocommerce/woocommerce.php', $this::$active_plugins, true ) || array_key_exists( 'woocommerce/woocommerce.php', $this::$active_plugins );
    
	}

	public function get_supported_integrations( $filter = array() ) {

		if ( !is_array( $filter ) ) return array_merge( ...$this::$supported_integrations );

		$result = array();
		foreach ( $this::$supported_integrations as $type => $integration) {
			if ( !in_array( $type, $filter ) ) {
				$result = array_merge( $result, $integration );
			}
		}

		return $result;

	}

	public function get_active_integrations( ) {

		$plugins = [];

		$supported = $this->get_supported_integrations();
		if ( !is_array( $supported ) ) return $plugins;
		foreach ( $supported as $integration => $integration_path ) {
			if ( $this->is_integration_active( ['plugin_path' => $integration_path] ) ) array_push( $plugins, $integration );
		}

		return $plugins;
	}

	private function get_supported_meta_field_variables() {

		$variables = array();
		foreach ( array_keys( $this->get_supported_integrations( ['cart_abandon'] ) ) as $integration ) {
			$shortcodes = $this->get_integration_shortcodes( $integration );
			if ( empty( $shortcodes ) ) continue;
			$variables = array_merge( $variables, $shortcodes );
		}

		return $variables;

	}

	public function is_integration_active( $arg = [] ) {
		
		if ( !is_array( $arg ) ) return false;

		if ( isset( $arg['class'] ) && class_exists( $arg['class'] ) ) return true;

		if ( isset( $arg['function'] ) && !function_exists( $arg['function'] ) ) return false;

		if ( !isset( $arg['plugin_path'] ) ) $arg['plugin_path'] = '';
		
		return in_array( $arg['plugin_path'], self::$active_plugins, true ) || array_key_exists( $arg['plugin_path'], self::$active_plugins );


	}

	public function get_integration_notification_template( $template_name = '' ) {

		$custom_message_template = (isset(WPWFW_CONFIG_OVERRIDE['WPWFW_CUSTOM_TEMPLATE']) && !empty(WPWFW_CONFIG_OVERRIDE['WPWFW_CUSTOM_TEMPLATE']) ) ? WPWFW_CONFIG_OVERRIDE['WPWFW_CUSTOM_TEMPLATE'] : [
			// Ninja_Van_WooCommerce
			'nv-pending-pickup' => '[ *ORDER UPDATE : TRACKING NUMBER* ]\n\nHi *%billing_first_name%*!\n\nTracking number anda : *%ninja_van_tracking_number%*\n\nBoleh click untuk semak status parcel *terbaru*:\nhttps://www.ninjavan.co/en-my/tracking?id=%ninja_van_tracking_number%\n\nTerima Kasih!\n\n----\n\nAdmin %shop_name%',
			'nv-in-transit' => '[ *ORDER UPDATE : IN TRANSIT* ]\n\nHi *%billing_first_name%*!\n\nTracking number anda : *%ninja_van_tracking_number%*\n\nOrder anda telah pun dalam perjalanan ke Pusat Agihan Ninjavan.\n\n----\n\nAdmin %shop_name%',
			'nv-out-delivery' => '[ *ORDER UPDATE : OUT FOR DELIVERY* ]\n\nHi *%billing_first_name%*!\n\nTracking Number : *%ninja_van_tracking_number%*\nPilihan Pembayaran : *%payment_method_title%*\n\nOrder anda dalam perjalanan ke rumah anda.\n\nJika anda pilih untuk *bayar semasa barang sampai*, sila sediakan *RM%order_total%* untuk dibayar pada posmen Ninjavan nanti.\n\nSila jaga SOP ya! #kitajagakita\n\n----\n\nAdmin %shop_name%',
			'nv-returned' => '',
		];

		if ( empty( $template_name ) ) return $custom_message_template;

        if ( !array_key_exists( $template_name, $custom_message_template ) ) return '';

        return $custom_message_template[$template_name];

	}

	public function get_integration_options( $integration ) {
		$integration_options = [
			'woo-cart-abandonment-recovery' => array(
				'ca_time' => 'wcf_ca_cron_run_time',
			),
		];

		if ( empty( $integration ) ) return array();

		if ( !array_key_exists( $integration, $integration_options ) ) return array();

		return $integration_options[$integration];
	}

	public function get_integration_shortcodes( $integration ) {

		$custom_shortcodes = [
			/**
			 * NinjaVan Domestic
			 * ninja-van-woocommerce/ninja-van-woocommerce.php
			 * 'ninja_van_tracking_number'
			 */
			'ninja-van-woocommerce' => array(
				'ninja_van_tracking_number' => 'ninja_van_tracking_number',
			),
			/**
			 * DHL APAC Domestic
			 * dhl-ecommerce-apac/dhl-ecommerce-apac.php
			 * 'dhl_tracking_number'
			 */
			'dhl-ecommerce-apac' => array(
				'dhl_apac_tracking_number' => 'him_dhl_order_shipment_id',
			),
			/**
			 * DHL Domestic
			 * dhl-for-woocommerece-ecs-master/pr-dhl-woocommerce.php
			 * _pr_shipment_dhl_label_tracking = array(
			 * 	'tracking_number',
			 * 	'label_path',
			 * 	'return_label_number'
			 * )
			 */
			'dhl-for-woocommerece-ecs-master' => array(
				'dhl_tracking_number' => '_pr_shipment_dhl_label_tracking//tracking_number',
			),
			/**
			 * Pos Malaysia Domestic
			 * pos-for-woocommerce/posmalaysia.php
			 * 'plconnote'
			 */
			'pos-for-woocommerce' => array(
				'poslaju_tracking_number' => 'plconnote',
			),
			/**
			 * BizApp Domestic
			 * bizapp-for-woocommerce/bizapp-woocommerce.php
			 * '_bizapp_tracking_no', '_bizapp_courier_name',
			 * 'bizapp_for_woocommerce_tracking_id' (?)
			 */
			'bizapp-for-woocommerce' => array(
				'bizzapp_tracking_number' => '_bizapp_tracking_no',
			),
			/**
			 * EasyParcel Domestic
			 * EasyParcel-WooCommerce-Plugin-V1.1.3/easyparcel.php
			 * https://helpcentre-my.easyparcel.com/support/solutions/articles/9000189826-woocommerce
			 * '_ep_awb', '_ep_tracking_url'
			 */
			'EasyParcel-WooCommerce-Plugin-V1.1.3' => array(
				'easyparcel_tracking_number' => '_ep_awb',
				'easyparcel_tracking_url' => '_ep_tracking_url',
			),
			/**
			 * JNT Express Domestic
			 * jt-express/jnt.php
			 * 'jtawb', 'jtorder', 'jtcode'
			 */
			'jt-express' => array(
				'jt_express_order_id' => 'jtorder',
				'jt_express_tracking_number' => 'jtawb',
			),
			/**
			 * myGDEX Domestic
			 * mygdex-for-woocommerce/gdex.php
			 * 'gdex_tracking_number'
			 */
			'mygdex-for-woocommerce' => array(
				'gdex_tracking_number' => 'consignment_number',
			),
			/**
			 * myGDEX Prime Corporate
			 * mygdex-prime-for-woocommerce/gdex-prime.php
			 * 'gdex_prime_tracking_number'
			 */
			'mygdex-prime-for-woocommerce' => array(
				'gdex_prime_tracking_number' => 'gdex_prime_shipment_order_id',
			),
			/**
			 * DelyvaX Domestic
			 * delyvax/delyvax.php
			 * 'delyvax_order_id', 'delyvax_tracking_number'
			 */
			'delyvax' => array(
				'delyvax_order_id' => 'DelyvaXOrderID',
				'delyvax_tracking_number' => 'DelyvaXTrackingCode',
			),
			/**
			 * AfterShip Domestic
			 * aftership-woocommerce-tracking/aftership-woocommerce-tracking.php
			 * 'aftership_tracking_provider', '_aftership_tracking_number'
			 */
			'aftership-woocommerce-tracking' => array(
				'aftership_tracking_provider' => '_aftership_tracking_provider',
				'aftership_tracking_number' => '_aftership_tracking_number',
			),
			/**
			 * Advanced Shipment Tracking
			 * woo-advanced-shipment-tracking/woocommerce-advanced-shipment-tracking.php
			 * _wc_shipment_tracking_items = array(
			 * 	array(
			 * 		'tracking_number',
			 * 		'tracking_provider'
			 * 	)
			 * )
			 */
			'woo-advanced-shipment-tracking' => array(
				'ast_tracking_number' => '_wc_shipment_tracking_items//-1//tracking_number',
				'ast_tracking_provider' => '_wc_shipment_tracking_items//-1//tracking_provider'
			),
			/**
			 * Flash Express Malaysia Domestic
			 * flash-express-malaysia/flash-express-malaysia.php
			 * 'flash_tracking_number', 'flash_order_id', 'flash_returned_pno'
			 */
			'flash-express-malaysia' => array(
				'flash_tracking_number' => 'flash_tracking_number',

			),
			'woo-cart-abandonment-recovery' => array(
				'checkout_url'	=> 'checkout_url',
				'shop_name'	=> 'shop_name',
				'shop_url'	=> 'shop_url',
				'shopping_date' => 'time',
				'email' => 'email',
				'order_product' => 'cart_contents',
				'order_total' => 'cart_total',
				'billing_first_name' => 'wcf_first_name',
				'billing_last_name' => 'wcf_last_name',
				'billing_phone' => 'wcf_phone_number',
				'billing_company' => 'wcf_billing_company',
				'billing_address_1' => 'wcf_billing_address_1',
				'billing_address_2' => 'wcf_billing_address_2',
				'billing_state' => 'wcf_billing_state',
				'billing_postcode' => 'wcf_billing_postcode',
				'billing_country' => 'wcf_location',
				'shipping_first_name' => 'wcf_shipping_first_name',
				'shipping_last_name' => 'wcf_shipping_last_name',
				'shipping_company' => 'wcf_shipping_company',
				'shipping_country' => 'wcf_shipping_country',
				'shipping_address_1' => 'wcf_shipping_address_1',
				'shipping_address_2' => 'wcf_shipping_address_2',
				'shipping_city' => 'wcf_shipping_city',
				'shipping_state' => 'wcf_shipping_state',
				'shipping_postcode' => 'wcf_shipping_postcode',
				'shipping_country' => 'wcf_location',
			),
		];

		if ( empty( $integration ) ) return array();

		if ( !array_key_exists( $integration, $custom_shortcodes ) ) return array();

		return $custom_shortcodes[$integration];

	}

	public function is_default_event( $event = '' ) {
		
		$data = [
			// Ninja_Van_WooCommerce
			'nv-pending-pickup',
			'nv-out-delivery',
			// Flash_Express_Malaysia
			'flash-tracking-assigned',
			'flash-out-delivery',
		];

		if ( in_array( $event, $data ) ) return true;

		return false;
	}

	
}

if ( class_exists( 'WPWFW_Core' ) ) {
    WPWFW_Core::register( 'integration', 'WPWFW_Integrations' );
}
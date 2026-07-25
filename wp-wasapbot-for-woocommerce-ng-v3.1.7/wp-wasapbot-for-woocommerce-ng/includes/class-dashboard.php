<?php

defined( 'ABSPATH' ) or die( 'I feel very small...' );

/**
 * Class WPWFW_Dashboard
 */

class WPWFW_Dashboard {

    private static $_instance = null;
    public $page_data = [];

    public function __construct() {

    }

    public function prepare() {
        $this->get_data();
    }

    public function get_bulk_actions() {
        return array( 'wa_resend_message'=>'Resend WhatsApp' );
    }

    /**
     * Get user's preferred items per page from WooCommerce orders screen
     * Reuses the same setting that user configured in WooCommerce > Orders
     */
    private function get_items_per_page() {
        $user_id = get_current_user_id();
        
        // Use WooCommerce's existing screen option for orders per page
        // This is the same setting from WooCommerce > Orders page
        $per_page = get_user_meta($user_id, 'edit_shop_order_per_page', true);
        
        // If no preference set, use WooCommerce's default (20)
        if (empty($per_page) || $per_page < 1) {
            $per_page = 20; // WooCommerce default
        }
        
        // Ensure reasonable limits (same as WooCommerce)
        return max(5, min(999, intval($per_page)));
    }

    private function get_data() {
        
        /* WooCommerce is active or not */
        $this->page_data['is_woocommerce_enabled'] = ( WPWFW_Core()->integration->is_woocommerce_active() ) ? true : false;
        $woo_data = $this->get_woocommerce_orders();
        $this->page_data['order_sent_stats'] = $woo_data['total_sent'];
        $this->page_data['order_resend_data'] = $woo_data['table'];

        /* Cart abandon are active or not */
        $this->page_data['cart_abandon_app'] = $this->get_cart_abandon_plugin();
        $cart_abandonment_data = $this->get_abandoned_orders();
        $this->page_data['cart_abandon_sent_stats'] = $cart_abandonment_data['total_sent'];
        $this->page_data['cart_abandon_resend_data'] = $cart_abandonment_data['table'];
        $this->page_data['cart_abandon_data'] = $cart_abandonment_data;

        $this->page_data['message_all_time_stats'] = $this->get_messages();
        $queue = WPWFW_Core()->services->get_queued_messages();
        $this->page_data['queued_message_count'] = is_array($queue) ? count($queue) : 0; 
        $this->page_data['queued_message_data'] = $queue;

    }

    private function get_woocommerce_orders() {

        $result = array(
            'table' => [],
            'total_sent' => 0
        );
        if ( !$this->page_data['is_woocommerce_enabled'] ) return $result;

        // Get current page number from URL
        $current_page = isset($_GET['paged']) ? max(1, intval($_GET['paged'])) : 1;
        
        // Get user's preferred items per page
        $per_page = $this->get_items_per_page();
        
        // Get orders with pagination
        $result = WPWFW_Core()->services->wc_get_notified_orders($current_page, $per_page);

        return $result;

    }

    private function get_abandoned_orders() {

        $result = array(
            'table' => [],
            'total_sent' => 0
        );

        $settings = WPWFW_Core()->system->get_settings();
        if ( !isset( $settings['cart_abandon_plugin'] ) || !in_array($settings['cart_abandon_plugin'], $this->page_data['cart_abandon_app']) ) {
            $result['table'] = array('error'=>'`Cart Abandon` plugin initialization is required`');
            return $result;
        }

        $result = WPWFW_Core()->services->wc_get_abandoned_orders( $settings['cart_abandon_plugin'] );

        return $result;

    }

    private function get_cart_abandon_plugin() {
        return array_intersect( array_keys( WPWFW_Core()->services->cart_abandon_events ), WPWFW_Core()->integration::$active_integrations );
    }

    private function get_messages() {
        $analytics = WPWFW_Core()->system->get_plugin_data()['analytics'] ?? [];
        return empty( $analytics ) ? 0 : array_sum( $analytics );
    }

}
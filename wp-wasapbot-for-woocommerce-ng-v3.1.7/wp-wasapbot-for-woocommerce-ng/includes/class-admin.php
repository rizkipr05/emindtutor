<?php

defined( 'ABSPATH' ) or die( 'I feel very small...' );

/**
 * Class WPWFW_Admin
 */

class WPWFW_Admin {
    
    private static $_instance = null;
    public $admin_path = null;
    public $admin_url = null;
    public $ui = null;
    private $route = array();

    public function __construct() {

        $this->admin_path = WPWFW_PLUGIN_DIR;
        $this->admin_url = WPWFW_PLUGIN_URL;
        $this->route = array(
            '/settings' => 'admin_page'
        );

        
        add_action( 'admin_menu', array( $this, 'register_admin_menu' ), 90 );
        // Removed: old global asset loading - now handled by Asset Manager
        add_filter( 'plugin_action_links_' . WPWFW_PLUGIN_BASENAME, array( $this, 'plugin_action_links' ), 10 );
        add_filter( 'plugin_row_meta', array( $this, 'plugin_meta_links' ), 10, 4 );

        add_action('before_woocommerce_init', array( $this, 'add_wc_hpos_compatibility' ), 10);

        // Add AJAX handler for checking processing status
        add_action( 'wp_ajax_wpwfw_check_processing_status', array( $this, 'ajax_check_processing_status' ) );

    }

    public static function get_instance() {

        if ( null === self::$_instance ) {
            self::$_instance = new self();
        }

        return self::$_instance;
        
    }

    public function admin_enqueue_global_assets() {
        // Deprecated: Asset loading now handled by WPWFW_Asset_Manager
        // This method is kept for backward compatibility but does nothing
    }

    public function admin_enqueue_assets() {
        // Deprecated: Asset loading now handled by WPWFW_Asset_Manager
        // Only keeping admin footer text filter for backward compatibility
        add_filter( 'admin_footer_text', array( $this, 'admin_footer_text' ), 99999, 1 );
    }

    public function register_admin_menu() {

        $title = WPWFW_PLUGIN_SIMPLE_NAME;
        $capability = 'manage_woocommerce';

        $page = add_menu_page( WPWFW_PLUGIN_NAME, $title, $capability, WPWFW_PLUGIN_CORE_NAME, array( $this, 'page_route' ), WPWFW_Core()->system->get_plugin_icon( 'app' ), 11 );

        if ( class_exists( 'WPWFW_Dashboard' ) ) add_submenu_page( WPWFW_PLUGIN_CORE_NAME, __( 'Dashboard', WPWFW_PLUGIN_DOMAIN_LANGUAGE ), __( 'Dashboard', WPWFW_PLUGIN_DOMAIN_LANGUAGE ), $capability, WPWFW_PLUGIN_CORE_NAME, array( $this, 'page_route' ) );

        add_submenu_page( WPWFW_PLUGIN_CORE_NAME, __( 'Settings', WPWFW_PLUGIN_DOMAIN_LANGUAGE ), 'Settings', $capability, WPWFW_PLUGIN_CORE_NAME . '&path=/settings', 'page_settings' );

        if ( !class_exists( 'WPWFW_Dashboard' ) ) remove_submenu_page( WPWFW_PLUGIN_CORE_NAME, WPWFW_PLUGIN_CORE_NAME );

        // Removed: old specific asset loading - now handled by Asset Manager
   
    }

    public function load_admin_assets() {
        // Deprecated: Asset loading now handled by WPWFW_Asset_Manager
        // This method is kept for backward compatibility but does nothing
    }

    public function plugin_action_links( $actions ) {

        $custom_actions = array(
            '<a href="admin.php?page=' . WPWFW_PLUGIN_CORE_NAME . '&path=/settings" title="' . __( 'Settings of ', WPWFW_PLUGIN_DOMAIN_LANGUAGE ) . WPWFW_PLUGIN_SIMPLE_NAME .'">' . __( 'Settings', WPWFW_PLUGIN_DOMAIN_LANGUAGE ) . '</a>', 
        );

        foreach ( $custom_actions as $action ) {
            array_unshift( $actions, $action );
        }

        return $actions;

    }

    public function plugin_meta_links( $actions, $path ) {

        if ( strpos( $path, WPWFW_PLUGIN_BASENAME ) !== false ) {
            $custom_actions = array(
                '<a href="' . WPWFW_PLUGIN_WEBSITE . '/docs" title="' . WPWFW_PLUGIN_SIMPLE_NAME . '" target="_blank">Docs</a>',
                '<a href="' . WPWFW_PLUGIN_WEBSITE . '/" title="' . WPWFW_PLUGIN_SIMPLE_NAME . '" target="_blank"><img src="' . WPWFW_Core()->system->get_plugin_icon( 'row-meta' ) .'" alt="' . WPWFW_PLUGIN_SIMPLE_NAME . '" /></a>',
            );

            $actions = array_merge( $actions, $custom_actions );
        }

        return $actions;

    }

    public function page_route() {
        //phpcs:disable WordPress.Security.NonceVerification
        if ( ! isset( $_GET['page'] ) || WPWFW_PLUGIN_CORE_NAME !== sanitize_text_field( $_GET['page'] ) ) {
			return;
		}

        $route = 'admin_page';
        /**
         * Redirect if dashboard does not available (in development)
         */
        if ( ! class_exists( 'WPWFW_Dashboard' ) ) {
            WPWFW_Core()->ui->render( $route );
            return;
        }

        $route = 'dashboard_page';
        if ( isset( $_GET['path'] ) && array_key_exists( $_GET['path'], $this->route ) ) $route = $this->route[$_GET['path']];

        WPWFW_Core()->ui->render( $route );

        //phpcs:enable WordPress.Security.NonceVerification
    }

    public function page_settings() {
        //phpcs:disable WordPress.Security.NonceVerification
        if ( ! isset( $_GET['page'] ) || WPWFW_PLUGIN_CORE_NAME !== sanitize_text_field( $_GET['page'] ) ) {
			return;
		}

        $route = 'admin_page';
        if ( isset( $_GET['path'] ) && array_key_exists( $_GET['path'], $this->route ) ) $route = $this->route[$_GET['path']];
        
        WPWFW_Core()->ui->render( $route );

        //phpcs:enable WordPress.Security.NonceVerification
    }

    public function admin_footer_text() {

        $link = add_query_arg( array(
			'utm_source'   => 'WordPress',
			'utm_medium'   => 'Footer+More',
			'utm_campaign' => 'Lite+Plugin',
		), WPWFW_PLUGIN_WEBSITE . '/api-integration' );

		return sprintf( __( 'Thanks for using ' . WPWFW_PLUGIN_NAME . '. Do more with ' . WPWFW_PLUGIN_SIMPLE_NAME . '? <a href="%s" target="_blank">Visit Us</a>.', WPWFW_PLUGIN_DOMAIN_LANGUAGE ), $link );

    }

    public function is_dashboard_page() {
        
    }

    public function add_wc_hpos_compatibility() {
        if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
            
            \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );

        }
    }

    public function ajax_check_processing_status() {
        // Verify nonce for security
        if ( !wp_verify_nonce( $_POST['nonce'], 'wpwfw_check_status' ) ) {
            wp_send_json_error( 'Invalid nonce' );
        }

        // Check if order_id is provided
        if ( !isset( $_POST['order_id'] ) || empty( $_POST['order_id'] ) ) {
            wp_send_json_error( 'Order ID is required' );
        }

        $order_id = intval( $_POST['order_id'] );
        
        // Check if processing is still active
        $is_processing = false;
        
        // Check for processing locks
        $order = wc_get_order( $order_id );
        if ( $order ) {
            $order_status = $order->get_status();
            $processing_lock_key = 'wpwfw_processing_status_' . $order_id . '_' . $order_status;
            $has_processing_lock = get_transient( $processing_lock_key );
            
            // If no lock found for current status, check for any processing lock for this order
            if ( !$has_processing_lock ) {
                global $wpdb;
                $lock_pattern = '_transient_wpwfw_processing_status_' . $order_id . '_%';
                $existing_locks = $wpdb->get_var( $wpdb->prepare(
                    "SELECT option_name FROM {$wpdb->options} WHERE option_name LIKE %s LIMIT 1",
                    $lock_pattern
                ) );
                $has_processing_lock = !empty( $existing_locks );
            }
            
            // Check for pending or running Action Scheduler tasks
            $pending_customer_task = false;
            if ( function_exists( 'as_get_scheduled_actions' ) ) {
                $pending_actions = as_get_scheduled_actions( array(
                    'hook' => 'wpwfw_process_customer_notification',
                    'args' => array( $order_id ),
                    'status' => 'pending',
                    'per_page' => 1
                ) );
                $running_actions = as_get_scheduled_actions( array(
                    'hook' => 'wpwfw_process_customer_notification',
                    'args' => array( $order_id ),
                    'status' => 'in-progress',
                    'per_page' => 1
                ) );
                $pending_customer_task = !empty( $pending_actions ) || !empty( $running_actions );
            }
            
            $is_processing = $has_processing_lock || $pending_customer_task;
        }
        
        wp_send_json_success( array(
            'is_processing' => $is_processing,
            'order_id' => $order_id
        ) );
    }
    
}

if ( class_exists( 'WPWFW_Core' ) ) {
    WPWFW_Core::register( 'admin', 'WPWFW_Admin' );
}

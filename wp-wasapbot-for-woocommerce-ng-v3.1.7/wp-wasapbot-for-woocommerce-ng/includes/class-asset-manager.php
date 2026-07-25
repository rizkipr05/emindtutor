<?php

defined( 'ABSPATH' ) or die( 'I feel very small...' );

/**
 * Smart Asset Manager - Load only what's needed, when it's needed
 */
class WPWFW_Asset_Manager {
    
    private static $_instance = null;
    
    public static function instance() {
        if ( null === self::$_instance ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    
    public function __construct() {
        add_action( 'admin_enqueue_scripts', array( $this, 'smart_admin_assets' ), 10 );
        add_action( 'wp_enqueue_scripts', array( $this, 'smart_frontend_assets' ), 10 );
    }
    
    /**
     * Context-aware admin asset loading
     */
    public function smart_admin_assets() {
        $screen = get_current_screen();
        if ( ! $screen ) {
            return; // Early exit if screen not available
        }
        
        $page = isset( $_GET['page'] ) ? sanitize_text_field( $_GET['page'] ) : '';
        $path = isset( $_GET['path'] ) ? sanitize_text_field( $_GET['path'] ) : '';
        
        // Core fonts - ONLY on plugin pages
        if ( $this->is_plugin_page( $screen, $page ) ) {
            wp_enqueue_style( 'wpwfw-core-fonts', WPWFW_PLUGIN_URL . '/assets/css/shared/core-fonts.css', array(), WPWFW_VERSION );
            
            // Debug logging to help diagnose icon issues
            if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
                error_log( "WPWFW: Loading FontAwesome on screen: {$screen->id}, page: {$page}" );
            }
        } else {
            // Fallback: Load on all WooCommerce pages if main detection fails
            if ( strpos( $screen->id, 'shop_order' ) !== false || 
                 strpos( $screen->id, 'woocommerce' ) !== false ||
                 strpos( $screen->id, 'wc-orders' ) !== false ) {
                wp_enqueue_style( 'wpwfw-core-fonts', WPWFW_PLUGIN_URL . '/assets/css/shared/core-fonts.css', array(), WPWFW_VERSION );
                
                if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
                    error_log( "WPWFW: Fallback FontAwesome loading on screen: {$screen->id}" );
                }
            }
        }
        
        // Settings page assets
        if ( $page === 'wasapbot-for-woocommerce' || ( $page === 'wasapbot' && $path === '/settings' ) ) {
            $this->load_settings_assets();
        }
        
        // Order page assets  
        if ( $screen->id === 'shop_order' || $screen->id === 'woocommerce_page_wc-orders' ) {
            $this->load_order_assets();
        }
        
        // Dashboard/bulk action assets
        if ( $page === 'wasapbot' && $path !== '/settings' ) {
            $this->load_dashboard_assets();
        }
    }
    
    /**
     * Load settings page specific assets only
     */
    private function load_settings_assets() {
        // Select2 for dropdowns
        wp_enqueue_style( 'wpwfw-select2', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css', array(), '4.0.13' );
        wp_enqueue_script( 'wpwfw-select2', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.full.min.js', array('jquery'), '4.0.13', true );
        
        // Connection testing (ONLY on settings page)
        wp_enqueue_script( 'wpwfw-connection', WPWFW_PLUGIN_URL . '/assets/js/admin/connection-test.js', array('jquery'), WPWFW_VERSION, true );
        wp_localize_script( 'wpwfw-connection', 'wpwfw_ajax', array(
            'ajax_url' => admin_url( 'admin-ajax.php' ),
            'ajax_nonce' => wp_create_nonce( 'wpwfw-action-ajax' )
        ));
        
        // Settings page CSS
        wp_enqueue_style( 'wpwfw-settings', WPWFW_PLUGIN_URL . '/assets/css/admin/settings.css', array(), WPWFW_VERSION );
        
        // Core JS for settings functionality
        wp_enqueue_script( 'wpwfw-settings', WPWFW_PLUGIN_URL . '/assets/js/admin/settings.js', array('jquery', 'wpwfw-select2'), WPWFW_VERSION, true );
        
        // Common utilities
        wp_enqueue_script( 'wpwfw-common', WPWFW_PLUGIN_URL . '/assets/js/shared/common.js', array('jquery'), WPWFW_VERSION, true );
        
        // Localize settings script
        $data = array(
            'ajax_nonce'        =>  wp_create_nonce( 'wpwfw-action-admin' ),
            'plugin_url'        =>  plugin_dir_url( WPWFW_PLUGIN_FILE ),
            'ajax_url'          =>  admin_url( 'admin-ajax.php' ),
            'admin_url'         =>  admin_url(),
        );
        wp_localize_script( 'wpwfw-settings', 'wpwfw_js', $data);
    }
    
    /**
     * Load order page specific assets only  
     */
    private function load_order_assets() {
        // Order meta box styling
        wp_enqueue_style( 'wpwfw-order', WPWFW_PLUGIN_URL . '/assets/css/admin/order-meta.css', array(), WPWFW_VERSION );
        
        // Processing status checker (AJAX polling)
        wp_enqueue_script( 'wpwfw-order-status', WPWFW_PLUGIN_URL . '/assets/js/admin/order-status.js', array('jquery'), WPWFW_VERSION, true );
        wp_localize_script( 'wpwfw-order-status', 'wpwfwOrder', array(
            'ajax_url' => admin_url( 'admin-ajax.php' ),
            'nonce' => wp_create_nonce( 'wpwfw_check_status' ),
            'poll_interval' => 2000
        ));
    }
    
    /**
     * Load dashboard page specific assets only
     */
    private function load_dashboard_assets() {
        // Dashboard styling
        wp_enqueue_style( 'wpwfw-dashboard', WPWFW_PLUGIN_URL . '/assets/css/admin/dashboard.css', array(), WPWFW_VERSION );
        
        // Dashboard functionality
        wp_enqueue_script( 'wpwfw-dashboard', WPWFW_PLUGIN_URL . '/assets/js/admin/dashboard.js', array('jquery'), WPWFW_VERSION, true );
        wp_localize_script( 'wpwfw-dashboard', 'wpwfwDashboard', array(
            'ajax_url' => admin_url( 'admin-ajax.php' ),
            'nonce' => wp_create_nonce( 'wpwfw-dashboard' )
        ));
    }
    
    /**
     * Smart frontend asset loading - ONLY on checkout
     */
    public function smart_frontend_assets() {
        // Phone validation ONLY on checkout page - direct loading for reliability
        if ( is_checkout() && ! $this->is_phone_validation_disabled() ) {
            $this->load_phone_validation_assets();
        }
    }
    
    /**
     * Load phone validation assets conditionally
     */
    private function load_phone_validation_assets() {
        wp_enqueue_style( 'wpwfw-intl-tel', 'https://cdn.jsdelivr.net/npm/intl-tel-input@15.0.2/build/css/intlTelInput.css', array(), '15.0.2' );
        wp_enqueue_script( 'wpwfw-intl-tel', 'https://cdn.jsdelivr.net/npm/intl-tel-input@15.0.2/build/js/intlTelInput.js', array(), '15.0.2', true );
        
        wp_enqueue_style( 'wpwfw-checkout', WPWFW_PLUGIN_URL . '/assets/css/frontend/checkout.css', array(), WPWFW_VERSION );
        wp_enqueue_script( 'wpwfw-checkout', WPWFW_PLUGIN_URL . '/assets/js/frontend/checkout.js', array('jquery', 'wpwfw-intl-tel'), WPWFW_VERSION, true );
        
        // Safe WooCommerce data retrieval
        $default_country = 'US';
        $allowed_countries = array();
        
        if ( class_exists( 'WooCommerce' ) && function_exists( 'WC' ) && WC()->countries ) {
            $default_country = WC()->countries->get_base_country();
            $allowed_countries = array_keys( WC()->countries->get_allowed_countries() );
        }
        
        wp_localize_script( 'wpwfw-checkout', 'wpwfwCheckout', array(
            'defaultCountry' => $default_country,
            'allowedCountries' => $allowed_countries,
            'utilsScript' => 'https://cdn.jsdelivr.net/npm/intl-tel-input@15.0.2/build/js/utils.js'
        ));
    }
    
    private function is_plugin_page( $screen, $page ) {
        // Check for WasapBot pages
        if ( strpos( $page, 'wasapbot' ) !== false || strpos( $screen->id, 'wpwfw' ) !== false ) {
            return true;
        }
        
        // Check for WooCommerce order pages (multiple possible screen IDs)
        $wc_order_screens = array(
            'shop_order',                    // Traditional post type
            'woocommerce_page_wc-orders',   // HPOS single order
            'edit-shop_order',              // HPOS order list
            'wc-orders',                    // Alternative HPOS ID
            'shop-order'                    // Alternative format
        );
        
        foreach ( $wc_order_screens as $screen_id ) {
            if ( $screen->id === $screen_id || strpos( $screen->id, $screen_id ) !== false ) {
                return true;
            }
        }
        
        // Check if we're on any WooCommerce admin page as fallback
        if ( strpos( $screen->id, 'woocommerce' ) !== false ) {
            return true;
        }
        
        return false;
    }
    
    private function is_phone_validation_disabled() {
        // Safe check if phone validation is disabled in settings
        if ( ! function_exists( 'WPWFW_Core' ) || ! WPWFW_Core() || ! WPWFW_Core()->system ) {
            return false; // Default to enabled if core not available
        }
        
        $settings = WPWFW_Core()->system->get_settings();
        return !empty($settings['disable_phone_validation']);
    }
}

// Initialize the asset manager
WPWFW_Asset_Manager::instance();
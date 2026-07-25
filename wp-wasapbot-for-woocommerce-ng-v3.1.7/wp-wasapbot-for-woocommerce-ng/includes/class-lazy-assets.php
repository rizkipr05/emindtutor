<?php

defined( 'ABSPATH' ) or die( 'I feel very small...' );

/**
 * Lazy load heavy external libraries only when needed
 */
class WPWFW_Lazy_Assets {
    
    private static $_instance = null;
    
    public static function instance() {
        if ( null === self::$_instance ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    
    public function __construct() {
        // Initialize lazy loading hooks - higher priority than Asset Manager
        add_action( 'admin_enqueue_scripts', array( $this, 'maybe_enqueue_lazy_assets' ), 15 );
        add_action( 'wp_enqueue_scripts', array( $this, 'maybe_enqueue_frontend_lazy_assets' ), 15 );
    }
    
    /**
     * Conditionally enqueue lazy loading assets for admin
     */
    public function maybe_enqueue_lazy_assets() {
        $screen = get_current_screen();
        if ( ! $screen ) {
            return; // Early exit if screen not available
        }
        
        $page = isset( $_GET['page'] ) ? sanitize_text_field( $_GET['page'] ) : '';
        $path = isset( $_GET['path'] ) ? sanitize_text_field( $_GET['path'] ) : '';
        
        // Only load lazy Select2 on settings pages that need it
        if ( $page === 'wasapbot-for-woocommerce' || ( $page === 'wasapbot' && $path === '/settings' ) ) {
            $this->lazy_select2();
        }
    }
    
    /**
     * Conditionally enqueue lazy loading assets for frontend
     */
    public function maybe_enqueue_frontend_lazy_assets() {
        // Phone validation lazy loading disabled - using direct loading for reliability
        // Assets are now loaded directly by WPWFW_Asset_Manager on checkout pages
        return;
    }
    
    /**
     * Load Select2 only when dropdown is clicked
     */
    public function lazy_select2() {
        wp_enqueue_script( 'wpwfw-lazy-select2', WPWFW_PLUGIN_URL . '/assets/js/shared/lazy-select2.js', array('jquery'), WPWFW_VERSION, true );
    }
    
    /**
     * Load intlTelInput only when phone field is focused
     */  
    public function lazy_phone_validation() {
        // Safe WooCommerce data retrieval for lazy loading
        $default_country = 'US';
        $allowed_countries = array();
        
        if ( class_exists( 'WooCommerce' ) && function_exists( 'WC' ) && WC()->countries ) {
            $default_country = WC()->countries->get_base_country();
            $allowed_countries = array_keys( WC()->countries->get_allowed_countries() );
        }
        
        wp_enqueue_script( 'wpwfw-lazy-phone', WPWFW_PLUGIN_URL . '/assets/js/shared/lazy-phone.js', array('jquery'), WPWFW_VERSION, true );
        wp_localize_script( 'wpwfw-lazy-phone', 'wpwfwLazy', array(
            'intl_css' => 'https://cdn.jsdelivr.net/npm/intl-tel-input@15.0.2/build/css/intlTelInput.css',
            'intl_js' => 'https://cdn.jsdelivr.net/npm/intl-tel-input@15.0.2/build/js/intlTelInput.js',
            'utils_js' => 'https://cdn.jsdelivr.net/npm/intl-tel-input@15.0.2/build/js/utils.js',
            'defaultCountry' => $default_country,
            'allowedCountries' => $allowed_countries
        ));
        
        // Also enqueue checkout CSS
        wp_enqueue_style( 'wpwfw-checkout', WPWFW_PLUGIN_URL . '/assets/css/frontend/checkout.css', array(), WPWFW_VERSION );
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

// Initialize lazy assets
WPWFW_Lazy_Assets::instance();
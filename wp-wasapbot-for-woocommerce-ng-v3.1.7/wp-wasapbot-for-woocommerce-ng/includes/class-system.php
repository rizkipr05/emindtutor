<?php

defined( 'ABSPATH' ) or die( 'I feel very small...' );

/**
 * Class WPWFW_System
 */

class WPWFW_System {

    private static $_instance = null;
    public static $plugin_base_icon_dir = null;
    public static $plugin_base_icon = null;
    public static $plugin_base_assets_dir = null;
    public static $plugin_base_assets_abs_dir = null;

    public function __construct() {
        
        self::$plugin_base_assets_abs_dir = WPWFW_PLUGIN_DIR . '/assets';
        self::$plugin_base_assets_dir = WPWFW_PLUGIN_URL . '/assets';
        self::$plugin_base_icon_dir = self::$plugin_base_assets_dir . "/icon";

        if (isset(WPWFW_CONFIG_OVERRIDE['WPWFW_BASE_ICON_DIR']) && !empty(WPWFW_CONFIG_OVERRIDE['WPWFW_BASE_ICON_DIR']) ) {
            self::$plugin_base_icon_dir = self::$plugin_base_icon_dir . WPWFW_CONFIG_OVERRIDE['WPWFW_BASE_ICON_DIR'];
        }
        
        self::$plugin_base_icon = self::$plugin_base_icon_dir . '/core-app.png';

        add_action( 'admin_init', array( $this, 'register_settings' ) );
        add_action( 'upgrader_process_complete', array( $this, 'update' ), 10, 2 );
        
        // Clear cache whenever wpwfw_settings is updated directly
        add_action( 'update_option_wpwfw_settings', array( $this, 'on_settings_updated' ), 10, 3 );


    }

    public static function get_instance() {

        if ( null === self::$_instance ) {
            self::$_instance = new self();
        }

        return self::$_instance;
        
    }

    public static function update( $upgrader_object, $options ) {
        // If an update has taken place and the updated type is plugins and the plugins element exists
        if( $options['action'] == 'update' && $options['type'] == 'plugin' && isset( $options['plugins'] ) ) {
            // Iterate through the plugins being updated and check if ours is there
            foreach( $options['plugins'] as $plugin ) {
                if( $plugin == WPWFW_PLUGIN_BASENAME ) {
                    // Unschedule hooks 
                    self::unschedule_hooks();          
                }
            }
        }
    }

    public static function deactivation() {

        WPWFW_Core()->logger->add( 'app', 'Deactivating...', 'INFO', 'SYSTEM' );
        self::unschedule_hooks();
        WPWFW_Core()->logger->add( 'app', 'Plugin is deactivated', 'INFO', 'SYSTEM' );
                
    }

    public static function uninstallation() {

        $settings = get_option( 'wpwfw_settings', array() );

        $settings = wp_parse_args( $settings, self::get_default_settings() );
        
        if ( isset( $settings['delete_plugin_data'] ) && $settings['delete_plugin_data'] == '1' ) {

            delete_option( 'wpwfw_settings' );
            delete_option( 'wpwfw_plugin_data' );

            global $wpdb;

            /**
             * Get plugin list, check if supported and delete related data
             */
            $installed_plugins = get_plugins();
            $active_plugins = get_option( 'active_plugins', [] );
            $supported_plugins = array_intersect( array( 'woo-cart-abandonment-recovery/woo-cart-abandonment-recovery.php' ), $installed_plugins );

            if ( count( $installed_plugins ) === 0 ) return;

            foreach( $supported_plugins as $plugin ) {

                /**
                 * woo-cart-abandonment-recovery/woo-cart-abandonment-recovery.php
                 */
                if ( $plugin == "woo-cart-abandonment-recovery/woo-cart-abandonment-recovery.php" ) {
                    $cart_abandonment_table = $wpdb->prefix . "cartflows_ca_cart_abandonment";
                    $columns = $wpdb->get_results( "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = '" . $cart_abandonment_table . "' AND column_name = 'wpwfw_data'" );
                    if ( !empty( $columns ) ) {
                        $sql = "ALTER TABLE $cart_abandonment_table DROP COLUMN wpwfw_data";
                        $wpdb->query( $sql );
                    }
                }

            }
            
        }

    }

    /**
     * Remove events
     */
    private static function unschedule_hooks() {

        foreach ( WPWFW_Core()->services->aftersale_events as $event => $name ) {
            if ( wp_clear_scheduled_hook( 'cron_hook_' . $event ) !== false ) WPWFW_Core()->logger->add( 'app', "Succesfully unscheduled cron_hook_$event", 'INFO', 'SYSTEM' ); ;
        }
        $data = wp_parse_args( get_option( 'wpwfw_plugin_data', array() ), array(
            'running_cart_abandon_hook' => '',
            'analytics' => array(
                'sent_messages_stat'    => 0,
                'order_follow_up_stat'  => 0,
                'cart_follow_up_stat'   => 0,
            ),
        ) );
        if ( count( $data ) ) {
            if ( isset( $data['running_cart_abandon_hook'] ) && !empty( $data['running_cart_abandon_hook'] ) ) {
                if ( is_array( WPWFW_Core()->services->cart_abandon_events[$data['running_cart_abandon_hook']] ) ) {
                    foreach (WPWFW_Core()->services->cart_abandon_events[$data['running_cart_abandon_hook']] as $event => $name ) if( wp_clear_scheduled_hook( 'cron_hook_' . $event ) !== false ) WPWFW_Core()->logger->add( 'app', "Succesfully unscheduled cron_hook_$event", 'INFO', 'SYSTEM' ); 
                }
            }
        }

    }

    /**
     * Register app settings
     */
    public function register_settings() {
        
        register_setting( 'wpwfw_settings_group', 'wpwfw_settings', array( $this, 'sanitize_settings_callback' ) );
        register_setting( 'wpwfw_plugin_data_group', 'wpwfw_plugin_data', array( $this, 'sanitize_plugin_data_callback' ) );

    }

    /**
     * Perform a sanitization of settings values such as string translation
     * Also clears cache when settings are saved via WordPress admin
     */
    public function sanitize_settings_callback( $settings ) {
        
        // Clear settings cache when saved via WordPress Settings API
        $this->clear_settings_cache();
        
        WPWFW_Core()->logger->add( 'app', 'Settings saved via WordPress Settings API - cache cleared', 'INFO', 'CACHE' );
        
        return $settings;

    }

    /**
     * Called whenever wpwfw_settings option is updated
     * Ensures cache is always cleared when settings change
     */
    public function on_settings_updated( $old_value, $value, $option ) {
        
        $this->clear_settings_cache();
        
        WPWFW_Core()->logger->add( 'app', 'Settings updated via update_option - cache cleared', 'INFO', 'CACHE' );
        
    }

    public function sanitize_plugin_data_callback( $data ) {

        return $data;

    }

    /**
     * Get default settings
     */
    public static function get_default_settings() {
        
        $notification_template = self::get_notification_template();

        $defaults = array_replace( array(
            'subdomain' => '',
            'access_token' => '',
            'instance_id' => '',
            'custom_shortcodes' => '',
            'debug' => '0',
            'debug_email_field' => '',
            'sales_notification' => '0',
            'sales_team_phone' => '',
            'products' => '',
            'cart_abandon_plugin' => '',
            'delete_plugin_data' => '0',
            'aftersale_deduplication' => '0',
            'aftersale_deduplication_days' => '7',
        ), $notification_template );

        return $defaults;

    }

    /**
     * Get default template
     */
    public static function get_notification_template( $template_name = '' ) {
        
        $custom_message_template = [
            'order_message'		=> __( 'Hi,\n\nOrder (No. %id%) telah ditempah oleh *%billing_first_name%* di %shop_name%.\n\nSila ambil maklum!\n\n----\n\nAdmin %shop_name%', WPWFW_PLUGIN_DOMAIN_LANGUAGE ),
            'pending_message'		=> __( 'Hi *%billing_first_name%*!\n\nTerima kasih kerana membeli belah dengan kami!\n\nOrder (No. %id%) kini berstatus: %status%', WPWFW_PLUGIN_DOMAIN_LANGUAGE ),
            'failed_message'		=> __( 'Hi *%billing_first_name%*!\n\nTerima kasih kerana membeli belah dengan kami!\n\nOrder (No. %id%) kini berstatus: %status%', WPWFW_PLUGIN_DOMAIN_LANGUAGE ),
            'received_message'		=> __( 'Hi *%billing_first_name%*!\n\n Order anda (No. %id%) telah diterima di %shop_name%.\n\nTerima kasih kerana membeli belah dengan kami!\n\n----\n\nAdmin %shop_name%', WPWFW_PLUGIN_DOMAIN_LANGUAGE ),
            'processing_message'	=> __( 'Hi *%billing_first_name%*!\n\nOrder anda telah diterima. Pihak kami akan mula process order anda secepat mungkin semasa waktu bekerja.\n\nIsnin hingga Sabtu ( 9 Pagi - 6 Petang )\n\nDetail order anda:\n\nOrder ID : *%id%*\n\nNama : *%billing_first_name% %billing_last_name%*\n\nNo Phone : *%billing_phone%*\n\nTotal Price : *%order_total%*\n\nItem : *%order_product%*\n\nCara Pembayaran : *%payment_method_title%*\n\nSila simpan nombor ini untuk memudahkan anda menerima status order anda!\n\n----\n\nTerima Kasih!\n\nAdmin %shop_name%', WPWFW_PLUGIN_DOMAIN_LANGUAGE ),
            'completed_message'	=> __( 'Hi *%billing_first_name%*!\n\nTerima Kasih kami ucapkan dari %shop_name%.\n\nJika ada sebarang masalah, boleh terus PM kami di number whatsapp ini ya! Kalau best, share dengan kami, Kalau xbest, share dengan kami untuk kami tambah baik servis dan produk kami!\n\n----\n\nTerima Kasih sekali lagi!\n\nAdmin %shop_name%', WPWFW_PLUGIN_DOMAIN_LANGUAGE ),
            'refunded_message'		=> __( 'Hi *%billing_first_name%*!\n\nTerima kasih kerana membeli belah dengan kami!\n\nOrder (No. %id%) kini berstatus: %status%', WPWFW_PLUGIN_DOMAIN_LANGUAGE ),
            'cancelled_message'		=> __( 'Hi *%billing_first_name%*!\n\nTerima kasih kerana membeli belah dengan kami!\n\nOrder (No. %id%) kini berstatus: %status%', WPWFW_PLUGIN_DOMAIN_LANGUAGE ),
            'note_message'			=> __( 'Hi *%billing_first_name%*!\n\nOrder anda (No. %id%) mempunyai remark yang baharu: %note%\n\n----\n\nAdmin %shop_name%', WPWFW_PLUGIN_DOMAIN_LANGUAGE ),
        ];

        if ( empty( $template_name ) ) return $custom_message_template;

        if ( !array_key_exists( $template_name, $custom_message_template ) ) return '';

        return $custom_message_template[$template_name];

    }

    /**
     * Get global settings (with caching)
     * Cache expires in 1 hour or when settings are saved
     */
    public function get_settings() {
        
        $cache_key = 'wpwfw_settings_cache';
        $cache_group = 'wpwfw';
        
        // Try to get from cache first
        $settings = wp_cache_get( $cache_key, $cache_group );
        
        if ( false === $settings ) {
            // Cache miss - load from database
            $settings = get_option( 'wpwfw_settings', array() );
            $settings = wp_parse_args( $settings, self::get_default_settings() );
            
            // Cache for 1 hour (3600 seconds)
            wp_cache_set( $cache_key, $settings, $cache_group, 3600 );
            
            // Log cache miss for monitoring
            WPWFW_Core()->logger->add( 'app', 'Settings loaded from database (cache miss)', 'DEBUG', 'CACHE' );
        } else {
            // Log cache hit for monitoring
            WPWFW_Core()->logger->add( 'app', 'Settings loaded from cache (cache hit)', 'DEBUG', 'CACHE' );
        }

        return $settings;

    }

    /**
     * Clear settings cache
     * Called when settings are saved to ensure fresh data
     */
    public function clear_settings_cache() {
        $cache_key = 'wpwfw_settings_cache';
        $cache_group = 'wpwfw';
        
        $deleted = wp_cache_delete( $cache_key, $cache_group );
        
        WPWFW_Core()->logger->add( 'app', 'Settings cache cleared' . ($deleted ? ' successfully' : ' (was not cached)'), 'INFO', 'CACHE' );
        
        return $deleted;
    }

    /**
     * Save settings and clear cache
     * Ensures cache is invalidated when settings are updated
     */
    public function save_settings( $settings ) {
        // Save to database
        $saved = update_option( 'wpwfw_settings', $settings );
        
        // Clear cache to force reload from database
        $this->clear_settings_cache();
        
        WPWFW_Core()->logger->add( 'app', 'Settings saved and cache invalidated', 'INFO', 'CACHE' );
        
        return $saved;
    }

    /**
     * Get plugin data
     */
    public function get_plugin_data() {

        $data = get_option( 'wpwfw_plugin_data', array() );

        $data = wp_parse_args( $data, array(
            'subdomains' => [],
            'running_cart_abandon_hook' => '',
            'analytics' => array(
                'sent_messages_stat'    => 0,
                'order_follow_up_stat'  => 0,
                'cart_follow_up_stat'   => 0,
            ),
            'last_updated' => time()
        ) );

        return $data;
        
    }

    /**
     * Get analytics data
     */
    public function get_analytics( $filter = '' ) {

        $data = $this->get_plugin_data();

        if ( !empty( $filter ) ) return ( in_array( $filter, $data['analytics'] ) ) ? $data['analytics'][$filter] : null ;
        return $data;

    }

    /**
     * Get assets
     */
    public function get_plugin_assets( $path, $type='img' ) {
 
        $file_path = self::$plugin_base_assets_abs_dir . $path;
        $file = self::$plugin_base_assets_dir . $path;
        if ( file_exists( $file_path ) && is_file( $file_path ) ) {
            return $file;   
        }

        return ($type === 'img') ? self::$plugin_base_assets_dir . '/img/bg-404.png' : false;
    }

    /**
     * Get plugin icon
     */
    public static function get_plugin_icon( $template, $custom_size = '' ) {

        if ( ! in_array( $template, array(
            'app', 'sidebar', 'top-nav', 'top-nav-simple', 'row-meta', 'banner'
        ) ) ) {
          return self::$plugin_base_icon;  
        }

        if ( ! in_array( $custom_size, array( 
            '20x20'
        ) ) ) {
            $custom_size = '';
        } else {
            $custom_size = '_' . $custom_size;
        }

        $icon = self::$plugin_base_icon_dir . "/core-$template$custom_size.png";

        return $icon;

    }


}

if ( class_exists( 'WPWFW_Core' ) ) {
    WPWFW_Core::register( 'system', 'WPWFW_System' );
}

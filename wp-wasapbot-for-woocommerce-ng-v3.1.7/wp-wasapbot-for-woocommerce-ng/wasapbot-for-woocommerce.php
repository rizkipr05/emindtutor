<?php

/*
Plugin Name: WasapBot for Woocommerce (Next Gen)
Plugin URI:  https://github.com/kyraex/wasapbot-for-woocommerce
Description: Easily send order notification to your woocommerce customers.
Author:       Ahmad Syauqi
Author URI:   https://ahmadsyauqi.com
Version:      3.1.7
License:      MIT
License URI:  license.txt
Text Domain:  wp-wasapbot-for-woocommerce
Domain Path:  /languages
Requires PHP: 7.4
Requires at least: 2.1
*/

defined( 'ABSPATH' ) or die( 'I feel very small...' );

/**
 * {PLUGIN_SHORTNAME}_Begin
 */
class WasapBot_Begin {

    private $file = null;
    private $plugin = null;
    private static $_instance = null;

    public function __construct() {
        $this->file = __FILE__;
        $this->plugin = (is_admin()) ? get_plugin_data( $this->file ) : [];

        // Register activation, deactivation hook
        register_activation_hook( $this->file , [ $this, 'version_compatibility_check' ]);
        register_deactivation_hook( $this->file, [ 'WPWFW_System', 'deactivation' ] );
        register_uninstall_hook( $this->file, [ 'WPWFW_System', 'uninstallation' ] );

        $incompatible_plugins = $this->check_incompatible_plugins();

        if ( !$incompatible_plugins ) {
            $this->load_core();
            return true;
        }
    }

    public static function get_instance() {
        if ( null === self::$_instance ) {
            self::$_instance = new self;
        }

        return self::$_instance;
    }

    public function check_incompatible_plugins() {
        $incompatible_plugins = array(
            'wp-wasapbot-for-woocommerce-ng/wasapbot-for-woocommerce.php',
            'wp-ninjawasap-for-woocommerce-ng/ninjawasap-for-woocommerce.php',
            'wp-poswasap-for-woocommerce-ng/poswasap-for-woocommerce.php'
            // Add more plugin slugs to check for compatibility
        );

        $plugin_basename = plugin_basename( $this->file );
        $current_plugin = dirname( $plugin_basename ) . '/' . basename( $plugin_basename );

        $slug_key = array_search( $current_plugin, $incompatible_plugins );
        if ( $slug_key !== false ) {
            unset( $incompatible_plugins[ $slug_key ] ); // Remove current plugin from the list
        }

        foreach ( $incompatible_plugins as $plugin ) {
            if ( is_admin() && is_plugin_active( $plugin ) ) {
                return $plugin;
            }
        }
    
        return false;
    }

    public function version_compatibility_check() {
        $plugin_slug = $this->check_incompatible_plugins();
        if ( $plugin_slug ) {

            $plugin_data = $this->get_plugin_data( $plugin_slug );
            $plugin_name = ( isset( $plugin_data['Name'] ) && !empty( $plugin_data['Name'] ) ) ? $plugin_data['Name'] : $plugin_slug; 
            deactivate_plugins( plugin_basename( $this->file ) );

            $current_plugin = ( isset( $this->plugin['Name'] ) && !empty( $this->plugin['Name'] ) ) ? $this->plugin['Name'] : plugin_basename( $this->file );
            $error_message = '<div><img src="' . plugin_dir_url( $this->file ) . 'assets/img/bg-404.png" style="width: 50%;margin-left: 22%;"></div><div style="margin-top:20px;">Plugin `<strong>' . $current_plugin . '</strong>` cannot be activated due to incompatibility with the following plugin: `<strong>' . $plugin_name . '</strong>`.</div><div style="margin-top:10px;"><a href="' . admin_url('plugins.php') .'" class="button button-primary">Back</a></div>';
            wp_die($error_message);
            exit;
        }
    }

    public function get_plugin_data( $plugin_slug ) {
        $plugin = dirname( plugin_dir_path( $this->file ) ) . "/$plugin_slug";
        return get_plugin_data( $plugin );
    }

    public function load_core() {
        require_once 'base.php';
    }

}

if ( ! function_exists( 'WasapBot_Begin' ) ) {

	/**
	 * Global Common function to load all the classes
	 * @return WasapBot_Begin
	 */
	function WasapBot_Begin() {  //@codingStandardsIgnoreLine
		return WasapBot_Begin::get_instance();
	}
}

if( is_admin() && !function_exists('get_plugin_data') ){
    require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
}

WasapBot_Begin();
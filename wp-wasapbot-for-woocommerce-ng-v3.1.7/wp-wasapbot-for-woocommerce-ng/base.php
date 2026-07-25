<?php

defined( 'ABSPATH' ) or die( 'I feel very small...' );

final class WPWFW_Core {
    /**
     * @var WPWFW_Core
     */
    private static $_instance = null;
    private static $_classes = array(
        'active'    =>  array(),
        'inactive'  =>  array()
    );

    /**
     * @var WPWFW_System
     */
    public $system;

    /**
     * @var WPWFW_Admin
     */
    public $admin;

    /**
     * @var WPWFW_UI
     */
    public $ui;

    /**
     * @var WPWFW_Service
     */
    public $services;

    /**
     * @var WPWFW_Integrations
     */
    public $integration;

    /**
     * @var WPWFW_Public
     */
    public $public;

    /**
     * @var WPWFW_Logger
     */
    public $logger;

    private function __construct() {

        /**
		 * Load important variables and constants
		 */
        $this->define_plugin_properties();

        /**
         * Load core classes
         */
        $this->load_wasapbot_core_classes();

        /**
         * Load common file
         */
        $this->load_commons();

        /**
         * Load integration classes
         */
        $this->load_integrations_support();
        
    }

    /**
	 * Defining constants
	 */
	public function define_plugin_properties() {

        define( 'WPWFW_CONFIG_OVERRIDE', include_once 'config.php' );

		define( 'WPWFW_VERSION', '3.1.7' );
		define( 'WPWFW_MIN_WC_VERSION', '3.0' );
        define( 'WPWFW_PLUGIN_NAME', ( isset(WPWFW_CONFIG_OVERRIDE['WPWFW_PLUGIN_NAME']) && !empty(WPWFW_CONFIG_OVERRIDE['WPWFW_PLUGIN_NAME']) ) ? WPWFW_CONFIG_OVERRIDE['WPWFW_PLUGIN_NAME'] : 'Wasapbot for WooCommerce' );
        define( 'WPWFW_PLUGIN_SIMPLE_NAME', ( isset(WPWFW_CONFIG_OVERRIDE['WPWFW_PLUGIN_SIMPLE_NAME']) && !empty(WPWFW_CONFIG_OVERRIDE['WPWFW_PLUGIN_SIMPLE_NAME']) ) ? WPWFW_CONFIG_OVERRIDE['WPWFW_PLUGIN_SIMPLE_NAME'] : 'Wasapbot');
        define( 'WPWFW_PLUGIN_CORE_NAME', ( isset(WPWFW_CONFIG_OVERRIDE['WPWFW_PLUGIN_CORE_NAME']) && !empty(WPWFW_CONFIG_OVERRIDE['WPWFW_PLUGIN_CORE_NAME']) ) ? WPWFW_CONFIG_OVERRIDE['WPWFW_PLUGIN_CORE_NAME'] : 'wasapbot' );
        define( 'WPWFW_PLUGIN_DOMAIN_LANGUAGE', ( isset(WPWFW_CONFIG_OVERRIDE['WPWFW_PLUGIN_DOMAIN_LANGUAGE']) && !empty(WPWFW_CONFIG_OVERRIDE['WPWFW_PLUGIN_DOMAIN_LANGUAGE']) ) ? WPWFW_CONFIG_OVERRIDE['WPWFW_PLUGIN_DOMAIN_LANGUAGE'] : 'wp-wasapbot-for-woocommerce');
        define( 'WPWFW_PLUGIN_WEBSITE', ( isset(WPWFW_CONFIG_OVERRIDE['WPWFW_PLUGIN_WEBSITE']) && !empty(WPWFW_CONFIG_OVERRIDE['WPWFW_PLUGIN_WEBSITE']) ) ? WPWFW_CONFIG_OVERRIDE['WPWFW_PLUGIN_WEBSITE'] : 'https://dash.wasapbot.my');
        define( 'WPWFW_PLUGIN_GUIDE', ( isset(WPWFW_CONFIG_OVERRIDE['WPWFW_PLUGIN_GUIDE']) && !empty(WPWFW_CONFIG_OVERRIDE['WPWFW_PLUGIN_GUIDE']) ) ? WPWFW_CONFIG_OVERRIDE['WPWFW_PLUGIN_GUIDE'] : 'https://guide.wasapbot.my/en');
        define( 'WPWFW_PLUGIN_DASHBOARD', ( isset(WPWFW_CONFIG_OVERRIDE['WPWFW_PLUGIN_DASHBOARD']) && !empty(WPWFW_CONFIG_OVERRIDE['WPWFW_PLUGIN_DASHBOARD']) ) ? WPWFW_CONFIG_OVERRIDE['WPWFW_PLUGIN_DASHBOARD'] : 'https://dash.wasapbot.my');
        define( 'WPWFW_PLUGIN_SOCIAL', ( isset(WPWFW_CONFIG_OVERRIDE['WPWFW_PLUGIN_SOCIAL']) && !empty(WPWFW_CONFIG_OVERRIDE['WPWFW_PLUGIN_SOCIAL']) ) ? WPWFW_CONFIG_OVERRIDE['WPWFW_PLUGIN_SOCIAL'] : array(
            'telegram' => '',
            'facebook' => '',
            'helpdesk' => 'http://go.wasapbot.my/help',
        ));
		define( 'WPWFW_PLUGIN_FILE', __FILE__ );
		define( 'WPWFW_PLUGIN_DIR', __DIR__ );

		$plugin_url = untrailingslashit( plugin_dir_url( WPWFW_PLUGIN_FILE ) );
		if ( is_ssl() ) {
			$plugin_url = preg_replace( "/^http:/i", "https:", $plugin_url );
		}

		define( 'WPWFW_PLUGIN_URL', $plugin_url );
        define( 'WPWFW_PLUGIN_REMOTE_URL', 'https://wasapbot-woo.b-cdn.net' );
		define( 'WPWFW_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
		define( 'WPWFW_DB_VERSION', '1.0' );
        define( 'WPWFW_APP_MODE', 'STANDARD' ); // STANDARD || EXTENDED
        define( 'WPWFW_IS_DEVELOPMENT', false ); // true || false

	}

    /**
     * Load core classes for Wasapbot
     */
    public function load_wasapbot_core_classes() {

        require WPWFW_PLUGIN_DIR . '/includes/class-logger.php';
        require WPWFW_PLUGIN_DIR . '/includes/class-system.php';
        require WPWFW_PLUGIN_DIR . '/includes/class-db.php';

    }

    /**
     * Initialize dependency classes
     */
    public function load_integrations_support() {

        require WPWFW_PLUGIN_DIR . '/includes/class-integrations.php';

        add_action( 'admin_notices', array( $this, 'woocommerce_prerequisite_notice'), 1 );
        
    }

    public function load_commons() {

        require WPWFW_PLUGIN_DIR . '/includes/class-common.php';

        WPWFW_Common::init();
        
        /**
         * Load common hooks()
         */
        $this->load_hooks();
    }

    public function load_hooks() {

        /** Initialize localization */
        add_action( 'init', array( $this, 'localization' ) );
        
        add_action( 'plugins_loaded', array( $this, 'load_classes' ), 1 );

        /** Redirecting Plugin to settings page after activation */
        add_action( 'activated_plugin', array( $this, 'redirect_on_activation') );

    }

    public static function get_instance() {
        if ( null === self::$_instance ) {
            self::$_instance = new self;
        }

        return self::$_instance;
    }

    /**
     * Perform class registration
     */
    public static function register( $short_name, $class ) {
        /**
         * Skip if marked as inactive
         */
        if ( in_array( $class, self::$_classes['inactive'], true ) ) {
            return;
        }

        self::$_classes['active'][ $short_name ] = $class;

    }

    public function load_classes() {
        /**
         * Load public classes
         */
        $this->load_public();

        /**
         * Load admin classes
         */
        if ( is_admin() ) {
            $this->load_admin();
        }

        /**
         * Load core classes
         */
        
        require WPWFW_PLUGIN_DIR . '/includes/class-services.php';
        require WPWFW_PLUGIN_DIR . '/includes/class-ui.php';
        require WPWFW_PLUGIN_DIR . '/includes/class-dashboard.php';
        require WPWFW_PLUGIN_DIR . '/includes/class-asset-manager.php';
        require WPWFW_PLUGIN_DIR . '/includes/class-lazy-assets.php';

        /**
         * Initialize all classes or functions
         */
        $this->register_classes();
        
    }

    public function load_public() {

    }

    public function load_admin() {

        require WPWFW_PLUGIN_DIR . '/includes/class-admin.php';

    }

    public function register_classes() {
        
        $load_classes = self::get_registered_class();

        if ( is_array( $load_classes ) && count( $load_classes ) > 0 ) {
            foreach ( $load_classes as $key => $class ) {
                if ( ! method_exists( $class, 'get_instance' ) ) {
                    continue;
                } 
                $this->{$key} = $class::get_instance();
            }
        } 

    }

    public static function get_registered_class() {
        
        return self::$_classes['active'];

    }

    /**
     * Redirection on plugin activation
     */
    public function redirect_on_activation( $plugin ) {

        if ( ! defined( 'WP_CLI' ) ) {
            if ( plugin_basename( __FILE__ ) === $plugin ) {
                wp_safe_redirect( add_query_arg( array( 'page' => WPWFW_PLUGIN_CORE_NAME, ), admin_url( 'admin.php' ) ) );
                exit;
            }
        }

    }

    public function localization() {

        load_plugin_textdomain( WPWFW_PLUGIN_DOMAIN_LANGUAGE, false, plugin_basename( dirname( __FILE__ ) ) . '/languages' );

    }

    public function woocommerce_prerequisite_notice() {

        if ( WPWFW_Core()->integration->is_woocommerce_active() ) {
            return;
        }

        ?>
        <div class="notice notice-warning" style="display: block!important;">
            <p>
				<?php
				echo _e( '<strong>Warning! WooCommerce is not activated nor installed.</strong> We strongly recommend to install the latest version of WooCommerce in order to work with ' . WPWFW_PLUGIN_NAME, WPWFW_PLUGIN_DOMAIN_LANGUAGE );
				?>
            </p>
        </div>
		<?php

    }

    public function is_wc_hpos_compatible() {
        if ( class_exists(\Automattic\WooCommerce\Utilities\OrderUtil::class) ) {

            return Automattic\WooCommerce\Utilities\OrderUtil::custom_orders_table_usage_is_enabled();
    
        } else {
    
            return false;
    
        }
    }

    /**
	 * to avoid unserialize of the current class
	 */
	public function __wakeup() {

		throw new ErrorException( 'WPWFW_Core can`t converted to string' );

	}

	/**
	 * to avoid serialize of the current class
	 */
	public function __sleep() {

		throw new ErrorException( 'WPWFW_Core can`t converted to serialized' );

	}

	/**
	 * To avoid cloning of current class
	 */
	protected function __clone() {
        throw new ErrorException( 'WPWFW_Core can`t be cloned' );
	}
    
}

if ( ! function_exists( 'WPWFW_Core' ) ) {

	/**
	 * Global Common function to load all the classes
	 * @return WPWFW_Core
	 */
	function WPWFW_Core() {  //@codingStandardsIgnoreLine
		return WPWFW_Core::get_instance();
	}
}

WPWFW_Core();
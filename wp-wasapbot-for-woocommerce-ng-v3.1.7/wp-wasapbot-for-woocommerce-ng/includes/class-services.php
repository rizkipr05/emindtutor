<?php

defined( 'ABSPATH' ) or die( 'I feel very small...' );

use Automattic\WooCommerce\Internal\DataStores\Orders\CustomOrdersTableController;
use Automattic\WooCommerce\Utilities\OrderUtil;

/**
 * Class WPWFW_Service
 */
class WPWFW_Service {

    private static $_instance = null;
    private $admin_api_path = null;
    private $admin_key = null;
    public $services_data = null;
    public $default_service_data = array();
    public $bulk_success;
    public $bulk_failed;
    public $wc_products;
    public $default_status;
    public $custom_status = array();
    public $default_template_name;
    public $default_selectable_status;
    public $aftersale_events;
    public $cart_abandon_events;
    public $events_delay_data;
    public $order_metadata;
    public $order_shortcodes;
    public $shortcodes_group;

    public $plugin_data;

    public function __construct() {

        $this->admin_api_path = "https://api.shazrinsaliman.com";
        $this->admin_key = "e5ef4ff5-843a-4288-a77d-f92734ea245f";
        

        $this->plugin_data = WPWFW_Core()->system->get_plugin_data();

        $this->default_service_data['dash'] = [
            'core_url' => 'dash.wasapbot.my',
        ];

        $this->services_data = $this->plugin_data['subdomains'] ?? [];

        $this->wc_products = $this->get_wc_products();

        $this->default_status = [
            'pending',
            'failed',
            'on-hold',
            'processing',
            'completed',
            'refunded',
            'cancelled',
        ];

        $this->default_template_name = [
            'pending_message',
            'failed_message',
            'received_message',
            'processing_message',
            'completed_message',
            'refunded_message',
            'cancelled_message',
            'note_message',
        ];

        $this->default_selectable_status = [
            'all'					=> __( 'All Messages', WPWFW_PLUGIN_DOMAIN_LANGUAGE ),
            'pending_message'		=> __( 'Order Pending Custom Message', WPWFW_PLUGIN_DOMAIN_LANGUAGE ),
            'failed_message'		=> __( 'Order Failed Custom Message', WPWFW_PLUGIN_DOMAIN_LANGUAGE ),
            'received_message'		=> __( 'Order On-hold Custom Message', WPWFW_PLUGIN_DOMAIN_LANGUAGE ),
            'processing_message'	=> __( 'Order Processing Custom Message', WPWFW_PLUGIN_DOMAIN_LANGUAGE ),
            'completed_message'	=> __( 'Order Completed Custom Message', WPWFW_PLUGIN_DOMAIN_LANGUAGE ),
            'refunded_message'		=> __( 'Order Refunded Custom Message', WPWFW_PLUGIN_DOMAIN_LANGUAGE ),
            'cancelled_message'		=> __( 'Order Cancelled Custom Message', WPWFW_PLUGIN_DOMAIN_LANGUAGE ),
            'note_message'			=> __( 'Notes Custom Message', WPWFW_PLUGIN_DOMAIN_LANGUAGE ),
        ];

        $this->aftersale_events = [
            'followup_aftersales_1' => __( 'Completed Follow Up #1', WPWFW_PLUGIN_DOMAIN_LANGUAGE ),
            'followup_aftersales_2'	=> __( 'Completed Follow Up #2', WPWFW_PLUGIN_DOMAIN_LANGUAGE ),
            'followup_aftersales_3'	=> __( 'Completed Follow Up #3', WPWFW_PLUGIN_DOMAIN_LANGUAGE ),
        ];

        $this->cart_abandon_events = [
            'woo-cart-abandonment-recovery' => array(
                'cart_abandon_followup_1' => __( 'Abandonment Follow Up #1', WPWFW_PLUGIN_DOMAIN_LANGUAGE ),
                'cart_abandon_followup_2' => __( 'Abandonment Follow Up #2', WPWFW_PLUGIN_DOMAIN_LANGUAGE ),
                'cart_abandon_followup_3' => __( 'Abandonment Follow Up #3', WPWFW_PLUGIN_DOMAIN_LANGUAGE ),
            ),
        ];

        $this->events_delay_data = [
            'min'   => 60000, // 1 * 60 * 1000 (Min)
            'hour'  => 3600000, // 1 * 60 * 60 * 1000 (Hour)
            'day'   => 86400000, // 1 * 60 * 60 * 24 * 1000 (Day)
        ];

        $this->order_metadata = [
            "id", 
            "status", 
            "prices_include_tax", 
            "tax_display_cart", 
            "display_totals_ex_tax", 
            "display_cart_ex_tax", 
            "order_date", 
            "modified_date", 
            "customer_message", 
            "customer_note", 
            "post_status", 
            "shop_name",
            "shop_url",
            "note", 
            "order_product",
            "order_product_name",
            "shipping_method", 
        ];

        $this->order_shortcodes = [
            "customer_id",
            "order_key", 
            "billing_first_name", 
            "billing_last_name", 
            "billing_company", 
            "billing_address_1", 
            "billing_address_2", 
            "billing_city", 
            "billing_postcode", 
            "billing_country", 
            "billing_state", 
            "billing_email", 
            "billing_phone", 
            "shipping_first_name", 
            "shipping_last_name", 
            "shipping_company", 
            "shipping_address_1", 
            "shipping_address_2", 
            "shipping_city", 
            "shipping_postcode", 
            "shipping_country", 
            "shipping_state", 
            "shipping_method_title", 
            "payment_method", 
            "payment_method_title", 
            "order_discount", 
            "cart_discount", 
            "order_tax", 
            "order_shipping", 
            "order_shipping_tax", 
            "order_total"
        ];

        $this->shortcodes_group = [
            'order_details' => [
                'id','status','order_key','order_date','modified_date', 'customer_id', 'customer_message','customer_note','post_status','shop_name', 'shop_url', 'note','shipping_method','shipping_method_title'
            ],
            'cart_items' => [
                'order_product', 'order_product_name'
            ],
            'billing_address' => [
                'billing_first_name','billing_last_name','billing_company','billing_address_1','billing_address_2','billing_city','billing_postcode','billing_country','billing_state','billing_email','billing_phone'
            ],
            'shipping_address' => [
                'shipping_first_name','shipping_last_name','shipping_company','shipping_address_1','shipping_address_2','shipping_city','shipping_postcode','shipping_country','shipping_state'
            ],
            'payment_details' => [
                'payment_method','payment_method_title','order_total','order_discount','cart_discount','order_tax','order_shipping','order_shipping_tax','prices_include_tax','tax_display_cart','display_totals_ex_tax','display_cart_ex_tax'
            ]
        ];

        /**
         * General WooCommerce
         */
        add_action( 'wp_loaded', array( $this, 'register_custom_order_status'), 9 );
        add_action( 'admin_notices', array( $this, 'admin_notice' ), 90 );
        
        // Additional hook for HPOS pages
        add_action( 'woocommerce_admin_order_data_after_order_details', array( $this, 'admin_notice' ), 90 );

        if ( class_exists( '\Automattic\WooCommerce\Utilities\OrderUtil' ) && OrderUtil::custom_orders_table_usage_is_enabled() ) {
            add_filter( 'manage_woocommerce_page_wc-orders_columns', [$this, 'add_custom_columns'] );
            add_filter( 'manage_woocommerce_page_wc-orders_custom_column', [$this, 'manage_custom_columns'], 10, 2 );
            add_filter( 'bulk_actions-woocommerce_page_wc-orders', [$this, 'add_custom_bulk_actions'] );
            add_filter( 'handle_bulk_actions-woocommerce_page_wc-orders', [$this, 'handle_custom_bulk_actions'], 10, 3 );
        } else {
            add_filter( 'manage_edit-shop_order_columns', array($this,'add_custom_columns') );
            add_action( 'manage_shop_order_posts_custom_column', array($this, 'manage_custom_columns' ), 10, 2 );
            add_filter( 'bulk_actions-edit-shop_order', [$this, 'add_custom_bulk_actions'] );
            add_filter( 'handle_bulk_actions-edit-shop_order', [$this, 'handle_custom_bulk_actions'], 10, 3 );
        }

        add_action( 'add_meta_boxes', [$this, 'register_wasapbot_order_dashboard'], 10 );

        /**
         * WooCommerce Events - Modified for Action Scheduler Background Processing
         */
        // Replace direct processing with Action Scheduler for better performance
        add_action( 'woocommerce_thankyou', array( $this, 'wc_schedule_notifications'), 10 );
        add_action( 'woocommerce_order_status_pending', array( $this, 'wc_schedule_notifications' ), 10 );
		add_action( 'woocommerce_order_status_failed', array( $this, 'wc_schedule_notifications' ), 10 );
		add_action( 'woocommerce_order_status_on-hold', array( $this, 'wc_schedule_notifications' ), 10 );
		add_action( 'woocommerce_order_status_completed', array( $this, 'wc_schedule_notifications' ), 10 );
		add_action( 'woocommerce_order_status_processing', array( $this, 'wc_schedule_notifications' ), 10 );
		add_action( 'woocommerce_order_status_refunded', array( $this, 'wc_schedule_notifications' ), 10 );
		add_action( 'woocommerce_order_status_cancelled', array( $this, 'wc_schedule_notifications' ), 10 );
        
        // Action Scheduler hooks for background processing
        add_action( 'wpwfw_process_customer_notification', array( $this, 'wc_process_order_status' ), 10, 1 );
        add_action( 'wpwfw_process_sales_notification', array( $this, 'wc_process_order_checkout' ), 10, 1 );
        add_action( 'woocommerce_new_customer_note', array( $this, 'wc_process_notes' ), 10 );
        // Deprecated: Frontend asset loading now handled by WPWFW_Asset_Manager
        // add_action( 'woocommerce_before_checkout_form', array( $this, 'wc_checkout_phone_validation' ), 10);
        add_action( 'woocommerce_checkout_create_order', array( $this, 'wc_modify_billing_phone' ), 10 );

        // add_action( 'transition_post_status', array( $this, 'wc_trigger_custom_status_hooks' ), 12, 3 );

        
        add_filter( 'cron_schedules', array( $this, 'cron_schedule' ) );
        /**
         * Follow Up & Aftersales Events - Modified for Action Scheduler
         */
        foreach ( $this->aftersale_events as $event => $name ) {
            // Register Action Scheduler hooks for each follow-up event
            add_action( 'wpwfw_process_' . $event, array( $this, 'wc_process_follow_up_single' ), 10, 2 );
            
            // Keep legacy cron for backward compatibility but reduce frequency
            $this->{'wc_' . $event} = function () use ( $event ) {
                $this->wc_process_follow_up_legacy( $event, 'completed' );
            };
            add_action( 'cron_hook_' . $event, array( $this, 'wc_' . $event ) );
            if ( ! wp_next_scheduled( 'cron_hook_' . $event ) ) {
                // Reduce frequency to every 30 minutes to avoid conflicts with Action Scheduler
                wp_schedule_event( time(), 'every_thirty_minutes', 'cron_hook_' . $event );
            }
        }
        
        /**
         * Abandoned Events
         */
        $this->sync_integrations();

        /**
         * Register ajax action
         */
        add_action( 'wp_ajax_wa_send_test_message', array( $this, 'ajax_wa_send_test_message' ) );
        add_action( 'wp_ajax_wa_check_instance', array( $this, 'wa_check_instance' ) );
        add_action( 'wp_ajax_wa_resend_message', array( $this, 'ajax_wa_resend_message' ) );
        add_action( 'wp_ajax_wa_disconnect', array( $this, 'ajax_wa_disconnect') );
        add_action( 'wp_ajax_cancel_all_message_queues', array( $this, 'cancel_all_queue_actions' ) );
        add_action( 'wp_ajax_cancel_message_queue', array( $this, 'cancel_queue_action' ) );

    }

    public function __call(string $method, array $arguments) {
        return call_user_func_array($this->$method, $arguments);
    }

    public static function get_instance() {
        
        if ( null === self::$_instance ) {
            self::$_instance = new self();
        }

        return self::$_instance;

    }

    /**
     * Schedule WhatsApp notifications using Action Scheduler for better performance
     * This method replaces direct processing during checkout to avoid delays
     * 
     * @param int $order_id WooCommerce Order ID
     * @return void
     */
    public function wc_schedule_notifications( $order_id ) {
        
        if ( ! $order_id ) {
            WPWFW_Core()->logger->add( 'app', 'wc_schedule_notifications called with empty order_id', 'WARN', 'SERVICES' );
            return;
        }

        // Get the current order status to create status-specific transient key
        $order = wc_get_order( $order_id );
        if ( ! $order ) {
            WPWFW_Core()->logger->add( 'app', "Order #$order_id not found, skipping scheduling", 'WARN', 'SERVICES' );
            return;
        }
        
        $order_status = $order->get_status();
        
        // Prevent duplicate scheduling for the same order AND same status within a short time frame
        $transient_key = 'wpwfw_scheduled_' . $order_id . '_' . $order_status;
        if ( get_transient( $transient_key ) ) {
            WPWFW_Core()->logger->add( 'app', "Duplicate scheduling prevented for order #$order_id status '$order_status' (already scheduled for this status)", 'INFO', 'SERVICES' );
            return;
        }

        // Set transient to prevent duplicate scheduling for this specific order+status for 60 seconds
        set_transient( $transient_key, true, 60 );

        WPWFW_Core()->logger->add( 'app', "Scheduling background notifications for order #$order_id status '$order_status'", 'INFO', 'SERVICES' );

        $settings = WPWFW_Core()->system->get_settings();

        // Check if background processing is enabled (default: enabled)
        $background_enabled = isset( $settings['background_processing'] ) ? $settings['background_processing'] === 'yes' : true;

        if ( ! $background_enabled ) {
            WPWFW_Core()->logger->add( 'app', "Background processing disabled, processing immediately for order #$order_id", 'INFO', 'SERVICES' );
            // Fall back to immediate processing if background processing is disabled
            $this->wc_process_order_status( $order_id );
            $this->wc_process_order_checkout( $order_id );
            return;
        }

        // Check if Action Scheduler is available (comes with WooCommerce)
        if ( ! function_exists( 'as_schedule_single_action' ) ) {
            WPWFW_Core()->logger->add( 'app', "Action Scheduler not available, falling back to immediate processing for order #$order_id", 'WARN', 'SERVICES' );
            // Fallback to immediate processing if Action Scheduler is not available
            $this->wc_process_order_status( $order_id );
            $this->wc_process_order_checkout( $order_id );
            return;
        }

        try {
            // Check if there are already pending actions for this order to prevent duplicates
            $existing_customer = as_get_scheduled_actions( array(
                'hook' => 'wpwfw_process_customer_notification',
                'args' => array( $order_id ),
                'status' => 'pending',
                'per_page' => 1
            ) );

            $existing_sales = as_get_scheduled_actions( array(
                'hook' => 'wpwfw_process_sales_notification',
                'args' => array( $order_id ),
                'status' => 'pending',
                'per_page' => 1
            ) );

            if ( empty( $existing_customer ) ) {
                // Schedule customer notification with 5 second delay to avoid checkout blocking
                as_schedule_single_action(
                    time() + 5,
                    'wpwfw_process_customer_notification',
                    array( $order_id ),
                    'wpwfw_notifications'
                );
                WPWFW_Core()->logger->add( 'app', "Scheduled customer notification for order #$order_id status '$order_status'", 'INFO', 'SERVICES' );
            } else {
                WPWFW_Core()->logger->add( 'app', "Customer notification already scheduled for order #$order_id", 'INFO', 'SERVICES' );
            }

            if ( empty( $existing_sales ) ) {
                // Schedule sales team notification with 10 second delay to spread API load
                as_schedule_single_action(
                    time() + 10,
                    'wpwfw_process_sales_notification', 
                    array( $order_id ),
                    'wpwfw_notifications'
                );
                WPWFW_Core()->logger->add( 'app', "Scheduled sales notification for order #$order_id status '$order_status'", 'INFO', 'SERVICES' );
            } else {
                WPWFW_Core()->logger->add( 'app', "Sales notification already scheduled for order #$order_id", 'INFO', 'SERVICES' );
            }

            WPWFW_Core()->logger->add( 'app', "Successfully processed Action Scheduler tasks for order #$order_id status '$order_status'", 'INFO', 'SERVICES' );
            
            // Set transient for admin notification (only on admin pages and if messages were actually scheduled)
            if ( is_admin() && ( empty( $existing_customer ) || empty( $existing_sales ) ) ) {
                set_transient( 'wpwfw_message_scheduled_' . get_current_user_id(), $order_id, 10 );
            }

        } catch ( Exception $e ) {
            WPWFW_Core()->logger->add( 'app', "Error scheduling notifications for order #$order_id: " . $e->getMessage(), 'ERROR', 'SERVICES' );
            
            // Fallback to immediate processing on scheduling error
            WPWFW_Core()->logger->add( 'app', "Falling back to immediate processing for order #$order_id", 'INFO', 'SERVICES' );
            $this->wc_process_order_status( $order_id );
            $this->wc_process_order_checkout( $order_id );
        }
        
        // Schedule follow-up messages for completed orders using Action Scheduler
        $order = wc_get_order( $order_id );
        if ( $order && $order->get_status() === 'completed' ) {
            $this->wc_schedule_follow_ups( $order_id );
        }
    }

    /**
     * Schedule follow-up messages for completed orders using Action Scheduler
     * This provides precise timing and avoids the overlap issues with cron
     * 
     * @param int $order_id WooCommerce Order ID
     * @return void
     */
    public function wc_schedule_follow_ups( $order_id ) {
        
        if ( ! $order_id ) {
            WPWFW_Core()->logger->add( 'app', 'wc_schedule_follow_ups called with empty order_id', 'WARN', 'SERVICES' );
            return;
        }

        $settings = WPWFW_Core()->system->get_settings();
        $order = wc_get_order( $order_id );
        
        if ( ! $order ) {
            WPWFW_Core()->logger->add( 'app', "Invalid order for follow-up scheduling: #$order_id", 'WARN', 'SERVICES' );
            return;
        }

        WPWFW_Core()->logger->add( 'app', "Scheduling follow-up messages for completed order #$order_id", 'INFO', 'SERVICES' );

        // Check if Action Scheduler is available
        if ( ! function_exists( 'as_schedule_single_action' ) ) {
            WPWFW_Core()->logger->add( 'app', "Action Scheduler not available for follow-ups, using legacy cron", 'WARN', 'SERVICES' );
            return;
        }

        try {
            foreach ( $this->aftersale_events as $event => $name ) {
                
                // Check if this follow-up is configured
                if ( ! isset( $settings[$event] ) || empty( $settings[$event] ) ) {
                    WPWFW_Core()->logger->add( 'app', "Follow-up $event not configured, skipping", 'INFO', 'SERVICES' );
                    continue;
                }
                
                if ( ! isset( $settings[$event . '_delay'] ) || empty( $settings[$event . '_delay'] ) ) {
                    WPWFW_Core()->logger->add( 'app', "Follow-up $event delay not configured, skipping", 'INFO', 'SERVICES' );
                    continue;
                }

                $delay = (int) $settings[$event . '_delay'];
                $delay_unit = isset( $settings[$event . '_delay_unit'] ) ? $settings[$event . '_delay_unit'] : 'hour';
                
                // Convert delay to seconds
                $delay_seconds = 0;
                switch ( $delay_unit ) {
                    case 'min':
                        $delay_seconds = $delay * 60;
                        break;
                    case 'hour':
                        $delay_seconds = $delay * 3600;
                        break;
                    case 'day':
                        $delay_seconds = $delay * 86400;
                        break;
                    default:
                        $delay_seconds = $delay * 3600; // Default to hours
                }

                // Check if already scheduled to prevent duplicates
                $existing_actions = as_get_scheduled_actions( array(
                    'hook' => 'wpwfw_process_' . $event,
                    'args' => array( $order_id, $event ),
                    'status' => 'pending',
                    'per_page' => 1
                ) );

                if ( empty( $existing_actions ) ) {
                    // Schedule the follow-up message
                    $scheduled_time = time() + $delay_seconds;
                    
                    as_schedule_single_action(
                        $scheduled_time,
                        'wpwfw_process_' . $event,
                        array( $order_id, $event ),
                        'wpwfw_followups'
                    );
                    
                    WPWFW_Core()->logger->add( 'app', "Scheduled $event for order #$order_id at " . date('Y-m-d H:i:s', $scheduled_time) . " (delay: $delay $delay_unit)", 'INFO', 'SERVICES' );
                } else {
                    WPWFW_Core()->logger->add( 'app', "Follow-up $event already scheduled for order #$order_id", 'INFO', 'SERVICES' );
                }
            }

        } catch ( Exception $e ) {
            WPWFW_Core()->logger->add( 'app', "Error scheduling follow-ups for order #$order_id: " . $e->getMessage(), 'ERROR', 'SERVICES' );
        }
    }

    /**
     * Process a single follow-up message via Action Scheduler
     * This method handles individual follow-up messages with proper sequence validation
     * 
     * @param int $order_id WooCommerce Order ID
     * @param string $event Follow-up event name
     * @return array Result of the follow-up processing
     */
    public function wc_process_follow_up_single( $order_id, $event ) {
        
        if ( ! $order_id || ! $event ) {
            WPWFW_Core()->logger->add( 'app', 'wc_process_follow_up_single called with missing parameters', 'WARN', 'SERVICES' );
            return array( 'status' => 'error', 'message' => 'Missing required parameters' );
        }

        WPWFW_Core()->logger->add( 'app', "Processing single follow-up $event for order #$order_id (background)", 'INFO', 'SERVICES' );

        $settings = WPWFW_Core()->system->get_settings();
        $order = wc_get_order( $order_id );

        if ( ! $order ) {
            WPWFW_Core()->logger->add( 'app', "Invalid order for follow-up processing: #$order_id", 'WARN', 'SERVICES' );
            return array( 'status' => 'error', 'message' => 'Invalid order' );
        }

        // Check if order is still completed
        if ( $order->get_status() !== 'completed' ) {
            WPWFW_Core()->logger->add( 'app', "Order #$order_id is no longer completed, skipping follow-up $event", 'INFO', 'SERVICES' );
            return array( 'status' => 'error', 'message' => 'Order no longer completed' );
        }

        // Check if this follow-up was already sent
                    $event_data = $this->get_order_meta_hpos_compatible( $order_id, 'wpwfw_' . $event, true );
        if ( WPWFW_Common::is_json( $event_data ) ) {
            $fu_data = json_decode( $event_data, true );
            if ( isset( $fu_data['status'] ) && $fu_data['status'] == 'success' ) {
                WPWFW_Core()->logger->add( 'app', "Follow-up $event already sent successfully for order #$order_id", 'INFO', 'SERVICES' );
                return array( 'status' => 'success', 'message' => 'Already sent' );
            }
        }

        // Validate sequence - ensure previous follow-ups were sent first
        $event_id = (int) substr($event, -1);
        $previous_event_id = $event_id - 1;
        $previous_event = substr( $event, 0, strlen( $event ) - 1 ) . $previous_event_id;

        if ( $previous_event_id >= 1 ) {
            $previous_data = $this->get_order_meta_hpos_compatible( $order_id, 'wpwfw_' . $previous_event, true );
            if ( ! WPWFW_Common::is_json( $previous_data ) ) {
                WPWFW_Core()->logger->add( 'app', "Previous follow-up $previous_event not sent yet for order #$order_id, skipping $event", 'WARN', 'SERVICES' );
                return array( 'status' => 'error', 'message' => "Previous follow-up $previous_event not sent yet" );
            }
            
            $prev_data = json_decode( $previous_data, true );
            if ( ! isset( $prev_data['status'] ) || $prev_data['status'] !== 'success' ) {
                WPWFW_Core()->logger->add( 'app', "Previous follow-up $previous_event not successful for order #$order_id, skipping $event", 'WARN', 'SERVICES' );
                return array( 'status' => 'error', 'message' => "Previous follow-up $previous_event not successful" );
            }
        }

        // Process the follow-up message using existing logic
        return $this->wc_process_follow_up( $event, 'completed', $order_id );
    }

    /**
     * Legacy follow-up processing method for cron compatibility
     * This method processes all pending follow-ups but with reduced frequency
     * 
     * @param string $event Follow-up event name
     * @param string $order_status Order status to filter
     * @return bool
     */
    public function wc_process_follow_up_legacy( $event, $order_status = 'completed' ) {
        
        WPWFW_Core()->logger->add( 'app', "Processing legacy follow-up $event (cron fallback)", 'INFO', 'SERVICES' );
        
        // Use existing method but with additional duplicate prevention
        return $this->wc_process_follow_up( $event, $order_status );
    }

    /**
     * Cached phone validation to reduce API calls and improve performance
     * Uses WordPress transients for 1 hour caching
     * 
     * @param string $phone Phone number to validate
     * @param array $credentials API credentials
     * @return array Validation result
     */
    public function wa_check_phone_cached( $phone, $credentials = [] ) {
        
        // Create cache key based on phone and credentials
        $cache_key = 'wpwfw_phone_check_' . md5( $phone . serialize( $credentials ) );
        $cached_result = get_transient( $cache_key );
        
        if ( $cached_result !== false ) {
            WPWFW_Core()->logger->add( 'app', "Using cached phone validation result for $phone", 'INFO', 'SERVICES' );
            return $cached_result;
        }
        
        WPWFW_Core()->logger->add( 'app', "Performing new phone validation for $phone", 'INFO', 'SERVICES' );
        
        try {
            // Use existing phone validation method
            $result = $this->wa_check_phone( $phone, $credentials );
            
            // Cache successful results for 1 hour, failed results for 5 minutes
            $cache_duration = ( $result['status'] === 'success' ) ? 3600 : 300;
            set_transient( $cache_key, $result, $cache_duration );
            
            WPWFW_Core()->logger->add( 'app', "Phone validation result cached for $phone: " . $result['status'], 'INFO', 'SERVICES' );
            
            return $result;
            
        } catch ( Exception $e ) {
            WPWFW_Core()->logger->add( 'app', "Error during phone validation for $phone: " . $e->getMessage(), 'ERROR', 'SERVICES' );
            
            // Return error result without caching
            return array(
                'status' => 'error',
                'message' => 'Phone validation failed: ' . $e->getMessage()
            );
        }
    }

    public function update_service_data() {

        // fetch to api.shazrinsaliman.com with bearer token abc
        // test case for when app is down. page unable to load successfully
        $this->plugin_data = WPWFW_Core()->system->get_plugin_data();
        $this->plugin_data['subdomains'] = $this->default_service_data;

        $request = WPWFW_Common::make_wp_requests( $this->admin_api_path . ( WPWFW_IS_DEVELOPMENT ? '/hosts/0/testing' : '/hosts' ), [], ['Authorization' => 'Bearer ' . $this->admin_key] );

        $request['body'] = is_array( $request['body'] ) ? array_change_key_case( $request['body'], CASE_LOWER) : $request['body'];

        if ( !is_array( $request['body'] ) ) {
            WPWFW_Core()->logger->add( 'app', "Syncing latest list of subdomains through `update_service_data` failed: Invalid response", 'WARN', 'SERVICES' );
            return $this->default_service_data;
        }

        if ( isset( $request['body']['status'] ) && $request['body']['status'] == 'error' ) {
            $error_message = isset( $request['body']['message'] ) ? $request['body']['message'] : 'Remote server error';
            WPWFW_Core()->logger->add( 'app', "Syncing latest list of subdomains through `update_service_data` failed: " . $error_message, 'WARN', 'SERVICES' );
            return $this->default_service_data;
        }

        $keys = array_keys( $request['body'] );

        $versions = array_filter( $keys, function( $key ) {
            return preg_match( '/^v[0-9]+$/', $key );
        } );

        if ( empty( $versions ) ) {
            error_log( "Syncing latest list of subdomains through `update_service_data` failed: No latest version found");
            return $this->default_service_data;
        }

        $subdomains = [];
        foreach ( $versions as $version ) {
            $subdomains = array_merge( $subdomains, $request['body'][$version] );
        }

        if ( empty( $subdomains ) ) {
            WPWFW_Core()->logger->add( 'app', "Syncing latest list of subdomains through `update_service_data` failed: No subdomains found", 'WARN', 'SERVICES' );
            return;
        }

        $this->services_data = [];
        foreach ( $subdomains as $key => $value ) {
            $url = parse_url( $value['host'] );
            $this->services_data[$value['label']] = [
                'core_url' => $url['host'],
            ];
        }

        $settings = WPWFW_Core()->system->get_settings();
        $current_subdomain = $settings['subdomain'];
        if ( isset($settings['subdomain']) && !in_array( $current_subdomain, array_column( $subdomains, 'label') ) ) {
            WPWFW_Core()->logger->add( 'app', "Current subdomain `$current_subdomain` is not in the list of subdomains, updating... in `update_service_data`", 'INFO', 'SERVICES' );
            $settings['subdomain'] = $subdomains[0]['label'];
            WPWFW_Core()->system->save_settings( $settings );
        }

        
        $this->plugin_data['subdomains'] = $this->services_data;
        $this->plugin_data['last_updated'] = time();
        update_option( 'wpwfw_plugin_data', $this->plugin_data );
        
        WPWFW_Core()->logger->add( 'app', "Syncing latest list of subdomains through `update_service_data` successful", 'INFO', 'SERVICES' );

        return $this->services_data;
    }

    public function get_service_data($now = false) {
        $this->plugin_data = WPWFW_Core()->system->get_plugin_data();

        $last_updated = isset( $this->plugin_data['last_updated'] ) ? $this->plugin_data['last_updated'] : 0;

        $this->services_data = $this->plugin_data['subdomains'] ?? [];
        
        if ( $now ) WPWFW_Core()->logger->add( 'app', "Forced syncing is enabled for `get_service_data`", 'INFO', 'SERVICES' );

        // if last sync is more than 24 hours ago, update domains
        WPWFW_Core()->logger->add( 'app', "Syncing latest list of subdomains through `get_service_data` (Last Updated: " . date("Y-m-d H:i:s", $last_updated) . ")", 'INFO', 'SERVICES' );
        if ( empty( $this->services_data ) || $last_updated + 86400 < time() || $now ) {
            $this->services_data = $this->update_service_data();
        }

        return $this->services_data;
    }

    public function sync_integrations() {

        $this->plugin_data = WPWFW_Core()->system->get_plugin_data();

        if ( !empty( WPWFW_Core()->integration::$active_integrations ) ) {
            $settings = WPWFW_Core()->system->get_settings();
            foreach ( $this->cart_abandon_events as $integration => $events ) {
                if ( !in_array( $integration, WPWFW_Core()->integration::$active_integrations ) ) continue;
                // Do not register events if plugin is not set
                if ( !isset( $settings['cart_abandon_plugin'] ) || $settings['cart_abandon_plugin'] != $integration ) continue;
                if ( isset( $this->plugin_data['running_cart_abandon_hook'] ) && $this->plugin_data['running_cart_abandon_hook'] != $integration ) {
                    // Clear old hook
                    wp_clear_scheduled_hook( 'cron_hook_' . $this->plugin_data['running_cart_abandon_hook'] );
                }
                foreach ( $events as $event => $event_name ) {
                    $this->{'wc_' . $event} = function () use ( $integration, $event ) {
                        $this->wc_process_abandonment( $integration, $event );
                    };
                    add_action( 'cron_hook_' . $event, array( $this, 'wc_' . $event ) );
                    if ( ! wp_next_scheduled( 'cron_hook_' . $event ) ) {
                        wp_schedule_event( time(), 'every_ten_minutes', 'cron_hook_' . $event );
                        // Register hook as running
                        $this->plugin_data['running_cart_abandon_hook'] = $integration;
                        update_option( 'wpwfw_plugin_data', $this->plugin_data );
                    }
                }
            }
        }
    }

    public function add_custom_columns( $columns ) {

        $columns[WPWFW_PLUGIN_CORE_NAME] = __( WPWFW_PLUGIN_SIMPLE_NAME );
        return $columns;

    }

    public function manage_custom_columns( $column_name, $post_id ) {

        if ( WPWFW_PLUGIN_CORE_NAME == $column_name ) {
            $settings = WPWFW_Core()->system->get_settings();

            $status_color = array(
                'unavailable' => '#bbb',
                'success' => '#8bcb61',
                'fail' => '#d63638',
            );

            $status_label = array(
                'unavailable' => '<i class="fa-brands fa-whatsapp"></i>',
                'success' => '<i class="fa-solid fa-check"></i>',
                'fail' => '<i class="fa-solid fa-xmark"></i>',
            );

            $display_status = function( $arg = [], $type = 'icon' ) use ( $status_color, $status_label ) {
                $icon = ( $type == 'icon' ) ? '{status_indicator}' : '<i style="height: 10px;width: 10px;background-color:{status_indicator};border-radius:50%;display:inline-block;margin-right: 5px;margin-top: 10px;"></i>';
                $indicator = ( $type == 'icon' ) ? $status_label : $status_color;
                if ( isset( $arg['status']) && $arg['status'] === 'success' ) {
                    return str_replace( '{status_indicator}', $indicator['success'], $icon );
                } else if ( isset( $arg['status']) && $arg['status'] === 'error' ) {
                    return str_replace( '{status_indicator}', $indicator['fail'], $icon );
                } else {
                    return str_replace( '{status_indicator}', $indicator['unavailable'], $icon );
                }
            };

            $send_message = get_post_meta( $post_id, 'wpwfw_send_message', true );

            if ( ! isset( $settings['instance_id'] ) || ! isset( $settings['access_token'] ) ) {
                echo '<a href="' . admin_url( 'admin.php?page=' . WPWFW_PLUGIN_CORE_NAME . '&path=/settings&focus=connection' ) . '"><i class="far fa-exclamation-circle"></i> Setup required</a>';
                return;
            }

            if ( ! $send_message ) {
                // Check if there are pending or running background tasks for this order
                $pending_customer_task = false;
                if ( function_exists( 'as_get_scheduled_actions' ) ) {
                    $pending_actions = as_get_scheduled_actions( array(
                        'hook' => 'wpwfw_process_customer_notification',
                        'args' => array( $post_id ),
                        'status' => 'pending',
                        'per_page' => 1
                    ) );
                    $running_actions = as_get_scheduled_actions( array(
                        'hook' => 'wpwfw_process_customer_notification',
                        'args' => array( $post_id ),
                        'status' => 'in-progress',
                        'per_page' => 1
                    ) );
                    $pending_customer_task = !empty( $pending_actions ) || !empty( $running_actions );
                }
                
                // Also check for processing locks for any order status
                $has_processing_lock = false;
                $order = wc_get_order( $post_id );
                if ( $order ) {
                    $order_status = $order->get_status();
                    $processing_lock_key = 'wpwfw_processing_status_' . $post_id . '_' . $order_status;
                    $has_processing_lock = get_transient( $processing_lock_key );
                    
                    // If no lock found for current status, check for any processing lock for this order
                    if ( !$has_processing_lock ) {
                        global $wpdb;
                        $lock_pattern = '_transient_wpwfw_processing_status_' . $post_id . '_%';
                        $existing_locks = $wpdb->get_var( $wpdb->prepare(
                            "SELECT option_name FROM {$wpdb->options} WHERE option_name LIKE %s LIMIT 1",
                            $lock_pattern
                        ) );
                        $has_processing_lock = !empty( $existing_locks );
                    }
                }
                
                if ( $pending_customer_task || $has_processing_lock ) {
                    // Show processing status while background task is running or processing lock is active
                    echo '<a href="' . admin_url( 'edit.php?post_type=shop_order&wpwfw_id='.$post_id ) . '" title="Processing message in background..." class="button wc-action-button wpwfw-processing-status" data-order-id="' . $post_id . '" style="background-color: #17a2b8; color: white; border-color: #17a2b8;"><i class="fa-solid fa-spinner fa-spin" style="color: white;"></i> Processing</a>';
                    echo '<script>
                        // Auto-refresh processing status for order #' . $post_id . '
                        (function() {
                            var orderId = ' . $post_id . ';
                            var button = document.querySelector(\'[data-order-id="\' + orderId + \'"]\');
                            if (button) {
                                var checkInterval = setInterval(function() {
                                    // Check if processing is still active by making an AJAX call
                                    jQuery.ajax({
                                        url: ajaxurl,
                                        type: "POST",
                                        data: {
                                            action: "wpwfw_check_processing_status",
                                            order_id: orderId,
                                            nonce: "' . wp_create_nonce('wpwfw_check_status') . '"
                                        },
                                        success: function(response) {
                                            if (response.success && !response.data.is_processing) {
                                                // Processing is complete, refresh the page to show updated status
                                                clearInterval(checkInterval);
                                                location.reload();
                                            }
                                        },
                                        error: function() {
                                            // On error, stop checking after 30 seconds
                                            setTimeout(function() {
                                                clearInterval(checkInterval);
                                            }, 30000);
                                        }
                                    });
                                }, 2000); // Check every 2 seconds
                                
                                // Stop checking after 5 minutes to prevent infinite checking
                                setTimeout(function() {
                                    clearInterval(checkInterval);
                                }, 300000);
                            }
                        })();
                    </script>';
                } else {
                    // Show "Not Yet" only if no background task is pending and no processing lock is active
                    echo '<a href="' . admin_url( 'edit.php?post_type=shop_order&wpwfw_id='.$post_id ) . '" title="Click to send message" class="button wc-action-button" style="background-color: #fbbf24; color: black; border-color: #fbbf24;"><i class="fa-brands fa-whatsapp" style="color: black;"></i> Send Now</a>';
                }
                return;
            }

            if ( ! WPWFW_Common::is_json( $send_message ) ) {
                echo '<a href="' . admin_url( 'edit.php?post_type=shop_order&wpwfw_id='.$post_id ) . '" title="Invalid message data format&#10;Click to retry" class="button wc-action-button" style="background-color: #dc3545; color: white; border-color: #dc3545;"><i class="fa-solid fa-xmark" style="color: white;"></i> Failed</a>';
                return;
            }

            $data = json_decode( $send_message, true );
            if ( ! isset( $data['status'] ) ) {
                echo '<a href="' . admin_url( 'edit.php?post_type=shop_order&wpwfw_id='.$post_id ) . '" title="Missing status information&#10;Click to retry" class="button wc-action-button" style="background-color: #dc3545; color: white; border-color: #dc3545;"><i class="fa-solid fa-xmark" style="color: white;"></i> Failed</a>';
                return;
            }

            // Check for processing locks even for orders with existing message data
            $has_processing_lock = false;
            $pending_customer_task = false;
            $order = wc_get_order( $post_id );
            if ( $order ) {
                $order_status = $order->get_status();
                $processing_lock_key = 'wpwfw_processing_status_' . $post_id . '_' . $order_status;
                $has_processing_lock = get_transient( $processing_lock_key );
                
                // If no lock found for current status, check for any processing lock for this order
                if ( !$has_processing_lock ) {
                    global $wpdb;
                    $lock_pattern = '_transient_wpwfw_processing_status_' . $post_id . '_%';
                    $existing_locks = $wpdb->get_var( $wpdb->prepare(
                        "SELECT option_name FROM {$wpdb->options} WHERE option_name LIKE %s LIMIT 1",
                        $lock_pattern
                    ) );
                    $has_processing_lock = !empty( $existing_locks );
                }
                
                // Check for pending or running Action Scheduler tasks
                if ( function_exists( 'as_get_scheduled_actions' ) ) {
                    $pending_actions = as_get_scheduled_actions( array(
                        'hook' => 'wpwfw_process_customer_notification',
                        'args' => array( $post_id ),
                        'status' => 'pending',
                        'per_page' => 1
                    ) );
                    $running_actions = as_get_scheduled_actions( array(
                        'hook' => 'wpwfw_process_customer_notification',
                        'args' => array( $post_id ),
                        'status' => 'in-progress',
                        'per_page' => 1
                    ) );
                    $pending_customer_task = !empty( $pending_actions ) || !empty( $running_actions );
                }
            }
            
            // If there's a processing lock or pending task, show processing animation regardless of existing message data
            if ( $has_processing_lock || $pending_customer_task ) {
                echo '<a href="' . admin_url( 'edit.php?post_type=shop_order&wpwfw_id='.$post_id ) . '" title="Processing message in background..." class="button wc-action-button wpwfw-processing-status" data-order-id="' . $post_id . '" style="background-color: #17a2b8; color: white; border-color: #17a2b8;"><i class="fa-solid fa-spinner fa-spin" style="color: white;"></i> Processing</a>';
                echo '<script>
                    // Auto-refresh processing status for order #' . $post_id . '
                    (function() {
                        var orderId = ' . $post_id . ';
                        var button = document.querySelector(\'[data-order-id="\' + orderId + \'"]\');
                        if (button) {
                            var checkInterval = setInterval(function() {
                                // Check if processing is still active by making an AJAX call
                                jQuery.ajax({
                                    url: ajaxurl,
                                    type: "POST",
                                    data: {
                                        action: "wpwfw_check_processing_status",
                                        order_id: orderId,
                                        nonce: "' . wp_create_nonce('wpwfw_check_status') . '"
                                    },
                                    success: function(response) {
                                        if (response.success && !response.data.is_processing) {
                                            // Processing is complete, refresh the page to show updated status
                                            clearInterval(checkInterval);
                                            location.reload();
                                        }
                                    },
                                    error: function() {
                                        // On error, stop checking after 30 seconds
                                        setTimeout(function() {
                                            clearInterval(checkInterval);
                                        }, 30000);
                                    }
                                });
                            }, 2000); // Check every 2 seconds
                            
                            // Stop checking after 5 minutes to prevent infinite checking
                            setTimeout(function() {
                                clearInterval(checkInterval);
                            }, 300000);
                        }
                    })();
                </script>';
                return;
            }

            if ( $data['status'] == 'success' ) {
                $status = 'Click to resend message';
                $button_text = 'Success';
                $button_style = ' style="background-color: #28a745; color: white; border-color: #28a745;"';
            } else {
                $error_reason = isset( $data['message'] ) ? $data['message'] : 'Unknown error';
                $status = $error_reason . '&#10;Click to retry';
                $button_text = 'Failed';
                $button_style = ' style="background-color: #dc3545; color: white; border-color: #dc3545;"';
            }
            echo '<a href="' . admin_url( 'edit.php?post_type=shop_order&wpwfw_id='.$post_id ) . '" title="' . $status . '" class="button wc-action-button"' . $button_style . '>' . $display_status( $data ) . ' ' . $button_text . '</a>' ;
            
        }
    }

    public function add_custom_bulk_actions( $actions ) {

        $actions['wpwf_resend_message'] = __( 'Resend WhatsApp', WPWFW_PLUGIN_DOMAIN_LANGUAGE );

        return $actions;

    }

    public function handle_custom_bulk_actions( $redirect_to, $action, $post_ids ) {

        if ( ! in_array( $action, array( 'wpwf_resend_message' ) ) ) {
            return $redirect_to;
        }

        /**
         * Initialize bulk stats
         */
        $this->bulk_success = 0;
        $this->bulk_failed = 0;

        if ( $action == 'wpwf_resend_message' ) {
            // Set global flag to indicate we're in a manual bulk resend
            global $wpwfw_manual_resend_active;
            $wpwfw_manual_resend_active = true;
            
            $scheduled_orders = array();
            
            foreach ($post_ids as $post_id) {
                // Log resend attempt
                WPWFW_Core()->logger->add( 'app', "Bulk resend: Attempting to resend message for order #$post_id", 'INFO', 'SERVICES' );
                
                // Use existing background processing method - this triggers animations and proper processing
                $this->wc_schedule_notifications( $post_id );
                $scheduled_orders[] = $post_id;
                
                // For immediate feedback, also process synchronously to get results
                $response = $this->wc_process_order_status( $post_id );

                // Count success/failure based on actual resend attempt
                if ( $response['status'] == 'success' ) {
                    ++$this->bulk_success;
                } else{
                    ++$this->bulk_failed;
                } 
            }

            // Clear the global flag
            $wpwfw_manual_resend_active = false;

            // Set transient for bulk scheduled orders (overrides individual transients)
            if ( is_admin() && !empty($scheduled_orders) ) {
                set_transient( 'wpwfw_message_scheduled_' . get_current_user_id(), $scheduled_orders, 10 );
            }

            return $redirect_to = add_query_arg( array(
                'wpwfw_bulk' => implode( ',', [$this->bulk_success, $this->bulk_failed] ),
            ), $redirect_to );
        }

        return $redirect_to;
    }

    /**
     * Add meta box with HPOS compatibility
     */
    public function register_wasapbot_order_dashboard() {

        $screen = class_exists( '\Automattic\WooCommerce\Internal\DataStores\Orders\CustomOrdersTableController' ) && wc_get_container()->get( CustomOrdersTableController::class )->custom_orders_table_usage_is_enabled()
		? wc_get_page_screen_id( 'shop-order' )
		: 'shop_order';

        add_meta_box( 'wasapbot_order_dashboard', __( WPWFW_PLUGIN_SIMPLE_NAME . ' - Dashboard', WPWFW_PLUGIN_CORE_NAME ), [WPWFW_Core()->ui, 'woocommerce_order_meta'], $screen, 'advanced', 'high' );
    }

    /**
     * Register custom order action hook
     */
    public function register_custom_order_status() {
        
        $default_status = $this->default_status;

        if (!WPWFW_Core()->integration->is_woocommerce_active()) return false;

        if ( ! function_exists( 'wc_get_order_statuses' ) ) return false;

        $all_status = wc_get_order_statuses();
        
        foreach ( $all_status as $key => $status_name ) {
            $status_key = str_replace( "wc-", "", $key );
            if (! in_array( $status_key, $default_status ) ) {
                $this->custom_status[$status_key] = $status_name;
                // Use Action Scheduler for custom statuses too to avoid duplicate notifications
                add_action( 'woocommerce_order_status_'.$status_key, array( $this, 'wc_schedule_notifications' ), 10 );
            }
        }

    }

    /**
     * Trigger custom status hook
     * @param string $new_status
     * @param string $old_status
     * @param object $post
     * 
     * @return null
     */
    public function wc_trigger_custom_status_hooks( $new_status, $old_status, $post ) {

        $custom_status = $this->custom_status;
        // Check if the post type is 'shop_order' (WooCommerce order)
        if ( $post->post_type === 'shop_order' ) {

            $new_status = str_replace( "wc-", "", $new_status );
            WPWFW_Core()->logger->add( 'app', "Executing `wc-trigger-custom-status-hooks` for status " . ucwords( $new_status ) . "...", 'INFO', 'SERVICES' );
            WPWFW_Core()->logger->add( 'app', "`wc-trigger-custom-status-hooks` has available custom status " . implode( ', ', array_keys( $custom_status ) ) . "...", 'INFO', 'SERVICES' );
            // Check if the new status is one of your custom statuses
            if ( in_array( $new_status, array_keys( $custom_status ) ) ) {
                WPWFW_Core()->logger->add( 'app', "Executing `wc_process_order_status` manually for order #" . $post->ID . " via `wc-trigger-custom-status-hooks`...", 'INFO', 'SERVICES' );
                // Trigger the WooCommerce action hook for your custom status
                $this->wc_process_order_status( $post->ID );
                //do_action( 'woocommerce_order_status_' . $new_status, $post->ID );
            }
            WPWFW_Core()->logger->add( 'app', "`wc-trigger-custom-status-hooks` executed successfully...", 'INFO', 'SERVICES' );
        }
    }

    public function get_wc_products() {
        $args = array(
            'post_type' => 'product',
            'posts_per_page' => -1,
        );
        $products = get_posts( $args );

        return $products;
    }

    /**
     * Process message relative to the order status
     * @param int $order_id WC Order Id
     * 
     * @return array|null 
     */
    public function wc_process_order_status( $order_id ) {

        global $woocommerce;

        $order = new WC_Order( $order_id );
        $oid = is_callable([$order, 'get_id']) ? $order->get_id() : $order->id;
        $status = is_callable( [$order, 'get_status'] ) ? $order->get_status() : $order->status;

        WPWFW_Core()->logger->add( 'app', "Executing `wc-process-order-status` (background) for status " . ucwords( $status ) . "...", 'INFO', 'SERVICES' );
        
        // Prevent duplicate message sending for the same order status
        $processing_lock_key = 'wpwfw_processing_status_' . $oid . '_' . $status;
        if ( get_transient( $processing_lock_key ) ) {
            WPWFW_Core()->logger->add( 'app', "Order #$oid status $status is currently being processed, preventing duplicate", 'INFO', 'SERVICES' );
            return array(
                'status' => 'error',
                'message' => 'Message is currently being processed for this order status',
                'order_status' => $status,
            );
        }
        
        // Set processing lock for 2 minutes to prevent overlapping executions
        set_transient( $processing_lock_key, true, 120 );
        
        // Check if this is a manual resend request (don't skip for manual resends)
        global $wpwfw_manual_resend_active;
        $is_manual_resend = isset( $_GET['wpwfw_id'] ) || 
                           (defined('DOING_AJAX') && DOING_AJAX) || 
                           $wpwfw_manual_resend_active;
        
        // Enhanced logging for debugging manual resend detection
        WPWFW_Core()->logger->add( 'app', "Manual resend detection for order #$oid: wpwfw_id=" . (isset($_GET['wpwfw_id']) ? 'yes' : 'no') . ", DOING_AJAX=" . (defined('DOING_AJAX') && DOING_AJAX ? 'yes' : 'no') . ", bulk_flag=" . ($wpwfw_manual_resend_active ? 'yes' : 'no') . ", is_manual_resend=" . ($is_manual_resend ? 'yes' : 'no'), 'INFO', 'SERVICES' );
        
        // Only skip duplicates for automatic triggers, not manual resends
        if ( !$is_manual_resend ) {
            // Use new status-specific duplicate prevention
            if ( $this->is_notification_sent_for_status( $oid, $status, false ) ) {
                WPWFW_Core()->logger->add( 'app', "Message already sent successfully for order #$oid status '$status', skipping duplicate", 'INFO', 'SERVICES' );
                delete_transient( $processing_lock_key );
                return array(
                    'status' => 'success',
                    'message' => 'Message already sent successfully for this order status',
                    'order_status' => $status,
                );
            }
        } else {
            WPWFW_Core()->logger->add( 'app', "Manual resend detected for order #$oid, forcing resend regardless of previous status", 'INFO', 'SERVICES' );
        }
        
        /**
         * Prepare list of events
         */
        $template_name = [];
        foreach ( $this->default_status as $id => $default_event ) {
            if ( $default_event == 'received_message' && false ) continue; // disable on-hold events. for now, we allow it
            $template_name[$default_event] = $this->default_template_name[$id];
        }

        /**
         * Merge with custom events
         */
        foreach ( $this->custom_status as $custom_event => $custom_name ) {
            $template_name[$custom_event] = $custom_event;
        }

        /*
        $template_name = array(
            'pending' => 'pending_message',
			'failed' => 'failed_message',
			'on-hold' => 'received_message',
			'processing' => 'processing_message',
			'completed' => 'completed_message',
			'refunded' => 'refunded_message',
			'cancelled' => 'cancelled_message',
        );
        */
        
        $settings = WPWFW_Core()->system->get_settings();
        $this->plugin_data = WPWFW_Core()->system->get_plugin_data();

        // No events should be allowed
        if ( ! isset( $settings['default_events'] ) && ! isset( $settings['custom_events'] ) ) {
            WPWFW_Core()->logger->add( 'app', "`wc-process-order-status` executed with an error: Configuration is required", 'WARN', 'SERVICES' );
            delete_transient( $processing_lock_key );
            return array(
                'status' => 'error',
                'message' => 'Configuration is required.',
                'order_status' => $status, 
            );
        }

        /**
         * Merge default events with custom events
         */
        $active_events = array_merge( $settings['default_events'], $settings['custom_events'] ?? array() );
        
        $template = $template_name[$status];
        if ( !array_intersect( ['all', $template] , $active_events ) ) {
            WPWFW_Core()->logger->add( 'app', "`wc-process-order-status` executed with an error: " . ucwords( $status ) . " is not configured", 'WARN', 'SERVICES' );
            delete_transient( $processing_lock_key );
            return array(
                'status' => 'error',
                'message' => ucwords( $status ) . " is not configured",
                'order_status' => $status,
            );
        }

        /* if ( $status == 'on-hold' && !array_intersect( ['all', 'order_message', 'received_message'], $settings['default_events'] ) ) return;
        else if ( $status == 'pending' && !array_intersect( ['all', 'pending_message'], $settings['default_events'] ) ) return;
        else if ( $status ==  'failed' && !array_intersect( ['all', 'failed_message'], $settings['default_events'] ) ) return;
        else if ( $status ==  'processing' && !array_intersect( ['all', 'processing_message'], $settings['default_events'] ) ) return;
        else if ( $status ==  'completed' && !array_intersect( ['all', 'completed_message'], $settings['default_events'] ) ) return;
        else if ( $status ==  'refunded' && !array_intersect( ['all', 'refunded_message'], $settings['default_events'] ) ) return;
        else if ( $status ==  'cancelled' && !array_intersect( ['all', 'cancelled_message'], $settings['default_events'] ) ) return; */
        
        // @TODO: Get Product(s) Id in an array

        $billing_country = is_callable( [ $order, 'get_billing_country' ] ) ? $order->get_billing_country() : $order->billing_country;
        $billing_phone = is_callable( [ $order, 'get_billing_phone' ] ) ? $order->get_billing_phone() : $order->billing_phone;

        // To add: Priority between shipping and billing phone (interchangeable)
        $send_to = $this->wc_process_phone_number( $billing_phone, $billing_country );

        if ( is_array( $send_to ) ) {
            $ar = array( 'status' => 'error', 'message' => $send_to[0], 'order_status' => $status );
            $this->update_order_meta( $oid, json_encode( $ar ) );
            WPWFW_Core()->logger->add( 'app', "`wc-process-order-status` executed with response: " . json_encode( $ar ), 'WARN', 'SERVICES' );
            delete_transient( $processing_lock_key );
            return $ar;
        }

        // Use cached phone validation to improve performance
        $is_valid_number = $this->wa_check_phone_cached( $send_to );
        if ( $is_valid_number['status'] == 'error' ) {
            $is_valid_number_msg = ($is_valid_number['message'] == 'not available') ? 'Not a valid Whatsapp number' : $is_valid_number['message'] ;
            $ar = array( 'status' => 'error', 'message' => $is_valid_number_msg, 'order_status' => $status );
            $this->update_order_meta( $oid, json_encode( $ar ) );
            WPWFW_Core()->logger->add( 'app', "`wc-process-order-status` (background) executed with response: " . json_encode( $ar ), 'WARN', 'SERVICES' );
            delete_transient( $processing_lock_key );
            return $ar;
        }

        // Use phone in $is_valid_number if exists
        $send_to = $is_valid_number['phone'] ?? $send_to;

        if ( $status == 'on-hold' && false ) {
            /**
             * Temporarily do nothing... initially we want to send a message when order is on-hold with a set of delay 
             * For now, we will just process normally
             */
            $ar = array( 'status' => 'error', 'message' => 'On-hold not available', 'order_status' => $status );
            $this->update_order_meta( $oid, json_encode( $ar ) );
            WPWFW_Core()->logger->add( 'app', "`wc-process-order-status` executed with response: " . json_encode( $ar ), 'WARN', 'SERVICES' );
            delete_transient( $processing_lock_key );
            return $ar;
        }

        // Template resolution with validation-enforced fallback
        if ( empty( $settings[$template] ) ) {
            // For default statuses, try system template
            $settings[$template] = WPWFW_Core()->system->get_notification_template( $template );
            
            // For custom statuses, try integration template as fallback
            if ( empty( $settings[$template] ) ) {
                $settings[$template] = WPWFW_Core()->integration->get_integration_notification_template( $template );
            }
        }
        
        // If still empty after all attempts, this means no template is configured
        if ( empty( $settings[$template] ) ) {
            WPWFW_Core()->logger->add( 'app', "No template configured for status '$template' - user must configure template in settings", 'ERROR', 'SERVICES' );
            delete_transient( $processing_lock_key );
            return array(
                'status' => 'error',
                'message' => 'No template configured for this status. Please configure in settings.',
                'order_status' => $status,
            );
        }
        $msg = $this->wc_process_order_shortcodes( $settings[$template], $order, array( 'custom_shortcodes' => $settings['custom_shortcodes']) );
        $img = null;

        $response = ( isset( $settings['sandbox_mode'] ) && $settings['sandbox_mode'] == '1') ? array( 'status'=>'success', 'message'=>'Success (Sandbox)' ) : $this->wa_send_message( $send_to, ['msg' => $msg, 'img' => $img], $this->wa_get_credentials() );

        $debug_data = array(
            'recipient'	=> $billing_phone,
            'processed'	=> $send_to,
            'msg'		=> $msg,
            'img'		=> $img,
            'response'	=> $response,
        );
        if ( isset($settings['debug'] ) && $settings['debug'] == '1' ) {
            if ( isset( $settings['debug_email_field'] ) && !empty( $settings['debug_email_field'] ) ) {
                if ( !isset( $response['status'] ) && !isset( $response['message'] ) ) $response = array(
                    'status' => 'error',
                    'message' => 'No response received',
                    'debug' => $debug_data
                );

                $mail = __( 'Action: Order', WPWFW_PLUGIN_DOMAIN_LANGUAGE )."\r\n". strtolower( $status ) . " Event" . "\r\n\r\n";
                $mail .= __( 'Mobile number: ', WPWFW_PLUGIN_DOMAIN_LANGUAGE )."\r\n".$send_to."\r\n\r\n";
                $mail .= __( 'Message: ', WPWFW_PLUGIN_DOMAIN_LANGUAGE )."\r\n".$msg."\r\n\r\n";
                $mail .= __( 'Gateway response: ', WPWFW_PLUGIN_DOMAIN_LANGUAGE )."\r\n".print_r( $response, true );
                wp_mail( $settings['debug_email_field'], '[DEBUG] ' . WPWFW_PLUGIN_SIMPLE_NAME, $mail, 'charset=UTF-8'."\r\n");
            }
        }

        if ( isset( $response['status'] ) && isset( $response['message'] ) ) {
            $response['order_status'] = $status;
            if ( $response['status'] == 'success' ) $response['data'] = 'classified';
            
            // Enhanced logging for debugging manual resends
            WPWFW_Core()->logger->add( 'app', "Updating order meta for order #$oid with response: " . json_encode( $response ), 'INFO', 'SERVICES' );
            $this->update_order_meta( $oid, json_encode( $response ) );
            
            // Clear any potential order caches to ensure fresh data is displayed
            if ( function_exists( 'wc_delete_shop_order_transients' ) ) {
                wc_delete_shop_order_transients( $oid );
            }
            if ( ( isset( $settings['sandbox_mode'] ) && $settings['sandbox_mode'] == '1') ) {
                delete_transient( $processing_lock_key );
                return $response;
            }
            if ( array_key_exists( 'analytics', $this->plugin_data ) && array_key_exists( 'sent_message_stat', $this->plugin_data['analytics'] ) ) {
                $this->plugin_data['analytics']['sent_message_stat']++;
                update_option( 'wpwfw_plugin_data', $this->plugin_data );
            }
            
            WPWFW_Core()->logger->add( 'app', "`wc-process-order-status` (background) executed with response: " . json_encode( $response ), 'INFO', 'SERVICES' );
            delete_transient( $processing_lock_key );
            return $response;
        }
        else {
            $ar = array( 'status' => 'error', 'message' => 'Unable to send message','order_status' => $status, 'debug' => $debug_data );
            WPWFW_Core()->logger->add( 'app', "`wc-process-order-status` (background) executed with response: " . json_encode( $ar ), 'WARN', 'SERVICES' );
            $this->update_order_meta( $oid, json_encode( $ar ) );
            delete_transient( $processing_lock_key );
            return $ar;
        }

    }

    /**
     * Post order checkout notification for team sales
     */
    public function wc_process_order_checkout( $order_id ) {

        if ( ! $order_id ) {
            // do nothing
            return;
        }

        global $woocommerce;
        $order = new WC_Order( $order_id );
        $oid = is_callable([$order, 'get_id']) ? $order->get_id() : $order->id;
        $status = $order->get_status();

        // Prevent duplicate admin notification sending for the same order
        $admin_processing_lock_key = 'wpwfw_processing_admin_' . $oid;
        if ( get_transient( $admin_processing_lock_key ) ) {
            WPWFW_Core()->logger->add( 'app', "Admin notification for order #$oid is currently being processed, preventing duplicate", 'INFO', 'SERVICES' );
            return;
        }
        
        // Set processing lock for 2 minutes to prevent overlapping executions
        set_transient( $admin_processing_lock_key, true, 120 );
        
        // Check if admin notification was already sent successfully for this order
        $existing_admin_message = $this->get_custom_order_meta( $oid, 'wpwfw_admin_send_message', 'id' );
        if ( !empty( $existing_admin_message ) && WPWFW_Common::is_json( $existing_admin_message ) ) {
            $admin_message_data = json_decode( $existing_admin_message, true );
            // Check if any of the responses were successful
            $has_success = false;
            if ( is_array( $admin_message_data ) ) {
                foreach ( $admin_message_data as $phone => $response ) {
                    if ( isset( $response['status'] ) && $response['status'] === 'success' ) {
                        $has_success = true;
                        break;
                    }
                }
            }
            if ( $has_success ) {
                WPWFW_Core()->logger->add( 'app', "Admin notification already sent successfully for order #$oid, skipping duplicate", 'INFO', 'SERVICES' );
                delete_transient( $admin_processing_lock_key );
                return;
            }
        }

        $settings = WPWFW_Core()->system->get_settings();

        $whitelisted_events = array(
            'all',
            'on-hold',
            'processing',
            'completed'
        ); // Reference

        WPWFW_Core()->logger->add( 'app', "Validating `wc-process-order-checkout` (background)... ($status)", 'INFO', 'SERVICES' );
        /**
         * Prepare list of events
         */
        $template_name = [];
        foreach ( $this->default_status as $id => $default_event ) {
            if ( $default_event == 'received_message' && false ) continue; // disable on-hold events. for now, we allow it
            $template_name[$default_event] = $this->default_template_name[$id];
        }
        $template = $template_name[$status];
        
        if ( !$settings['sales_notification'] || !array_intersect( [$settings['admin_events']], [$template] ) ) {
            delete_transient( $admin_processing_lock_key );
            return;
        }

        WPWFW_Core()->logger->add( 'app', "Executing `wc-process-order-checkout` (background)...", 'INFO', 'SERVICES' );
        /**
         * Team sales number for notification
         * 
         */
        $sales_numbers = ( strpos( $settings['sales_team_phone'], '|' ) ) ? $sales_numbers = explode( "|", $settings['sales_team_phone'] ) : array( $settings['sales_team_phone'] ) ;
        if ( empty( $settings['order_message'] ) ) $settings['order_message'] = WPWFW_Core()->system->get_notification_template( 'order_message' );

        WPWFW_Core()->logger->add( 'app', "Sending to team sales: " . implode( ', ', $sales_numbers ), 'INFO', 'SERVICES' );
        $response = array();
        foreach ($sales_numbers as $number) {
            if ( empty( $number ) ) continue;
            $send_to = $this->wc_process_phone_number( $number, WC()->countries->get_base_country() );
            if ( is_array( $send_to ) ) {
                WPWFW_Core()->logger->add( 'app', "$number is not a valid number", 'WARN', 'SERVICES' );
                continue;
            }

            // Use cached phone validation to improve performance and reduce API calls
            $is_valid_number = $this->wa_check_phone_cached( $send_to );
            if ( $is_valid_number['status'] == 'error' ) {
                $response[$send_to] = $is_valid_number;
                continue;
            }

            // Use phone in $is_valid_number if exists
            $send_to = $is_valid_number['phone'] ?? $send_to;
            $msg = $this->wc_process_order_shortcodes( $settings['order_message'], $order, array( 'custom_shortcodes' => $settings['custom_shortcodes'] ) );
            $img = null;

            $response[$send_to] = $this->wa_send_message( $send_to, ['msg' => $msg, 'img' => $img], $this->wa_get_credentials() );
            
            // Small delay between API calls to avoid rate limiting (background processing allows this)
            usleep( 500000 ); // 0.5 second delay
        }

        WPWFW_Core()->logger->add( 'app', "Event `wc-process-order-checkout` (background) executed successfully...", 'INFO', 'SERVICES' );
        $this->update_order_meta( $oid, json_encode($response), true );
        delete_transient( $admin_processing_lock_key );

    }

    /**
     * Process customer notes
     */
    public function wc_process_notes( $order_id ) {

        global $woocommerce;

        $order = new WC_Order( $order_id );
        $oid = is_callable([$order, 'get_id']) ? $order->get_id() : $order->id;
        $status = is_callable( [$order, 'get_status'] ) ? $order->get_status() : $order->status;

        $settings = WPWFW_Core()->system->get_settings();
        $this->plugin_data = WPWFW_Core()->system->get_plugin_data();

        if ( ! isset( $settings['default_events'] ) ) {
            return;
        }

        // Check if the message has to be sent
        if ( !array_intersect( ['all', 'note_message'], $settings['default_events'] ) ) {
            return;
        }

        WPWFW_Core()->logger->add( 'app', "Executing `wc-process-notes`...", 'INFO', 'SERVICES' );
        $billing_country = is_callable( [ $order, 'get_billing_country' ] ) ? $order->get_billing_country() : $order->billing_country;
        $billing_phone = is_callable( [ $order, 'get_billing_phone' ] ) ? $order->get_billing_phone() : $order->billing_phone;

        // To add: Priority between shipping and billing phone (interchangeable)
        $send_to = $this->wc_process_phone_number( $billing_phone, $billing_country );

        if ( is_array( $send_to ) ) {
            WPWFW_Core()->logger->add( 'app', "Event `wc-process-notes` resulting with " . $send_to[0] . " event...", 'INFO', 'SERVICES' );
            return $this->update_order_meta( $oid, json_encode( array( 'status' => 'error', 'message' => $send_to[0] ) ) );
        }

        $is_valid_number = $this->wa_check_phone( $send_to );
        if ( $is_valid_number['status'] == 'error' ) {
            $is_valid_number_msg = ($is_valid_number['message'] == 'not available') ? 'Not a valid Whatsapp number' : $is_valid_number['message'] ;
            WPWFW_Core()->logger->add( 'app', "Event `wc-process-notes` resulting with $is_valid_number_msg...", 'WARN', 'SERVICES' );
            return $this->update_order_meta( $oid, json_encode( array( 'status' => 'error', 'message' => $is_valid_number_msg ) ) );
        }

        // Use phone in $is_valid_number if exists
        $send_to = $is_valid_number['phone'] ?? $send_to;

        if ( empty( $settings['note_message'] ) ) $settings['note_message'] = WPWFW_Core()->system->get_notification_template( 'note_message' );
        $msg = $this->wc_process_order_shortcodes( $settings['note_message'], $order, array( 'custom_shortcodes' => $settings['custom_shortcodes']) );
        $img = null;

        $response = $this->wa_send_message( $send_to, ['msg' => $msg, 'img' => $img], $this->wa_get_credentials() );

        if ( isset($settings['debug'] ) && $settings['debug'] == '1' ) {
            if ( isset( $settings['debug_email_field'] ) && !empty( $settings['debug_email_field'] ) ) {
                $mail = __( 'Action: New Order Note Event', WPWFW_PLUGIN_DOMAIN_LANGUAGE )."\r\n\r\n";
                $mail .= __( 'Mobile number: ', WPWFW_PLUGIN_DOMAIN_LANGUAGE )."\r\n".$send_to."\r\n\r\n";
                $mail .= __( 'Message: ', WPWFW_PLUGIN_DOMAIN_LANGUAGE )."\r\n".$msg."\r\n\r\n";
                $mail .= __( 'Gateway response: ', WPWFW_PLUGIN_DOMAIN_LANGUAGE )."\r\n".print_r( $response, true );
                wp_mail( $settings['debug_email_field'], '[DEBUG] ' . WPWFW_PLUGIN_SIMPLE_NAME, $mail, 'charset=UTF-8'."\r\n");
            }
        }

        if ( isset( $response['status'] ) && isset( $response['message'] ) ) {
            if ( $response['status'] == 'success' && ( !isset( $settings['sandbox_mode'] ) || $settings['sandbox_mode'] != '1' ) ) {
                if ( array_key_exists( 'analytics', $this->plugin_data ) && array_key_exists( 'sent_message_stat', $this->plugin_data['analytics'] ) ) {
                    $this->plugin_data['analytics']['sent_message_stat']++;
                    update_option( 'wpwfw_plugin_data', $this->plugin_data );
                }
            }
            WPWFW_Core()->logger->add( 'app', "Event `wc-process-notes` executed with response: " . json_encode( $response ), 'INFO', 'SERVICES' );
            return $this->update_order_meta( $oid, json_encode( $response ) );
        }
        else {
            WPWFW_Core()->logger->add( 'app', "Event `wc-process-notes` executed with response: Unable to send message to " . $send_to[0] , 'INFO', 'SERVICES' );
            return $this->update_order_meta( $oid, json_encode( array( 'status' => 'error', 'message' => 'Unable to send message', 'debug' => array( 'phone' => $send_to ) ) ) );
        }


    }

    /**
     * Get credentials
     */
    private static function wa_get_credentials() {

        $settings = WPWFW_Core()->system->get_settings();
        
        return array( 'subdomain' => $settings['subdomain'], 'access_token' => $settings['access_token'], 'instance_id' => $settings['instance_id'] );

    }

    /**
     * Check instance
     */
    public function wa_check_instance() {

        $param = [
            'subdomain' => $_REQUEST['subdomain'],
            'access_token' => $_REQUEST['access_token'],
            'instance_id' => $_REQUEST['instance_id']
        ];
        
        if ( empty( $param['subdomain'] ) || empty( $param['access_token'] ) || empty( $param['instance_id'] ) ) {
            $is_invalid = [];
            foreach ($param as $key => $value) {
                if ( empty( $value ) ) array_push($is_invalid, $key);
            }
            wp_send_json_error( ['message'=>'['.join(",", $is_invalid).'] cannot be empty'] );
            exit();
        }

        if ( !array_key_exists( $param['subdomain'], $this->services_data ) ) {
            wp_send_json_error( array(
            'status' => 'error',
                'message' => 'Invalid host'
            ) );
            exit();
        }
        
        $api = $this->services_data[$param['subdomain']]['core_url'];

        $url = add_query_arg(
            [
                'instance_id' => $_REQUEST['instance_id'],
                'access_token' => $_REQUEST['access_token']
            ], "https://" . $api . "/api/check_instance.php"
        );

        $request = WPWFW_Common::make_wp_requests( $url );

        if ( !is_array( $request['body'] ) ) $request['body'] = ['status' => 'error', 'message' => $request['body']];
        
        else if ( !isset( $request['body']['status'] ) || !isset( $request['body']['message'] ) ) {
            wp_send_json_error( array(
                'status' => 'error',
                'message' => 'Remote server error',
                'data' => $request['body']
            ) );
            exit();
        }

        if ( $request['body']['status'] == 'success' && $request['body']['message'] == 'Success') { 
            wp_send_json_success( array(
                'status' => 'success',
                'message' => 'Success'
            ) );
            exit();
        }
        
        wp_send_json_error( array(
            'status' => 'error',
            'message' => $request['body']['message'],
            'api' => $url
        ) );
        exit();

    }

    /**
     * Disconnect from Wasapbot
     */
    public function ajax_wa_disconnect() {

        WPWFW_Core()->logger->add( 'app', "Deleting instance via ajax request for `ajax_wa_disconnect`", 'INFO', 'SERVICES' );
        $settings = WPWFW_Core()->system->get_settings();
        $settings['subdomain'] = 'app';
        $settings['access_token'] = '';
        $settings['instance_id'] = '';

        WPWFW_Core()->system->save_settings( $settings );

        wp_send_json_success( array(
            'status' => 'success',
            'message' => 'Success'
        ) );
        exit();
    }

    /**
     * Check if WhatsApp number exist
     * @param string $phone Receipient number
     * @param object $credentials Access Token and Instance ID
     * 
     * @return bool
     */
    public function wa_check_phone( $phone, $credentials = [] ) {

        if ( empty( $credentials ) ) $credentials = $this->wa_get_credentials();

        if ( empty( $credentials['subdomain' ] ) || empty( $credentials['access_token' ] ) || empty( $credentials['instance_id' ] ) ) return array(
            'status' => 'error',
            'message' => 'Credentials must be correctly configured first to continue'
        );

        if ( !array_key_exists( $credentials['subdomain'], $this->services_data ) ) return array(
            'status' => 'error',
            'message' => 'Invalid host'
        );

        $node = $this->services_data[$credentials['subdomain']]['core_url'];

        $url = add_query_arg( [
            'access_token'					=> $credentials['access_token'],
            'instance_id'					=> $credentials['instance_id'],
            'number'					    => $phone,
        ], "https://" . $node . "/api/check_phone.php" );

        $args = [
            'method' => 'POST'
        ];

        $request = WPWFW_Common::make_wp_requests( $url, $args );

        $request['body'] = is_array( $request['body'] ) ? array_change_key_case( $request['body'], CASE_LOWER) : $request['body'];

        if ( !is_array( $request['body'] ) ) $request['body'] = ['status' => 'error', 'message' => $request['body']];
        
        else if ( !isset( $request['body']['status'] ) || !isset( $request['body']['message'] ) ) return array(
            'status' => 'error',
            'message' => 'Remote server error',
            'data' => $request['body']
        );

        if ( $request['body']['status'] == 'success' && $request['body']['message'] == 'available') {
            if ( isset( $request['body']['data'] ) && isset( $request['body']['data']['jid'] ) ) {
                // Split @, replace + - and trim jid. If jid is not the same with $phone, use the jid instead
                $jid = trim( str_replace( '+', '', str_replace( '-', '', explode( '@', $request['body']['data']['jid'] )[0] ) ) );
                if ( $jid != $phone ) $phone = $jid;
            } 
            return array(
                'status' => 'success',
                'message' => 'Available',
                'phone' => $phone
            );
        }
        
        return array(
            'status' => 'error',
            'message' => $request['body']['message']
        );
      

    }

    /**
     * Ajax send sample message to user
     */
    public function ajax_wa_send_test_message() {

        check_ajax_referer( 'wpwfw-action-ajax', 'nonce' );
        
        WPWFW_Core()->logger->add( 'app', "Sending message via ajax request for `ajax_wa_send_test_message`", 'INFO', 'SERVICES' );
        $param = [
            'phone' => $_POST['phone'],
            'subdomain' => $_POST['subdomain'],
            'access_token' => $_POST['access_token'],
            'instance_id' => $_POST['instance_id'],
            'msg' => $_POST['msg']
        ];

        $credentials = ['subdomain' => $param['subdomain'], 'access_token' => $param['access_token'], 'instance_id' => $param['instance_id'] ];

        $is_valid = $this->wa_check_phone( $param['phone'], $credentials );
        if ( $is_valid['status'] == 'error' ) {
            WPWFW_Core()->logger->add( 'app', "`ajax_wa_send_test_message` executed with response: " . $is_valid['message'] . " (CHK_PHONE_FAILED)", 'INFO', 'SERVICES' );
            WPWFW_Core()->logger->add( 'app', "`ajax_wa_send_test_message` services: " . json_encode($this->services_data) , 'INFO', 'SERVICES' );
            wp_send_json_error( ['message' => $is_valid['message']], 400 );
            exit();
        }

        // Use the phone in $is_valid if exists
        $param['phone'] = $is_valid['phone'] ?? $param['phone'];

        $response = $this->wa_send_message( $param['phone'], ['msg' => $param['msg']], $credentials );

        if ( $response['status'] == 'error' ) {
            WPWFW_Core()->logger->add( 'app', "`ajax_wa_send_test_message` executed with response: " . $response['message'] . " (SEND_FAILED)", 'INFO', 'SERVICES' );
            wp_send_json_error( ['message' => $response['message']], 400 );
            exit(); 
        }

        WPWFW_Core()->logger->add( 'app', "`ajax_wa_send_test_message` executed successfully", 'INFO', 'SERVICES' );
        wp_send_json_success( ['message' => $response['message']] );
        exit();

    }

    /**
     * Send WhatsApp to user
     * @param string $phone Receipient number
     * @param array $arg Message and optionally Image
     * @param array $credentials Access Token and Instance ID
     * 
     * @return object
     */
    public function wa_send_message( $phone, $arg, $credentials = [] ) {
        
        if ( empty( $credentials ) ) $credentials = $this->wa_get_credentials();

        if ( empty( $credentials['subdomain' ] ) || empty( $credentials['access_token' ] ) || empty( $credentials['instance_id' ] ) ) return array(
            'status' => 'error',
            'message' => 'Credentials must be correctly configured first to continue'
        );

        if ( !array_key_exists( $credentials['subdomain'], $this->services_data ) ) return array(
            'status' => 'error',
            'message' => 'Invalid host'
        );

        $node = $this->services_data[$credentials['subdomain']]['core_url'];

        $message = $arg['msg'] ?? '';
        $attachment = $arg['img'] ?? '';

        if ( empty( $message ) ) return array(
            'status' => 'error',
            'message' => 'Blank message is not allowed!'
        );

        $type = 'text';

        $url = add_query_arg( [
            'access_token'					=> $credentials['access_token'],
            'instance_id'					=> $credentials['instance_id'],
            'type'						    => $type,
            'number'					    => $phone,
            'message'					    => WPWFW_Common::encode_content( $message )
        ], "https://" . $node . "/api/send.php" );

        $request = WPWFW_Common::make_wp_requests( $url );

        if ( !is_array( $request['body'] ) ) $request['body'] = ['status' => 'error', 'message' => $request['body']];
        
        else if ( !isset( $request['body']['status'] ) || !isset( $request['body']['message'] ) ) return array(
            'status' => 'error',
            'message' => 'Remote server error',
            'data' => $request['body']
        );

        return $request['body'];

    }

    /**
     * Ajax resend message (from Dashboard)
     */
    public function ajax_wa_resend_message() {

        check_ajax_referer( 'wpwfw-action-ajax', 'nonce' );

        if ( !isset( $_POST['task'] ) || empty( $_POST['task'] ) || !in_array( $_POST['task'], ['bulk', 'single'] ) ) {
            wp_send_json_error( ['message' => '`Task` is required', 'data' => json_encode( array() )], 400 );
            exit();
        }

        // $sample = ( isset( $_POST['payload'] ) ) ? $_POST['payload'] : [] ;
        // $error_messages = ['Relogin Required', 'Not A Valid WhatsApp Number', 'Unable to send message'];
        // $sample_result = array(
        //     'status' => 'error',
        //     'message' => $error_messages[array_rand($error_messages)],
        //     'row' => $sample[0]['row']
        // );
        // wp_send_json_success( ['message' => "Success", 'data' => array( 'success' => array( ), 'error' => array( $sample_result ) ), 'debug_data' => $sample] );
        // exit();

        if ( !isset( $_POST['payload' ] ) || !is_array( $_POST['payload'] ) ) {
            wp_send_json_error( ['message' => 'A valid `Payload` is required', 'data' => array( 'payload' => $_POST['payload'], 'is_array' => is_array( $_POST['payload'] ) )], 400 );
            exit();
        }

        WPWFW_Core()->logger->add( 'app', "Re-sending message via ajax request for `ajax_wa_resend_message`", 'INFO', 'SERVICES' );
        $tasks = $_POST['payload'];        
        
        $settings = WPWFW_Core()->system->get_settings();

        $results = array(
            'success' => array(),
            'error' => array(),
        );

        foreach ( $tasks as $key => $value ) {

            $task_type = str_replace( '_wpwfw_', '', explode( " ", $value['type'] )[0] );
            $t = array(
                'status' => 'error',
                'message' => "There are no task named `" . $task_type . "`",
                'row' => $value['row'] ?? -1,
            );
            if ( true ) $t['debug_data'] = [$key, $value];
            
            // Type Order
            if ( $task_type == 'order' && $value['row'] != -1 ) {
                $response = $this->wc_process_order_status( $value['id'] );

                if ( !isset( $response['status'] ) || !isset( $response['message'] ) ) {
                    $response = array(
                        'status'    => 'error',
                        'message'   => "Unable to send message for order id `" . $value['id'] . "`"
                    );
                }            
            }

            // Type Follow Up
            if ( strpos( $task_type, 'followup_aftersales') !== false && $value['row'] != -1  ) {
                $response = $this->wc_process_follow_up( $task_type, strtolower( $value['status'] ), $value['id'] );

                if ( !isset( $response['status'] ) || !isset( $response['message'] ) ) {
                    $response = array(
                        'status'    => 'error',
                        'message'   => "Unable to send message for order id `" . $value['id'] . "`"
                    );
                }
            }

            if ( strpos( $task_type, 'cart_abandon_followup' ) !== false && $value['row'] != -1  ) {
                $integration = $settings['cart_abandon_plugin'];
                if ( empty( $integration ) ) {
                    $response = array(
                        'status'    => 'error',
                        'message'   => 'There are no `cart_abandon` integration configured'
                    );
                }
                else {
                    $response = $this->wc_process_abandonment( $integration, $task_type, $value['id'] );

                    if ( !isset( $response['status'] ) || !isset( $response['message'] ) ) {
                        $response = array(
                            'status'    => 'error',
                            'message'   => "Unable to send message for cart id `" . $value['id'] . "` (" . $task_type . ")"
                        );
                    }
                }
            }

            if ( $response['status'] == 'error' || $t['row'] == -1 ) {
                if ( $t['row'] == -1 ) $t['message'] = 'Invalid row id';
                elseif ( isset( $response['message'] ) ) $t['message'] = $response['message'];
                array_push( $results['error'], $t );
                continue;
            }

            array_push( $results['success'], array(
                'status' => 'success',
                'message' => "Success",
                'row' => $t['row'],
            ) );

        }

        $sent_messages = count( $results['success'] ) ?? 0;
        $failed_messages = count( $results['error'] ) ?? 0;
        if ( count($results['success']) == 0 && count($results['error']) == 0 ) {
            WPWFW_Core()->logger->add( 'app', "`ajax_wa_resend_message` executed with an error: There was a problem sending the message(s)", 'WARN', 'SERVICES' );
            wp_send_json_error( ['message' => 'There was a problem sending the message(s)', 'data' => json_encode( $results )], 400 );
            exit();
        }

        WPWFW_Core()->logger->add( 'app', "`ajax_wa_resend_message` executed with response: $sent_messages message(s) sent, $failed_messages message(s) failed", 'INFO', 'SERVICES' );
        wp_send_json_success( ['message' => "Task completed with $sent_messages message(s) sent, $failed_messages message(s) failed", 'data' => json_encode( $results )] );
        exit();

    }

    /**
     * Process Phone Number |
     * Considering PHP PhoneNumberLib Library in the future
     */
    public function wc_process_phone_number( $phone_number, $country, $arg = [] ) {

        // Extract number
        $phone_number = str_replace( ['+', '-'],'', $phone_number );
        // Trim
        $phone_number = trim( $phone_number );

        if ( empty( $country ) ) return $phone_number;

        $country_code = WPWFW_Common::get_country_code( $country );

        if ( empty( $country_code ) ) {
            return array( 'A valid country is required. Given `' . ( empty( $country ) ? 'null' : $country ) . '`' ) ;
        }

        $validate = WPWFW_Common::validate_number( $phone_number, 'LITE', array( 'country_code' => $country_code ) );

        if ( ! isset( $validate['status'] ) ) return array( 'Internal error while processing phone number' );
        if ( ! $validate['status'] ) return array( $validate['response'] );

        return $validate['data']['phone'];

    }

    /**
     * wc_checkout_phone_validation
     */
    public function wc_checkout_phone_validation() {
        // Deprecated: Frontend asset loading now handled by WPWFW_Asset_Manager
        // This method is kept for backward compatibility but does nothing
        // Phone validation assets are now loaded conditionally on checkout pages only
    }



    /**
     * wc_modify_billing_phone
     * Modify `billing_phone` by adding (+) sign if there isn't one
     */
    public function wc_modify_billing_phone($order ) {
        $billing_phone = $order->get_billing_phone();
        if ( ! empty( $billing_phone ) && substr( $billing_phone, 0, 1 ) != '+' ) {
            $order->set_billing_phone( '+' . $billing_phone );
        }
    }


    /**
     * Process order shortcodes
     */
    public function wc_process_order_shortcodes( $message, $order, $arg = [] ) {

        if ( empty( $arg ) ) return $message;

        $custom_shortcodes = isset( $arg['custom_shortcodes'] ) ? explode( "\n", str_replace([
            "\r\n",
            "\r"
        ], "\n", $arg['custom_shortcodes'] ) ) : [];

        $order_id = is_callable([$order, 'get_id']) ? $order->get_id() : $order->id;
        if (WPWFW_Core()->is_wc_hpos_compatible()) {
            $order_meta = WPWFW_Common::get_meta_datas($order->get_data()['meta_data'] ?? []);
        } else {
            $order_meta = get_post_custom( $order_id );
        }

        preg_match_all( "/%(.*?)%/", $message, $shortcodes );

        foreach ( $shortcodes[1] as $shortcode ) {
            $shortcode = strtolower( $shortcode );

            if ( !isset( $order_meta[$shortcode] ) && !in_array( $shortcode, $this->order_metadata ) && !in_array( $shortcode, $this->order_shortcodes ) && !in_array( $shortcode, $custom_shortcodes ) ) {
                continue;
            }

            /**
             * List of shortcodes that require special processing
             */
            $special_shortcode = [
                "order_date" => date_i18n( wc_date_format(), strtotime( $order->get_date_created() ) ), 
                "modified_date" => date_i18n( wc_date_format(), strtotime( $order->get_date_modified() ) ), 
                "shop_url" => get_bloginfo( 'url' ),
                "shop_name" => get_bloginfo( 'name' ),
                "note" => isset( $arg['note'] ) ? $arg['note'] : '', 
                "id" => $order->get_order_number(), 
                "order_product" => '1',
                "order_product_name" => '1',
                "order_total" => $order->get_total(),
                "order_shipping" => $order->get_shipping_total(),
                "order_tax" => $order->get_total_tax(),
                "order_shipping_tax" => $order->get_shipping_tax(),
                "order_discount" => $order->get_discount_total(), 
                "payment_method" => $order->get_payment_method(),
                "payment_method_title" => $order->get_payment_method_title(),
                "shipping_method_title" => $order->get_shipping_method(), 
            ];

            $special_case = [ 'order_product', 'order_product_name'];
            if ( array_key_exists( $shortcode, $special_shortcode ) && !in_array( $shortcode, $special_case ) ) $message = str_replace( "%" . $shortcode . "%", $special_shortcode[$shortcode], $message ); 
            else if ( array_key_exists( $shortcode, $special_shortcode ) && $shortcode == 'order_product' ) {
                // Append cart items
                $items = array();
                $products = $order->get_items();
                foreach ( $products as $product_id => $product ) {
                    if ( isset( $settings['more_product_info'] ) && $settings['more_product_info'] != 1 ) $items[] = $product->get_name() . ' x' . $product->get_quantity();
                    else $items[] = $product->get_name() . ' x' . $product->get_quantity() . ' ( ' . $order->get_currency() . number_format( $product->get_total(), wc_get_price_decimals() ) . ' )';
                }

                $cart_items = implode( ', ', $items);

                $message = str_replace("%".$shortcode."%", $cart_items, $message);
            }
            else if ( array_key_exists( $shortcode, $special_shortcode ) && $shortcode == 'order_product_name' ) {
                // Append cart items
                $items = array();
                $products = $order->get_items();
                foreach ( $products as $product_id => $product ) {
                    $items[] = $product->get_name();
                }

                $cart_items = implode( ', ', $items);

                $message = str_replace("%".$shortcode."%", $cart_items, $message);
            }
            else if ( in_array( $shortcode, $this->order_metadata ) ) $message = str_replace( "%".$shortcode."%", is_callable( [$order, 'get_'.$shortcode] ) ? $order->{'get_'.$shortcode}() : $order->$shortcode, $message );
            else if ( in_array( $shortcode, $this->order_shortcodes ) ) {
                // HPOS and Traditional compatibility for order shortcodes
                $meta_value = $this->get_order_meta_value( $order, $order_meta, $shortcode );
                $message = str_replace( "%".$shortcode."%", $meta_value, $message );
            }
            else if ( isset( $order_meta[$shortcode] ) || in_array( $shortcode, $custom_shortcodes ) ) { // previously $order_meta[$shortcode][0]
                $shortcode_key = $shortcode;
                $is_supported = array_key_exists( $shortcode_key, WPWFW_Core()->integration::$meta_field_variables );                
                if ( $is_supported === true ) $shortcode_key = WPWFW_Core()->integration::$meta_field_variables[$shortcode_key];
                $message = str_replace( "%".$shortcode."%", $this->get_custom_order_meta( $order_meta, $shortcode_key, 'meta' ), $message );
            }
        }

        return $message;

    }

    /**
     * Process follow-ups
     */
    public function wc_process_follow_up( $event, $order_status = 'completed', $order_id = -1 ) {

        global $woocommerce;
        $settings = WPWFW_Core()->system->get_settings();
        $this->plugin_data = WPWFW_Core()->system->get_plugin_data();

        WPWFW_Core()->logger->add( 'app', "Processing `wc-process-follow-up`", 'INFO', 'SERVICES' );
        // Just don't bother to proceed if settings are not properly set
        if ( !isset( $settings[$event . '_delay'] ) || empty( $settings[$event . '_delay'] ) ) return false; // maybe send logs later
        if ( !isset( $settings[$event] ) || empty( $settings[$event] ) ) return false;

        if ( !isset( $settings[$event . '_delay_unit'] ) || empty( $settings[$event . '_delay_unit'] ) ) $settings[$event . '_delay_unit'] = 'hour';

        WPWFW_Core()->logger->add( 'app', "Executing `wc-process-follow-up` for $event...", 'INFO', 'SERVICES' );
        $single_response = array(
            'status' => 'error',
            'message' => 'Unable to send message (' . $order_id . ')'
        );
        if ( !is_numeric( $order_id ) && $order_id != -1 ) {
            WPWFW_Core()->logger->add( 'app', "`wc-process-follow-up` executed with response: Not a valid `order_id`", 'WARN', 'SERVICES' );
            $single_response['message'] = 'Not a valid `order_id`';
            return $single_response;
        }

        // If unit is day, check if delay is greater than 30 days
        // If unit is hour, check if delay is greater than 30 * 24 hours
        // If unit is minute, check if delay is greater than 30 * 24 * 60 minutes
        $last_days = 30;
        if ( $settings[$event . '_delay_unit'] == 'day' && $settings[$event . '_delay'] > $last_days ) {
            $last_days = $settings[$event . '_delay'];
        } else if ( $settings[$event . '_delay_unit'] == 'hour' && $settings[$event . '_delay'] > $last_days * 24 ) {
            $last_days = $settings[$event . '_delay'] / 24;
        } else if ( $settings[$event . '_delay_unit'] == 'min' && $settings[$event . '_delay'] > $last_days * 24 * 60 ) {
            $last_days = $settings[$event . '_delay'] / 24 / 60;
        }

        WPWFW_Core()->logger->add( 'app', "`wc-process-follow-up` getting orders with status `$order_status` for the last $last_days days: " . date( 'Y-m-d', strtotime( "-$last_days days" ) ), 'INFO', 'SERVICES' );
        $results = ( is_numeric( $order_id ) && $order_id != -1 ) ? [ new WC_Order( $order_id ) ] : wc_get_orders( array(
            'limit' => -1,
            'date_after' => date( 'Y-m-d', strtotime( "-$last_days days" ) ),
            'status' => $order_status
        ) );
        WPWFW_Core()->logger->add( 'app', "`wc-process-follow-up` found " . count( $results ) . " orders", 'INFO', 'SERVICES' );

        if ( $order_id != -1 && empty( $results ) ) $single_response['message'] = "There is no order with an id of $order_id";

        if ( isset( $results ) ) {
            foreach ( $results as $orders => $order ) {
                $oid = is_callable([$order, 'get_id']) ? $order->get_id() : $order->id;

                date_default_timezone_set('Asia/Kuala_Lumpur');
                $std_format = 'Y-m-d H:i:s';
                $now = date( $std_format );
                $dt = new DateTime();
                $aging = 1;
                if ( $order_status == 'completed' ) {
                    $date_purchased = date_create( $order->get_date_created()->date( $std_format ) );

                    $date_completed = date_format( date_create( get_post_meta( $oid, '_completed_date', true ) ), $std_format );
                    $date_completed = $dt->createFromFormat( $std_format, $date_completed );

                    // $today_ts = strtotime( $now->format( $std_format ) );
                    $completed_ts = strtotime( $date_completed->format( $std_format ) );
                    $aging = WPWFW_Common::get_time_difference( $date_completed->format( $std_format ), $now, $settings[$event . '_delay_unit'] );
                    // $aging = round( abs( $today_ts - $completed_ts ) / 3600 );
                }

                else {
                    if ( is_numeric( $order_id ) && $order_id != -1 ) $single_response['message'] = 'Only orders with status `Completed` are eligible for After Sale Follow Up notification(s)';
                    continue;
                }

                $event_id = (int) substr($event, -1);
                $previous_event_id = $event_id - 1;
                $previous_event = substr( $event, 0, strlen( $event ) - 1 ) . $previous_event_id;
                $aging_cutoff = (int) $settings[$event . '_delay'];
                
                // FIXED: Check for precise timing window to prevent multiple follow-ups at once
                // For follow-up 1: 1-2 min window, follow-up 2: 2-3 min window, follow-up 3: 3+ min window
                $next_event_id = $event_id + 1;
                $next_event = substr( $event, 0, strlen( $event ) - 1 ) . $next_event_id;
                $max_cutoff = isset( $settings[$next_event . '_delay'] ) ? (int) $settings[$next_event . '_delay'] : PHP_INT_MAX;
                
                // Check if aging is within the specific window for this follow-up
                $in_timing_window = ( $aging >= $aging_cutoff );
                if ( $max_cutoff !== PHP_INT_MAX ) {
                    $in_timing_window = ( $aging >= $aging_cutoff && $aging < $max_cutoff );
                }
                
                if ( $in_timing_window || $order_id != -1 ) {
                    WPWFW_Core()->logger->add( 'app', "`wc-process-follow-up` for #$oid has aged: $aging " . $settings[$event . '_delay_unit'] . '(s) with cutoff of ' . $aging_cutoff . ' ' . $settings[$event . '_delay_unit'] .'(s)', 'INFO', 'SERVICES' );
                    // We check wpwfw_send_message, task does not continue if it was never been notified before
                    if ( !WPWFW_Common::is_json( $this->get_order_meta_hpos_compatible( $oid, 'wpwfw_send_message', true ) ) && $order_id == -1 ) {
                        WPWFW_Core()->logger->add( 'app', "`wpwfw_send_message` sequence must be queued first before executing `$event`", 'WARN', 'SERVICES' );
                        continue;
                    } 
                    
                    // We compare with previous event id, task does not continue if previous sequence is not configured
                    if ( $previous_event_id >= 1 && !WPWFW_Common::is_json( $this->get_order_meta_hpos_compatible( $oid, 'wpwfw_' . $previous_event, true ) ) && $order_id == -1 ) {
                        WPWFW_Core()->logger->add( 'app', "`$previous_event` sequence must be queued first before executing `$event`", 'WARN', 'SERVICES' );
                        continue;
                    } 
                    
                    $event_data = $this->get_order_meta_hpos_compatible( $oid, 'wpwfw_' . $event, true );
                    if ( ( $previous_event_id >= 1 && !empty( $event_data ) && !WPWFW_Common::is_json( $event_data ) && $order_id == -1 ) ) continue;

                    if ( $order_id == -1 ) {
                        $fu_data = json_decode( $event_data, true );
                        if ( ( isset( $fu_data['status'] ) && $fu_data['status'] == 'success' ) || ( isset( $fu_data['status'] ) && $fu_data['status'] == 'error' ) ) continue;
                    }

                    // FIXED: Additional duplicate prevention - check for recent processing to avoid cron overlap
                    $processing_lock_key = 'wpwfw_processing_' . $event . '_' . $oid;
                    if ( get_transient( $processing_lock_key ) && $order_id == -1 ) {
                        WPWFW_Core()->logger->add( 'app', "Follow-up $event for #$oid is currently being processed, skipping duplicate", 'INFO', 'SERVICES' );
                        continue;
                    }
                    
                    // Set processing lock for 5 minutes to prevent overlapping executions
                    set_transient( $processing_lock_key, true, 300 );

                    $result = array(
                        'status' => 'error',
                        'message' => 'Unable to send message'
                    );

                    WPWFW_Core()->logger->add( 'app', "Preparing to send message for `$event`...", 'INFO', 'SERVICES' );
                    $msg = $this->wc_process_order_shortcodes( $settings[$event], $order, array( 'custom_shortcodes' => $settings['custom_shortcodes']) );
                    $img = '';

                    $billing_country = is_callable( [ $order, 'get_billing_country' ] ) ? $order->get_billing_country() : $order->billing_country;
                    $billing_phone = is_callable( [ $order, 'get_billing_phone' ] ) ? $order->get_billing_phone() : $order->billing_phone;

                    $send_to = $this->wc_process_phone_number( $billing_phone, $billing_country );

                    if ( is_array( $send_to ) ) {
                        $result['message'] = $send_to[0] ;
                        $this->update_order_meta( $oid, json_encode($result), false, 'wpwfw_' . $event );
                        if ( is_numeric( $order_id ) && $order_id != -1 ) $single_response['message'] = $result['message'];
                        continue;
                    }
                    
                    $is_valid_number = $this->wa_check_phone( $send_to, $this->wa_get_credentials() );
                    if ( $is_valid_number['status'] == 'error' ) {
                        $result['message'] = ($is_valid_number['message'] == 'not available') ? 'Not a valid WhatsApp number' : $is_valid_number['message'] ;
                        $this->update_order_meta( $oid, json_encode($result), false, 'wpwfw_' . $event );
                        if ( is_numeric( $order_id ) && $order_id != -1 ) $single_response['message'] = $result['message'];
                        continue;
                    }

                    // Use phone in $is_valid_number if it exists
                    $send_to = $is_valid_number['phone'] ?? $send_to;

                    // Check for phone number deduplication if enabled
                    if ( isset( $settings['aftersale_deduplication'] ) && $settings['aftersale_deduplication'] == '1' ) {
                        $deduplication_days = isset( $settings['aftersale_deduplication_days'] ) ? (int) $settings['aftersale_deduplication_days'] : 7;
                        
                        if ( $this->has_phone_received_followup( $send_to, $event, $deduplication_days ) ) {
                            $result = array(
                                'status' => 'success',
                                'message' => 'Skipped (duplicate prevention - phone already received this follow-up)',
                                'duplicate_skipped' => true
                            );
                            $this->update_order_meta( $oid, json_encode($result), false, 'wpwfw_' . $event );
                            if ( is_numeric( $order_id ) && $order_id != -1 ) {
                                $single_response = array(
                                    'status' => 'success',
                                    'message' => 'Skipped due to duplicate prevention',
                                );
                            }
                            WPWFW_Core()->logger->add( 'app', "Follow-up $event skipped for order #$oid - phone $send_to already received this follow-up within $deduplication_days days", 'INFO', 'SERVICES' );
                            continue;
                        }
                    }

                    $response = ( isset( $settings['sandbox_mode'] ) && $settings['sandbox_mode'] == '1') ? array( 'status'=>'success', 'message'=>'Success (Sandbox)' ) : $this->wa_send_message( $send_to, ['msg'=> $msg, 'img'=> $img], $this->wa_get_credentials() );
                    
                    if ( isset($settings['debug'] ) && $settings['debug'] == '1' ) {
                        if ( isset( $settings['debug_email_field'] ) && !empty( $settings['debug_email_field'] ) ) {
                            $mail = __( 'Action: Order', WPWFW_PLUGIN_DOMAIN_LANGUAGE )."\r\n". strtolower( $event ) . " Event" . "\r\n\r\n";
                            $mail .= __( 'Mobile number: ', WPWFW_PLUGIN_DOMAIN_LANGUAGE )."\r\n".$send_to."\r\n\r\n";
                            $mail .= __( 'Message: ', WPWFW_PLUGIN_DOMAIN_LANGUAGE )."\r\n".$msg."\r\n\r\n";
                            $mail .= __( 'Gateway response: ', WPWFW_PLUGIN_DOMAIN_LANGUAGE )."\r\n".print_r( $response, true );
                            wp_mail( $settings['debug_email_field'], '[DEBUG] ' . WPWFW_PLUGIN_SIMPLE_NAME, $mail, 'charset=UTF-8'."\r\n");
                        }
                    }
            
                    if ( isset( $response['status'] ) && isset( $response['message'] ) ) {
                        if ( $response['status'] == 'success' ) {
                            $this->update_order_meta( $oid, json_encode( array( 'status'=>'success', 'message'=>$response['message'] ) ), false, 'wpwfw_' . $event );
                            if ( is_numeric( $order_id ) && $order_id != -1 ) {
                                $single_response = array(
                                    'status' => 'success',
                                    'message' => $response['message'],
                                );
                            }
                            
                            if ( isset( $settings['sandbox_mode'] ) && $settings['sandbox_mode'] == '1') continue;
                            if ( array_key_exists( 'analytics', $this->plugin_data ) && array_key_exists( 'order_follow_up_stat', $this->plugin_data['analytics'] ) ) {
                                $this->plugin_data['analytics']['order_follow_up_stat']++;
                                update_option( 'wpwfw_plugin_data', $this->plugin_data );
                            }
                            continue;
                        }
                    }

                    $result['message'] = $response['message'] ?? 'Unable to send message' ;
                    WPWFW_Core()->logger->add( 'app', "`wc-process-follow-up` for #$oid executed with response: " . $result['message'], 'INFO', 'SERVICES' );
                    $this->update_order_meta( $oid, json_encode($result), false, 'wpwfw_' . $event );

                    if ( is_numeric( $order_id ) && $order_id != -1 ) $single_response['message'] = $response['message'];

                }

                // Clear processing lock after completion (success or failure)
                if ( isset( $processing_lock_key ) ) {
                    delete_transient( $processing_lock_key );
                }

            }

        }

        if ( is_numeric( $order_id ) && $order_id != -1 ) return $single_response;

        return true;

    }

    /**
     * Get order analytics with pagination
     * Uses the same per_page setting as WooCommerce Orders screen
     * 
     * @param int $page Current page number (1-based)
     * @param int $per_page Items per page (from WooCommerce user preference)
     * @return array
     */
    public function wc_get_notified_orders($page = 1, $per_page = 20) {

        $result = array(
            'table' => [],
            'total_sent' => 0,
            'pagination' => array()
        );

        // Calculate offset for pagination
        $offset = ($page - 1) * $per_page;

        $args = array(
            'meta_key'      => 'wpwfw_send_message',
            'meta_compare'  => 'EXISTS',
            'orderby'       => 'date',
            'order'         => 'DESC',
            'limit'         => $per_page,  // ✅ Use pagination instead of loading all
            'offset'        => $offset     // ✅ Skip previous pages
        );
        $orders = wc_get_orders( $args );
        
        // Get total count for pagination info (separate lightweight query)
        global $wpdb;
        $total_orders = $wpdb->get_var("
            SELECT COUNT(DISTINCT post_id) 
            FROM {$wpdb->postmeta} 
            WHERE meta_key = 'wpwfw_send_message'
        ");

        if ( empty($orders) ) {
            return $result;
        }

        $table = array();
        $sent_messages = 0;
        foreach ($orders as $order) {

            $wpwfw_send_message = $order->get_meta('wpwfw_send_message');
            if ( WPWFW_Common::is_json( $wpwfw_send_message ) ) {
                $sent_data = json_decode( $wpwfw_send_message, true );
                if ( isset( $sent_data['status'] ) && $sent_data['status'] == 'success' ) {
                    $sent_messages++;
                    // Don't add successful messages to Failed History table
                    // This table should only show failed messages
                }
                else {
                    // Get the triggering status from stored message data, fallback to current status
                    $triggering_status = isset( $sent_data['order_status'] ) ? $sent_data['order_status'] : ( is_callable( [$order, 'get_status'] ) ? $order->get_status() : $order->status );
                    $type_title = 'Order ' . ucfirst( $triggering_status ) . ' Notification';
                    
                    array_push( $table, array(
                        'date' => date_i18n( wc_date_format(), strtotime( $order->get_date_modified() ) ),
                        'type' => 'order',
                        'type_title' => $type_title,
                        'id' => is_callable([$order, 'get_id']) ? $order->get_id() : $order->id,
                        'order_status' => ucwords( is_callable( [$order, 'get_status'] ) ? $order->get_status() : $order->status ),
                        'number' => is_callable( [ $order, 'get_billing_phone' ] ) ? $order->get_billing_phone() : $order->billing_phone,
                        'status' => ( isset( $sent_data['message'] ) ) ? ucwords( $sent_data['message'] ) : 'Unable to send message',
                        'action' => '<button class="button-primary wpwfw_dash_resend">Resend</button>'
                    ) );
                }
            }

            foreach ( WPWFW_Core()->services->aftersale_events as $event => $event_name ) {
                $wpwfw_aftersales = $order->get_meta('wpwfw_' . $event);
                if ( !WPWFW_Common::is_json( $wpwfw_aftersales ) ) continue;
                $wpwfw_aftersales = json_decode( $wpwfw_aftersales, true );
                if ( isset( $wpwfw_aftersales['status'] ) && $wpwfw_aftersales['status'] == 'success' ) $sent_messages++;
                else {
                    array_push( $table, array(
                        'date' => date_i18n( wc_date_format(), strtotime( $order->get_date_modified() ) ),
                        'type' => $event,
                        'type_title' => 'Follow Up ' . substr($event, -1),
                        'id' => is_callable([$order, 'get_id']) ? $order->get_id() : $order->id,
                        'order_status' => ucwords( is_callable( [$order, 'get_status'] ) ? $order->get_status() : $order->status ),
                        'number' => is_callable( [ $order, 'get_billing_phone' ] ) ? $order->get_billing_phone() : $order->billing_phone,
                        'status' => ( isset( $wpwfw_aftersales['message'] ) ) ? ucwords( str_replace( '_', ' ', $wpwfw_aftersales['message'] ) ) : 'Unable to send message',
                        'action' => '<button class="button-primary wpwfw_dash_resend">Resend</button>'
                    ) );
                }
            }
            
        }

        $result['table'] = ( empty($table) ) ? array( 'error' => 'No log available') : $table;
        $result['total_sent'] = $sent_messages;
        
        // Add pagination information
        $result['pagination'] = array(
            'current_page' => $page,
            'per_page' => $per_page,
            'total_orders' => intval($total_orders),
            'total_pages' => ceil($total_orders / $per_page),
            'showing_from' => $offset + 1,
            'showing_to' => min($offset + $per_page, $total_orders)
        );

        return $result;

    }

    /**
     * Requires: /woo-cart-abandonment-recovery/
     */
    public function wc_process_abandonment( $integration, $event, $cart_session = -1 ) {

        WPWFW_Core()->logger->add( 'app', 'Executing Cart Abandon Recovery...', 'INFO', 'SERVICES' );

        global $wpdb;
        $settings = WPWFW_Core()->system->get_settings();
        $this->plugin_data = WPWFW_Core()->system->get_plugin_data();

        // Just don't bother to proceed if settings are not properly set
        if ( !isset( $settings[$event] ) || !isset( $settings[$event . '_delay'] ) ) return false;
        if ( empty( $settings[$event] ) || empty( $settings[$event . '_delay'] ) ) return false;

        if ( !isset( $settings[$event . '_delay_unit'] ) || empty( $settings[$event . '_delay_unit'] ) ) $settings[$event . '_delay_unit'] = 'hour';

        $single_response = array(
            'status' => 'error',
            'message' => 'Unable to send message'
        );
        if ( is_numeric( $cart_session ) && $cart_session != -1 ) {
            $single_response['message'] = '`cart_session` must not be an integer';
            return $single_response;
        }

        $default_wpwfw_md = serialize( array(
            'cart_abandon_followup_1'=>[],
            'cart_abandon_followup_2'=>[],
            'cart_abandon_followup_3'=>[],
        ) );

        WPWFW_Core()->logger->add( 'app', 'Processing `' . $integration . '` ' . ($cart_session != -1 ? '(Resend)':''), 'INFO', 'SERVICES' );

        $shortcodes = WPWFW_Core()->integration->get_integration_shortcodes( $integration );
        if ( $integration == 'woo-cart-abandonment-recovery' ) {
            $email_history_table = $wpdb->prefix . "cartflows_ca_email_history";
            $cart_abandonment_table = $wpdb->prefix . "cartflows_ca_cart_abandonment";
            $std_format = 'Y-m-d H:i:s';
            
            // phpcs:disable WordPress.DB.PreparedSQL.NotPrepared
            $columns = $wpdb->get_results( "SELECT COLUMN_NAME, COLUMN_DEFAULT FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = '" . $cart_abandonment_table . "' AND column_name = 'wpwfw_data'" );
            if ( empty( $columns ) ) {
                $sql = "ALTER TABLE $cart_abandonment_table ADD COLUMN wpwfw_data LONGTEXT";
                $wpdb->query( $sql );
                if ( $wpdb->last_error !== '' ) {
                    WPWFW_Core()->logger->add( 'error', 'There was an issue while adding column `wpwfw_data` to the table ' . $cart_abandonment_table . ': ' . $wpdb->last_error, 'ERROR', 'SERVICES' );
                    return false;
                }
                $wpdb->query( "UPDATE $cart_abandonment_table SET wpwfw_data = 1");
                if ( $wpdb->last_error !== '' ) {
                    WPWFW_Core()->logger->add( 'error', 'There was an issue while initializing column `wpwfw_data` in the table ' . $cart_abandonment_table . ': ' . $wpdb->last_error, 'ERROR', 'SERVICES' );
                    return false;
                }

                WPWFW_Core()->logger->add( 'app', 'Column `wpwfw_data` was added to the table ' . $cart_abandonment_table, 'INFO', 'SERVICES' );
            } else {
                foreach ( $columns as $column ) {
                    if ( $column->COLUMN_DEFAULT !== null ) {
                        // Update the column_default to null
                        $wpdb->query( "ALTER TABLE " . $cart_abandonment_table . " ALTER COLUMN " . $column->COLUMN_NAME . " DROP DEFAULT" );
                    }
                }
                // @TODO: If column default is already updated, update_option
            }

            $current_time = current_time( $std_format );
            // phpcs:disable WordPress.DB.PreparedSQL.NotPrepared
            $query = "SELECT * FROM " . $cart_abandonment_table . " as CAT 
            WHERE CAT.`order_status` = %s AND CAT.unsubscribed = 0 AND (CAT.wpwfw_data != 1 OR CAT.wpwfw_data IS NULL)";
            $query_group = array(
                'all' => $query,
                'single' => $query . " AND CAT.`session_id` = %s",
            );

            if ( $cart_session != -1 ) {
                $abandoned = $wpdb->get_results(
                    $wpdb->prepare(
                        $query_group['single'],
                        "abandoned",
                        $cart_session,
                    )
                );
            }
            else {
                $abandoned = $wpdb->get_results(
                    $wpdb->prepare(
                        $query_group['all'],
                        "abandoned",
                    )
                );
            }
            

            if ( $cart_session != -1 && empty( $abandoned ) ) $single_response['message'] = "There are no `cart_session` with id of $cart_session";

            $update_table = function ( $wpwfw_md, $session_id ) use ( $wpdb, $cart_abandonment_table ) {
                $wpdb->update(
                    $cart_abandonment_table,
                    array( 'wpwfw_data' => serialize( $wpwfw_md ) ),
                    array( 'session_id' => $session_id )
                );
            };

            foreach ( $abandoned as $data ) {

                $wpwfw_md = maybe_unserialize( $data->wpwfw_data );

                // Only accept new sequence(s) with empty `wpwfw_md`
                if ( !is_array( $wpwfw_md ) && $wpwfw_md == 1 ) continue;

                // For null || column upgrade
                if ( empty( $wpwfw_md ) || !is_array( $wpwfw_md) ) $wpwfw_md = maybe_unserialize( $default_wpwfw_md );

                $event_id = (int) substr($event, -1);
                $previous_event_id = $event_id - 1;
                $previous_event = substr( $event, 0, strlen( $event ) - 1 ) . $previous_event_id;

                if ( $previous_event_id >= 1 && empty( $wpwfw_md[$previous_event]['status'] ) ) {
                    WPWFW_Core()->logger->add( 'app', "`$previous_event` sequence must be queued first before executing `$event`", 'WARN', 'SERVICES' );
                    continue;
                }

                if ( !empty($wpwfw_md[$event]['status']) && $wpwfw_md[$event]['status'] == true ) continue;

                $cart = array_merge(
                    array(
                        'id'            => $data->id,
                        'session_id'    => $data->session_id,
                        'email'         => $data->email,
                        'cart_contents' => maybe_unserialize( $data->cart_contents ),
                        'cart_total'    => $data->cart_total,
                        'time'          => $data->time,
                        'checkout_id'   => $data->checkout_id,
                    ),
                    maybe_unserialize( $data->other_fields ),
                );

                if ( empty( $shortcodes ) ) {
                    WPWFW_Core()->logger->add( 'app', 'There are no shortcodes available for ' . $integration, 'WARN', 'SERVICES' );
                    $wpwfw_md[$event] = array( 'status' => false, 'message' => 'There are no shortcodes available for ' . $integration );
                    $update_table( $wpwfw_md, $data->session_id );
                    
                    if ( !is_numeric( $cart_session ) && $cart_session != -1 ) $single_response['message'] = "There are no shortcodes available for $integration";
                    continue;
                } // Add critical log data here
                
                $msg = $settings[$event];
                $img = '';

                if ( empty( $msg ) ) {
                    if ( !is_numeric( $cart_session ) && $cart_session != -1 ) $single_response['message'] = "Message cannot be empty";
                    continue;
                }
                
                WPWFW_Core()->logger->add( 'app', 'Verifying cart ' . $cart['session_id'] . '...', 'INFO', 'SERVICES' );

                date_default_timezone_set('Asia/Kuala_Lumpur');
                $date_checkout = $cart['time'];
                $date_now = date( $std_format );
                $aging_cutoff = $settings[$event . '_delay'];
                $aging = WPWFW_Common::get_time_difference( $date_checkout , $date_now, $settings[$event . '_delay_unit'] );
                // $aging = round( abs( ( strtotime( $date_now ) - strtotime( $date_checkout ) ) ) / 3600 );

                if ( $aging <= $aging_cutoff ) continue;

                // if the cart is older than 7 days, we don't send follow up
                if ( strtotime($date_now) - strtotime($date_checkout) > 604800 ) continue;

                WPWFW_Core()->logger->add( 'app', '[' . $data->session_id . '] Preparing to send follow up', 'INFO', 'SERVICES' );

                $country = wc_get_base_location()['country'];

                $msg = $this->wc_process_ca_shortcodes( $msg, $cart, $shortcodes );

                $send_to = $this->wc_process_phone_number( $cart[$shortcodes['billing_phone']], $country );

                if ( is_array( $send_to ) ) {
                    // Add log data
                    WPWFW_Core()->logger->add( 'app', '[' . $data->checkout_id . '] Unable to process number ' . $cart[$shortcodes['billing_phone']] . ': ' . $send_to[0], 'WARN', 'SERVICES' );
                    $wpwfw_md[$event] = array( 'status' => false, 'message' => 'Unable to process number ' . $cart[$shortcodes['billing_phone']] . ': ' . $send_to[0] );
                    $update_table( $wpwfw_md, $data->session_id );
                    
                    if ( !is_numeric( $cart_session ) && $cart_session != -1 ) $single_response['message'] = "Unable to process number " . $cart[$shortcodes['billing_phone']];
                    continue;
                }

                $is_valid_number = $this->wa_check_phone( $send_to, $this->wa_get_credentials() );
                if ( $is_valid_number['status'] == 'error' ) {
                    // Add log data
                    $is_valid_number_msg = ($is_valid_number['message'] == 'not available') ? $cart[$shortcodes['billing_phone']] . ' is not a valid number' : $is_valid_number['message'] ;
                    WPWFW_Core()->logger->add( 'app', '[' . $data->checkout_id . '] ' . $is_valid_number_msg , 'WARN', 'SERVICES' );
                    $wpwfw_md[$event] = array( 'status' => false, 'message' => $is_valid_number_msg );
                    $update_table( $wpwfw_md, $data->session_id );
                    
                    if ( !is_numeric( $cart_session ) && $cart_session != -1 ) $single_response['message'] = $is_valid_number_msg;
                    continue;
                }

                // Use phone in $is_valid_number if it exists
                $send_to = $is_valid_number['phone'] ?? $send_to;

                $response = ( isset( $settings['sandbox_mode'] ) && $settings['sandbox_mode'] == '1') ? array( 'status'=>'success', 'message'=>'Success (Sandbox)' ) : $this->wa_send_message( $send_to, ['msg' => $msg, 'img' => $img], $this->wa_get_credentials() );

                if ( isset($settings['debug'] ) && $settings['debug'] == '1' ) {
                    if ( isset( $settings['debug_email_field'] ) && !empty( $settings['debug_email_field'] ) ) {
                        $mail = __( 'Action: Order', WPWFW_PLUGIN_DOMAIN_LANGUAGE )."\r\n". strtolower( $event ) . " Event" . "\r\n\r\n";
                        $mail .= __( 'Mobile number: ', WPWFW_PLUGIN_DOMAIN_LANGUAGE )."\r\n".$send_to."\r\n\r\n";
                        $mail .= __( 'Message: ', WPWFW_PLUGIN_DOMAIN_LANGUAGE )."\r\n".$msg."\r\n\r\n";
                        $mail .= __( 'Gateway response: ', WPWFW_PLUGIN_DOMAIN_LANGUAGE )."\r\n".print_r( $response, true );
                        wp_mail( $settings['debug_email_field'], '[DEBUG] ' . WPWFW_PLUGIN_SIMPLE_NAME, $mail, 'charset=UTF-8'."\r\n");
                    }
                }

                if ( isset( $response['status'] ) && isset( $response['message'] ) ) {
                    if ( $response['status'] != 'success' ) {
                        // Add log data
                        WPWFW_Core()->logger->add( 'app', '[' . $data->checkout_id . '] ' . 'Unable to send message: ' . $response['message'], 'WARN', 'SERVICES' );
                        $wpwfw_md[$event] = array( 'status' => false, 'message' => 'Unable to send message: ' . $response['message'] );
                        $update_table( $wpwfw_md, $data->session_id );
                        
                        if ( !is_numeric( $cart_session ) && $cart_session != -1 ) $single_response['message'] = $response['message'];
                        continue;
                    }
                }

                WPWFW_Core()->logger->add( 'app', 'Event `' . $event . '` response: ' . json_encode($response), 'INFO', 'SERVICES' );
                // Add log data
                
                $wpwfw_md[$event] = array( 'status' => true, 'message' => 'Success' );
                $update_table( $wpwfw_md, $data->session_id );

                $single_response = array(
                    'status' => 'success',
                    'message' => 'Success'
                );

                if ( isset( $settings['sandbox_mode'] ) && $settings['sandbox_mode'] == '1') continue;
                $this->plugin_data['analytics']['cart_follow_up_stat']++;
                update_option( 'wpwfw_plugin_data', $this->plugin_data );
            }

            // Add log data for finishing
            if ( empty( $abandoned ) ) WPWFW_Core()->logger->add( 'app', 'Event `' . $event . '` executed with no abandoned cart...', 'INFO', 'SERVICES' );
            else WPWFW_Core()->logger->add( 'app', 'Event `' . $event . '` successfully executed...', 'INFO', 'SERVICES' );
            
            if ( !is_numeric( $cart_session ) && $cart_session != -1 ) return $single_response;
            return true;
        }

        // Add log data for missing 
        WPWFW_Core()->logger->add( 'app', 'Event `' . $event . '` is not supported yet', 'WARN', 'SERVICES' );


        if ( !is_numeric( $cart_session ) && $cart_session != -1 ) {
            $single_response['message'] = "No event named `$event`";
            return $single_response;
        }
        return false;

    }

    /**
     * Process cart abandonment shortcodes
     */
    public function wc_process_ca_shortcodes( $message, $cart, $shortcodes ) {
        /**
         * List of shortcodes that require special processing
         */
        $special_shortcode = [
            "shop_url" => get_bloginfo( 'url' ),
            "shop_name" => get_bloginfo( 'name' ),
            "checkout_url" => wc_get_checkout_url(),
        ];

        // Todo list: To add more integration, the cart data has to be consistent with its key (product_id, order_product etc...)
        preg_match_all( "/%(.*?)%/", $message, $msg_shortcodes );
        foreach ( $msg_shortcodes[1] as $msg_shortcode ) {
            if ( !array_key_exists( $msg_shortcode, $shortcodes ) ) continue;

            if ( array_key_exists( $msg_shortcode, $special_shortcode ) ) $message = str_replace( "%" . $msg_shortcode . "%", $special_shortcode[$msg_shortcode], $message ); 
            
            else if ( $msg_shortcode == 'order_product' ) {
                if ( empty( $cart[$shortcodes[$msg_shortcode]] ) ) continue;
                $cart_contents = array();
                $cart_data = $cart[$shortcodes[$msg_shortcode]];
                foreach( $cart_data as $cart_items => $cart_item ) {
                    if ( isset( $cart_item['product_id'] ) && isset( $cart_item['quantity'] ) && isset( $cart_item['line_total'] ) ) {
                        $id = $cart_item['product_id'];
                        $product = wc_get_product( $id );
                        $product_name = $product ? $product->get_formatted_name() : '#' . $id;

                        if ( isset( $settings['more_product_info'] ) && $settings['more_product_info'] != 1 ) $cart_contents[] = $product_name . ' x' . $cart_item['quantity'];
                        else $cart_contents[] = $product_name . ' x' . $cart_item['quantity'] . ' ( ' . get_woocommerce_currency_symbol( $cart_item['line_total'] ) .' )' ;

                        $cart_info = implode( ', ', $cart_contents );

                        $message = str_replace( "%$msg_shortcode%", $cart_info, $message);
                    }
                }
            }

            else if ( $msg_shortcode == 'billing_country' || $msg_shortcode == 'shipping_country' ) {
                $parts = explode( ',', $cart[$shortcodes[$msg_shortcode]] );
                $country = $parts[0];

                $message = str_replace( "%$msg_shortcode%", $country, $message);
            }

            else {
                if ( empty( $shortcodes[$msg_shortcode] ) ) continue;
                $message = str_replace( "%$msg_shortcode%", $cart[$shortcodes[$msg_shortcode]], $message );
            }

        }

        return $message;

    }

    /**
     * Requires: /woo-cart-abandonment-recovery/
     */
    public function wc_get_abandoned_orders( $integration ) {

        $result = array(
            'table' => [],
            'total_sent' => 0
        );

        if ( empty( $integration ) ) {
            $result['table']['error'] = 'No log available';
            return $result;
        }

        global $wpdb;

        $std_format = 'Y-m-d H:i:s';
        $debug_data = array();
        $table = array();
        $sent_messages = 0;
        if ( $integration == 'woo-cart-abandonment-recovery' ) {

            $cart_abandonment_table = $wpdb->prefix . "cartflows_ca_cart_abandonment";

            $columns = $wpdb->get_results( "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = '" . $cart_abandonment_table . "' AND column_name = 'wpwfw_data'" );
            if ( !empty( $columns ) ) {
                $cart_data = $wpdb->get_results(
                    $wpdb->prepare(
                        "SELECT * FROM " . $cart_abandonment_table . " as CAT 
                        WHERE CAT.wpwfw_data !=%d",
                        1
                    )
                );

                if ( !empty( $cart_data ) ) {
                    $table['error'] = 'No log available';
                    
                } else {
                    foreach ( $cart_data as $cart_item ) {
    
                        $wpwfw_md = maybe_unserialize( $cart_item->wpwfw_data );
            
                        // For null || column upgrade
                        if ( empty( $wpwfw_md ) || !is_array( $wpwfw_md ) ) continue;
            
                        $item = array_merge(
                            array(
                                'id'            => $cart_item->id,
                                'session_id'    => $cart_item->session_id,
                                'status'        => $cart_item->order_status,
                                'email'         => $cart_item->email,
                                'cart_contents' => maybe_unserialize( $cart_item->cart_contents ),
                                'cart_total'    => $cart_item->cart_total,
                                'time'          => $cart_item->time,
                                'checkout_id'   => $cart_item->checkout_id,
                                'wpwfw_data'    => $wpwfw_md,
                            ),
                            maybe_unserialize( $cart_item->other_fields ),
                        );
            
                        array_push( $debug_data, $item);
                        foreach ( WPWFW_Core()->services->cart_abandon_events[$integration] as $event => $event_name ) {
                            // array_push( $debug_data, $wpwfw_md[$event]);
                            if ( !in_array($event, array_keys($wpwfw_md)) ) continue;
                            if ( !isset($wpwfw_md[$event]['status']) || !isset($wpwfw_md[$event]['message']) ) continue;
                            if ( $wpwfw_md[$event]['status'] == true ) $sent_messages++;
                            if ( $wpwfw_md[$event]['status'] != null ) continue;
            
                            array_push( $table, array(
                                'date' => date_i18n( wc_date_format(), strtotime( $item['time'] ) ),
                                'type_title' => ucwords( str_replace( '_', ' ', $event ) ),
                                'type' => $event,
                                'id' => $item['session_id'],
                                'order_status' => ucwords($item['status']),
                                'number' => $item['wcf_phone_number'],
                                'status' => ( !empty( $wpwfw_md[$event]['message'] ) ) ? ucwords( str_replace( '_', ' ', $wpwfw_md[$event]['message'] ) ) : 'Unable to send message',
                                'action' => '<button class="button-primary wpwfw_dash_resend">Resend</button>'
                            ) );
                        }
                    }
                }
            } else {
                $table['error'] = '`Cart Abandon` plugin initialization is required`';
            }

        } else {
            $table['error'] = 'No active `Cart Abandon` integration';
        }

        $result['table'] = ( empty($table) ) ? array( 'error' => 'No log available') : $table;
        $result['total_sent'] = $sent_messages;
        $result['debug_data'] = $debug_data;

        return $result;

    }

    public function cron_schedule( $schedules ) {
        
        $schedules['every_ten_minutes'] = array(
            'interval' => 600,
            'display' => __( 'Every 10 minutes' ),
        );
        $schedules['every_six_hours'] = array(
            'interval' => 21600,
            'display' => __( 'Every 6 hours' ),
        );
        $schedules['every_half_hours'] = array(
            'interval' => 1800,
            'display' => __( 'Every 30 minutes' ),
        );
        $schedules['every_thirty_minutes'] = array(
            'interval' => 1800,
            'display' => __( 'Every 30 minutes' ),
        );
        return $schedules;

    }

    /**
     * Get dynamic order meta
     */
    public function get_custom_order_meta($input, $key_string, $type = 'id') {
        $separator = '//';
        $meta_key = $key_string;

        if ( empty( $meta_key ) ) return 'null';
    
        // Check if the key string contains the '//' separator
        if (strpos($key_string, $separator) !== false) {
            // Explode the key string and use the first value as the meta key
            $keys = explode($separator, $key_string);
            $meta_key = $keys[0];
        }
    
        // Retrieve the custom order meta value based on the input type
        if ($type === 'id') {
            $order_id = $input;
            if ( WPWFW_Core()->is_wc_hpos_compatible()) {
                $order = wc_get_order($order_id);
                if ( !is_object( $order ) ) return $key_string;
                $meta_value = $order->get_meta($meta_key);
            } else {
                $meta_value = get_post_meta($order_id, $meta_key, true);
            }
        } elseif ($type === 'meta') {
            $order_meta = $input;
            $meta_value = isset($order_meta[$meta_key]) ? maybe_unserialize($order_meta[$meta_key][0]) : $key_string;
        } else {
            return $key_string; // Return null if the input type is not recognized
        }
    
        // If the key string contains the '//' separator, iterate through the keys
        if (isset($keys)) {
            array_shift($keys); // Remove the first key, which is the meta key
    
            // Access the nested values in the meta_value
            foreach ($keys as $key) {
                if (is_numeric($key)) {
                    // Handle array indexing
                    if (is_array($meta_value)) {
                        if ($key == -1) {
                            $meta_value = end($meta_value);
                        } elseif (isset($meta_value[$key])) {
                            $meta_value = $meta_value[$key];
                        } else {
                            return $key_string; // Return null if the key is not found
                        }
                    } else {
                        return $key_string; // Return null if the key is not found
                    }
                } else if (is_array($meta_value) || is_object($meta_value)) {
                    // Handle associative array or object keys
                    if (is_array($meta_value) && isset($meta_value[$key])) {
                        $meta_value = $meta_value[$key];
                    } elseif (is_object($meta_value) && isset($meta_value->$key)) {
                        $meta_value = $meta_value->$key;
                    } else {
                        return $key_string; // Return null if the key is not found
                    }
                } else {
                    return $key_string; // Return null if the key is not found
                }
            }
        }
    
        return $meta_value;
    }   

    /**
     * Update order meta
     */
    public function update_order_meta( $order_id, $data, $is_admin = false, $override_key = '' ) {

        $key = ( $is_admin ) ? 'wpwfw_admin_send_message' : 'wpwfw_send_message';
        if ( !empty( $override_key ) ) $key = $override_key;

        // Enhanced logging for debugging
        WPWFW_Core()->logger->add( 'app', "update_order_meta called for order #$order_id with key '$key' and data: " . substr($data, 0, 200) . "...", 'INFO', 'SERVICES' );

        // Add timestamp to the data if it's JSON
        if ( is_string( $data ) && WPWFW_Common::is_json( $data ) ) {
            $data_array = json_decode( $data, true );
            if ( is_array( $data_array ) ) {
                $data_array['timestamp'] = current_time( 'Y-m-d H:i:s' );
                $data = json_encode( $data_array );
            }
        }

        // Store status-specific log to prevent race conditions
        if ( is_string( $data ) && WPWFW_Common::is_json( $data ) ) {
            $notification_data = json_decode( $data, true );
            if ( isset( $notification_data['order_status'] ) ) {
                $status = $notification_data['order_status'];
                $is_admin_notification = ( $is_admin || strpos( $key, 'admin' ) !== false );
                $this->update_notification_status_log( $order_id, $status, $notification_data, $is_admin_notification );
                WPWFW_Core()->logger->add( 'app', "Status log updated for order #$order_id status '$status' (admin: " . ($is_admin_notification ? 'yes' : 'no') . ")", 'INFO', 'SERVICES' );
            }
        }

        // Store the main data (latest notification - for backward compatibility)
        if ( WPWFW_Core()->is_wc_hpos_compatible() ) {
            $order = wc_get_order( $order_id );
            if ( !is_object( $order ) ) {
                WPWFW_Core()->logger->add( 'app', "Failed to get order object for order #$order_id", 'ERROR', 'SERVICES' );
                return false;
            }
            $order->update_meta_data( $key, $data );
            
            // Store notification history for complete log
            $this->store_notification_history( $order, $data, $key );
            
            $result = $order->save();
            WPWFW_Core()->logger->add( 'app', "Order meta updated for order #$order_id, save result: " . ($result ? 'success' : 'failed'), 'INFO', 'SERVICES' );
        } else {
            if ( !$this->get_order_meta_hpos_compatible( $order_id, $key, true) ) {
                $result = add_post_meta( $order_id, $key, $data );
                WPWFW_Core()->logger->add( 'app', "Added post meta for order #$order_id, result: " . ($result ? 'success' : 'failed'), 'INFO', 'SERVICES' );
            } else {
                $result = update_post_meta( $order_id, $key, $data );
                WPWFW_Core()->logger->add( 'app', "Updated post meta for order #$order_id, result: " . ($result ? 'success' : 'failed'), 'INFO', 'SERVICES' );
            }
            
            // Store notification history for complete log
            $this->store_notification_history_legacy( $order_id, $data, $key );
        }
        
        // Store separate timestamp meta for tracking
        $timestamp_key = '_wpwfw_last_processed';
        if ( !empty( $override_key ) ) {
            $timestamp_key = '_wpwfw_' . str_replace( 'wpwfw_', '', $override_key ) . '_processed';
        }
        
        if ( WPWFW_Core()->is_wc_hpos_compatible() ) {
            $order = wc_get_order( $order_id );
            if ( is_object( $order ) ) {
                $order->update_meta_data( $timestamp_key, current_time( 'Y-m-d H:i:s' ) );
                $order->save();
            }
        } else {
            update_post_meta( $order_id, $timestamp_key, current_time( 'Y-m-d H:i:s' ) );
        }
        
    }

    /**
     * Store notification history for HPOS orders
     * 
     * @param WC_Order $order Order object
     * @param string $data JSON notification data
     * @param string $key Meta key for the notification
     */
    private function store_notification_history( $order, $data, $key ) {
        $history_key = str_replace( 'wpwfw_send_message', 'wpwfw_notification_history', $key );
        
        // Get existing history
        $existing_history = $order->get_meta( $history_key );
        if ( empty( $existing_history ) || !is_array( $existing_history ) ) {
            $existing_history = array();
        }
        
        // Add new notification to history
        if ( WPWFW_Common::is_json( $data ) ) {
            $notification_data = json_decode( $data, true );
            $notification_data['history_id'] = time() . '_' . wp_rand( 1000, 9999 );
            $existing_history[] = $notification_data;
            
            // Keep only last 50 notifications to prevent database bloat
            if ( count( $existing_history ) > 50 ) {
                $existing_history = array_slice( $existing_history, -50 );
            }
            
            $order->update_meta_data( $history_key, $existing_history );
        }
    }

    /**
     * Store notification history for legacy post meta
     * 
     * @param int $order_id Order ID
     * @param string $data JSON notification data
     * @param string $key Meta key for the notification
     */
    private function store_notification_history_legacy( $order_id, $data, $key ) {
        $history_key = str_replace( 'wpwfw_send_message', 'wpwfw_notification_history', $key );
        
        // Get existing history
        $existing_history = get_post_meta( $order_id, $history_key, true );
        if ( empty( $existing_history ) || !is_array( $existing_history ) ) {
            $existing_history = array();
        }
        
        // Add new notification to history
        if ( WPWFW_Common::is_json( $data ) ) {
            $notification_data = json_decode( $data, true );
            $notification_data['history_id'] = time() . '_' . wp_rand( 1000, 9999 );
            $existing_history[] = $notification_data;
            
            // Keep only last 50 notifications to prevent database bloat
            if ( count( $existing_history ) > 50 ) {
                $existing_history = array_slice( $existing_history, -50 );
            }
            
            update_post_meta( $order_id, $history_key, $existing_history );
        }
    }

    /**
     * Check if a message was already sent successfully for an order
     * 
     * @param int $order_id WooCommerce Order ID
     * @param bool $is_admin Whether to check admin message or customer message
     * @return bool True if message was sent successfully, false otherwise
     */
    public function is_message_sent_successfully( $order_id, $is_admin = false ) {
        
        $meta_key = $is_admin ? 'wpwfw_admin_send_message' : 'wpwfw_send_message';
        $existing_message = $this->get_custom_order_meta( $order_id, $meta_key, 'id' );
        
        if ( empty( $existing_message ) || !WPWFW_Common::is_json( $existing_message ) ) {
            return false;
        }
        
        $message_data = json_decode( $existing_message, true );
        
        if ( $is_admin ) {
            // For admin messages, check if any of the responses were successful
            if ( is_array( $message_data ) ) {
                foreach ( $message_data as $phone => $response ) {
                    if ( isset( $response['status'] ) && $response['status'] === 'success' ) {
                        return true;
                    }
                }
            }
            return false;
        } else {
            // For customer messages, check the main status
            return isset( $message_data['status'] ) && $message_data['status'] === 'success';
        }
    }

    /**
     * Get notification status log for an order
     * 
     * @param int $order_id Order ID
     * @param bool $is_admin Whether to get admin status log
     * @return array Status log array
     */
    private function get_notification_status_log( $order_id, $is_admin = false ) {
        $meta_key = $is_admin ? 'wpwfw_admin_status_log' : 'wpwfw_status_log';
        
        if ( WPWFW_Core()->is_wc_hpos_compatible() ) {
            $order = wc_get_order( $order_id );
            if ( !is_object( $order ) ) {
                return array();
            }
            $status_log = $order->get_meta( $meta_key );
        } else {
            $status_log = get_post_meta( $order_id, $meta_key, true );
        }
        
        // Ensure we return an array
        if ( empty( $status_log ) || !is_array( $status_log ) ) {
            return array();
        }
        
        return $status_log;
    }

    /**
     * Update notification status log for an order
     * 
     * @param int $order_id Order ID
     * @param string $status Order status
     * @param array $notification_data Notification result data
     * @param bool $is_admin Whether this is admin notification
     * @return bool Success
     */
    private function update_notification_status_log( $order_id, $status, $notification_data, $is_admin = false ) {
        $meta_key = $is_admin ? 'wpwfw_admin_status_log' : 'wpwfw_status_log';
        
        // Get existing log
        $status_log = $this->get_notification_status_log( $order_id, $is_admin );
        
        // Add timestamp if not present
        if ( !isset( $notification_data['timestamp'] ) ) {
            $notification_data['timestamp'] = current_time( 'Y-m-d H:i:s' );
        }
        
        // Update the specific status entry
        $status_log[$status] = $notification_data;
        
        // Save back to meta
        if ( WPWFW_Core()->is_wc_hpos_compatible() ) {
            $order = wc_get_order( $order_id );
            if ( !is_object( $order ) ) {
                WPWFW_Core()->logger->add( 'app', "Failed to get order object for status log update, order #$order_id", 'ERROR', 'SERVICES' );
                return false;
            }
            $order->update_meta_data( $meta_key, $status_log );
            $result = $order->save();
            WPWFW_Core()->logger->add( 'app', "Status log updated for order #$order_id status '$status', save result: " . ($result ? 'success' : 'failed'), 'INFO', 'SERVICES' );
            return $result;
        } else {
            $result = update_post_meta( $order_id, $meta_key, $status_log );
            WPWFW_Core()->logger->add( 'app', "Status log updated (legacy) for order #$order_id status '$status', result: " . ($result ? 'success' : 'failed'), 'INFO', 'SERVICES' );
            return $result;
        }
    }

    /**
     * Check if notification was already sent for specific status
     * 
     * @param int $order_id Order ID
     * @param string $status Order status
     * @param bool $is_admin Whether to check admin notifications
     * @return bool True if already sent successfully
     */
    private function is_notification_sent_for_status( $order_id, $status, $is_admin = false ) {
        $status_log = $this->get_notification_status_log( $order_id, $is_admin );
        
        if ( isset( $status_log[$status] ) && 
             isset( $status_log[$status]['status'] ) && 
             $status_log[$status]['status'] === 'success' ) {
            return true;
        }
        
        return false;
    }

    /**
     * Display admin notice when available
     */
    public function admin_notice() {
        
        // Resend through shop
        if ( isset( $_GET['post_type'] ) && $_GET['post_type'] == 'shop_order' ) {
            // Resend method
            if ( isset( $_GET['wpwfw_id'] ) ) :
                $post_id = $_GET['wpwfw_id'];
                
                // Check if message was already sent successfully
                if ( $this->is_message_sent_successfully( $post_id, false ) ) {
                    $label = 'notice-info';
                    $msg = 'already sent successfully';
                } else {
                    $result = $this->wc_process_order_status( $post_id );

                    $label = 'notice-warning';
                    $msg = 'failed';

                    if ( !isset( $result['status'] ) || !isset( $result['message'] ) ) $msg = 'was not sent.';
                    elseif ( $result['status'] == 'error' ) $msg = 'failed to send. (Error: ' . $result['message'] . ')';
                    else {
                        $label = 'notice-success';
                        $msg = 'successful';
                    }
                }
            ?>
                <div class="notice <?php echo $label; ?> is-dismissible">
                    <p><?php echo _e( sprintf( 'Resend %s', $msg), WPWFW_PLUGIN_DOMAIN_LANGUAGE ); ?></p>
                </div>
            <?php
            endif;



            if ( isset( $_GET['wpwfw_id'] ) || isset( $_GET['wpwfw_bulk'] ) ) :
                // Remove wpwfw_id and wpwfw_bulk parameters from the URL
                $url = remove_query_arg( array( 'wpwfw_id', 'wpwfw_bulk' ) );
                ?>
                <script>
                    history.replaceState({}, '', '<?php echo esc_url( $url ); ?>');
                </script>
                <?php
            endif;
        }

        // Resend through order page (Traditional and HPOS)
        $is_order_edit_page = ( isset( $_GET['post'] ) && isset( $_GET['action'] ) && $_GET['action'] == 'edit' ) ||
                              ( isset( $_GET['page'] ) && $_GET['page'] == 'wc-orders' && isset( $_GET['action'] ) && $_GET['action'] == 'edit' && isset( $_GET['id'] ) );
        
        if ( $is_order_edit_page ) {
            // Resend method
            if ( isset( $_GET['wpwfw_type'] ) ) :
                // Get order ID from either traditional or HPOS format
                $post_id = isset( $_GET['post'] ) ? $_GET['post'] : ( isset( $_GET['id'] ) ? $_GET['id'] : 0 );
                
                if ( $_GET['wpwfw_type'] == 'order' ) {
                    // Check if customer message was already sent successfully
                    if ( $this->is_message_sent_successfully( $post_id, false ) ) {
                        $label = 'notice-info';
                        $msg = 'already sent successfully';
                    } else {
                        $result = $this->wc_process_order_status( $post_id );
                        $label = 'notice-warning';
                        $msg = 'failed';

                        if ( !isset( $result['status'] ) || !isset( $result['message'] ) ) $msg = 'was not sent.';
                        elseif ( $result['status'] == 'error' ) $msg = 'failed to send. (Error: ' . $result['message'] . ')';
                        else {
                            $label = 'notice-success';
                            $msg = 'successful';
                        }
                    }
                } else if ( in_array( $_GET['wpwfw_type'], array_keys( $this->aftersale_events ) ) ) {
                    $result = $this->wc_process_follow_up( $_GET['wpwfw_type'], 'completed', $post_id );
                    $label = 'notice-warning';
                    $msg = 'failed';

                    if ( !isset( $result['status'] ) || !isset( $result['message'] ) ) $msg = 'was not sent.';
                    elseif ( $result['status'] == 'error' ) $msg = 'failed to send. (Error: ' . $result['message'] . ')';
                    else {
                        $label = 'notice-success';
                        $msg = 'successful';
                    }
                } else {
                    $result = array( 'status' => 'error', 'message' => 'Invalid type' );
                    $label = 'notice-warning';
                    $msg = 'failed';
                }
            ?>
                <div class="notice <?php echo $label; ?> is-dismissible">
                    <p><?php echo _e( sprintf( 'Resend %s', $msg), WPWFW_PLUGIN_DOMAIN_LANGUAGE ); ?></p>
                </div>
            <?php

            // Remove wpwfw_type parameter from the URL
            $url = remove_query_arg( 'wpwfw_type' );
            ?>
            <script>
                // Get the URL from PHP and replace #038; with an empty string using JavaScript
                var currentUrl = '<?php echo esc_js($url); ?>';
                var modifiedUrl = currentUrl.replace(/amp;/g, '');
                history.replaceState({}, '', modifiedUrl);
            </script>
            <?php

            endif;
        }

        // Resend through dashboard
        if ( ( isset( $_GET['page'] ) && !isset( $_GET['path'] ) ) && $_GET['page'] == WPWFW_PLUGIN_CORE_NAME ) {
            if ( isset( $_GET['wpwfw_debug_mode'] ) ) :
                if ( $_GET['wpwfw_debug_mode'] == 'clear_locks' ) {
                    $cleared_locks = $this->clear_processing_locks();
                    $label = 'notice-info';
                    $msg = 'Processing locks cleared: ' . implode( ', ', $cleared_locks );
                ?>
                    <div class="notice <?php echo $label; ?> is-dismissible">
                        <p><?php echo _e( $msg, WPWFW_PLUGIN_DOMAIN_LANGUAGE ); ?></p>
                    </div>
                <?php
                }
            endif;
        }

        // Check for scheduled message notification
        $scheduled_order_data = get_transient( 'wpwfw_message_scheduled_' . get_current_user_id() );
        if ( $scheduled_order_data ) {
            // Delete the transient so the notice only shows once
            delete_transient( 'wpwfw_message_scheduled_' . get_current_user_id() );
            
            // Handle both single order ID and array of order IDs
            if ( is_array( $scheduled_order_data ) ) {
                // Multiple orders (bulk resend)
                $order_count = count( $scheduled_order_data );
                if ( $order_count <= 3 ) {
                    // Show individual order numbers if 3 or fewer
                    $order_list = '#' . implode( ', #', $scheduled_order_data );
                    $message = sprintf( 'Sending WhatsApp messages for Orders %s in 3-5 seconds...', $order_list );
                } else {
                    // Show count if more than 3 orders
                    $message = sprintf( 'Sending WhatsApp messages for %d orders in 3-5 seconds...', $order_count );
                }
            } else {
                // Single order (individual resend)
                $message = sprintf( 'Sending WhatsApp message for Order #%s in 3-5 seconds...', $scheduled_order_data );
            }
            ?>
            <div class="notice notice-info is-dismissible" style="background-color: #00a32a; border-left-color: #00a32a;">
                <p style="color: white; font-weight: 500;">
                    <span class="dashicons dashicons-smartphone" style="color: #25d366; margin-right: 5px;"></span>
                    <?php echo _e( $message, WPWFW_PLUGIN_DOMAIN_LANGUAGE ); ?>
                </p>
            </div>
            <script>
                // Auto-dismiss the notification after 6 seconds
                setTimeout(function() {
                    jQuery('.notice:contains("Sending WhatsApp")').fadeOut();
                }, 6000);
            </script>
            <?php
        }

    }

    /**
     * Check if phone number already received follow-up within timeframe
     * Prevents duplicate follow-up messages to same customer
     */
    private function has_phone_received_followup( $phone, $event, $days_ago = 7 ) {
        if ( empty( $phone ) || empty( $event ) ) {
            return false;
        }

        // Normalize phone number (remove spaces, dashes, etc.)
        $phone = preg_replace( '/[^0-9+]/', '', $phone );
        
        // Get orders from the last X days with this phone number
        $date_from = date( 'Y-m-d', strtotime( "-{$days_ago} days" ) );
        
        try {
            $orders = wc_get_orders( array(
                'limit' => -1,
                'date_created' => '>=' . $date_from,
                'meta_query' => array(
                    array(
                        'key' => '_billing_phone',
                        'value' => $phone,
                        'compare' => '='
                    )
                ),
                'return' => 'ids' // Only get IDs for performance
            ) );

            if ( empty( $orders ) ) {
                return false;
            }

            // Check if any of these orders already received this follow-up successfully
            foreach ( $orders as $order_id ) {
                $followup_data = $this->get_order_meta_hpos_compatible( $order_id, 'wpwfw_' . $event, true );
                
                if ( ! empty( $followup_data ) && WPWFW_Common::is_json( $followup_data ) ) {
                    $followup_result = json_decode( $followup_data, true );
                    if ( isset( $followup_result['status'] ) && $followup_result['status'] === 'success' ) {
                        WPWFW_Core()->logger->add( 'app', "Phone $phone already received follow-up $event within last $days_ago days (order #$order_id)", 'INFO', 'SERVICES' );
                        return true;
                    }
                }
            }

        } catch ( Exception $e ) {
            WPWFW_Core()->logger->add( 'app', "Error checking follow-up deduplication: " . $e->getMessage(), 'ERROR', 'SERVICES' );
            return false; // On error, allow sending (fail-safe)
        }

        return false;
    }

    /**
     * Get order meta with HPOS compatibility
     * Works for both WooCommerce orders and plugin-specific meta
     */
    private function get_order_meta_hpos_compatible( $order_id, $meta_key, $single = true ) {
        if ( WPWFW_Core()->is_wc_hpos_compatible() ) {
            $order = is_object( $order_id ) ? $order_id : wc_get_order( $order_id );
            if ( ! $order ) return $single ? '' : array();
            return $order->get_meta( $meta_key, $single );
        } else {
            return get_post_meta( $order_id, $meta_key, $single );
        }
    }

    /**
     * Get order meta value with HPOS compatibility
     * Handles both traditional (with underscore prefix) and HPOS (without prefix) meta keys
     */
    private function get_order_meta_value( $order, $order_meta, $shortcode ) {
        // First try WooCommerce's built-in getter method (most reliable)
        $getter_method = 'get_' . $shortcode;
        if ( is_callable( [$order, $getter_method] ) ) {
            return $order->$getter_method();
        }
        
        // Try WooCommerce's get_meta method (HPOS compatible)
        $meta_value = $order->get_meta( $shortcode );
        if ( ! empty( $meta_value ) ) {
            return $meta_value;
        }
        
        // For billing/shipping fields, try with underscore prefix
        $meta_value = $order->get_meta( '_' . $shortcode );
        if ( ! empty( $meta_value ) ) {
            return $meta_value;
        }
        
        // Fallback to direct meta array access
        if ( WPWFW_Core()->is_wc_hpos_compatible() ) {
            // HPOS: Try both with and without underscore
            if ( isset( $order_meta[$shortcode] ) && isset( $order_meta[$shortcode][0] ) ) {
                return $order_meta[$shortcode][0];
            }
            if ( isset( $order_meta['_' . $shortcode] ) && isset( $order_meta['_' . $shortcode][0] ) ) {
                return $order_meta['_' . $shortcode][0];
            }
        } else {
            // Traditional: Use underscore prefix
            if ( isset( $order_meta['_' . $shortcode] ) && isset( $order_meta['_' . $shortcode][0] ) ) {
                return $order_meta['_' . $shortcode][0];
            }
        }
        
        return '';
    }

    /**
     * Clear stuck processing locks for debugging
     * This method can be used to clear any transient locks that might be stuck
     * 
     * @param int $order_id Optional order ID to clear specific locks
     * @return array Array of cleared locks
     */
    public function clear_processing_locks( $order_id = null ) {
        
        global $wpdb;
        $cleared_locks = array();
        
        if ( $order_id ) {
            // Clear specific order locks
            $lock_patterns = array(
                'wpwfw_scheduled_' . $order_id . '_%', // Updated to handle status-specific scheduling locks
                'wpwfw_processing_status_' . $order_id . '_%',
                'wpwfw_processing_admin_' . $order_id,
                'wpwfw_processing_%_' . $order_id
            );
            
            foreach ( $lock_patterns as $pattern ) {
                $deleted = $wpdb->query( $wpdb->prepare( 
                    "DELETE FROM {$wpdb->options} WHERE option_name LIKE %s AND option_name LIKE '_transient_%'", 
                    $pattern 
                ) );
                if ( $deleted > 0 ) {
                    $cleared_locks[] = $pattern;
                }
            }
        } else {
            // Clear all WasapBot processing locks
            $deleted = $wpdb->query( 
                "DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_wpwfw_%' AND option_name LIKE '%processing%'" 
            );
            if ( $deleted > 0 ) {
                $cleared_locks[] = "Cleared $deleted processing locks";
            }
        }
        
        WPWFW_Core()->logger->add( 'app', "Cleared processing locks: " . implode( ', ', $cleared_locks ), 'INFO', 'SERVICES' );
        return $cleared_locks;
    }

    /**
     * Fetch queued WhatsApp messages from Action Scheduler
     * Returns array of queue rows for dashboard table
     */
    public function get_queued_messages() {
        $rows = array();
        if ( function_exists( 'as_get_scheduled_actions' ) ) {
            $actions = as_get_scheduled_actions( array(
                'hook' => 'wpwfw_process_customer_notification',
                'status' => array( 'pending', 'in-progress' ),
                'per_page' => 100
            ) );
            foreach ( $actions as $action_id => $action ) {
                if ( ! $action ) continue;
                $args = $action->get_args();
                $order_id = isset( $args[0] ) ? $args[0] : null;
                $order = $order_id ? wc_get_order( $order_id ) : null;
                $customer = $order ? $order->get_billing_first_name() . ' ' . $order->get_billing_last_name() : '-';
                $phone = $order ? $order->get_billing_phone() : '-';
                $msg_preview = $order ? 'Hi ' . $order->get_billing_first_name() . ', your order is being processed...' : '-';
                $queued_at = (method_exists($action, 'get_schedule') && $action->get_schedule() && $action->get_schedule()->next())
                    ? $action->get_schedule()->next()->format('Y-m-d H:i:s')
                    : '-';
                $status = (method_exists($action, 'is_finished') && $action->is_finished()) ? 'Finished' : 'Pending';
                $rows[] = array(
                    'queue_id' => $action_id,
                    'order_id' => $order_id,
                    'customer' => $customer,
                    'phone' => $phone,
                    'msg_preview' => $msg_preview,
                    'queued_at' => $queued_at,
                    'status' => $status,
                );
            }
        }
        return $rows;
    }

    /**
     * Cancel a single scheduled WhatsApp queue action by ID
     * @param int $action_id
     * @return bool
     */
    public function cancel_queue_action( $action_id ) {
        check_ajax_referer( 'wpwfw-dashboard', 'nonce' );
        $action_id = (empty( $action_id ) ? (isset($_POST['actionId']) ? intval($_POST['actionId']) : null) : intval($action_id));
        $ajax = (isset($_POST['isAjax']) && $_POST['isAjax'] == 'true') ? true : false;
        if ( empty( $action_id ) ) {
            if ( $ajax ) {
                wp_send_json_error( [ 'message' => 'Invalid action ID.' ] );
                exit();
            }
            return false;
        }
        if ( !empty( $action_id ) ) {
            $store = null;
            if ( class_exists('ActionScheduler') && method_exists('ActionScheduler', 'store') ) {
                $store = ActionScheduler::store();
            } elseif ( class_exists('ActionScheduler_DBStore') ) {
                $store = new ActionScheduler_DBStore();
            }
            if ( $store && method_exists($store, 'cancel_action') ) {
                $store->cancel_action( $action_id );
                if ( $ajax ) {
                    wp_send_json_success( [ 'message' => 'Action cancelled successfully.' ] );
                    exit();
                }
                return true;
            }
        }
        if ( $ajax ) {
            wp_send_json_error( [ 'message' => 'Invalid action ID or Action Scheduler not available.' ] );
            exit();
        }
        return false;
    }

    /**
     * Cancel all queued WhatsApp actions (bulk clear)
     * @return array Cancelled action IDs
     */
    public function cancel_all_queue_actions() {
        check_ajax_referer( 'wpwfw-dashboard', 'nonce' );
        $ajax = (isset($_POST['isAjax']) && $_POST['isAjax'] == 'true') ? true : false;
        $cancelled = array();
        $queues = $this->get_queued_messages();
        if ( !empty( $queues ) ) {
            foreach ( $queues as $row ) {
                $action_id = $row['queue_id'];
                if ( $this->cancel_queue_action( $action_id ) ) {
                    $cancelled[] = $action_id;
                }
            }
        }
        if ( $ajax ) {
            if ( !empty( $cancelled ) ) {
                wp_send_json_success( [ 'message' => 'All actions cancelled successfully.', 'cancelled' => $cancelled ] );
                exit();
            } else {
                wp_send_json_error( [ 'message' => 'No actions were cancelled.' ] );
                exit();
            }
        }
        // Return the list of cancelled actions
        return $cancelled;
    }

}

if ( class_exists( 'WPWFW_Core' ) ) {
    WPWFW_Core::register( 'services', 'WPWFW_Service' );
}
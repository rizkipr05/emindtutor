<?php

defined( 'ABSPATH' ) or die( 'I feel very small...' );

/**
 * Class UI
 */

class WPWFW_UI {

    private static $_instance = null;
    public $settings = null;
    public $assets_url = null;
    public $assets_remote_url = null;
    public $page_tab = null;
    public $faq_accordion = array();

    public $control_accordion = array();
    public $custom_accordion = array();
    public $form_required_fields = array();
    private $dashboard = null;

    private $override_config =  null;

    public function __construct() {

        $this->settings = WPWFW_Core()->system->get_settings();
        $this->assets_url = WPWFW_PLUGIN_URL . '/assets';
        $this->assets_remote_url = WPWFW_PLUGIN_REMOTE_URL . '/wpwfw';

        if ( isset( WPWFW_CONFIG_OVERRIDE['WPWFW_CSS_OVERRIDE'] ) && !empty( WPWFW_CONFIG_OVERRIDE['WPWFW_CSS_OVERRIDE'] ) ) $this->override_config = WPWFW_CONFIG_OVERRIDE['WPWFW_CSS_OVERRIDE'];

        $this->faq_accordion = array(
            array(
                'title' => '<i class="card-title fa fa-spinner" aria-hidden="true"></i> Customer did not receive any order notification',
                'content' => '
                <div class="panel-content">
                    <p><strong>Check Status Error Message in your Order</strong></p>
                    
                    <ul>
                    <li>From WooCommerce Menu, Click <strong>Orders</strong>.</li>
                    </ul>
                    
                    <figure class="wp-block-image"><img src="'. WPWFW_Core()->system->get_plugin_assets("/img/sc-no-notification-1.png") . '" alt="" width="698" height="383"/></figure>
                    
                    <ul>
                    <li>View any order that requires your attention. You can quickly look for notification status indicator in `' . WPWFW_PLUGIN_SIMPLE_NAME . '` Column (2) </li>
                    </ul>
                    
                    <figure class="wp-block-image is-resized"><img src="'. WPWFW_Core()->system->get_plugin_assets("/img/sc-no-notification-2.png") . '" alt="" width="698" height="239"/></figure>
                    
                    <p>There are three (3) status indicator of order notification which are as follows:</p>
                    
                    <div class="img-text-container">
                        <figure class="wp-block-image"><img src="'. WPWFW_Core()->system->get_plugin_assets("/img/sc-no-notification-3.png") . '" alt=""/></figure>
                        
                        <p>✔️ Message sent</p>
                    </div>
                    
                    <div class="img-text-container">
                        <figure class="wp-block-image"><img src="'. WPWFW_Core()->system->get_plugin_assets("/img/sc-no-notification-4.png") . '" alt=""/></figure>
                        
                        <p>🔁 Not sent yet</p>
                    </div>
                    
                    <div class="img-text-container">
                        <figure class="wp-block-image"><img src="'. WPWFW_Core()->system->get_plugin_assets("/img/sc-no-notification-5.png") . '" alt=""/></figure>
                        
                        <p>❌ Message failed</p>
                    </div>

                    <ul>
                    <li>In a particular order, you can look for the error message in the Custom Field with metadata `wpwfw_send_message`. Please make sure that the error given was corrected before retrying.</li>
                    </ul>
                    
                    <figure class="wp-block-image is-resized"><img src="'. WPWFW_Core()->system->get_plugin_assets("/img/sc-no-notification-6.png") . '" alt="" width="698" height="272"/></figure>
                    
                    <figure class="wp-block-image is-resized"><img src="'. WPWFW_Core()->system->get_plugin_assets("/img/sc-no-notification-7.png") . '" alt="" width="699" height="297"/></figure>
                </div>
                '
            ),
            array(
                'title' => '<i class="card-title fa fa-ban" aria-hidden="true"></i> My number has been banned',
                'content' => '
                <div class="panel-content">
                    <p>You need to send an email to the WhatsApp support for verification of the banned number. You can follow the steps below:</p>
                    
                    <ul>
                    <li>In your WhatsApp app, click<strong> `Support` </strong>to initiate conversation with WhatsApp support</li>
                    
                    <li>Fill in the <strong>Describe your problem </strong> field with "My account has been banned please assist me on this" and click <strong>Next</strong>.</li>
                    
                    <li>Click <strong>This doesn\'t answer my question</strong> option.</li>
                    
                    <li>A request form will be auto generated in your email. Klik the <strong>email</strong> icon to send it. Wait for response within 24 hours.</li>
                    </ul>
                </div>
                '
            ),
        );



        $this->control_accordion = array(
            array(
                'title' => '<i class="fas fa-undo-alt"></i> Relogin Instance',
                'content' => '
                <div class="panel-content">
                    <p>(a) Keeping your old Instance Id:</p>
                    
                    <ul>
                    <li>On your <strong>Whatsapp &gt; Linked Devices</strong>, Logout one (1) or all linked devices in your phone.</li>
                    
                    <li>Click <strong>Add Device</strong> and select <strong>Relogin</strong> in your ' . WPWFW_PLUGIN_SIMPLE_NAME . ' dashboard</li>
                    
                    <li>Scan the <strong>QR code</strong> with your phone to connect to ' . WPWFW_PLUGIN_SIMPLE_NAME . ' using the same Instance Id.</li>
                    </ul>
                    
                    <figure class="wp-block-image is-resized"><img src="'. WPWFW_Core()->system->get_plugin_assets("/img/cn-relogin-instance-1.png") . '" alt="" width="657" height="302"/></figure>
                    
                    <p>(b) Scan with a new Instance Id:</p>
                    
                    <ul>
                    <li>Navigate to <strong>Account Manager</strong> in your dasboard, you may tick <strong>Whatsapp Account</strong> to remove and then click the `Trash` icon.</li>
                    </ul>
                    
                    <figure class="wp-block-image is-resized"><img src="'. WPWFW_Core()->system->get_plugin_assets("/img/cn-relogin-instance-2.jpeg") . '" alt="" width="586" height="271"/></figure>
                    
                    <ul>
                    <li>Then you can follow the steps in (a) to connect your WhatsApp again.</li>
                    </ul>
                </div>
                '
            ),
        );

        $this->form_required_fields = array(
            'instance_id', 'access_token',
        );

    }

    public static function get_instance() {
        
        if ( null === self::$_instance ) {
            self::$_instance = new self();
        }

        return self::$_instance;

    }

    public function render( $page ) {

        if ( empty( $page ) ) {
            echo '<div class="wpwf-ui-element-danger"><b>Unable to render page with empty arguments '. $page . '</b></div>';
            return;
        }

        if ( ! method_exists( $this, $page ) ) {
            echo '<div class="wpwf-ui-element-danger"><b>Failed to render page '. $page . '</b></div>';
            return;
        }

        $this->{$page}();

    }

    public function page_navigation() {

        ?>
            <ul>
                <li class="sidebar-active sidebar-div">
                    <a href="#/" id="notification">
                        <i class="fas fa-bullhorn" aria-hidden="true"></i>
                        <div>Notification</div>
                    </a>
                </li>
                <li class="sidebar-div">
                    <a href="#/" id="support">
                        <i class="fa fa-circle-info"></i>
                        <div>Support Center</div>
                    </a>
                </li>
                <li>
                    <a href="#/" id="connection">
                        <i class="fa fa-share-nodes"></i>
                        <div>Connection</div>
                    </a>
                </li>
                <li>
                    <a href="#/" id="general">
                        <i class="fa fa-sliders"></i>
                        <div>General</div>
                    </a>
                </li>
                <li>
                    <a href="#/" id="advanced">
                        <i class="fa fa-gear"></i>
                        <div>Advanced</div>
                    </a>
                </li>
            </ul>
        <?php

    }

    public function page_header() {

        ?>
            <div class="wpwfw-logo">
                <img src="<?php echo WPWFW_Core()->system->get_plugin_icon( 'top-nav-simple' ); ?>" style="width:55px; height:45px;" alt="">
                <div class="wpwfw-logo-title">
                    WasapBot
                    <span class="wpwfw-version">
                        V<?php echo WPWFW_VERSION; ?> <!-- Updated to 3.0.18 -->
                    </span>
                </div>
            </div>
            <div class="menu">
                <a href="<?php echo WPWFW_PLUGIN_GUIDE . '/collections/8-woocommerce'; ?>" target="_blank">Tutorial</a>
                <div class="menu-right"></div>
            </div>
            <div class="btn-topbar">
                <a href="<?php echo WPWFW_PLUGIN_DASHBOARD; ?>" target="_blank">Dashboard</a>
            </div>
        <?php

    }

    public function dashboard_page() {

        $this->settings_page_css();

        ?>
            <div class="wrap woocommerce wasapbot">
                <div class="container" style="max-width:100%;">
                    <div class="topbar"><?php $this->page_header(); ?></div>
                    <div class="main">
                        <div class="content">
                            <div class="card">
                                <?php $this->component( 'dashboard_home' ); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php

    }

    public function admin_page() {

        // Sync integrations
        WPWFW_Core()->services->sync_integrations();

        $this->settings = WPWFW_Core()->system->get_settings();

        $this->page_tab = 0;

        $this->settings_page_css();

        ?>
        <div class="wrap woocommerce wasapbot">
            <form id="form-settings" action="options.php" method="post">
                <?php settings_fields( 'wpwfw_settings_group' ); ?>
                <div class="container" style="max-width:100%;">
                    <div class="topbar"><?php $this->page_header(); ?></div>
                    <div class="main">
                        <div class="sidebar"><?php $this->page_navigation(); ?></div>
                        <div class="content">
                            <div class="card">
                                <?php
                                    settings_errors();
                                ?>
                                <h3><i class="card-title fas fa-bullhorn" aria-hidden="true"></i>Notification</h3>
                                <?php $this->component( 'settings_page_notification' ); ?>
                                <?php $this->component( 'settings_page_support' ); ?>
                                <?php $this->component( 'settings_page_connection' ); ?>
                                <?php $this->component( 'settings_page_general' ); ?>
                                <?php $this->component( 'settings_page_advanced' ); ?>
                                <input class="button-primary form-submit" type="submit" value="<?php _e( 'Save', WPWFW_PLUGIN_DOMAIN_LANGUAGE ); ?>" name="save" id="submit"/>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <?php
        $this->settings_page_js();

    }

    public function component( $component ) {
        
        if ( !method_exists( $this, $component ) ) echo '<div class="wpwf-ui-element-danger"><b>Failed to build component '. $component . '</b></div>';
        $this->{$component}();

    }

    public function fragment_accordion( $fragment ) {

        if ( !property_exists( $this, $fragment . '_accordion' ) ) echo '<div class="wpwf-ui-element-danger"><b>Failed to build fragment '. $fragment . '</b></div>';
        
        $fragment = $fragment . '_accordion';
        foreach( $this->{$fragment} as $item ) :
            ?>
                <button class="accordion" style="padding-top:10px;"><?php _e( $item['title'], WPWFW_PLUGIN_DOMAIN_LANGUAGE ); ?></button>
                <div class="accordion-panel" style="padding-top:10px;margin-bottom:10px;"><?php echo $item['content']; ?></div>
            <?php
        endforeach;

    }

    private function dashboard_home() {

        $this->dashboard = new WPWFW_Dashboard();
        $this->dashboard->prepare();

        ?>
            <div class="dashboard-header"><i class="fa fa-pie-chart" aria-hidden="true"></i><?php _e( 'Dashboard', WPWFW_PLUGIN_DOMAIN_LANGUAGE); ?></div>

            <div class="dashboard-content">
                <h3><i class="fa fa-area-chart" aria-hidden="true"></i>Statistics</h3>

                <div class="dashboard-tiles">
                    <div role="menu" aria-orientation="horizontal">
                        <ul class="tiles-summary">
                            <li class="tiles-summary-container message-sent-stat">
                                <div class="tiles-summary-item">
                                    <div class="summary-item-component">
                                        <div class="summary-item-content">
                                            <div class="summary-item-label">Messages Sent</div>
                                            <div class="summary-item-data"><?php _e( $this->dashboard->page_data['message_all_time_stats'] ); ?></div>
                                        </div>
                                        <div class="summary-item-icon"><i class="fa-brands fa-whatsapp" aria-hidden="true"></i></div>
                                    </div>
                                </div>
                            </li>
                            <li class="tiles-summary-container order-sent-stat">
                                <div class="tiles-summary-item">
                                    <div class="summary-item-component">
                                        <div class="summary-item-content">
                                            <div class="summary-item-label">Order Notified</div>
                                            <div class="summary-item-data"><?php _e( $this->dashboard->page_data['order_sent_stats'] ); ?></div>
                                        </div>
                                        <div class="summary-item-icon"><i class="fa fa-bullhorn" aria-hidden="true"></i></div>
                                    </div>
                                </div>
                            </li>
                            <li class="tiles-summary-container cart-sent-stat">
                                <div class="tiles-summary-item">
                                    <div class="summary-item-component">
                                        <div class="summary-item-content">
                                            <div class="summary-item-label">Cart Reminded</div>
                                            <div class="summary-item-data"><?php _e( $this->dashboard->page_data['cart_abandon_sent_stats'] ); ?></div>
                                        </div>
                                        <div class="summary-item-icon"><i class="fa fa-shopping-cart" aria-hidden="true"></i></div>
                                    </div>
                                </div>
                            </li>
                            <li class="tiles-summary-container queue-stat">
                                <div class="tiles-summary-item">
                                    <div class="summary-item-component">
                                        <div class="summary-item-content">
                                            <div class="summary-item-label">Queued Messages</div>
                                            <div class="summary-item-data"><?php _e( $this->dashboard->page_data['queued_message_count'] ); ?></div>
                                        </div>
                                        <div class="summary-item-icon"><i class="fa-brands fa-whatsapp" aria-hidden="true"></i></div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="wpwfw-queue-header" style="display: flex; align-items: baseline; justify-content: space-between; margin-bottom: 10px;">
                    <h3 style="margin: 0;"><i class="fa-brands fa-whatsapp fa-lg" aria-hidden="true"></i>Queued WhatsApp Messages</h3>
                    <button class="button wpwfw-clear-all-queues" type="button" style="background:#ff9800;color:#fff;min-width: 115px;vertical-align: middle;">
                        <i class="fa fa-trash" style="padding-right: 5px;"></i> Cancel All
                    </button>
                </div>
                <div class="form-wrapper">
                    <table class="wp-list-table widefat fixed striped table-view-list posts table-queue-logs" style="margin:10px 0;">
                        <thead>
                            <tr>
                                <td class="check-column"><input type="checkbox" class="wpwfw_dash_select_all queue-data"></td>
                                <th>Order ID</th>
                                <th>Customer</th>
                                <th>Phone</th>
                                <th>Message Preview</th>
                                <th>Queued At</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $queue_rows = WPWFW_Core()->services->get_queued_messages();
                            if ( empty( $queue_rows ) ) : ?>
                                <tr>
                                    <td colspan="8" style="text-align:center; color:#888;">No queues available</td>
                                </tr>
                            <?php else :
                                foreach ( $queue_rows as $row ) : ?>
                                <tr>
                                    <td><input type="checkbox" class="select-row queue-data" data-queue-id="<?php echo esc_attr( $row['queue_id'] ); ?>"></td>
                                    <td><?php echo esc_html( $row['order_id'] ); ?></td>
                                    <td><?php echo esc_html( $row['customer'] ); ?></td>
                                    <td><?php echo esc_html( $row['phone'] ); ?></td>
                                    <td><?php echo esc_html( $row['msg_preview'] ); ?></td>
                                    <td><?php echo esc_html( $row['queued_at'] ); ?></td>
                                    <td><span style="color:orange;font-weight:bold;"><?php echo esc_html( $row['status'] ); ?></span></td>
                                    <td><button class="button-secondary wpwfw-cancel-queue" type="button" data-queue-id="<?php echo esc_attr( $row['queue_id'] ); ?>">
                                        <i class="fa fa-times" style="padding-right: 5px;"></i> Cancel
                                    </button></td>
                                </tr>
                            <?php endforeach;
                            endif; ?>
                        </tbody>
                    </table>
                </div>

                <h3><i class="fa-brands fa-whatsapp fa-lg" aria-hidden="true"></i>Order Message Failed History</h3>
                <?php $this->component( 'dashboard_resend_log' ); ?>

                <h3><i class="fa fa-shopping-cart" aria-hidden="true"></i>Cart Abandon Follow-Up Failed History</h3>
                <?php $this->component( 'dashboard_ca_resend_log' ); ?>

                <?php $this->dashboard_page_js(); ?>

            </div>
        <?php

    }

    private function dashboard_resend_log() {

        ?>
            <div class="form-wrapper">
                <div class="tablenav top elem-hide">
                    <div class="alignleft actions bulkactions">
                        <label for="bulk-action-selector-top" class="screen-reader-text">Select bulk action</label>
                        <?php 
                        $bulk_actions_data = $this->dashboard->get_bulk_actions();
                        if ( false && is_array( $bulk_actions_data ) && count( $bulk_actions_data ) != 0 && !isset( $this->dashboard->page_data['order_resend_data']['error'] ) ) : 
                        ?>
                        <select name="action" id="bulk-action-selector-top">
                            <option value="-1">Bulk actions</option>
                            <?php foreach ( $bulk_actions_data as $action => $action_title ): ?>
                            <option value="<?php echo $action ?>"><?php _e( $action_title, WPWFW_PLUGIN_DOMAIN_LANGUAGE ) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <input type="button" id="ba_order" class="button action bulk_action" value="Apply">
                        <?php endif; ?>
                    </div>
                </div>
                <table class="wp-list-table widefat fixed striped table-view-list posts table-resend-logs" style="margin:10px 0;">
                    <thead>
                        <tr>
                            <td class="check-column"><input type="checkbox" class="wpwfw_dash_select_all order-data"></td>
                            <th>Date</th>
                            <th>Type</th>
                            <th>Order Id</th>
                            <th>Order Status</th>
                            <th>WhatsApp Number</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if( isset( $this->dashboard->page_data['order_resend_data']['error'] ) ): ?>
                        <tr>
                            <td colspan="8" align="center">No log available</td>
                        </tr>
                        <?php else: 
                            foreach ( $this->dashboard->page_data['order_resend_data'] as $id => $value ) : ?>
                        <tr>
                            <td><input type="checkbox" class="select-row order-data"></td>
                            <td><?php _e( $value['date'], WPWFW_PLUGIN_DOMAIN_LANGUAGE ); ?></td>
                            <td class="<?php _e( "_wpwfw_" . $value['type'], WPWFW_PLUGIN_DOMAIN_LANGUAGE ); ?>"><?php _e( $value['type_title'], WPWFW_PLUGIN_DOMAIN_LANGUAGE ); ?></td>
                            <td id="<?php _e( "row_id_" . $id ) ?>"><a href="<?php _e( get_edit_post_link( $value['id'] ), WPWFW_PLUGIN_DOMAIN_LANGUAGE ); ?>" target="_blank"><span><i class="fa fa-external-link" aria-hidden="true"></i></span></a><?php _e( $value['id'], WPWFW_PLUGIN_DOMAIN_LANGUAGE ); ?></td>
                            <td><?php _e( $value['order_status'], WPWFW_PLUGIN_DOMAIN_LANGUAGE ); ?></td>
                            <td><?php _e( $value['number'], WPWFW_PLUGIN_DOMAIN_LANGUAGE ); ?></td>
                            <td><?php _e( $value['status'], WPWFW_PLUGIN_DOMAIN_LANGUAGE ); ?></td>
                            <td><?php _e( $value['action'], WPWFW_PLUGIN_DOMAIN_LANGUAGE ); ?></td>
                        </tr>
                        <?php endforeach;
                            endif;
                        ?>
                        <!-- <tr><td><?php print_r( $this->dashboard->page_data ); ?></td></tr> -->
                    </tbody>   
                </table>
                <form method="post" id="wpwfw-resend">
                    <input type="hidden" name="wpwfw_resend_id">
                    <input type="submit" name="wpwfw_resend_wa" style="display:none;" value="Resend Message">
                </form>    
            </div>
        <?php

    }

    private function dashboard_ca_resend_log() {
        
        ?>
            <div class="form-wrapper">
                <div class="tablenav top elem-hide">
                    <div class="alignleft actions bulkactions">
                        <label for="bulk-action-selector-top" class="screen-reader-text">Select bulk action</label>
                        <?php 
                        $bulk_actions_data = $this->dashboard->get_bulk_actions();
                        if ( false && is_array( $bulk_actions_data ) && count( $bulk_actions_data ) != 0 && isset( $this->dashboard->page_data['cart_abandon_resend_data']['error'] ) ) : 
                        ?>
                        <select name="action" id="bulk-action-selector-top">
                            <option value="-1">Bulk actions</option>
                            <?php foreach ( $bulk_actions_data as $action => $action_title ): ?>
                            <option value="<?php echo $action ?>"><?php _e( $action_title, WPWFW_PLUGIN_DOMAIN_LANGUAGE ) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <input type="button" id="ba_cart_abandon" class="button action bulk_action" value="Apply">
                        <?php endif; ?>
                    </div>
                </div>
                <table class="wp-list-table widefat fixed striped table-view-list posts table-cart-resend-logs" style="margin:10px 0;">
                    <thead>
                        <tr>
                            <td class="check-column"><input type="checkbox" class="wpwfw_dash_select_all cart-data"></td>
                            <th>Date</th>
                            <th>Type</th>
                            <th>Cart Id</th>
                            <th>Cart Status</th>
                            <th>WhatsApp Number</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if( isset( $this->dashboard->page_data['cart_abandon_resend_data']['error'] ) ): ?>
                        <tr>
                            <td colspan="8" align="center">No log available</td>
                        </tr>
                        <?php else: 
                            foreach ( $this->dashboard->page_data['cart_abandon_resend_data'] as $id => $value ) : ?>
                        <tr>
                            <td><input type="checkbox" class="select-row cart-data"></td>
                            <td><?php _e( $value['date'], WPWFW_PLUGIN_DOMAIN_LANGUAGE ); ?></td>
                            <td class="<?php _e( '_wpwfw_' . $value['type'], WPWFW_PLUGIN_DOMAIN_LANGUAGE ); ?>"><?php _e( $value['type_title'], WPWFW_PLUGIN_DOMAIN_LANGUAGE ); ?></td>
                            <td id="<?php _e( "row_id_" . $id ) ?>"><?php _e( $value['id'], WPWFW_PLUGIN_DOMAIN_LANGUAGE ); ?></td>
                            <td><?php _e( $value['order_status'], WPWFW_PLUGIN_DOMAIN_LANGUAGE ); ?></td>
                            <td><?php _e( $value['number'], WPWFW_PLUGIN_DOMAIN_LANGUAGE ); ?></td>
                            <td><?php _e( $value['status'], WPWFW_PLUGIN_DOMAIN_LANGUAGE ); ?></td>
                            <td><?php _e( $value['action'], WPWFW_PLUGIN_DOMAIN_LANGUAGE ); ?></td>
                        </tr>
                        <?php endforeach;
                            endif;
                        ?>
                    </tbody>  
                </table>
                <form method="post" id="wpwfw-ca-resend">
                    <input type="hidden" name="wpwfw_resend_cart_id">
                    <input type="hidden" name="wpwfw_resend_cart_integration">
                    <input type="submit" name="wpwfw_resend_ca" style="display:none;" value="Resend Message">
                </form>    
            </div>
        <?php

    }

    private function settings_page_notification() {

        $custom_status = WPWFW_Core()->services->custom_status;
        ?>
            <div class="card-content notification elem-show">
                <ul class="card-tab">
                    <li class="nav-tab nav-tab-active"><a href="#/" id="tab-notification">Notification Message</a></li>
                    <li class="nav-tab"><a href="#/" id="tab-aftersale-follow-up">After-sale Follow-Up Message</a></li>
                    <li class="nav-tab"><a href="#/" id="tab-cart-abandon-follow-up">Cart Abandon Follow-Up Message</a></li>
                </ul>
                <div class="wp-tab-static elem-show">
                    <div class="<?php if ( count( array_intersect( array_keys( WPWFW_Core()->services->cart_abandon_events ), WPWFW_Core()->integration::$active_integrations ) ) == 0 ) echo 'shortcode-order'; ?> elem-show">Shortcode</div>
                    <figure class="quote <?php if ( count( array_intersect( array_keys( WPWFW_Core()->services->cart_abandon_events ), WPWFW_Core()->integration::$active_integrations ) ) == 0 ) echo 'shortcode-order'; ?> elem-show"><blockquote>You can customize your message by using order information in the form of shortcodes. To use it, a shortcode must be written in the <b>%name%</b> format.</blockquote></figure>
                    <table class="form-table shortcode-order elem-show">
                        <tbody>
                            <tr class="shortcode-helper-desktop">
                                <th scope="row"><?php _e( 'Order Shortcodes', WPWFW_PLUGIN_DOMAIN_LANGUAGE ); ?>:</th>
                                <td>
                                    <?php $this->plain_shortcode_helper(); ?>
                                </td>
                            </tr>
                            <tr class="shortcode-helper-mobile">
                                <th scope="row"><?php _e( 'Order Shortcodes', WPWFW_PLUGIN_DOMAIN_LANGUAGE ); ?>:</th>
                                <td>
                                    <?php $this->shortcode_helper(); ?>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><?php _e( 'Custom Order Shortcodes:', WPWFW_PLUGIN_DOMAIN_LANGUAGE ); ?></th>
                                <td>
                                    <?php
                                    if ( !empty( WPWFW_Core()->integration::$active_integrations ) ) {
                                        $suggested_variables = [];
                                        foreach ( WPWFW_Core()->integration->get_supported_integrations() as $plugin => $plugin_path ) {
                                            if ( !in_array( $plugin, WPWFW_Core()->integration::$active_integrations ) ) continue;
                                            $plugin_meta_variable = array_keys( WPWFW_Core()->integration->get_integration_shortcodes( $plugin ) );
                                            if ( empty( $plugin_meta_variable ) ) continue;
                                            foreach ( $plugin_meta_variable as $variable ) {
                                                array_push( $suggested_variables, $variable);
                                            }
                                        }
                                    } 
                                    ?>
                                    <label for="wpwfw_settings[custom_shortcodes]" class="form-label" title="<?php _e( 'Refer to one of your order to see available custom field data', WPWFW_PLUGIN_DOMAIN_LANGUAGE ); ?>">
                                        <textarea class="form-control" id="wpwfw_settings[custom_shortcodes]" name="wpwfw_settings[custom_shortcodes]" cols="55" rows="3" placeholder="custom_field_variable_1&#10;custom_field_variable_2" tabindex="<?php echo $this->page_tab++; ?>"><?php echo stripcslashes( isset( $this->settings[ 'custom_shortcodes' ] ) ? $this->settings[ 'custom_shortcodes' ] : ( !empty( $suggested_variables ) ? implode( PHP_EOL, $suggested_variables )  : '' ) ); ?></textarea>
                                    </label>
                                    <div class="ws-smart-helper-label">
                                        <p><span><i class="fa fa-circle-info" aria-hidden="true"></i></span><em> <?php _e( 'You can use data from an order custom field as a custom shortcode. Separate each shortcode with a new line', WPWFW_PLUGIN_DOMAIN_LANGUAGE ) ?></em></p>
                                    </div>
                                    <div id="custom-shortcodes-container">
                                        <!-- Clickable shortcodes will be generated here by JavaScript -->
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <?php if ( count( array_intersect( array_keys( WPWFW_Core()->services->cart_abandon_events ), WPWFW_Core()->integration::$active_integrations ) ) == 0 ): ?>
                    <?php $this->cart_extension_not_installed();  ?>
                    <?php else: ?>
                    <table class="form-table shortcode-cart elem-hide">
                        <tr class="cart-integration">
                           <th scope="row"><?php _e( 'Cart Abandon Integration', WPWFW_PLUGIN_DOMAIN_LANGUAGE ); ?>:</th>
                           <td>
                                <?php $this->populate_cart_integration_select_field(); ?>
                           </td> 
                        </tr>
                        <tr class="cart-integration-capture">
                            <th scope="row"><?php _e( 'Cart Abandoned Cut-off Time', WPWFW_PLUGIN_DOMAIN_LANGUAGE ); ?>:</th>
                            <td>
                                Not Configured
                            </td>
                        </tr>
                        <tr class="shortcode-helper-desktop">
                            <th scope="row"><?php _e( 'Cart Shortcodes', WPWFW_PLUGIN_DOMAIN_LANGUAGE ); ?>:</th>
                            <td>
                                <?php $this->plain_cart_shortcode_helper(); ?>
                            </td>
                        </tr>
                        <tr class="shortcode-helper-mobile">
                            <th scope="row"><?php _e( 'Cart Shortcodes', WPWFW_PLUGIN_DOMAIN_LANGUAGE ); ?>:</th>
                            <td>
                                <?php $this->cart_shortcode_helper(); ?>
                            </td>
                        </tr>
                    </table>
                    <?php endif; ?>
                    <hr>
                </div>
                <div class="wp-tab-panels elem-show table-tab" id="table-tab-notification">
                    <div class="sales-group">Admin Order Notification</div>
                    <table class="form-table sales-group" tabindex="1">
                        <tbody>
                            <tr valign="top">
                                <th scope="row" class="titledesc">
                                    <?php _e( 'Order Status Events', WPWFW_PLUGIN_DOMAIN_LANGUAGE ); ?>:
                                </th>
                                <td><?php $this->populate_event_select_field( 'admin_events'); ?></td>
                            </tr>
                            <?php 
                                $this->populate_custom_message_field( 'admin_events' );
                            ?>
                        </tbody>
                    </table>
                    <hr class="sales-group">
                    <div class="wp-tab-panels-item">Default Order Status</div>
                    <table class="form-table">
                        <tbody>
                            <tr valign="top">
                                <th scope="row" class="titledesc">
                                    <?php _e( 'Order Status Events:', WPWFW_PLUGIN_DOMAIN_LANGUAGE ); ?>
                                </th>
                                <td>
                                    <?php $this->populate_event_select_field( 'default_events' ); ?>
                                </td>
                            </tr>
                            <?php 
                                $this->populate_custom_message_field( 'default_events' );
                            ?>
                        </tbody>
                    </table>
                    <?php if ( isset( $custom_status ) ) : // Check if custom statuses exists ?>
                    <hr>
                    <div class="wp-tab-panels-item">Custom Order Status</div>
                    <figure class="quote">
                        <blockquote>You can also use custom order status to be triggered for sending notification</blockquote>
                    </figure>
                    <table class="form-table">
                        <tbody>
                            <tr valign="top">
                                <th scope="row" class="titledesc">
                                    <?php _e( 'Custom Order Status Events:', WPWFW_PLUGIN_DOMAIN_LANGUAGE ); ?>
                                </th>
                                <td>
                                    <?php $this->populate_event_select_field( 'custom_events' ); ?>
                                </td>
                            </tr>
                            <?php
                                $this->populate_custom_message_field( 'custom_events' ); 
                            ?>
                        </tbody>
                    </table>
                    <?php endif; ?>
                    <?php if( WPWFW_Core()->services->wc_products && false ): ?>
                    <hr>
                    <div class="wp-tab-panels-item">Filter By Product(s)</div>
                    <figure class="quote">
                        <blockquote>You can filter type of products to be triggered for sending notification</blockquote>
                    </figure>
                    <table class="form-table">
                        <tbody>
                            <tr valign="top">
                                <th scope="row" class="titledesc">
                                    <?php _e( 'Filter By Product(s):', WPWFW_PLUGIN_DOMAIN_LANGUAGE ); ?>
                                </th>
                                <td>
                                    <?php $this->populate_products_select_field(); ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <?php endif; ?>
                </div>
                <div class="wp-tab-panels elem-hide table-tab" id="table-tab-aftersale-follow-up">
                    <div>After-sales Follow Up Notification</div>
                    <figure class="quote">
                        <blockquote>
                            Follow-up `Completed` orders after a set period
                        </blockquote>
                    </figure>
                    <table class="form-table">
                        <tbody>
                            <?php $this->populate_custom_message_field( 'aftersale_events' ); ?>
                        </tbody>
                    </table>
                </div>
                <div class="wp-tab-panels elem-hide table-tab" id="table-tab-cart-abandon-follow-up">
                    <?php if ( count( array_intersect( array_keys( WPWFW_Core()->services->cart_abandon_events ), WPWFW_Core()->integration::$active_integrations ) ) != 0 ): ?>
                        <div>Cart Abandon Follow Up Notification</div>
                        <table class="form-table">
                            <tbody>
                                <?php $this->populate_custom_message_field( 'cart_abandon_events' ); ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
                
            </div>

        <?php

    }



    private function settings_page_support() {

        ?>
            <div class="card-content support elem-hide">
                <div class="row fragment-title">
                    <div class="col"><i class="fa fa-circle-question"></i>FAQ</div>
                </div>
                <div class="row fragment-content">
                    <div class="col">
                        <?php
                            $this->fragment_accordion( 'faq' ); 
                        ?>
                    </div>
                </div>
                <hr>
                <div class="row fragment-title">
                    <div class="col"><i class="fa fa-paper-plane" aria-hidden="true"></i>Ask a question</div>
                </div>
                <?php if ( isset( WPWFW_PLUGIN_SOCIAL['telegram'] ) && !empty( WPWFW_PLUGIN_SOCIAL['telegram'] ) ): ?>
                <div class="row fragment-content">
                    <div class="col">
                        <a href="<?php echo WPWFW_PLUGIN_SOCIAL['telegram']; ?>" class="telegram-btn" target="_blank">
                            <svg enable-background="new 0 0 512 512" id="Layer_1" version="1.1" viewbox="0 0 512 512" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><circle cx="256" cy="256" fill="#ffffff" id="ellipse" r="230"/><path d="M246.4,332.1c-12.3,11.9-24.4,23.7-36.5,35.5c-4.2,4.1-8.9,6.4-15,6.1c-4.1-0.2-6.4-2-7.7-5.9  c-9.2-28.6-18.6-57.2-27.8-85.9c-0.9-2.8-2.2-4.1-5-5c-21.7-6.6-43.5-13.4-65.1-20.3c-3.3-1.1-6.7-2.4-9.6-4.4  c-4.5-3-5.1-7.9-1.1-11.5c3.7-3.3,8.1-6.1,12.7-7.9c26.6-10.5,53.3-20.7,80-31c67.7-26.1,135.4-52.3,203.1-78.4  c12.9-5,22.8,2,21.4,16c-0.9,8.9-3.2,17.7-5,26.5c-14.7,69.4-29.4,138.9-44.2,208.3c-3.5,16.5-15.1,20.8-28.6,10.8  c-22.7-16.7-45.4-33.5-68.1-50.3C248.8,333.8,247.7,333,246.4,332.1z M195.4,353.2c0.3-0.1,0.5-0.1,0.8-0.2c0.1-0.7,0.3-1.3,0.4-1.9  c1.5-15.7,3-31.5,4.3-47.2c0.3-3.5,1.5-6,4.1-8.4c20.9-18.7,41.8-37.6,62.6-56.4c23.1-20.8,46.2-41.6,69.2-62.5c1.4-1.3,2-3.5,3-5.3  c-2.2-0.2-4.5-1.1-6.5-0.6c-2.7,0.7-5.2,2.3-7.6,3.8c-50.9,32.1-101.9,64.2-152.8,96.3c-2.9,1.8-3.4,3.3-2.3,6.5  c3.8,10.8,7.2,21.7,10.7,32.6C186,324.3,190.7,338.8,195.4,353.2z" fill="#0088CC" id="logo"/></path></circle></svg>
                            <span class="telegram-text">Join Our Telegram Group</span>
                        </a>
                    </div>
                </div>
                <?php endif; ?>
                <?php if ( isset( WPWFW_PLUGIN_SOCIAL['facebook'] ) && !empty( WPWFW_PLUGIN_SOCIAL['facebook'] ) ): ?>
                <div class="row fragment-content">
                    <div class="col">
                        <a href="<?php echo WPWFW_PLUGIN_SOCIAL['facebook']; ?>" target="_blank" class="facebook-btn"><svg xmlns="http://www.w3.org/2000/svg" height="60" width="60" viewBox="-204.79995 -341.33325 1774.9329 2047.9995"><path d="M1365.333 682.667C1365.333 305.64 1059.693 0 682.667 0 305.64 0 0 305.64 0 682.667c0 340.738 249.641 623.16 576 674.373V880H402.667V682.667H576v-150.4c0-171.094 101.917-265.6 257.853-265.6 74.69 0 152.814 13.333 152.814 13.333v168h-86.083c-84.804 0-111.25 52.623-111.25 106.61v128.057h189.333L948.4 880H789.333v477.04c326.359-51.213 576-333.635 576-674.373" fill="#1877f2"></path><path d="M948.4 880l30.267-197.333H789.333V554.609C789.333 500.623 815.78 448 900.584 448h86.083V280s-78.124-13.333-152.814-13.333c-155.936 0-257.853 94.506-257.853 265.6v150.4H402.667V880H576v477.04a687.805 687.805 0 00106.667 8.293c36.288 0 71.91-2.84 106.666-8.293V880H948.4" fill="#fff"></path></svg>
                            <span class="facebook-text">Join Our Facebook Group</span>
                        </a>
                    </div>
                </div>
                <?php endif; ?>
                <?php if ( isset( WPWFW_PLUGIN_SOCIAL['helpdesk'] ) && !empty( WPWFW_PLUGIN_SOCIAL['helpdesk'] ) ): ?>
                <div class="row fragment-content">
                    <div class="col">
                        <a href="<?php echo WPWFW_PLUGIN_SOCIAL['helpdesk']; ?>" target="_blank" class="helpdesk-btn"><svg xmlns="http://www.w3.org/2000/svg" width="60" height="60" viewBox="0 0 24 24" fill="none">
<path d="M9 10C9 9.40666 9.17595 8.82664 9.50559 8.33329C9.83524 7.83994 10.3038 7.45543 10.852 7.22836C11.4001 7.0013 12.0033 6.94189 12.5853 7.05765C13.1672 7.1734 13.7018 7.45912 14.1213 7.87868C14.5409 8.29824 14.8266 8.83279 14.9424 9.41473C15.0581 9.99667 14.9987 10.5999 14.7716 11.1481C14.5446 11.6962 14.1601 12.1648 13.6667 12.4944C13.1734 12.8241 12.5933 13 12 13V14M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
<circle cx="12" cy="17" r="1" fill="#000000"/>
</svg>
                            <span class="helpdesk-text">Contact Support</span>
                        </a>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        <?php

    }

    private function settings_page_connection() {

        $settings = $this->settings;
        $services_data = WPWFW_Core()->services->get_service_data(true);
        
        ?>
            <div class="card-content connection elem-hide">
                <div class="wp-tab-panels wfw-service-credentials">
                    <table class="form-table">
                        <tbody>
                            <?php if ( count( $services_data ) !== 1 ): ?>
                            <tr>
                                <th scope="row"><?php _e( 'Subdomain', WPWFW_PLUGIN_DOMAIN_LANGUAGE ); ?></th>
                                <td>
                                    <label for="wpwfw_settings[subdomain]" class="form-label" title="<?php _e( 'Select your connected dashboard', WPWFW_PLUGIN_DOMAIN_LANGUAGE ); ?>">
                                        <select class="form-control wpwfw_subdomain" data-role="select2" id="wpwfw_settings[subdomain]" name="wpwfw_settings[subdomain]" style="width:400px;" tabindex="<?php echo $this->page_tab++; ?>">
                                                <?php
                                                    foreach ( $services_data as $app => $data ) {
                                                        $is_selected = ( isset( $settings['subdomain'] ) && $settings['subdomain'] == $app ) ? ' selected="selected"': '';
                                                        echo '<option value="'.$app.'"'.$is_selected.'>'.$data['core_url'].'</option>';
                                                    }
                                                ?>
                                        </select>
                                    </label>
                                    <div class="ws-smart-helper-label">
                                        <p><span><i class="fa fa-circle-info ws-smart-helper" aria-hidden="true" data-type="img" data-image-src="<?php echo $this->assets_url . '/img/LookForDashboard.png'; ?>"></i></span><em><?php _e( 'Select your connected dashboard', WPWFW_PLUGIN_DOMAIN_LANGUAGE ); ?></em></p>
                                    </div>                                                
                                </td>
                            </tr>
                            <?php else: ?>
                            <tr class="elem-hide">
                                <th scope="row"><?php _e( 'Subdomain', WPWFW_PLUGIN_DOMAIN_LANGUAGE ); ?></th>
                                <td><input type="hidden" id="wpwfw_settings[subdomain]" name="wpwfw_settings[subdomain]" style="width:400px;" value="<?php echo array_keys( $services_data )[0]; ?>"></td>
                            </tr>
                            <?php endif; ?>
                            <tr>
                                <th scope="row"><?php _e( 'Access Token', WPWFW_PLUGIN_DOMAIN_LANGUAGE ); ?></th>
                                <td>
                                    <label for="wpwfw_settings[access_token]" class="form-label" title="<?php _e( sprintf( 'The %s for your account in %s', 'Access Token', WPWFW_PLUGIN_SIMPLE_NAME ), WPWFW_PLUGIN_DOMAIN_LANGUAGE ) ?>">
                                        <input type="text" class="form-control wpwfw_access_token" id="wpwfw_settings[access_token]" style="width:400px;" name="wpwfw_settings[access_token]" size="50" value="<?php echo $settings['access_token']; ?>" tabindex="<?php echo $this->page_tab++; ?>" />
                                    </label>
                                    <div class="ws-smart-helper-label">
                                        <p><span><i class="fa fa-circle-info ws-smart-helper" aria-hidden="true" data-type="img" data-image-src="<?php echo $this->assets_url . '/img/LookForCredentials.png'; ?>"></i></span><em><?php _e( sprintf( 'The %s for your account in %s', 'Access Token', WPWFW_PLUGIN_SIMPLE_NAME ), WPWFW_PLUGIN_DOMAIN_LANGUAGE ) ?></em></p>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><?php _e( 'Instance ID', WPWFW_PLUGIN_DOMAIN_LANGUAGE ); ?></th>
                                <td>
                                    <label for="wpwfw_settings[instance_id]" class="form-label" title="<?php _e( sprintf( 'The %s for your account in %s' , 'Instance ID', WPWFW_PLUGIN_SIMPLE_NAME ), WPWFW_PLUGIN_DOMAIN_LANGUAGE ) ?>">
                                        <input type="text" class="form-control wpwfw_instance_id" id="wpwfw_settings[instance_id]" style="width:400px;" name="wpwfw_settings[instance_id]" size="50" value="<?php echo $settings['instance_id']; ?>" tabindex="<?php echo $this->page_tab++?>" />
                                    </label>
                                    <div class="ws-smart-helper-label">
                                        <p><span><i class="fa fa-circle-info ws-smart-helper" aria-hidden="true" data-type="img" data-image-src="<?php echo $this->assets_url . '/img/LookForCredentials.png'; ?>"></i></span><em><?php _e( sprintf( 'The %s for your account in %s' , 'Instance ID', WPWFW_PLUGIN_SIMPLE_NAME ), WPWFW_PLUGIN_DOMAIN_LANGUAGE ) ?></em></p>
                                    </div>
                                </td>
                            </tr>
                            <?php if ( isset( $settings['access_token'] ) && !empty( $settings['access_token'] ) ): ?>
                            <tr>
                                <th scope="row">Action</th>
                                <td>
                                    <button type="button" class="button-primary" id="wpwfw_disconnect">
                                        <span class="fa fa-times" style="padding-right: 10px;"></span>Disconnect
                                    </button>
                                    <p><em>Disconnect &amp; deletes the current <?php echo WPWFW_PLUGIN_SIMPLE_NAME; ?> credentials</em></p>
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                    <!-- Experimental -->
                    <div class="wp-tab-panels elem-show">
                        <hr>
                        <div>Control</div>
                        <div class="row fragment-content">
                            <div class="col" style="padding-top: 10px;">
                            <button class="accordion"><i class="fa-brands fa-whatsapp" aria-hidden="true"></i> Send a test message</button>
                                <div class="accordion-panel">
                                <table class="form-table control">
                                    <tbody>
                                        <tr>
                                            <th scope="row">
                                                <?php _e( 'Receipient Phone Number:', WPWFW_PLUGIN_DOMAIN_LANGUAGE ); ?>
                                            </th>
                                            <td>
                                                <label for="wpwfw-sample-receipient" class="form-label" title="<?php _e( 'WhatsApp number to receive sample message', WPWFW_PLUGIN_DOMAIN_LANGUAGE ); ?>">
                                                    <input type="text" class="form-control" id="wpwfw-sample-receipient" style="width:400px;" size="50" value="" tabindex="<?php echo $this->page_tab++; ?>"/>
                                                </label>
                                                <p><em><?php _e( 'Recepient WhatsApp mobile number with Country Code. Example: 60125328403', WPWFW_PLUGIN_DOMAIN_LANGUAGE ); ?></em></p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row"><?php _e( 'Sample message', WPWFW_PLUGIN_DOMAIN_LANGUAGE ); ?></th>
                                            <td>
                                                <label for="wpwfw-sample-msg" class="form-label" title="<?php _e( "Shortcodes are not available when sending a sample message", WPWFW_PLUGIN_DOMAIN_LANGUAGE ); ?>">
                                                    <textarea class="form-control" id="wpwfw-sample-msg" cols="55" rows="5" tabindex="<?php echo $this->page_tab++; ?>"><?php _e( 'Send a sample message! (' . time() . ')', WPWFW_PLUGIN_DOMAIN_LANGUAGE ); ?></textarea>
                                                </label>
                                                <p><em><?php _e( 'Shortcodes are not available when sending a sample message', WPWFW_PLUGIN_DOMAIN_LANGUAGE); ?></em></p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th scope="row"></th>
                                            <td><button class="button-primary" id="wpwfw-sample-button">Send</button></td>
                                        </tr>
                                    </tbody>
                                </table>
                                </div>
                                <?php $this->fragment_accordion( 'control' ); ?>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
        <?php

    }

    private function settings_page_general() {

        $settings = $this->settings;
        
        ?>
            <div class="card-content general elem-hide">
                <div class="wp-tab-panels wfw-general-settings">
                    <table class="form-table">
                        <tbody>
                            <tr>
                                <th scope="row"><?php _e( 'New Order Notification:', WPWFW_PLUGIN_DOMAIN_LANGUAGE ); ?></th>
                                <td>
                                    <label for="wpwfw_settings[sales_notification]" class="form-label" title="<?php _e( " New order notification", WPWFW_PLUGIN_DOMAIN_LANGUAGE ); ?>">
                                        <input class="form-control sales-notification" id="wpwfw_settings[sales_notification]" name="wpwfw_settings[sales_notification]" type="checkbox" value="1" <?php echo ( isset( $settings[ 'sales_notification' ] ) && $settings[ 'sales_notification' ] == "1" ) ? 'checked="checked" ' : ' '; ?> tabindex="<?php echo $this->page_tab++; ?>" />
                                    </label>
                                    <p><em><?php _e( " Check if you want to receive a WhatsApp message when there's a new order", WPWFW_PLUGIN_DOMAIN_LANGUAGE ); ?></em></p>
                                    <button class="form-label sales-group tooltip-btn">Customize Message</button>
                                </td>
                            </tr>
                            <tr class="sales-group">
                                <th scope="row">
                                    <?php _e( 'Admin Phone Number:', WPWFW_PLUGIN_DOMAIN_LANGUAGE ); ?>
                                </th>
                                <td>
                                    <label for="wpwfw_settings[sales_team_phone]" class="form-label" title="<?php _e( 'WhatsApp number to receive notification', WPWFW_PLUGIN_DOMAIN_LANGUAGE ); ?>">
                                        <input type="text" class="form-control" id="wpwfw_settings[sales_team_phone]" name="wpwfw_settings[sales_team_phone]" style="width:400px;" size="50" value="<?php echo ( isset( $settings[ 'sales_team_phone' ] ) ) ? $settings[ 'sales_team_phone' ] : ''; ?>" tabindex="<?php echo $this->page_tab++; ?>"/>
                                    </label>
                                    <p><em><?php _e( 'Recepient WhatsApp mobile number with Country Code. You can add multiple mobile numbers separated by | character. Example: 60125328403|60145449720', WPWFW_PLUGIN_DOMAIN_LANGUAGE ); ?></em></p>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><?php _e( 'More Order Product Details:', WPWFW_PLUGIN_DOMAIN_LANGUAGE ); ?></th>
                                <td>
                                    <label for="wpwfw_settings[more_product_info]" class="form-label" title="<?php _e( 'Check if you want to send the WhatsApp messages with full order product information', WPWFW_PLUGIN_DOMAIN_LANGUAGE ); ?>">
                                        <input class="form-control" id="wpwfw_settings[more_product_info]" name="wpwfw_settings[more_product_info]" type="checkbox" value="1" <?php echo ( isset( $settings[ 'more_product_info' ] ) && $settings[ 'more_product_info' ] == "1" ) ? 'checked="checked"' : ''; ?> tabindex="<?php echo $this->page_tab++; ?>" />
                                    </label>
                                    <p><em><?php _e( 'Switch between simple and detailed order information for %order_shipping%', WPWFW_PLUGIN_DOMAIN_LANGUAGE ); ?></em></p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php

    }

    private function settings_page_advanced() {

        $settings = $this->settings;

        ?>
            <div class="card-content advanced elem-hide">
                <div class="wp-tab-panels wfw-general-settings">
                    <table class="form-table">
                        <tbody>
                            <tr>
                                <th scope="row"><?php _e( 'Enable Sandbox:', WPWFW_PLUGIN_DOMAIN_LANGUAGE ); ?></th>
                                <td>
                                    <label for="wpwfw_settings[sandbox_mode]" class="form-label" title="<?php _e( 'When enabled, message won\'t be delivered for each requests and assumed successful', WPWFW_PLUGIN_DOMAIN_LANGUAGE ); ?>">
                                        <input class="form-control sandbox_mode" id="wpwfw_settings[sandbox_mode]" name="wpwfw_settings[sandbox_mode]" type="checkbox" value="1" <?php echo ( isset( $settings[ 'sandbox_mode' ] ) && $settings[ 'sandbox_mode' ] == "1" ) ? 'checked="checked"' : ''; ?> tabindex="<?php echo $this->page_tab++; ?>" />
                                    </label>
                                    <p><em><?php _e( 'When enabled, message won\'t be delivered for each requests and assumed successful', WPWFW_PLUGIN_DOMAIN_LANGUAGE ); ?></em></p>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><?php _e( 'Enable Background Processing:', WPWFW_PLUGIN_DOMAIN_LANGUAGE ); ?></th>
                                <td>
                                    <label for="wpwfw_settings[background_processing]" class="form-label" title="<?php _e( 'Process WhatsApp notifications in background using Action Scheduler to improve checkout performance', WPWFW_PLUGIN_DOMAIN_LANGUAGE ); ?>">
                                        <input class="form-control background_processing" id="wpwfw_settings[background_processing]" name="wpwfw_settings[background_processing]" type="checkbox" value="yes" <?php echo ( !isset( $settings[ 'background_processing' ] ) || $settings[ 'background_processing' ] == "yes" ) ? 'checked="checked"' : ''; ?> tabindex="<?php echo $this->page_tab++; ?>" />
                                    </label>
                                    <p><em><?php _e( 'Process WhatsApp notifications in background using Action Scheduler to improve checkout performance. <strong>Recommended: Enabled</strong>', WPWFW_PLUGIN_DOMAIN_LANGUAGE ); ?></em></p>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><?php _e( 'Enable Debug:', WPWFW_PLUGIN_DOMAIN_LANGUAGE ); ?></th>
                                <td>
                                    <label for="wpwfw_settings[debug]" class="form-label" title="<?php _e( 'Check if you want to receive debug information for each requests', WPWFW_PLUGIN_DOMAIN_LANGUAGE ); ?>">
                                        <input class="form-control debug" id="wpwfw_settings[debug]" name="wpwfw_settings[debug]" type="checkbox" value="1" <?php echo ( isset( $settings[ 'debug' ] ) && $settings[ 'debug' ] == "1" ) ? 'checked="checked"' : ''; ?> tabindex="<?php echo $this->page_tab++; ?>" />
                                    </label>
                                    <p><em><?php _e( 'Receive debug information for each requests', WPWFW_PLUGIN_DOMAIN_LANGUAGE ); ?></em></p>
                                </td>
                            </tr>
                            <tr class="debug-group">
                                <th scope="row"><?php _e( 'Email Address:', WPWFW_PLUGIN_DOMAIN_LANGUAGE ); ?></th>
                                <td>
                                    <label for="wpwfw_settings[debug_email_field]" class="form-label" title="<?php _e( 'Add an email address where you want to receive the debug information', WPWFW_PLUGIN_DOMAIN_LANGUAGE ); ?>">
                                        <input class="form-control" type="text" id="wpwfw_settings[debug_email_field]" name="wpwfw_settings[debug_email_field]" size="50" style="width:400px;" value="<?php echo ( isset( $settings[ 'debug_email_field' ] ) ) ? $settings[ 'debug_email_field' ] : ''; ?>" tabindex="<?php echo $this->page_tab++; ?>"/>
                                    </label>
                                    <p><em><?php _e( 'Add an email address where you want to receive the debug information', WPWFW_PLUGIN_DOMAIN_LANGUAGE ); ?></em></p>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><?php _e( 'Delete plugin data on uninstallation:', WPWFW_PLUGIN_DOMAIN_LANGUAGE ); ?></th>
                                <td>
                                    <label for="wpwfw_settings[delete_plugin_data]" class="form-label" title="<?php _e( 'Check if you want to receive debug information for each requests', WPWFW_PLUGIN_DOMAIN_LANGUAGE ); ?>:">
                                        <input class="form-control" id="wpwfw_settings[delete_plugin_data]" name="wpwfw_settings[delete_plugin_data]" type="checkbox" value="1" <?php echo ( isset( $settings[ 'delete_plugin_data' ] ) && $settings[ 'delete_plugin_data' ] == "1" ) ? 'checked="checked"' : ''; ?> tabindex="<?php echo $this->page_tab++; ?>" />
                                    </label>
                                    <p style="color:#666; padding: 0 10px; border-left: 3px solid #ff0000; margin: 15px 0;"><em><?php _e( 'This option will delete all `' . WPWFW_PLUGIN_SIMPLE_NAME . '` options data on plugin deletion. If you enable this and deletes the plugin, you can\'t restore the saved settings.', WPWFW_PLUGIN_DOMAIN_LANGUAGE ); ?></em></p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <?php
                        if ( isset( $settings[ 'debug' ] ) && $settings[ 'debug' ] == '1' ) $this->debug_workspace_ui();
                    ?>
                </div>
            </div>
        <?php
        
    }

    private function settings_page_css() {

        ?>
            <style>

                .wrap.woocommerce.wasapbot {
                    font-family: 'Poppins', sans-serif;
                } 

                .wpwfw-logo {
                    font-size: 24px;
                    color: white;
                    display: -ms-flexbox;
                    display: flex;
                    -ms-flex-align: center;
                    align-items: center;
                    position: -webkit-sticky;
                    top: 48px;
                    margin-left: 10px;
                }

                .wpwfw-logo-title {
                    display: -ms-flexbox;
                    display: flex;
                    -ms-flex-direction: column;
                    flex-direction: column;
                    font-family: 'Pathway Gothic One';
                    margin: 18px 30px 0 10px;
                }

                .wpwfw-version {
                    font-size: 12px;
                    margin-top: 2px;
                    font-weight: 400;
                    font-family: 'Poppins'
                }

                .topbar {
                    background: #0b2c5a;
                    box-shadow: 0 px 8px 0 rgba(0, 0, 0, 0.08);
                    height: 80px;
                    padding: 0 10px;
                    display: grid;
                    grid-template-columns: 180px 8fr 135px 20px;
                    align-items: center;
                    z-index: 1;
                }

                .topbar .menu a {
                    color: #fff;
                    float: left;
                    font-size: 15px;
                    padding: 14px 16px;
                    margin-bottom: 10px;
                    text-decoration: none;
                }

                .topbar .menu a:hover {
                    color: #ffb617;
                }

                .topbar .menu .menu-right {
                    float: right;
                }


                .logo img {
                    height: 60px;
                    padding: 5px 5px;
                }

                .topbar .btn-topbar a {
                    font-size: 15px;
                    font-weight: 500;
                    text-transform: uppercase;
                    letter-spacing: 0px;
                    fill: #FFA300;
                    color: #FFA300;
                    background-color: #006BFF00;
                    border-style: solid;
                    border-width: 2px 2px 2px 2px;
                    border-color: #FFA300;
                    border-radius: 5px 5px 5px 5px;
                    padding: 12px 20px 11px 20px;
                    text-decoration: none;
                    box-shadow: none;
                }

                .topbar .btn-topbar a:hover {
                    color: #07234B;
                    background-color: #FFA300;
                }

                /* sidebar start */

                .sidebar {
                    display: table-cell;
                    top: 80px;
                    width: 270px;
                    /* height: calc(100% - 80px); */
                    background: #fff;
                    overflow-x: hidden;
                    border-right: 1px solid #c3c4c7;
                }

                .sidebar ul {
                    margin-top: 20px;
                }

                .sidebar ul li {
                    width: 100%;
                    list-style: none;
                    margin-bottom: 0px;
                }

                .sidebar ul li a:hover {
                    color: #335DE7;
                    background: rgba(85,120,235,.1);
                }

                .sidebar ul li.sidebar-active {
                    background-color: #ebebeb;
                }

                .sidebar ul li a {
                    width: 100%;
                    text-decoration: none;
                    color: #74788d;
                    height: 45px;
                    display: flex;
                    align-items: center;
                    box-shadow: none;
                }

                .sidebar ul li a i:hover, .sidebar ul li a div:hover {
                    text-decoration: none;
                    box-shadow: none;
                }

                .sidebar ul li a i {
                    min-width: 60px;
                    font-size: 15px;
                    text-align: center;
                }

                .sidebar .sidebar-div {
                    border-bottom: 1px solid #c3c4c7;
                }

                /* main start */

                .main {
                    display: table;
                    top: 80px;
                    width: 100%;
                    left: 260px;
                    background: #fff;
                }

                .quote {
                    margin: 0;
                    background: #eee;
                    padding: 1em;
                    border-radius: 1em;
                    width: 620px;
                }

                .quote figcaption,
                .quote blockquote {
                    margin: 1em;
                }

                .content {
                    display: table-cell;
                    padding: 10px 10px;
                }

                .content .card {
                    text-align: left;
                    margin: 10px;
                    padding: 20px 25px;
                    align-items: center;
                    justify-content: space-between;
                    background: #fff;
                    max-width: 100%;
                    border-radius: 5px;
                    box-shadow: 0 7px 25px 0 rgba(0, 0, 0, 0.08);
                }

                .content .card .card-content {
                    margin-top: 35px;
                    margin-bottom: 10px;
                }

                .content .card h1 i, h2 i, h3 i, h4 i, h5 i, h6 i, div i {
                    padding-right: 20px;
                }

                .content .card div p {
                    padding-top: 4px;
                    font-size: 13px;
                    width: 475px;
                }

                .content .card div.dashboard-header {
                    font-size: 28px;
                    font-weight: 500;
                    height: 40px;
                    margin: 24px 0 20px;
                }

                .content .card div.dashboard-content {
                    padding: 0px 0 0 5px;
                }

                .content .card div.dashboard-content .dashboard-table-wrapper {
                    display: grid;
                    grid-gap: 30px;
                    grid-template-columns: repeat(2, 1fr);
                }

                .content .card div.dashboard-content .dashboard-tiles {
                    padding-top: 10px;
                }

                .content .card div.dashboard-content .dashboard-tiles .tiles-summary {
                    display: grid;
                    grid-template-columns: repeat(4, 1fr);
                    grid-gap: 20px;
                    margin-bottom: 20px;
                    background: rgba(0,0,0,0);
                    border-radius: 0;
                }

                .content .card div.dashboard-content .dashboard-tiles .tiles-summary .tiles-summary-container {
                    list-style: none;
                }

                .content .card div.dashboard-content .dashboard-tiles .tiles-summary .tiles-summary-container .tiles-summary-item {
                    display: flex;
                    flex-direction: column;
                    padding: 24px;
                    background-color: #fff;
                    line-height: 1.4em;
                    text-decoration: none;
                    border-radius: 6px;
                    border: 1px solid #dedfea;
                }

                .content .card div.dashboard-content .dashboard-tiles .tiles-summary .tiles-summary-container .tiles-summary-item .summary-item-component {
                    box-sizing: border-box;
                    display: flex;
                    width: 100%;
                    -webkit-box-align: center;
                    align-items: center;
                    -webkit-box-pack: justify;
                    justify-content: space-between;
                }

                .content .card div.dashboard-content .dashboard-tiles .tiles-summary .tiles-summary-container .tiles-summary-item .summary-item-component .summary-item-label {
                    font-weight: 550;
                    margin-bottom: 16px;
                }

                .content .card div.dashboard-content .dashboard-tiles .tiles-summary .tiles-summary-container .tiles-summary-item .summary-item-component .summary-item-icon {
                    font-size: 38px;
                }

                .content .card div.dashboard-content h3 {
                    font-size: 1.5em;
                    padding-top: 20px;
                }

                .content .card .card-content .row {
                    padding-top: 25px;
                    padding-bottom: 25px;
                }

                .content .card .card-content .row.fragment-title {
                    font-size: 1.2em;
                    font-weight: 500;
                    padding-top: 5px;
                    padding-bottom: 10px;
                }

                .content .card .card-content .row.fragment-content {
                    padding-top: 10px;
                    padding-bottom: 10px;
                }

                .content .card .card-content .content-not-available {
                    background: url('<?php echo $this->assets_url . '/img/bg-404.png'; ?>');
                    height: 60vh;
                    background-repeat: no-repeat;
                    background-size: auto;
                    background-position-x: center;
                    background-position-y: 60px;
                }

                .content .card .card-content .row i {
                    padding-right: 20px;
                }

                .card-tab li a {
                    text-decoration: none;
                    box-shadow: none;
                }

                .card-tab li.nav-tab {
                    background: #0b2c5a;
                }

                .card-tab li.nav-tab a {
                    color: #fff;
                }

                .card-tab li.nav-tab:hover {
                    background: #2d548a;
                }

                .card-tab li.nav-tab:hover a {
                    color: #fff;
                }

                .card-tab li.nav-tab.nav-tab-active {
                    background: #fff;
                }

                .card-tab li.nav-tab.nav-tab-active:not(:first-child) {
                    margin-bottom: 6px;
                }

                .card-tab li.nav-tab.nav-tab-active a {
                    color: #0b2c5a;
                }

                .accordion {
                    background-color: #eee;
                    color: #444;
                    cursor: pointer;
                    padding: 18px;
                    width: 100%;
                    text-align: left;
                    border: none;
                    outline: none;
                    transition: 0.4s;
                    font-size: 15px;
                    font-weight: 400;
                }

                .accordion:after {
                    font-family: FontAwesome;
                    content: '\f078'; /* \25BC */
                    font-size: 13px;
                    color: #777;
                    float: right;
                    margin-left: 5px;
                }

                .accordion-active, .accordion:hover {
                    background-color: #ccc;
                }

                .accordion-active:after {
                    font-family: FontAwesome;
                    content: "\f077"; /* \25BC */
                }

                .accordion-panel {
                    padding-top: 10px !important;
                    background-color: white;
                    display: none;
                    overflow: hidden;
                    font-weight: normal !important;
                }

                .accordion-panel p {
                    padding-left: 15px;
                }

                .accordion-panel .panel-content {
                    font-weight: normal !important;
                    font-size: 13px !important;
                    padding-left: 15px;
                }

                .accordion-panel .panel-content ul li {
                    list-style-type: disc;
                    list-style-position: inside;
                }

                .accordion-panel table p {
                    padding-left: 0px;
                }

                .accordion-panel .accordion-panel-fragment {
                    padding-top: 5px !important;
                    font-size: small !important;
                    font-weight: normal !important;
                }

                .accordion-panel .accordion-panel-fragment .accordion-panel-fragment-title {
                    background-color: #0b2c5a;
                    color: #fff;
                    white-space: nowrap;
                    padding: 10px 10px;
                    font-size: 15px;
                    font-weight: 450;
                }

                .accordion-panel .accordion-panel-fragment .accordion-panel-fragment-content {
                    font-size: small;
                    font-weight: normal;
                    padding: 10px 5px;
                }

                .wp-tab-panels div {
                    font-size: medium;
                    font-weight: bold;
                    padding-bottom: 15px;
                }

                .wp-tab-panels div.wpwfw_delay_estimate {
                    margin: 5px 0px;
                }

                .wp-tab-panels em, .wp-tab-static em {
                    font-weight: 400;
                }

                .wp-tab-static {
                    display: block;
                    clear: both;         /* Ensures it starts below floated elements */
                    margin-top: 20px;    /* Adds space below the tabs */
                    padding-top: 10px;   /* Optional: extra spacing */
                }

                .wp-tab-static div {
                    font-size: medium;
                    font-weight: bold;
                    padding-bottom: 15px;
                    padding-top: 10px;
                }

                .wp-tab-panels {
                    padding-top: 20px;
                }

                .wp-tab-panels-item:not(:first-child) {
                    padding-top: 20px;
                }

                .ws-smart-helper {
                    padding-right: 5px;
                }

                .ws-smart-helper-label {
                    position: relative;
                    padding-top: 2px !important;
                    padding-bottom: 1px !important;
                }

                .ws-smart-helper-popup {
                    position: absolute;
                    width: 500px;
                    height: 300px;
                    float: left;
                    border: 2px solid #c7c7c7;
                    border-radius: 15px;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    overflow: hidden;
                    z-index: 1;
                }

                .ws-smart-helper-popup img {
                    min-height:100%; 
                    min-width: 100%; 
                    object-fit: cover;
                }

                .form-table.control {
                    font-weight: 400;
                }

                .elem-show {
                    display: block;
                }

                .wp-tab-static.elem-show {
                    display: block;
                }

                .img-text-container {
                    display: flex;
                    align-items: center;
                }

                .img-text-container img {
                    max-width: 100px; /* adjust as needed */
                }

                .elem-hide {
                    display: none;
                }

                .footer {
                    font-size: 14px;
                    position: fixed;
                    top: auto;
                    bottom: 0;
                    background: #fff;
                    box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.08);
                    width: 100%;
                    height: 50px;
                    padding: 0 20px;
                    display: grid;
                    justify-items: center;
                    grid-template-columns: 4fr 1fr;
                    align-items: center;
                    z-index: 1;
                }

                button.sales-group  {
                    font-weight: normal;
                    font-size: small;
                    margin-top: 10px;
                }

                /* Select2 Selected Items Styling Fix */
                .select2-container--default .select2-selection--multiple .select2-selection__choice {
                    background-color: #0b2c5a !important;
                    border: 1px solid #0b2c5a !important;
                    color: #fff !important;
                    border-radius: 4px !important;
                    padding: 0 8px 0 4px !important;
                    margin: 3px 2px !important;
                    font-size: 13px !important;
                    line-height: 1.4 !important;
                    opacity: 1 !important;
                    visibility: visible !important;
                    display: inline-flex !important;
                    align-items: center !important;
                    min-height: 24px !important;
                }

                .select2-container--default .select2-selection--multiple .select2-selection__choice__display {
                    color: #fff !important;
                    padding: 6px 12px 6px 8px !important;
                    font-weight: 500 !important;
                    text-shadow: none !important;
                    opacity: 1 !important;
                    line-height: 1.2 !important;
                    min-height: 16px !important;
                    padding-left: 8px !important;
                    padding-right: 12px !important;
                    padding-top: 6px !important;
                    padding-bottom: 6px !important;
                    box-sizing: border-box !important;
                }

                /* More specific selector for text display padding */
                .select2-container.select2-container--default .select2-selection--multiple .select2-selection__choice .select2-selection__choice__display {
                    padding: 6px 12px 6px 8px !important;
                    padding-left: 8px !important;
                    padding-right: 12px !important;
                }

                /* Force padding with highest specificity */
                body .select2-container--default .select2-selection--multiple .select2-selection__choice__display,
                body .select2-container .select2-selection--multiple .select2-selection__choice__display {
                    padding: 6px 12px 6px 8px !important;
                    margin: 0 !important;
                }

                /* Target specifically the span element that contains the text */
                .select2-container--default .select2-selection--multiple .select2-selection__choice span.select2-selection__choice__display {
                    padding: 6px 12px 6px 8px !important;
                }

                .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
                    color: #fff !important;
                    border-right: 1px solid rgba(255,255,255,0.3) !important;
                    background-color: transparent !important;
                    font-size: 16px !important;
                    line-height: 1 !important;
                    opacity: 1 !important;
                    padding: 6px 8px 6px 8px !important;
                    margin: 0 4px 0 -4px !important;
                    border-radius: 4px 0 0 4px !important;
                    cursor: pointer !important;
                    display: flex !important;
                    align-items: center !important;
                    justify-content: center !important;
                }

                .select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover {
                    background-color: rgba(255,255,255,0.2) !important;
                    color: #fff !important;
                    border-radius: 4px 0 0 4px !important;
                    transition: background-color 0.2s ease !important;
                }

                /* Additional fixes for text visibility */
                .select2-container--default .select2-selection--multiple .select2-selection__choice * {
                    color: #fff !important;
                    opacity: 1 !important;
                    visibility: visible !important;
                }

                /* Fix for any potential overlay issues */
                .select2-container .select2-selection--multiple .select2-selection__rendered {
                    padding: 2px 5px !important;
                }

                .select2-container--default .select2-selection--multiple {
                    min-height: 40px !important;
                    padding-bottom: 5px !important;
                }

                /* Delay Unit Dropdown Alignment Fix */
                .wpwfw_delay_unit {
                    display: inline-block !important;
                    vertical-align: top !important;
                    margin-left: 5px !important;
                    height: 34px !important;
                    line-height: 2 !important;
                }

                .wpwfw_delay {
                    display: inline-block !important;
                    vertical-align: top !important;
                }

                .debug-workspace {
                    width: 800px;
                }

                .debug-workspace .debug-workspace-title {
                    margin-top: 20px;
                    padding: 10px 10px 10px 10px;
                    font-family: 'Roboto', sans-serif;
                    font-size: 1.1em;
                    color: #fff;
                    background-color: #3D9970;
                    -webkit-border-radius: 6px 6px 0px 0px;
                    -moz-border-radius: 6px 6px 0px 0px;
                    border-radius: 6px 6px 0px 0px;
                    user-select: none;
                    -ms-user-select: none; /* IE10+, Edge */
                    -moz-user-select: none; /* Mozilla */
                    -webkit-user-select: none; /* Safari */
                }

                .debug-workspace .debug-workspace-content {
                    background-color: #2c3e50;
                    color: #fff;
                    font-family: Courier, sans-serif;
                    font-weight: 400;
                    line-height: 1.3;
                    padding: 20px 25px;
                    overflow-y: scroll;
                    height: 200px;
                    max-width: 100%;
                    word-wrap: break-word;
                    box-shadow: inset -7px -5px 25px 0 rgb(0 0 0 / 8%);
                }

                /* Social Media */
                .telegram-btn {
                    background-color: #0088cc;   
                    box-shadow: 0 4px 5px rgba(0, 0, 0, 0.3);   
                    display: inline-flex ;   
                    justify-content: center;    
                    text-decoration: none;   
                    height: 60px;   
                    border-radius: 50px;  
                }

                .telegram-btn:hover {
                    opacity:80%;
                }

                span.telegram-text {
                    color: white;   
                    font-size: 18px;      
                    padding: 20px;
                    font-weight: 400;
                    margin: 0;
                }

                .facebook-btn {
                    background-color: #ffffff;
                    box-shadow: 0 4px 5px rgb(0 0 0 / 30%);
                    display: inline-flex;
                    justify-content: center;
                    text-decoration: none;
                    height: 60px;
                    border-radius: 50px;
                }

                .facebook-btn:hover {
                    opacity: 80%;
                }

                span.facebook-text {
                    color: #1877f2;
                    font-size: 18px;
                    padding: 20px;
                    font-weight: 400;
                    margin: 0;
                }

                .helpdesk-btn {
                    background-color: #ffffff;
                    box-shadow: 0 4px 5px rgb(0 0 0 / 30%);
                    display: inline-flex;
                    justify-content: center;
                    text-decoration: none;
                    height: 60px;
                    border-radius: 50px;
                }

                .helpdesk-btn:hover {
                    opacity: 80%;
                }

                span.helpdesk-text {
                    color: #1877f2;
                    font-size: 18px;
                    padding: 20px;
                    font-weight: 400;
                    margin: 0;
                }

                /* For smaller screen */
                @media (max-width: 1000px), (min-width: 470) {

                    .topbar {
                        grid-template-columns: 90px 8fr 135px 20px;
                    }

                    .logo img {
                        content:url( <?php echo WPWFW_Core()->system->get_plugin_icon( 'top-nav-simple' ); ?> );
                        width: 65px !important;
                        height: 45px !important;
                    }

                    .sidebar {
                        width: 10px;
                    }

                    .sidebar ul li a div {
                        display: none;
                    }

                }

                @media (max-width: 568px) {
                    .topbar {
                        width: 575px !important;
                        max-width: 575px;
                    }
                }

                @media (max-width: 553px) {
                    .topbar {
                        max-width: 533px !important;
                    }

                    span.telegram-text, span.facebook-text, span.helpdesk-text {
                        display: none;
                    }
                }

                <?php
                if ( isset( $this->override_config ) && !empty( $this->override_config ) ) {

                    echo $this->override_config['THEME_COLOR'];

                }
                ?>

                
            </style>
        <?php

    }

    private function dashboard_page_js() {

        ?>
            <script>
                jQuery( document ).ready( function ( $ ) {
                    console.log('Dashboard Ready...');

                    $('.bulk_action').on( 'click', function(e) {
                        e.preventDefault();
                    } );

                    $('.wpwfw_dash_select_all').on( 'click', function() {
                        
                        let table = $(this).closest('table');
                        table.find('.select-row:checkbox').prop('checked', this.checked);
                        // $('.select-row').prop('checked', this.checked);
                    } );

                    $('.select-row').on( 'click', function() {
                        let table = $(this).closest('table');
                        if ($('.select-row:checked').length == $('.select-row').length) {
                            table.find('.wpwfw_dash_select_all').prop('checked', true);
                        } else {
                            table.find('.wpwfw_dash_select_all').prop('checked', false);
                        }
                    } );

                    $('.wpwfw_dash_resend_bulk').on( 'click', function() {
                        var selectedRows = $('.select-row:checked').closest('tr');
                        selectedRows.each(function() {
                        var date = $(this).find('td:eq(1)').text();
                        var task = $(this).find('td:eq(2)').text();
                        var id = $(this).find('td:eq(3)').text();
                        var status = $(this).find('td:eq(4)').text();
                        var phone = $(this).find('td:eq(5)').text();
                        var error = $(this).find('td:eq(6)').text();
                        // You can now use these variables to resend the data or perform other actions.
                        });
                    } );
                    $('.wpwfw_dash_resend').on( 'click', function(e) {
                        e.preventDefault();
                        let $row_event = $( this );
                        let payload = [];
                        let row = $(this).closest('tr');
                        let $table = $( this ).closest('tbody');
                        let $row_sum = $table.find('tr').length;
                        payload.push( {
                            row: row.find('td:eq(3)').attr('id'),
                            date: row.find('td:eq(1)').text(),
                            type: row.find('td:eq(2)').attr('class'),
                            id: row.find('td:eq(3)').text(),
                            status: row.find('td:eq(4)').text(),
                            phone: row.find('td:eq(5)').text(),
                            error: row.find('td:eq(6)').text(),
                        } );
                        // You can now use these variables to resend the data or perform other actions.

                        console.log( payload );

                        $row_event.text( 'Sending...');
                        
                        jQuery.ajax( {
                            type: "POST",
                            url: wpwfw_ajax.ajax_url,
                            data: {
                                action: 'wa_resend_message',
                                nonce: wpwfw_ajax.ajax_nonce,
                                payload: payload,
                                task: 'single',
                            },
                            success: function( response ) {

                                console.log( response );

                                let payload = response.data;

                                if ( !payload ) {
                                    createNotice( $table, 'Resend unsuccessful due to an error' );
                                    console.log( 'No payload `data`');
                                    return false;
                                }

                                if ( 'data' in payload ) {

                                    try {
                                        payload.data = JSON.parse( payload.data );
                                    } catch ( e ) {
                                        payload = {};
                                        console.log('payload.data is not a `object` type (' + typeof payload.data + ')');
                                    }

                                    let success_data = ( 'success' in payload.data ) ? payload.data.success : [] ;
                                    let error_data = ( 'error' in payload.data ) ? payload.data.error : [] ;

                                    success_data.forEach(data => {
                                        let $row = $table.find('tr > td[id="' + data.row + '"]').closest('tr');
                                        let type = $row.find('td:eq(2)').text();

                                        if ( type.includes('cart') ) {
                                            let item_data = $('.cart-sent-stat').find('.summary-item-data');
                                            let stat = item_data.text();
                                            if ( isNaN( stat ) ) stat = 0;
                                            stat++
                                            item_data.text(stat);
                                        }
                                        else {
                                            let item_data = $('.order-sent-stat').find('.summary-item-data');
                                            let stat = item_data.text();
                                            if ( isNaN( stat ) ) stat = 0;
                                            stat++
                                            item_data.text(stat);
                                        }
                                        let item_data = $('.message-sent-stat').find('.summary-item-data');
                                        let stat = item_data.text();
                                        if ( isNaN( stat ) ) stat = 0;
                                        stat++
                                        item_data.text(stat);

                                        $row.remove();
                                        let row_balance = $row_sum - 1;
                                        if ( row_balance == 0 ) {
                                            $table.append(
                                                '<tr><td colspan="8" align="center">No log available</td></tr>'
                                            );
                                        }
                                    });

                                    error_data.forEach(data => {
                                        let $row = $table.find('tr > td[id="' + data.row + '"]').closest('tr');
                                        $row.find('td:eq(6)').text(data.message);

                                        let bg_color = $row.css("background-color");
                                        let color = $row.css("color");
                                        $row.css("background-color", "red");
                                        $row.find('td').css("color", "white");

                                        setTimeout( function() {
                                            $row.css("background-color", bg_color);
                                            $row.find('td').css("color", color);
                                        }, 1000 );
                                    });

                                }

                                let notice_message = ( 'message' in payload ) ? payload.message : 'Task completed';
                                createNotice( $table, notice_message );

                            },
                            error: function( error ) {

                                createNotice( $table, 'Resend unsuccessful due to an error' );
                                console.log('Error: ' + error.responseText );

                            },
                            complete: function ( ) {
                                $row_event.text( 'Resend');
                            }

                        } );
                    } );

                    function createNotice( target, message = 'Fatal error', timeout = 5000 ) {

                        if ( !target || !target.length ) return false;
                        let notice_message = ( message ) ? message : "Task completed";
                        let notice_el = $('.dash-resend-notice');
                        if ( notice_el.length != 0 ) {
                            notice_el.html('<p><strong>' + notice_message + '</strong></p>');
                        } else {
                            $('<div class="notice notice-info dash-resend-notice"> <p><strong>' + notice_message + '</strong></p></div>').insertBefore(target.closest('table'));
                            notice_el = $('.dash-resend-notice');
                        }
                        if ( notice_el.data('fadeOutTimer') ) clearTimeout( notice_el.data('fadeOutTimer') );
                        notice_el.data('fadeOutTimer', setTimeout( function() {
                            notice_el.fadeOut(1, 0).remove();
                        }, timeout ) );

                        return true;

                    }
                });

            </script>

        <?php
    }

    private function settings_page_js() {
        
        ?>
            <script>
                jQuery( document ).ready( function ( $ ) {
                    console.log('Hi!');

                    <?php
                    $settings = $this->settings;
                    echo 'let isDebugEnabled = ';
                    echo ( isset( $settings['debug'] ) && !empty( $settings['debug'] ) ) ? $settings['debug'] : 0 ;
                    echo ';' . PHP_EOL;
                    ?>
                    /**
                     * Init variables
                     */
                    let cart_shortcode_group = new Array();
                    <?php
                        $integrations = array_keys( WPWFW_Core()->services->cart_abandon_events );
                        foreach ( $integrations as $integrations => $integration ) {
                            $integration_shortcode = ( $integration == 'cart-others') ? array( 'shop_name'  => 'shop_name' ) : WPWFW_Core()->integration->get_integration_shortcodes( $integration );
                            $integration_keys = [];
                            foreach ( $integration_shortcode as $shortcode => $shortcode_key ) {
                                array_push( $integration_keys, "{ id: '$shortcode', 'text': '" . ucwords( str_replace( ['-', '_'], ' ', $shortcode ) ) . "'}");
                            }
                            echo ( count( array_keys( $integration_shortcode ) ) != 0 ) ? "cart_shortcode_group['" . $integration . "'] = " . "{ id: '', text: '" . $integration . "', children: [" . implode( ",", $integration_keys ) . "], }" . PHP_EOL : '' . PHP_EOL;
                        }
                    ?>

                    if ( $( ".cart_abandon_plugin" ).length ) {
                        var plugin_options = {};
                        <?php 
                            foreach ( array_keys(WPWFW_Core()->services->cart_abandon_events) as $integration) {
                                foreach ( WPWFW_Core()->integration->get_integration_options($integration) as $options => $option ) {
                                    echo "plugin_options['$integration'] = {}" . PHP_EOL;
                                    echo "plugin_options['$integration']['$options'] = " . get_option($option, -1) . PHP_EOL;
                                }
                            }
                        ?>
                        for (var integration in plugin_options) {
                            for (var option in plugin_options[integration]) {
                                $('#table-tab-cart-abandon-follow-up table > tbody').append(
                                    '<tr valign="top" class="' + option + ' ' + integration + ' elem-hide"><th scope="row">' + option + '</th><td>' + plugin_options[integration][option] + '</td></tr>'
                                );
                            }
                        }
                    }

                    /* Init */

                    function init_menu() {
                        let url = new URL( window.location.href );
                        let path = url.searchParams.get( "path" );
                        if ( !path ) return;

                        $( '#toplevel_page_wasapbot .wp-submenu .wp-first-item.current' ).removeClass( 'current' );
                        if ( path == "/settings") {
                            let setting_el = $( '#toplevel_page_wasapbot .wp-submenu li a:contains("Settings")' );
                            setting_el.closest( 'li' ).addClass( 'current' );
                            setting_el.addClass( 'current' );
                        }
                    }
                    init_menu();

                    function init_form() {
                        $( '.sales-group' ).hide();
                        $( '.debug-group' ).hide();
                        $( '.shortcode-helper-desktop' ).hide();
                        $( '.shortcode-helper-mobile' ).hide();
                        
                        if ( $( '.sales-notification' ).attr( 'checked' ) ) {
                            $( '.sales-group' ).show();
                        }

                        if ( $( '.debug' ).attr( 'checked' ) ) {
                            $( '.debug-group' ).show();
                        }

                        if ( !isUserMobile() ) $( '.shortcode-helper-desktop' ).show(); // navigator.userAgentData.mobile (Unsupported in Mozilla Firefox & Safari)
                        else $( '.shortcode-helper-mobile' ).show();

                        if ( $( ".cart_abandon_plugin" ).length ) {
                            let preferred_id = $( ".cart_abandon_plugin" ).val();
                            console.log( 'loading initial cart abandon id: ' + preferred_id);
                            $( "#" + preferred_id + '-fragment' ).toggleClass( 'elem-show elem-hide' );
                            
                            if ( cart_shortcode_group[preferred_id] ) {
                                $( ".cart-select" ).empty().append('<option value="default">Select a shortcode to copy</option>').prop( 'disabled', false ).select2({
                                    width: "400px",
                                    data: [cart_shortcode_group[preferred_id]]
                                });
                            }
                        } 

                        let get_params = get_url_query();
                        if ( 'focus' in get_params ) {
                            if ( get_params['focus'] == 'connection') {
                                let eventId = 'connection';
                                set_settings_view( eventId );
                                return;
                            }
                        }

                        if ( $( ".wpwfw_instance_id" ).val() == '' && $( ".wpwfw_access_token" ).val() == '' ) {
                            set_settings_view( 'support' );
                            return;
                        }

                        let plugin = $( ".cart_abandon_plugin" ).val();
                        if ( plugin ) {
                            if (plugin_options && plugin_options[plugin]) {
                                let ca_time = plugin_options[plugin]['ca_time'];
                                $('.cart-integration-capture').find('td').html((ca_time) ? ca_time + ' min(s)' + '<p><span><i class="fa fa-circle-info" aria-hidden="true"></i></span><em><?php _e( 'This value refers to `Cut-off Time` set in your cart abandonment plugin settings', WPWFW_PLUGIN_DOMAIN_LANGUAGE ); ?></em></p>': 'Not configured');
                            }
                        }
                        
                    }
                    init_form();

                    /* Select2 Initialize */
                    $( 'select[data-role="select2"]' ).select2({
                        width: '400px'
                    });

                    /* Force text padding after Select2 initialization */
                    setTimeout(function() {
                        $('.select2-selection__choice__display').each(function() {
                            $(this).css({
                                'padding': '6px 12px 6px 8px',
                                'padding-left': '8px',
                                'padding-right': '12px',
                                'box-sizing': 'border-box'
                            });
                        });
                    }, 100);

                    /* Also apply padding when new selections are made */
                    $('select[data-role="select2"]').on('select2:select select2:unselect', function() {
                        setTimeout(function() {
                            $('.select2-selection__choice__display').each(function() {
                                $(this).css({
                                    'padding': '6px 12px 6px 8px',
                                    'padding-left': '8px',
                                    'padding-right': '12px',
                                    'box-sizing': 'border-box'
                                });
                            });
                        }, 50);
                    });

                    $( '.accordion-panel' ).on( 'click', function(e) {
                        // console.log( e.target.nodeName );
                        if ( e.target.className == 'accordion-panel-fragment-content' ) return;
                        if ( e.target.nodeName == 'P' || e.target.nodeName == 'STRONG' || e.target.nodeName == 'LI' || e.target.nodeName == 'IMG' || e.target.nodeName == 'FIGURE' ) return; 
                        if ( $( this ).prev().html().indexOf( 'Send a test message' ) != -1 ) return;
                        $( this ).prev().click();
                    } );

                    $('#log_download_button').on('click', async function(e) {
                        e.preventDefault();
                        // Get the selected log type
                        var logType = $('.debug-workspace-log-type').val();
                        var fileName = logType + '.log';
                        console.log('Downloading ' + fileName +'...');
                        
                        // Perform Ajax request to download the log file
                        let result = await wp_ajax( {
                            action: 'download_log_file',
                            log_type: logType,
                            nonce: wpwfw_ajax.ajax_nonce,
                        } );

                        if (result.status == 'success') {
                            // Decode the base64 encoded data if it exist
                            const content = atob(result.data.file_content);

                            // Create a blob object from the decoded content
                            const blob = new Blob([content], { type: 'text/plain;charset=utf-8' });

                            // Create a hidden anchor element
                            const downloadLink = document.createElement('a');
                            downloadLink.href = URL.createObjectURL(blob);
                            downloadLink.download = fileName;
                            downloadLink.style.display = 'none';
                            document.body.appendChild(downloadLink);

                            // Trigger a click event on the anchor element to download the file
                            downloadLink.click();

                            // Remove the anchor element from the DOM
                            document.body.removeChild(downloadLink);
                            URL.revokeObjectURL(downloadLink.href);
                            return true;
                        } 

                        toast_message( '.card > h3 ', 'error', result.message, false );

                    });

                    $( '#wpwfw_disconnect' ).on( 'click', async function(e) {
                        let result = await wp_ajax( {
                            action: 'wa_disconnect',
                            nonce: wpwfw_ajax.ajax_nonce,
                        } );

                        console.log(result);
                        if ( result.status == 'success') {
                            location.reload();
                            return true;
                        };

                        if ( wa_error.indexOf( result.message ) != -1 ) set_settings_view( 'connection' );
                        toast_message( '.card > h3 ', 'error', result.message, false );

                    });

                    $( 'form' ).submit( async function(e) {
                        e.preventDefault();
                        let required_fields = new Array();
                        <?php
                            foreach ( WPWFW_Core()->ui->form_required_fields as $field ) {
                                echo "required_fields.push('wpwfw_settings[" . $field . "]');" .PHP_EOL; 
                            }
                        ?>
                        let e_aftersales_delay = new Array();
                        <?php
                            foreach ( WPWFW_Core()->services->aftersale_events as $field => $field_value ) {
                                echo "e_aftersales_delay.push('wpwfw_settings[" . $field . '_delay' . "]');" .PHP_EOL; 
                            }
                        ?>
                        let form = $( '#form-settings' ).serializeArray();
                        let data = {};
                        let err = [];
                        $.each( form, function( i, field ) {
                            if ( required_fields.includes( field.name ) && field.value == '' ) err.push( field.name );
                            if ( e_aftersales_delay.includes( field.name ) && ( field.value == '' || parseInt( field.value ) < 1 ) ) err.push( field.name );

                            let matches = field.name.match(/\[(.*?)\]/g);
                            if (matches !== null) {
                                let key = matches[0].replace(/\[|\]/g, '');
                                // If the key is not already in the data object, create an empty array for it
                                if (!data[key]) {
                                    data[key] = [];
                                }
                                // Add the value to the array
                                data[key].push(field.value);
                            }
                        });

                        console.log( data );

                        if ( !('default_events' in data) && !('custom_events' in data) ) {
                            toast_message( '.card > h3 ', 'error', 'At least one (1) event must be selected', false );
                            return false;
                        }

                        // Validate custom status templates - if custom status is enabled, template must not be empty
                        if ('custom_events' in data) {
                            let custom_events = data.custom_events;
                            let missing_templates = [];
                            
                            // Get all custom status names
                            <?php 
                            $custom_status = WPWFW_Core()->services->custom_status;
                            foreach ( $custom_status as $status => $status_name ) {
                                echo "if (custom_events.includes('$status') || custom_events.includes('all')) {\n";
                                echo "    let template_field = $('textarea[name=\"wpwfw_settings[$status]\"]');\n";
                                echo "    if (template_field.length && template_field.val().trim() === '') {\n";
                                echo "        missing_templates.push('" . ucwords($status_name) . "');\n";
                                echo "    }\n";
                                echo "}\n";
                            }
                            ?>
                            
                            if (missing_templates.length > 0) {
                                let error_msg = 'Please provide notification templates for enabled custom statuses: ' + missing_templates.join(', ');
                                toast_message( '.card > h3 ', 'error', error_msg, false );
                                set_settings_view( 'notification' );
                                return false;
                            }
                        }

                        if ( err.length != 0) {
                            console.log( err.join( ', ') + ' is required' );
                            toast_message( '.card > h3 ', 'error', 'Please fill all the required fields correctly' );

                            let id = err[0];
                            let classes = $('#' + id.replace(/([\[\]])/g, "\\$1") ).closest('.card-content').attr('class');
                            if ( !classes  ) return false;

                            let core_class = classes.split(' ').filter(function(obj) { return ['notification', 'support', 'connection', 'general', 'advanced'].indexOf(obj) != -1 }).toString();
                            if ( set_settings_view( core_class ) ) $( '#' + id.replace(/([\[\]])/g, "\\$1") ).focus() ;
                            
                            return false;
                        }

                        // Validate cart abandon timer
                        if ( $( ".cart_abandon_plugin" ).length ) {
                            let plugin = $( ".cart_abandon_plugin" ).val();
                            if (plugin_options) {
                                let ca_time = $('.ca_time.' + plugin).find('td').text();
                                let ca_time_appropriate = (ca_time) ? Number(ca_time) + 5 : 10;
                                let ca_time_validate = [];
                                if (ca_time) {
                                    $('[class^="cart_abandon_followup_"]').each(function() {
                                        let event = capitalizeText($(this).attr('class').replace(/_/g, ' '));

                                        let msg = $(this).find('textarea').val();
                                        let delay = $(this).find('.wpwfw_delay').val();
                                        let unit = $(this).find('.wpwfw_delay_unit').val();
                                        // Perform actions with the value of the input
                                        if (unit === 'hour') delay = delay * 60;
                                        if (unit === 'day') delay = delay * 24 * 60;

                                        
                                        if (delay < ca_time_appropriate && msg) ca_time_validate.push(event);
                                    });

                                    if (ca_time_validate.length > 0) {
                                        let ca_time_error = 'Minimum of ' + (ca_time_appropriate) + ' (mins) is allowed for `' + ca_time_validate.join(', ') + '`';
                                        console.log(ca_time_error);
                                        set_settings_view( 'notification' );
                                        $('#tab-cart-abandon-follow-up').click();
                                        toast_message( '.card > h3 ', 'error', ca_time_error, false );
                                        return false;
                                    }
                                }
                            }
                            
                        }

                        let validate = await wp_ajax( {
                            action: 'wa_check_instance',
                            nonce: wpwfw_ajax.ajax_nonce,
                            subdomain: $( '#wpwfw_settings\\[subdomain\\]' ).val(),
                            access_token: $( '#wpwfw_settings\\[access_token\\]' ).val(),
                            instance_id: $( '#wpwfw_settings\\[instance_id\\]' ).val(),
                        } );

                        console.log(validate);
                        if ( validate.status == 'success') {
                            $( 'form' ).unbind( 'submit' );
                            $( '.form-submit' ).click();
                            return true;
                        };

                        let wa_error = ['Login required', 'Access token does not exist', 'Instance ID Invalidated 2']
                        if ( wa_error.indexOf( validate.message ) != -1 ) set_settings_view( 'connection' );
                        toast_message( '.card > h3 ', 'error', validate.message, false );

                        return false;
                        
                    });
                    
                    $( '.sidebar a' ).on( 'click', function () {
                        let eventId = $(this).attr("id");
                        if ( !eventId ) {
                            console.log('Event/Sidebar: Invalid id');
                            return false;
                        }
                        set_settings_view( eventId );
                    });

                    $( '.card-tab a' ).on( 'click', function() {
                        let eventId = $(this).attr("id");
                        console.log('id: '+eventId);
                        if (!eventId) {
                            console.log('Event/Tab: Invalid Id');
                            return false;
                        }
                        // Get active tab
                        let currentId = $('.nav-tab.nav-tab-active').find('a').attr("id");
                        $('.nav-tab').removeClass('nav-tab-active');
                        $(this).closest('li').addClass('nav-tab-active');
                        $('.table-tab:not(#table-'+eventId+')').removeClass('elem-show').addClass('elem-hide');
                        $('.table-tab#table-'+eventId).removeClass('elem-hide').addClass('elem-show');

                        if ( eventId == 'tab-cart-abandon-follow-up' && currentId != 'tab-cart-abandon-follow-up' ) {
                            $( '.shortcode-order' ).toggleClass( 'elem-show elem-hide');
                            $( '.shortcode-cart' ).toggleClass( 'elem-show elem-hide');
                        } else if ( eventId != 'tab-cart-abandon-follow-up' && currentId == 'tab-cart-abandon-follow-up' ) {
                            $( '.shortcode-order' ).toggleClass( 'elem-show elem-hide');
                            $( '.shortcode-cart' ).toggleClass( 'elem-show elem-hide');
                        }
                        
                    });

                    $('.accordion').on( 'click', function(e) {
                        $(this).toggleClass( 'accordion-active' );
                        $(this).next().toggle();
                        e.preventDefault();
                    });

                    $('.ws-smart-helper').on( {
                        mouseenter: function(e) {
                            let type = $(this).attr( 'data-type' );
                            if ( !type ) return;
                            if ( type == 'img') {
                                let src = $(this).attr( 'data-image-src' );
                                if (!src) src = '<?php echo $this->assets_remote_url . '/img/loading-media.gif'; ?>';
                                $(this).closest('.ws-smart-helper-label').append('<div class="ws-smart-helper-popup"><img src="'+src+'"></div>');
                            }
                            else if ( type == 'text') {
                                let content = $(this).attr( 'data-content' );
                                if (!content) content = '<i>Content not available</i>';
                                $(this).closest('.ws-smart-helper-label').append('<div class="ws-smart-helper-popup">' + $content + '</div>');
                            }
                        }, 
                        mouseleave: function() {
                            if ( $('.ws-smart-helper-popup').length ) {
                                $('.ws-smart-helper-popup').remove();
                            }
                        }
                    } );

                    // Cart handler
                    if ( $( ".cart_abandon_plugin" ).length ) {
                        $( ".cart_abandon_plugin" ).on( 'change', function (e) {
                            let selected_preferred = $(this).val();
                            console.log( 'preferred plugin: ' + selected_preferred );
                            // Desktop handler
                            <?php foreach ( array_keys( WPWFW_Core()->services->cart_abandon_events ) as $integrations => $integration ) {
                                echo "$( '#" . $integration . "-fragment' ).removeClass( 'elem-show' ).addClass( 'elem-hide' )" . PHP_EOL; 
                            } ?>
                            $( "#no-data-fragment").removeClass( 'elem-show' ).addClass( 'elem-hide' );

                            if ( $( "#" + selected_preferred + '-fragment' ).length ) $( '#' + selected_preferred + '-fragment' ).toggleClass( 'elem-hide elem-show' );
                            else $( "#no-data-fragment").toggleClass( 'elem-show elem-hide' );

                        } );

                        $( ".cart_abandon_plugin" ).select2().change( function(e) {
                            let selected_preferred = $(this).val();
                            // Mobile handler
                            console.log( !cart_shortcode_group[selected_preferred] );
                            let csg_placeholder = ( !cart_shortcode_group[selected_preferred] ) ? 'Unavailable' : 'Select a shortcode to copy';
                            let csg_data = ( !cart_shortcode_group[selected_preferred] ) ? [{ id: 'default', text: 'Unavailable'}] : [cart_shortcode_group[selected_preferred]];
                            let csg_disabled = ( !cart_shortcode_group[selected_preferred] ) ? true: false;
                            $( ".cart-select" ).empty().append('<option value="default">' + csg_placeholder + '</option>').prop( 'disabled', csg_disabled ).select2({
                                width: "400px",
                                data: csg_data
                            });
                        } );
                    }

                    // Mobile shortcode helper
                    $( '.shortcode-helper' ).on( 'change', function () {
                        if ($( this ).val() == 'default') return;
                        if ( shortcode_helper_copy_variable( $( this ).val() )) {
                            $( '.shortcode-helper-mobile th' ).append( '<span class="helper-item-copy" style="background-color:#38c3ff; color: #000;white-space: nowrap; border-radius:10px;margin-left:2px;font-size:10px;padding:3px 10px;">Copied</span>' );
                            setTimeout(()=>{
                                $('.helper-item-copy').remove();
                            }, 2000);
                        }
                    } );
                    var shortcode_helper_copy_variable = function ( value ) {
                        navigator.clipboard.writeText('%'+value+'%');
                        return true;
                    };

                    // Desktop shortcode helper
                    $(document).on('click', '.shortcode-helper-item', function(e) {
                        let item = $(this).text().split(': ').pop();
                        navigator.clipboard.writeText(item);
                        $('.helper-item-copy').remove();
                        $(this).append('<span class="helper-item-copy" style="background-color:#38c3ff; color: #000;white-space: nowrap; border-radius:10px;margin-left:2px;font-size:10px;padding:3px 10px;">Copied</span>');
                        setTimeout(()=>{
                            $('.helper-item-copy').remove();
                        }, 2000);
                        e.preventDefault();
                    });

                    $(document).on({
                        mouseenter: function(e) {
                            $(this).css('cursor', 'pointer');
                        },
                        mouseleave: function(e) {
                            $(this).css('cursor', 'auto');
                        }
                    }, '.shortcode-helper-item');

                    // Custom shortcodes handler
                    var custom_shortcodes_textarea = $('#wpwfw_settings\\[custom_shortcodes\\]');
                    var custom_shortcodes_container = $('#custom-shortcodes-container');

                    function update_custom_shortcode_buttons() {
                        custom_shortcodes_container.empty();
                        var shortcodes = custom_shortcodes_textarea.val().split('\n');
                        shortcodes.forEach(function(shortcode) {
                            shortcode = shortcode.trim();
                            if (shortcode) {
                                var button_text = shortcode.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase());
                                var button = $('<p class="shortcode-helper-item" style="display: inline-block; margin-right: 5px; margin-bottom: 5px; background-color: #f0f0f0; padding: 5px 10px; border-radius: 4px;"><strong>' + button_text + '</strong>: %' + shortcode + '%</p>');
                                custom_shortcodes_container.append(button);
                            }
                        });
                    }

                    // Initial generation
                    update_custom_shortcode_buttons();

                    // Update on change
                    custom_shortcodes_textarea.on('input', update_custom_shortcode_buttons);

                    // Default message template handler
                    $( '.default_events' ).on( 'change', function () {
                        update_default_template( $( this ).val() );
                    } );
                    var update_default_template = function ( value ) {
                        if ( value == '' ) {
                            value = $( '.default_events  option:selected' ).val();
                        }

                        var default_messages = new Array();
                        <?php 
                        foreach( WPWFW_Core()->services->default_selectable_status as $key => $value ) {
                            if ( $key == 'received_message' && false ) continue; // for now we allow this
                            echo "default_messages[ '$key' ] = '$value';" . PHP_EOL; 
                        }
                        ?>

                        for ( var item in default_messages ) {
                            $( '.' + item ).hide();
                            for ( var status in value ) {
                                if ( item == value[ status ] || value[ status ] == 'all' ) {
                                    $( '.' + item ).show();
                                }
                            }
                        }
                    };

                    $( '.default_events' ).each( function ( i, selected ) {
                        update_default_template( $( selected ).val() );
                    } );

                    // Custom message template handler
                    $( '.custom_events' ).on( 'change', function () {
                        update_custom_template( $( this ).val() );
                        
                        // Auto-populate template if field is empty and predefined template exists
                        let selectedValue = $( this ).val();
                        if (selectedValue && Array.isArray(selectedValue)) {
                            selectedValue.forEach(function(status) {
                                if (status !== 'all') {
                                    let templateField = $('textarea[name="wpwfw_settings[' + status + ']"]');
                                    if (templateField.length && templateField.val().trim() === '') {
                                        // Try to populate with predefined template
                                        <?php
                                        foreach ( $custom_status as $status => $status_name ) {
                                            $predefined_template = WPWFW_Core()->integration->get_integration_notification_template( $status );
                                            if ( !empty( $predefined_template ) ) {
                                                echo "if (status === '$status') {\n";
                                                echo "    templateField.val(" . json_encode( $predefined_template ) . ");\n";
                                                echo "}\n";
                                            }
                                        }
                                        ?>
                                    }
                                }
                            });
                        }
                    } );
                    var update_custom_template = function ( value ) {

                        var custom_messages = new Array();
                        <?php 
                        foreach( WPWFW_Core()->services->custom_status as $key => $value ) {
                            echo "custom_messages[ '$key' ] = '$value';" . PHP_EOL; 
                        }
                        ?>

                        for ( var item in custom_messages ) {
                            $( '.' + item ).hide();
                            for ( var status in value ) {
                                if ( item == value[ status ] ) {
                                    $( '.' + item ).show();
                                }
                            }
                        }
                    };

                    $( '.custom_events' ).each( function ( i, selected ) {
                        update_custom_template( $( selected ).val() );
                    } );

                    $( '.sales-notification' ).on( 'change', function() {
                        $( '.sales-group' ).hide();
                        if (this.checked) {
                            $( '.sales-group' ).show();
                        }
                    });

                    $( 'button.sales-group.tooltip-btn' ).on( 'click', function(e) {
                        set_settings_view( 'notification' );
                        $( '.form-table.sales-group' ).focus();
                        e.preventDefault();
                    });

                    $( '.wpwfw_delay' ).on( {
                            input: function(e) {
                                let millisDate = <?php print_r(json_encode(WPWFW_Core()->services->events_delay_data)); ?>;

                                let val = Math.ceil( $( this ).val() );
                                let val_unit = $( this ).closest('label').find('.wpwfw_delay_unit' ).val() ?? 'hour';
                                let estimate = millisToMinutesAndSecondsReadable( val * millisDate[val_unit] );
                                let el = $( this ).closest('.form-label').next();
                                if (!el) return;
                                let content_msg = ( $( this ).attr('class').includes('abandon') ) ? ' after cart is abandoned' : ' after order is completed';
                                let content = '<p><em>Estimated delivery: ' + estimate + content_msg + '.</em></p>';
                                if ( el.hasClass( 'elem-show' ) ) el.html( content );
                                else el.removeClass( 'elem-hide' ).addClass( 'elem-show' ).html( content );
                            },
                            blur: function(e) {
                                let el = $( '.wpwfw_delay_estimate');
                                el.removeClass( 'elem-show' ).removeClass( 'elem-show' );
                                el.addClass( 'elem-hide' );
                            }
                    });

                    $( '.debug' ).on( 'change', function() {
                        $( '.debug-group' ).hide();
                        if (this.checked) {
                            $( '.debug-group' ).show();
                        }
                    });

                    // Functions

                    function isUserMobile( agent = '' ) {
                        var isMobile = {
                            Android: function() {
                                return navigator.userAgent.match(/Android/i);
                            },
                            BlackBerry: function() {
                                return navigator.userAgent.match(/BlackBerry/i);
                            },
                            iOS: function() {
                                return navigator.userAgent.match(/iPhone|iPad|iPod/i);
                            },
                            Opera: function() {
                                return navigator.userAgent.match(/Opera Mini/i);
                            },
                            Windows: function() {
                                return navigator.userAgent.match(/IEMobile/i);
                            },
                            any: function() {
                                return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows());
                            }
                        };
                        
                        if ( !agent && isMobile.hasOwnProperty(agent) ) return isMobile[agent]() ? true : false;
                        return isMobile.any() ? true : false;
                    }

                    function get_url_query(){
                        var url = location.search;
                        var qs = url.substring(url.indexOf('?') + 1).split('&');
                        for(var i = 0, result = {}; i < qs.length; i++){
                            qs[i] = qs[i].split('=');
                            result[qs[i][0]] = decodeURIComponent(qs[i][1]);
                        }
                        return result;
                    }

                    function set_settings_view( eventId ) {
                        $( '.sidebar > ul' ).find( 'li' ).removeClass( 'sidebar-active' );
                        let target = $( 'a#' + eventId );
                        if ( !target ) return false;
                        target.closest( 'li' ).addClass( 'sidebar-active' );
                        let eventLogo = target.find( 'i' ).attr('class');
                        let eventTitle = target.find( 'div' ).html();
                        $( '.card h3' ).html('<i class="card-title '+eventLogo+'" aria-hidden="true"></i>'+eventTitle);
                        $('.card-content:not(.'+eventId+')').removeClass('elem-show').addClass('elem-hide');
                        $('.card-content.'+eventId).removeClass('elem-hide').addClass('elem-show');

                        return true;
                    }

                    async function wp_ajax( ajax_data ) {
                        return $.ajax( {
                            type: "POST",
                            url: wpwfw_ajax.ajax_url,
                            data: ajax_data,
                        } ).then(
                            response => response.data
                        );
                    }

                    function toast_message( selector, type = 'information', msg, is_after = true, timeout = 3000 ) {
                        let el = $( selector );
                        if ( !el.length ) return false;
                        let content = '<div id="form-settings-notice" class="notice notice-' + type + ' is-dismissible"><p>' + msg + '</p></div>';
                        
                        if ( !is_after ) el.before( content );
                        else el.after( content );
                        setTimeout( ()=> {
                            $( '#form-settings-notice' ).remove();
                        }, timeout );
                    }

                    function millisToMinutesAndSecondsReadable(ms) {
                        let seconds = (ms / 1000).toFixed(0);
                        let minutes = (ms / (1000 * 60)).toFixed(0);
                        let hours = (ms / (1000 * 60 * 60)).toFixed(0);
                        let days = (ms / (1000 * 60 * 60 * 24)).toFixed(0);
                        if (seconds < 60) return seconds + " Sec(s)";
                        else if (minutes < 60) return minutes + " Min(s)";
                        else if (hours < 24) return hours + " Hour(s)";
                        else return days + " Day(s)"
                    }

                    function capitalizeText(str) {
                        var words = str.toLowerCase().split(" ");

                        for (var i = 0; i < words.length; i++) {
                            words[i] = words[i].charAt(0).toUpperCase() + words[i].slice(1);
                        }

                        var result = words.join(" ");
                        return result;
                    }

                });
            </script>
        <?php

    }

    private function shortcode_helper() {
        
        $shortcodes_group = WPWFW_Core()->services->shortcodes_group;
        // @TODO: Add supported custom fields and plugin
        ?>
        <label for="shortcode-helper" class="form-label" title="<?php _e( 'Select any shortcode of your choice to copy it to the clipboard.', WPWFW_PLUGIN_DOMAIN_LANGUAGE ); ?>">
            <select class="shortcode-helper" data-role="select2" id="shortcode-helper" name="shortcode-helper" tabindex="<?php echo $this->page_tab++; ?>">
                    <option value="default" selected="selected"><?php _e('Select a shortcode to copy', WPWFW_PLUGIN_DOMAIN_LANGUAGE); ?> </option>
                    <?php foreach ( $shortcodes_group as $category => $items ) : ?>
                            <optgroup label="<?php _e( ucwords( str_replace( '_', ' ', $category ) ) , WPWFW_PLUGIN_DOMAIN_LANGUAGE ); ?>">
                                <?php foreach ($items as $item) : ?>
                                <option value="<?php echo $item ?>"> <?php _e( ucwords( str_replace( '_', ' ', $item ) ), WPWFW_PLUGIN_DOMAIN_LANGUAGE); ?> </option>
                                <?php endforeach; ?>
                            </optgroup>
                    <?php endforeach; ?>
            </select>
        </label>
        <p><em><?php _e( 'Select a variable from this menu to copy it the clipboard', WPWFW_PLUGIN_DOMAIN_LANGUAGE ); ?></em></p>
        
        <?php

    }

    private function plain_shortcode_helper() {

        ?>
            <button class="accordion" style="width: 400px;"><?php _e( 'Below is list of available shortcodes you can use in your notification &amp; follow-up message', WPWFW_PLUGIN_DOMAIN_LANGUAGE ); ?></button>
            <div class="accordion-panel" style="width: 400px;">
                <p style="padding-bottom: 10px;"><?php _e( 'Tip: Click on the shortcode to copy it to the clipboard', WPWFW_PLUGIN_DOMAIN_LANGUAGE); ?></p>
                <?php foreach ( WPWFW_Core()->services->shortcodes_group as $category => $items ) : ?>
                <div class="accordion-panel-fragment">
                    <div class="accordion-panel-fragment-title"><?php _e( ucwords( str_replace( '_', ' ', $category ) ) , WPWFW_PLUGIN_DOMAIN_LANGUAGE ); ?></div>
                    <div class="accordion-panel-fragment-content">
                        <?php foreach ( $items as $item ) : ?>
                        <p class="shortcode-helper-item"><strong><?php _e( ucwords( str_replace( '_', ' ', $item ) ), WPWFW_PLUGIN_DOMAIN_LANGUAGE); ?></strong>: %<?php echo $item ?>%</p>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endforeach; ?>
               
            </div>
            
        <?php

    }

    private function cart_shortcode_helper() {

        ?>
        <label for="shortcode-helper" class="form-label" title="<?php _e( 'Select any shortcode of your choice to copy it to the clipboard.', WPWFW_PLUGIN_DOMAIN_LANGUAGE ); ?>">
            <select class="shortcode-helper cart-select" data-role="select2" id="shortcode-helper" name="shortcode-helper" tabindex="<?php echo $this->page_tab++; ?>">
                    <option value="default" selected="selected"><?php _e('Loading...', WPWFW_PLUGIN_DOMAIN_LANGUAGE); ?> </option>
            </select>
        </label>
        <p><em><?php _e( 'Select a variable from this menu to copy it the clipboard', WPWFW_PLUGIN_DOMAIN_LANGUAGE ); ?></em></p>
        
        <?php

    }

    private function plain_cart_shortcode_helper() {

        ?>
            <button class="accordion" style="width: 400px;"><?php _e( 'Below is list of available shortcodes you can use in your notification &amp; follow-up message', WPWFW_PLUGIN_DOMAIN_LANGUAGE ); ?></button>
            <div class="accordion-panel" style="width: 400px;">
                <p style="padding-bottom: 10px;"><?php _e( 'Tip: Click on the shortcode to copy it to the clipboard', WPWFW_PLUGIN_DOMAIN_LANGUAGE); ?></p>
                <div class="accordion-panel-fragment elem-hide" id="no-data-fragment">
                    <div class="accordion-panel-fragment-title"><?php _e( 'Unavailable', WPWFW_PLUGIN_DOMAIN_LANGUAGE ); ?></div>
                    <div class="accordion-panel-fragment-content">There are no shortcode available</div>
                </div>
                <?php foreach ( WPWFW_Core()->services->cart_abandon_events as $integration => $events ) : ?>
                    <div class="accordion-panel-fragment elem-hide" id="<?php echo $integration .'-fragment'; ?>">
                        <?php if ( in_array( $integration, WPWFW_Core()->integration::$active_integrations ) ) : ?>
                        <div class="accordion-panel-fragment-title"><?php _e( ucwords( str_replace( array( '_', '-' ), ' ', $integration ) ) , WPWFW_PLUGIN_DOMAIN_LANGUAGE ); ?></div>
                        <div class="accordion-panel-fragment-content">
                            <?php foreach ( WPWFW_Core()->integration->get_integration_shortcodes( $integration) as $item => $key ) : ?>
                                <p class="shortcode-helper-item"><strong><?php _e( ucwords( str_replace( '_', ' ', $item ) ), WPWFW_PLUGIN_DOMAIN_LANGUAGE); ?></strong>: %<?php echo $item ?>%</p>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
            
        <?php

    }

    private function cart_extension_not_installed() {

        ?>
            <div class="shortcode-cart elem-hide">Enable Cart Abandon Follow Up</div>
            <figure class="quote shortcode-cart elem-hide" style="margin-bottom:20px;">
                <blockquote>
                    <p>You can enable cart abandon follow up by installing one of these supported plugins: </p>
                    <?php foreach ( array_keys( WPWFW_Core()->services->cart_abandon_events ) as $integrations => $integration ) : ?>
                        <a target="_blank" href="<?php echo admin_url( 'plugin-install.php' ); ?>?s=<?php echo urlencode( $integration ); ?>&tab=search&type=term"><?php echo ucwords( str_replace( array( '_', '-' ), ' ', $integration ) ); ?></a>
                    <?php endforeach; ?>
                </blockquote> 
            </figure>
        <?php

    }

    private function populate_cart_integration_select_field() {

        $settings = $this->settings;
        
        if ( count( array_intersect( array_keys( WPWFW_Core()->services->cart_abandon_events ), WPWFW_Core()->integration::$active_integrations ) ) == 0 ) return;
        ?>
            <label for="wpwfw_settings[cart_abandon_plugin]" class="form-label" title="<?php _e( 'Select your preferred Cart Abandon Plugin.', WPWFW_PLUGIN_DOMAIN_LANGUAGE ); ?>">
                <select class="cart_abandon_plugin" style="width:400px;" data-role="select2" id="wpwfw_settings[cart_abandon_plugin]" name="wpwfw_settings[cart_abandon_plugin]" tabindex="<?php echo $this->page_tab++; ?>">
                        <?php foreach ( WPWFW_Core()->services->cart_abandon_events as $integration => $events ) :
                            $is_selected = ( isset( $settings['cart_abandon_plugin'] ) && $settings['cart_abandon_plugin'] == $integration ) ? 'selected' : '';
                        ?>
                            <option value="<?php echo $integration ?>" selected="<?php echo $is_selected; ?>"> <?php echo ucwords( str_replace( array( '-', '_' ), ' ', $integration ) ); ?> </option>
                        <?php endforeach; ?>
                </select>
            </label>
            <p><em><?php _e( 'Select your preferred Cart Abandon Plugin', WPWFW_PLUGIN_DOMAIN_LANGUAGE ); ?></em></p>
            <p style="color:red;" class="elem-hide"><em><?php _e( 'Switching integration may delete the existing cart data', WPWFW_PLUGIN_DOMAIN_LANGUAGE ); ?></em></p>
        <?php

    }

    private function populate_products_select_field() {

        $settings = $this->settings;
        ?>
        <label for="wpwfw_settings[wc_product_filters]" class="form-label" title="<?php _e( 'Product(s) to allow for sending notification', WPWFW_PLUGIN_DOMAIN_LANGUAGE ); ?>">
            <select multiple="multiple" class="wpwfw_wc_product_filter" data-role="select2" id="wpwfw_settings[wc_product_filters]" name="wpwfw_settings[wc_product_filters][]" tabindex="<?php echo $this->page_tab++; ?>">
                <?php
                    foreach ( WPWFW_Core()->services->wc_products as $product ) {
                        $is_selected = in_array($product->ID, $settings['wc_product_filters']) ? 'selected' : '';
                        echo '<option value="' . esc_attr($product->ID) . '"' . $is_selected . '>' . esc_html($product->post_title) . '</option>' . PHP_EOL;
                    } 
                ?>
            </select>
        </label>
        <?php
    }

    private function populate_event_select_field( $event ) {

        $events = array( 
            'default_events' => array( 
                'caption' => 'Select one or more order status to send notification',
            ),
            'custom_events' => array( 
                'caption' => 'Select one or more custom order status to send notification'
            ),
            'admin_events' => array(
                'caption' => 'Select order status to receive notification'
            ),
        );

        if ( !array_key_exists( $event, $events ) ) {
            echo '<div class="wpwf-ui-element-danger"><b>Failed to build component '. $event . '</b></div>';
            return;
        }

        $settings = $this->settings;

        if ( $event == 'default_events' ) $select = WPWFW_Core()->services->default_selectable_status;
        else if ( $event == 'custom_events' ) $select = WPWFW_Core()->services->custom_status;
        else if ( $event == 'admin_events' ) $select = array(
            'pending_message'		=> __( 'Order Pending Custom Message', WPWFW_PLUGIN_DOMAIN_LANGUAGE ),
            'processing_message'	=> __( 'Order Processing Custom Message', WPWFW_PLUGIN_DOMAIN_LANGUAGE ),
        );

        if ( $event != 'admin_events' ) :
        ?>
        <label for="wpwfw_settings[<?php echo $event ?>]" class="form-label" title="<?php _e( 'Event to send order notification', WPWFW_PLUGIN_DOMAIN_LANGUAGE ); ?>">
            <select multiple="multiple" class="<?php echo $event ?>" data-role="select2" id="wpwfw_settings[<?php echo $event ?>]" name="wpwfw_settings[<?php echo $event ?>][]" tabindex="<?php echo $this->page_tab++; ?>">
                <?php
                    foreach ( $select as $value => $name) {
                        if ( $value == 'received_message' && false ) continue; // for now we allow it
                        $is_selected = '';
                        if ( isset( $settings[$event] ) && in_array( $value, $settings[$event] ) ) $is_selected = ' selected="selected"';
                        // Enable by default for default_events. Only true if custom events is not defined
                        else if ( !isset( $settings[$event] ) && $value == 'processing_message' && empty( $settings['custom_events'] ) ) $is_selected = ' selected="selected"';
                        else if ( !isset( $settings[$event] ) && $value == 'completed_message' && empty( $settings['custom_events'] ) ) $is_selected = ' selected="selected"';
                        // Enable by default for integrations. Only true if default events is not defined
                        else if ( !isset( $settings[$event] ) && $value == WPWFW_Core()->integration->is_default_event( $value ) && empty( $settings['default_events'] ) ) $is_selected = ' selected="selected"';
                        echo '<option value="' . $value . '"' . $is_selected . '>' . $name . '</option>' . PHP_EOL;
                    } 
                ?>
            </select>
        </label>

        <?php
        elseif ( $event == 'admin_events' ) :
        ?>
        <label for="wpwfw_settings[<?php echo $event ?>]" class="form-label" title="<?php _e( 'Event to receive order notification', WPWFW_PLUGIN_DOMAIN_LANGUAGE ); ?>">
            <select class="<?php echo $event ?>" data-role="select2" id="wpwfw_settings[<?php echo $event ?>]" name="wpwfw_settings[<?php echo $event ?>]" tabindex="<?php echo $this->page_tab++; ?>">
                <?php
                    $is_selected = '';
                    foreach ( $select as $value => $name ) {
                        if ( isset( $settings[$event] ) && $settings[$event] == $value ) $is_selected = ' selected="selected"';
                        else if ( !isset( $settings[$event] ) && $value == 'processing_message' ) $is_selected = ' selected="selected"';
                        echo '<option value="' . $value . '"' . $is_selected . '>' . $name . '</option>' . PHP_EOL;
                    } 
                ?>
            </select>
        </label>
        <?php
        endif;
        
        ?>
        <p><em><?php _e( $events[$event]['caption'], WPWFW_PLUGIN_DOMAIN_LANGUAGE ); ?></em></p>
        <?php

    }

    private function populate_custom_message_field( $event ) {

        $events = array( 'default_events', 'custom_events', 'admin_events', 'aftersale_events', 'cart_abandon_events' );
        if ( !in_array( $event, $events ) ) {
            echo '<div class="wpwf-ui-element-danger"><b>Failed to build component '. $event . '</b></div>';
            return;
        }

        if ( $event == 'default_events' ) $this->default_order_message_field();
        else if ( $event == 'custom_events' ) $this->custom_order_message_field();
        else if ( $event == 'admin_events' ) $this->admin_order_message_field();
        else if ( $event == 'aftersale_events' ) $this->aftersale_follow_up_message_field();
        else if ( $event == 'cart_abandon_events' ) $this->cart_abandon_follow_up_message_field();

    }

    private function default_order_message_field() {

        $settings = $this->settings;
        $message_fields = WPWFW_Core()->services->default_template_name;
        $message_fields_title = WPWFW_Core()->services->default_selectable_status;

        foreach ( $message_fields as $field ) :
            if ( $field == 'received_message' && false ) continue; // disable on-hold event. for now we allow it
            if ( !isset( $settings[$field] ) || empty( $settings[$field] ) ) $content = stripcslashes( WPWFW_Core()->system->get_notification_template( $field ) );
            else $content = stripcslashes( $settings[$field] );
        ?>
            <tr valign="top" class="<?php echo $field ?>">
                <th scope="row" class="titledesc">
                    <?php _e( $message_fields_title[ $field ], WPWFW_PLUGIN_DOMAIN_LANGUAGE) ; ?>:
                </th>
                <td>
                    <label for="wpwfw_settings[<?php echo $field ?>]" class="form-label" title="<?php _e( "You can customize your message. Remember that you can use order shortcodes available in your message.", WPWFW_PLUGIN_DOMAIN_LANGUAGE ); ?>">
                        <textarea class="form-control" id="wpwfw_settings[<?php echo $field ?>]" name="wpwfw_settings[<?php echo $field ?>]" cols="55" rows="5" tabindex="<?php echo $this->page_tab++; ?>"><?php echo $content; ?></textarea>
                    </label>
                    <p><em><?php _e( 'Customize your message with order shortcodes', WPWFW_PLUGIN_DOMAIN_LANGUAGE); ?></em></p>
                </td>
            </tr>
        <?php
        endforeach;

    }

    private function custom_order_message_field() {

        $settings = $this->settings;
        $custom_status = WPWFW_Core()->services->custom_status;

        foreach ( $custom_status as $status => $status_name ) :
            if ( !isset( $settings[$status] ) || empty( $settings[$status] ) ) {
                // Pre-populate with predefined template if available
                $content = stripcslashes( WPWFW_Core()->integration->get_integration_notification_template( $status ) );
            } else {
                $content = stripcslashes( $settings[$status] );
            }
        ?>
            <tr valign="top" class="<?php echo $status ?>">
                <th scope="row" class="titledesc">
                    <?php _e( sprintf( '%s Custom Message', ucwords($status_name) ), WPWFW_PLUGIN_DOMAIN_LANGUAGE ); ?>:
                </th>
                <td>
                    <label for="wpwfw_settings[<?php echo $status ?>]" class="form-label" title="<?php _e( "You can customize your message. Remember that you can use order shortcodes available in your message.", WPWFW_PLUGIN_DOMAIN_LANGUAGE ); ?>">
                        <textarea class="form-control" id="wpwfw_settings[<?php echo $status ?>]" name="wpwfw_settings[<?php echo $status ?>]" cols="55" rows="5" tabindex="<?php echo $this->page_tab++; ?>"><?php echo $content; ?></textarea>
                    </label>
                    <p><em><?php _e( 'Customize your message with order shortcodes', WPWFW_PLUGIN_DOMAIN_LANGUAGE); ?></em></p>
                </td>
            </tr>
        <?php
        endforeach;

    }

    private function aftersale_follow_up_message_field() {

        $settings = $this->settings;
        $aftersale_events = WPWFW_Core()->services->aftersale_events;

        // Add deduplication setting before individual follow-up events
        ?>
        <tr valign="top" class="aftersale-deduplication-setting">
            <th scope="row" class="titledesc">
                <?php _e( 'Avoid Duplicate Follow-Up', WPWFW_PLUGIN_DOMAIN_LANGUAGE ); ?>:
            </th>
            <td>
                <label for="wpwfw_settings[aftersale_deduplication]" class="form-label" title="<?php _e( 'Stop customers from getting the same follow-up message multiple times', WPWFW_PLUGIN_DOMAIN_LANGUAGE ); ?>">
                    <input class="form-control" id="wpwfw_settings[aftersale_deduplication]" name="wpwfw_settings[aftersale_deduplication]" type="checkbox" value="1" <?php echo ( isset( $settings['aftersale_deduplication'] ) && $settings['aftersale_deduplication'] == "1" ) ? 'checked="checked"' : ''; ?> tabindex="<?php echo $this->page_tab++; ?>" />
                    <?php _e( 'Each customer can only get one follow-up sequence', WPWFW_PLUGIN_DOMAIN_LANGUAGE ); ?>
                </label>
                                
                <div class="deduplication-timeframe" style="margin-top: 15px; <?php echo ( !isset( $settings['aftersale_deduplication'] ) || $settings['aftersale_deduplication'] != "1" ) ? 'display: none;' : ''; ?>">
                    <label for="wpwfw_settings[aftersale_deduplication_days]" class="form-label">
                        <span style="margin-right: 10px;"><b>How long to remember:</b></span>
                        <input type="number" class="form-control" id="wpwfw_settings[aftersale_deduplication_days]" name="wpwfw_settings[aftersale_deduplication_days]" min="1" max="365" value="<?php echo isset( $settings['aftersale_deduplication_days'] ) ? $settings['aftersale_deduplication_days'] : '7'; ?>" style="width: 80px;" tabindex="<?php echo $this->page_tab++; ?>" />
                        <span>days</span>
                    </label>
                    <div style="background: #fff3cd; padding: 8px; border-left: 3px solid #ffc107; margin-top: 8px;">
                        <p style="margin: 0; color: #856404; font-size: 13px;"><strong>📋 Example:</strong><br>
                        Set to <strong>7 days</strong> = If customer orders on Monday and again on Wednesday, they get 1 message.<br>
                        If they order again next Monday (8 days later), they get the message again.</p>
                    </div>
                    <p style="margin-top: 8px;"><em style="color: #666;">💡 <strong>Tip:</strong> Use 7-14 days for frequent buyers, 30 days for occasional buyers</em></p>
                </div>
            </td>
        </tr>
        
        <script>
        jQuery(document).ready(function($) {
            $('#wpwfw_settings\\[aftersale_deduplication\\]').change(function() {
                if ($(this).is(':checked')) {
                    $('.deduplication-timeframe').slideDown();
                } else {
                    $('.deduplication-timeframe').slideUp();
                }
            });
        });
        </script>
        <?php

        $count = 0;
        foreach ( $aftersale_events as $event => $event_name ) :
            if ( !isset( $settings[$event] ) || empty( $settings[$event] ) ) $content = stripcslashes( WPWFW_Core()->system->get_notification_template( $event ) );
            else $content = stripcslashes( $settings[$event] );

            if ( !isset( $settings[$event . '_delay'] ) || empty( $settings[$event . '_delay'] ) ) $delay_data = ( $count == 0 ? 1 : $count ) * 24;
            else $delay_data = $settings[$event . '_delay'];
        ?>
            <tr valign="top" class="<?php echo $event ?>">
                <th scope="row" class="titledesc">
                    <?php _e( sprintf( '%s Custom Message', ucwords( $event_name ) ), WPWFW_PLUGIN_DOMAIN_LANGUAGE ); ?>:
                </th>
                <td>
                    <label for="wpwfw_settings[<?php echo $event ?>]" class="form-label" title="<?php _e( "You can customize your message. Remember that you can use order shortcodes available in your message.", WPWFW_PLUGIN_DOMAIN_LANGUAGE ); ?>">
                        <textarea class="form-control" id="wpwfw_settings[<?php echo $event ?>]" name="wpwfw_settings[<?php echo $event ?>]" cols="55" rows="5" tabindex="<?php echo $this->page_tab++; ?>"><?php echo $content; ?></textarea>
                    </label>
                    <p><em><?php _e( 'Leave blank to disable. Customize your message with order shortcodes', WPWFW_PLUGIN_DOMAIN_LANGUAGE); ?></em></p>
                    <label for="wpwfw_settings[<?php echo $event . '_delay'; ?>]" class="form-label" title="To ">
                        <span style="margin-right: 25px;"><b>Send After:</b></span> <input type="number" class="form-control wpwfw_delay <?php echo "wpwfw_" . $event . '_delay'; ?>" id="wpwfw_settings[<?php echo $event . '_delay'; ?>]" style="width:100px; margin-top:10px;" name="wpwfw_settings[<?php echo $event . '_delay'; ?>]" min="1" cols="50" rows="5" value="<?php echo $delay_data; ?>" placeholder="24" tabindex="<?php echo $this->page_tab++;?>">
                        <select class="wpwfw_delay_unit" id="wpwfw_settings[<?php echo $event . '_delay_unit'; ?>]" name="wpwfw_settings[<?php echo $event . '_delay_unit'; ?>]" style="max-width: 87px !important; margin-top: 10px !important; margin-left: 5px !important; line-height: 2 !important; vertical-align: top !important; height: 34px !important;">
                        <?php
                            foreach ( WPWFW_Core()->services->events_delay_data as $unit => $ratio ) {
                                echo '<option value="' . $unit . '" ' . ( ( isset( $settings[$event . '_delay_unit'] ) && $settings[$event . '_delay_unit'] == $unit ) ? 'selected' : '' ) . '>' . ucwords($unit) . '(s)</option>';
                            }
                        ?>
                        </select>
                    </label>
                    <div class="wpwfw_delay_estimate elem-hide"><p><em>Estimated delivery: </em></p></div>
                </td>
            </tr>
        <?php
            $count = $count + 7;
        endforeach;

    }

    private function cart_abandon_follow_up_message_field() {

        $settings = $this->settings;
        $cart_abandon_events = WPWFW_Core()->services->cart_abandon_events;

        $count = 0;
        foreach ( $cart_abandon_events as $integration => $events ) :
            


            
            foreach ( $events as $event => $event_name ) :

                $template = $event;
                $template_delay = $event . '_delay';
                if ( !isset( $settings[$template] ) || empty( $settings[$template] ) ) $content = stripcslashes( WPWFW_Core()->system->get_notification_template( $template ) );
                else $content = stripcslashes( $settings[$template] );

                if ( !isset( $settings[$template_delay] ) || empty( $settings[$template_delay] ) ) $delay_data = ( $count == 0 ? 1 : $count ) * 24;
                else $delay_data = $settings[$template_delay];

            ?>
                <tr valign="top" class="<?php echo $event ?>">
                    <th scope="row" class="titledesc">
                        <?php _e( sprintf( '%s Custom Message', ucwords( $event_name ) ), WPWFW_PLUGIN_DOMAIN_LANGUAGE ); ?>:
                    </th>
                    <td>
                        <label for="wpwfw_settings[<?php echo $event ?>]" class="form-label" title="<?php _e( "You can customize your message. Remember that you can use order shortcodes available in your message.", WPWFW_PLUGIN_DOMAIN_LANGUAGE ); ?>">
                            <textarea class="form-control" id="wpwfw_settings[<?php echo $event ?>]" name="wpwfw_settings[<?php echo $event ?>]" cols="55" rows="5" tabindex="<?php echo $this->page_tab++; ?>"><?php echo $content; ?></textarea>
                        </label>
                        <p><em><?php _e( 'Leave blank to disable. Customize your message with cart shortcodes', WPWFW_PLUGIN_DOMAIN_LANGUAGE); ?></em></p>
                        <label for="wpwfw_settings[<?php echo $template_delay; ?>]" class="form-label" title="To ">
                            <span style="margin-right: 25px;"><b>Send After:</b></span> <input type="number" class="form-control wpwfw_delay <?php echo "wpwfw_" . $template_delay; ?>" id="wpwfw_settings[<?php echo $template_delay; ?>]" style="width:100px; margin-top:10px;" name="wpwfw_settings[<?php echo $template_delay; ?>]" min="1" cols="50" rows="5" value="<?php echo $delay_data; ?>" placeholder="24" tabindex="<?php echo $this->page_tab++;?>">
                            <select class="wpwfw_delay_unit" id="wpwfw_settings[<?php echo $event . '_delay_unit'; ?>]" name="wpwfw_settings[<?php echo $event . '_delay_unit'; ?>]" style="max-width: 87px !important; margin-top: 10px !important; margin-left: 5px !important; line-height: 2 !important; vertical-align: top !important; height: 34px !important;">
                            <?php
                                foreach ( WPWFW_Core()->services->events_delay_data as $unit => $ratio ) {
                                    echo '<option value="' . $unit . '" ' . ( ( isset( $settings[$event . '_delay_unit'] ) && $settings[$event . '_delay_unit'] == $unit ) ? 'selected' : '' ) . '>' . ucwords($unit) . '(s)</option>';
                                }
                            ?>
                            </select>
                        </label>
                        <div class="wpwfw_delay_estimate elem-hide"><p><em>Estimated delivery: </em></p></div>
                    </td>
                </tr>
            <?php
                $count = $count + 7;
            endforeach;
        endforeach;
    }

    private function debug_workspace_ui() {

        ?>
        <hr>
        <div class="debug-workspace-log-menu">
            <table class="form-table">
                <tbody>
                    <tr class="debug-workspace-log-download">
                        <th scope="row">Download Log</th>
                        <td>
                        <select class="debug-workspace-log-type" style="margin-right: 10px;">
                            <option value="app">app.log</option>
                            <option value="error">error.log</option>
                        </select>
                        <button class="button-primary" id="log_download_button">Download</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="debug-workspace">
            <div class="debug-workspace-title">Console Log</div>
            <div class="debug-workspace-content">
            <?php
                try {
                    global $wpdb, $woocommerce;
                    $std_format = 'Y-m-d H:i:s';

                    $active_integrations = WPWFW_Core()->integration::$active_integrations;

                    $system_info = array(
                        'PHP_Version'           => phpversion(),
                        'Wordpress_Version'     => get_bloginfo( 'version'),
                        'WC_Version'            => ( WPWFW_Core()->integration->is_woocommerce_active() ) ? $woocommerce->version : 'Not Installed',
                        'Active_Integrations'   => $active_integrations,
                    );

                    if ( true ) {
                        $system_info['WPWFW_Settings'] = $this->settings;
                        $system_info['WPWFW_Plugin_Data'] = WPWFW_Core()->system->get_plugin_data();
                    }

                    $display_info = print_r( json_encode( $system_info, JSON_PRETTY_PRINT ), true );
                    print("System Info:<br>");
                    print( "<pre>" . htmlspecialchars( $display_info, ENT_QUOTES, 'UTF-8', true ) . "</pre>");

                    $lines = ( WPWFW_APP_MODE !== 'EXTENDED' ) ? 30 : 15;

                    $app_log = WPWFW_Core()->logger->get_log_file( 'app' );
                    $error_log = WPWFW_Core()->logger->get_log_file( 'error' );

                    if (!empty($app_log)) {
                        $app_log = explode( "\n", $app_log );
                        $app_log = array_splice( $app_log, $lines * -1 );
                    }

                    if (!empty($error_log)) {
                        $error_log = explode( "\n", $error_log );
                        $error_log = array_splice( $error_log, $lines * -1 );
                    }

                    $app = (!empty($app_log)) ? print_r( implode( "\n", $app_log ), true ) : 'No logs found';
                    $error = (!empty($error_log)) ? print_r( implode( "\n", $error_log ), true ) : 'No logs found';
                    
                    print( "[app.log] & [error.log] Logs<br>");
                    
                    print( "[app.log]<br>" );
                    print( "<pre>Showing only last $lines lines...</pre>");
                    print( "<pre>" . htmlspecialchars( $app, ENT_QUOTES, 'UTF-8', true ) . "</pre>");
                    print( "[error.log]<br>");
                    print( "<pre>Showing only last $lines lines...</pre>");
                    print( "<pre>" . htmlspecialchars( $error, ENT_QUOTES, 'UTF-8', true ) . "</pre>");

                   

                    if ( WPWFW_APP_MODE === 'EXTENDED' ) {
                        // Debugging Section
                        date_default_timezone_set('Asia/Kuala_Lumpur');
                        $current_date = date( $std_format );
                        if ( count( array_intersect( ['woo-cart-abandonment-recovery'], $active_integrations ) ) != 0 ) {
                            print( "Cart Abandon Data from `Cartflows Cart Abandonment`<br>");
                            $cart_abandonment_table = $wpdb->prefix . "cartflows_ca_cart_abandonment";
                            $columns = $wpdb->get_results( "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = '" . $cart_abandonment_table . "' AND column_name = 'wpwfw_data'" );
                            if ( empty( $columns ) ) {
                                print( "<pre>" . htmlspecialchars( 'COLUMN `wpwfw_data` does not exist' , ENT_QUOTES, 'UTF-8', true ) . "</pre>");
                            }

                            $abandoned = $wpdb->get_results(
                                $wpdb->prepare(
                                    'SELECT * FROM ' . $cart_abandonment_table . ' as CAT 
                                    WHERE CAT.`order_status` <> %s AND CAT.unsubscribed = 0',
                                    ""
                                )
                            );

                            foreach ( $abandoned as $item ) {
                                $data = array();
                                $data = array_merge(
                                    array(
                                        'order_status'  => $item->order_status,
                                        'email'         => $item->email,
                                        'cart_contents' => maybe_unserialize( $item->cart_contents ),
                                        'cart_total'    => $item->cart_total,
                                        'time'          => $item->time,
                                        'checkout_id'   => $item->checkout_id,
                                    ),
                                    maybe_unserialize( $item->other_fields )
                                );
                                
                                $wpwfw_data = maybe_unserialize( $item->wpwfw_data );
                                // Only accept new sequence(s) with empty `wpwfw_md`
                                if ( !is_array( $wpwfw_data ) && $wpwfw_data == 1 ) continue;

                                if ( !empty( $wpwfw_data ) && is_array( $wpwfw_data) ) $data['wpwfw_data'] = $wpwfw_data;

                                $wpwfw_validate = array(
                                    'cart_abandon_followup_1'=>'Not configured',
                                    'cart_abandon_followup_2'=>'Not configured',
                                    'cart_abandon_followup_3'=>'Not configured',
                                );

                                if ( isset($this->settings['cart_abandon_followup_1_delay']) && !empty($this->settings['cart_abandon_followup_1_delay']) ) $wpwfw_validate['cart_abandon_followup_1'] = array(
                                    'cutoff' => $this->settings['cart_abandon_followup_1_delay'] . ' ' . $this->settings['cart_abandon_followup_1_delay_unit'] . '(s)',
                                    'queued' => (WPWFW_Common::get_time_difference( $item->time, $current_date, $this->settings['cart_abandon_followup_1_delay_unit'] ) > $this->settings['cart_abandon_followup_1_delay'] ) ? true : false
                                );
                                if ( isset($this->settings['cart_abandon_followup_2_delay']) && !empty($this->settings['cart_abandon_followup_2_delay']) ) $wpwfw_validate['cart_abandon_followup_2'] = array(
                                    'cutoff' => $this->settings['cart_abandon_followup_2_delay'] . ' ' . $this->settings['cart_abandon_followup_2_delay_unit'] . '(s)',
                                    'queued' => (WPWFW_Common::get_time_difference( $item->time, $current_date, $this->settings['cart_abandon_followup_2_delay_unit'] ) > $this->settings['cart_abandon_followup_2_delay'] ) ? true : false
                                );
                                if ( isset($this->settings['cart_abandon_followup_3_delay']) && !empty($this->settings['cart_abandon_followup_3_delay']) ) $wpwfw_validate['cart_abandon_followup_3'] = array(
                                    'cutoff' => $this->settings['cart_abandon_followup_3_delay'] . ' ' . $this->settings['cart_abandon_followup_3_delay_unit'] . '(s)',
                                    'queued' => (WPWFW_Common::get_time_difference( $item->time, $current_date, $this->settings['cart_abandon_followup_3_delay_unit'] ) > $this->settings['cart_abandon_followup_3_delay'] ) ? true : false
                                );
                                
                                $data['wpwfw_validate'] = $wpwfw_validate;

                                $result = print_r( json_encode( maybe_unserialize($data), JSON_PRETTY_PRINT ), true );
                                print( "<pre>" . htmlspecialchars( $result, ENT_QUOTES, 'UTF-8', true ) . "</pre>");

                            }

                            $abandoned = $wpdb->get_results(
                                $wpdb->prepare(
                                    "SELECT * FROM " . $cart_abandonment_table . " as CAT WHERE CAT.`order_status` = %s AND CAT.unsubscribed = 0 AND (CAT.wpwfw_data != 1 OR CAT.wpwfw_data IS NULL)",
                                    "abandoned",
                                )
                            );
                            print( "<pre> Total Valid Abandoned: " . count($abandoned) . "</pre>");
                            if ( count($abandoned) ) {
                                print( "<pre> Recent Abandoned Data: </pre>");
                                $result = print_r( json_encode( maybe_unserialize(end($abandoned)), JSON_PRETTY_PRINT ), true );
                                print( "<pre>" . htmlspecialchars( $result, ENT_QUOTES, 'UTF-8', true ) . "</pre>");
                            }

                        }

                    }
                    
                } catch (Exception $e) {
                    $result = print_r( $e->getMessage(), true );
                    print( "<pre>" . htmlspecialchars( $result, ENT_QUOTES, 'UTF-8', true ) . "</pre>");
                }
                
            ?>
            </div>
        </div>
        <?php

    }

    private function admin_order_message_field() {

        $settings = $this->settings;

        ?>
            <tr valign="top" class="order_message">
                <th scope="row" class="titledesc">
                    <?php _e( sprintf( '%s Custom Message', 'Admin Order' ), WPWFW_PLUGIN_DOMAIN_LANGUAGE ) ; ?>:
                </th>
                <td>
                    <label for="wpwfw_settings[order_message]" class="form-label" title="<?php _e( "You can customize your message. Remember that you can use order shortcodes available in your message.", WPWFW_PLUGIN_DOMAIN_LANGUAGE ); ?>">
                        <textarea class="form-control" id="wpwfw_settings[order_message]" name="wpwfw_settings[order_message]" cols="55" rows="5" tabindex="<?php echo $this->page_tab++; ?>"><?php echo stripcslashes( $settings['order_message'] ) ?? ''; ?></textarea>
                    </label>
                    <p><em><?php _e( 'Customize your message with order shortcodes', WPWFW_PLUGIN_DOMAIN_LANGUAGE); ?></em></p>
                </td>
            </tr>
        <?php

    }

    public function woocommerce_order_meta( $post ) {
        $metabox_table = '
            <table class="table ' . WPWFW_PLUGIN_CORE_NAME . '_dashboard wp-list-table widefat striped">
                <tbody>
                    {{LOGO_PLACEHOLDER}}
                    <tr>
                        <th></th>
                        <th><b>Date & Time</b></th>
                        <th><b>Status</b></th>
                        <th><b>Console Log</b></th>
                        <th><b>Action</b></th>
                    </tr>
        ';

        $placeholder = (false) ? '
        <tr>
            <th colspan="5" style="padding: 2.5em 1.5em; text-align: center;"><img src="' . WPWFW_Core()->system->get_plugin_icon('top-nav-simple') . '" alt="" style="width: 90%; max-width: 220px"></th>
        </tr>' : '';
        $metabox_table = str_replace( '{{LOGO_PLACEHOLDER}}', $placeholder, $metabox_table);

        if ( $wpwfw_send_message = get_post_meta( $post->ID, 'wpwfw_send_message', true ) ) {

            if ( !$wpwfw_send_message || !WPWFW_Common::is_json( $wpwfw_send_message ) ) {
                $response = 'No data available';
                $status = 'Error';
                $timestamp = 'N/A';
            } else {
                $send_message = json_decode( $wpwfw_send_message, true );
                $status = ( isset( $send_message['status'] ) && $send_message['status'] === 'success' ) ? $send_message['status'] : 'Error';
                $response = ( isset( $send_message['status'] ) && $send_message['status'] === 'success' ) ? 'Success' : ( isset( $send_message['message'] ) ? $send_message['message'] : 'Unable to send message' ) ;
                
                // Get timestamp from meta data or use current time
                $timestamp = isset( $send_message['timestamp'] ) ? $send_message['timestamp'] : get_post_meta( $post->ID, '_wpwfw_last_processed', true );
                if ( !$timestamp ) {
                    $timestamp = current_time( 'Y-m-d H:i:s' );
                }
                $formatted_date = date( 'M j, Y g:i A', strtotime( $timestamp ) );
            }
            
            $order_status = ( isset( $send_message['order_status'] ) && !empty( $send_message['order_status'] ) ) ? $send_message['order_status'] : $post->status;
            $status_icon = ( $status === 'success' ) ? '<i class="fa-solid fa-check" style="color: #28a745;"></i>' : '<i class="fa-solid fa-xmark" style="color: #dc3545;"></i>';
            $status_class = ( $status === 'success' ) ? 'success' : 'error';
            
            $metabox_table .= '
            <tr>
                <th>Order ' . ucfirst( $order_status ) . ' Notification</th>
                <td><small>' . $formatted_date . '</small></td>
                <td><span class="status-' . $status_class . '">' . $status_icon . ' ' . ucwords( $status ) . '</span></td>
                <td><small>' . $response . '</small></td>
                <td><a href="' . $this->get_resend_url( $post->ID, 'order' ) . '" title="' . ($status === 'success' ? 'Click to resend message' : $response . '&#10;Click to retry') . '" class="button wc-action-button"' . ($status === 'success' ? ' style="background-color: #28a745; color: white; border-color: #28a745;"' : ' style="background-color: #dc3545; color: white; border-color: #dc3545;"') . '>' . ($status === 'success' ? '<i class="fa-solid fa-check" style="color: white;"></i> Success' : '<i class="fa-solid fa-xmark" style="color: white;"></i> Failed') . '</a></td>
            </tr>
            ';
        } else {
            $metabox_table .= '
            <tr>
                <th>Order Notification</th>
                <td><small>Not sent yet</small></td>
                <td><span class="status-pending"><i class="fa-solid fa-clock" style="color: #fbbf24;"></i> Pending</span></td>
                <td><small>No message sent</small></td>
                <td><a href="' . $this->get_resend_url( $post->ID, 'order' ) . '" title="Click to send message" class="button wc-action-button" style="background-color: #fbbf24; color: black; border-color: #fbbf24;"><i class="fa-brands fa-whatsapp" style="color: black;"></i> Send Now</a></td>
            </tr>
            ';
        }

        foreach ( array_keys( WPWFW_Core()->services->aftersale_events ) as $event ) {

            if ( $wpwfw_followup_aftersales = get_post_meta( $post->ID, 'wpwfw_' . $event, true ) ) {

                $event_name = str_replace( '_', ' ', $event );
                if ( !$wpwfw_followup_aftersales ) continue;
                else if ( !WPWFW_Common::is_json( $wpwfw_followup_aftersales ) ) {
                    $response = 'No data available';
                    $status = 'Error';
                    $timestamp = 'N/A';
                } else {
                    $send_message = json_decode( $wpwfw_followup_aftersales, true );
                    $status = ( isset( $send_message['status'] ) && $send_message['status'] === 'success' ) ? $send_message['status'] : 'Error';
                    $response = ( isset( $send_message['status'] ) && $send_message['status'] === 'success' ) ? $send_message['message'] : ( isset( $send_message['message'] ) ? $send_message['message'] : 'Unable to send message' ) ;
                    
                    // Get timestamp from meta data
                    $timestamp = isset( $send_message['timestamp'] ) ? $send_message['timestamp'] : get_post_meta( $post->ID, '_wpwfw_' . $event . '_processed', true );
                    if ( !$timestamp ) {
                        $timestamp = current_time( 'Y-m-d H:i:s' );
                    }
                    $formatted_date = date( 'M j, Y g:i A', strtotime( $timestamp ) );
                }
                
                $status_icon = ( $status === 'success' ) ? '<i class="fa-solid fa-check" style="color: #28a745;"></i>' : '<i class="fa-solid fa-xmark" style="color: #dc3545;"></i>';
                $status_class = ( $status === 'success' ) ? 'success' : 'error';
    
                $metabox_table .= '
                <tr>
                    <th>' . ucwords( $event_name ) . ' Notification</th>
                    <td><small>' . $formatted_date . '</small></td>
                    <td><span class="status-' . $status_class . '">' . $status_icon . ' ' . ucwords( $status ) . '</span></td>
                    <td><small>' . $response . '</small></td>
                    <td><a href="' . $this->get_resend_url( $post->ID, $event ) . '" title="' . ($status === 'success' ? 'Click to resend message' : $response . '&#10;Click to retry') . '" class="button wc-action-button"' . ($status === 'success' ? ' style="background-color: #28a745; color: white; border-color: #28a745;"' : ' style="background-color: #dc3545; color: white; border-color: #dc3545;"') . '>' . ($status === 'success' ? '<i class="fa-solid fa-check" style="color: white;"></i> Success' : '<i class="fa-solid fa-xmark" style="color: white;"></i> Failed') . '</a></td>
                </tr>
                ';
            }
        }

        // Add notification history section
        $notification_history = $this->get_notification_history( $post->ID );
        if ( !empty( $notification_history ) ) {
            $metabox_table .= '
            </tbody>
        </table>
        
        <h4 style="margin-top: 20px; margin-bottom: 10px;"><i class="fa fa-history"></i> Notification History</h4>
        <table class="wc_shipping wc_tax wp-list-table widefat fixed striped posts">
            <thead>
                <tr>
                    <th>Date & Time</th>
                    <th>Status</th>
                    <th>Console Log</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>';
            
            foreach ( $notification_history as $notification ) {
                $status = $notification['status'] ?? 'error';
                $message = $notification['message'] ?? 'Unknown error';
                $order_status = $notification['order_status'] ?? 'unknown';
                $timestamp = $notification['timestamp'] ?? current_time( 'Y-m-d H:i:s' );
                $formatted_date = date( 'M j, Y g:i A', strtotime( $timestamp ) );
                
                $status_icon = ( $status === 'success' ) ? '<i class="fa-solid fa-check" style="color: #28a745;"></i>' : '<i class="fa-solid fa-xmark" style="color: #dc3545;"></i>';
                $status_class = ( $status === 'success' ) ? 'success' : 'error';
                $type_title = 'Order ' . ucfirst( $order_status ) . ' Notification';
                
                $metabox_table .= '
                <tr>
                    <td><small>' . $formatted_date . '</small></td>
                    <td><span class="status-' . $status_class . '">' . $status_icon . ' ' . ucwords( $status ) . '</span></td>
                    <td><small>' . esc_html( $message ) . '</small></td>
                    <td><span class="button wc-action-button" style="background-color: #' . ( $status === 'success' ? '28a745' : 'dc3545' ) . '; color: white; border-color: #' . ( $status === 'success' ? '28a745' : 'dc3545' ) . ';">' . ( $status === 'success' ? 'Success' : 'Failed' ) . '</span></td>
                </tr>';
            }
            
            $metabox_table .= '
            </tbody>
        </table>';
        } else {
            $metabox_table .= '
            </tbody>
        </table>';
        }

        echo trim( $metabox_table );

    }

    /**
     * Get notification history for an order
     * 
     * @param int $order_id Order ID
     * @return array Notification history entries
     */
    private function get_notification_history( $order_id ) {
        // Check if we're in HPOS mode
        if ( class_exists( '\Automattic\WooCommerce\Utilities\OrderUtil' ) && 
             \Automattic\WooCommerce\Utilities\OrderUtil::custom_orders_table_usage_is_enabled() ) {
            $order = wc_get_order( $order_id );
            if ( !$order ) return array();
            
            $history = $order->get_meta( 'wpwfw_notification_history' );
            $admin_history = $order->get_meta( 'wpwfw_admin_notification_history' );
        } else {
            $history = get_post_meta( $order_id, 'wpwfw_notification_history', true );
            $admin_history = get_post_meta( $order_id, 'wpwfw_admin_notification_history', true );
        }
        
        // Merge customer and admin notification histories
        $all_history = array();
        
        if ( is_array( $history ) && !empty( $history ) ) {
            $all_history = array_merge( $all_history, $history );
        }
        
        if ( is_array( $admin_history ) && !empty( $admin_history ) ) {
            $all_history = array_merge( $all_history, $admin_history );
        }
        
        // Sort by timestamp (newest first)
        if ( !empty( $all_history ) ) {
            usort( $all_history, function( $a, $b ) {
                $time_a = isset( $a['timestamp'] ) ? strtotime( $a['timestamp'] ) : 0;
                $time_b = isset( $b['timestamp'] ) ? strtotime( $b['timestamp'] ) : 0;
                return $time_b - $time_a; // Descending order (newest first)
            });
            
            // Limit to last 10 entries for display
            $all_history = array_slice( $all_history, 0, 10 );
        }
        
        return $all_history;
    }

    /**
     * Get the proper resend URL based on HPOS or traditional order system
     */
    private function get_resend_url( $order_id, $type ) {
        // Check if we're in HPOS mode
        if ( class_exists( '\Automattic\WooCommerce\Utilities\OrderUtil' ) && 
             \Automattic\WooCommerce\Utilities\OrderUtil::custom_orders_table_usage_is_enabled() ) {
            // HPOS mode - use the WooCommerce admin order edit URL with wpwfw_type
            return admin_url( 'admin.php?page=wc-orders&action=edit&id=' . $order_id . '&wpwfw_type=' . $type );
        } else {
            // Traditional mode - use post.php with wpwfw_type
            return admin_url( 'post.php?post=' . $order_id . '&action=edit&wpwfw_type=' . $type );
        }
    }

}

if ( class_exists( 'WPWFW_Core' ) ) {
    WPWFW_Core::register( 'ui', 'WPWFW_UI' );
}

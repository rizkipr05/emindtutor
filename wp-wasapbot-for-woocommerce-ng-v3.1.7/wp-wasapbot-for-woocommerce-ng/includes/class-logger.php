<?php

/**
 * Logger
 */

 defined( 'ABSPATH' ) or die( 'I feel very small...' );

 class WPWFW_Logger {

    private $_handles;
    private $log_directory, $log_url;
    private static $_instance = null;

    public function __construct() {
        // wp-content/plugins/wp-wasapbot-for-woocommerce-ng/logs/
        $this->log_directory = WPWFW_PLUGIN_DIR . '/logs/';
        $this->log_url = WPWFW_PLUGIN_URL . '/logs/';
        wp_mkdir_p( $this->log_directory );

        /**
         * Register ajax action
         */
        add_action( 'wp_ajax_download_log_file', array( $this, 'ajax_download_log_file') );
    }

    public static function get_instance() {
        
        if ( null === self::$_instance ) {
            self::$_instance = new self();
        }

        return self::$_instance;

    }

    private function open( $handle ) {
        if ( isset( $this->_handles[$handle] ) ) return true;
        if ( $this->_handles[$handle] = @fopen( $this->log_directory . $handle . '.log', 'a' ) ) return true;
        return false;
    }

    public function add( $handle, $message, $level = 'INFO', $module = 'LOGGER' ) {
        date_default_timezone_set('Asia/Kuala_Lumpur');
        $date = date( "Y-m-d H:i:s" );
        $log = "[{$date}] app[{$module}] {$level}: {$message}\n";
        if ( $this->open( $handle ) ) @fwrite( $this->_handles[$handle], $log );
    }

    public function clear( $handle ) {
        if ( $this->open( $handle ) ) @fopen( $this->log_directory . $handle . '.log', 'w+' );
    }

    public function get_log_file( $handle, $lines = 1000 ) {
        $file = $this->log_directory . "{$handle}.log";
        if ( file_exists( $file ) ) {
            $file_contents = $this->read_last_lines($file, $lines);
            return implode("\n", $file_contents);
        }
        return false;
    }

    public function ajax_download_log_file() {
        $logType = isset($_POST['log_type']) ? $_POST['log_type'] : '';

        // Validate the log type (to prevent any security issues)
        if ($logType === 'app' || $logType === 'error') {
            // Get the file path based on the log type
            $file = "$logType.log";
            $file_path = $this->log_directory . $file;
            $file_url = $this->log_url . $file;
            $file_content = $this->get_log_file( $logType );

            // Check if the file exists
            if (file_exists($file_path)) {
                wp_send_json_success( array(
                    'status' => 'success',
                    'message' => 'Success',
                    'data' => array(
                        'file' => $file,
                        'file_path' => $file_url,
                        'file_content' => base64_encode( $file_content )
                    ),
                ) );
                exit();
            } else {
                // File not found, handle error (optional)
                wp_send_json_error( array(
                    'status' => 'error',
                    'message' => 'File not found',
                ) );
            }
        } else {
            // Invalid log type, handle error (optional)
            wp_send_json_error( array(
                'status' => 'error',
                'message' => 'Invalid log type',
            ));
        }
    }

    private function read_last_lines($filename, $lines) {
        $handle = fopen($filename, "r");
        if (!$handle) {
            return [];
        }
    
        $line_counter = 0;
        $pos = -2;
        $beginning = false;
        $text = [];
        while ($line_counter < $lines) {
            $t = " ";
            while ($t != "\n") {
                if(fseek($handle, $pos, SEEK_END) == -1) {
                    $beginning = true; 
                    break; 
                }
                $t = fgetc($handle);
                $pos --;
            }
            $line_counter ++;
            if ($beginning) {
                rewind($handle);
            }
            $text[$lines-$line_counter-1] = fgets($handle);
            if ($beginning) break;
        }
        fclose ($handle);
        return array_reverse($text);
    }

 }

 if ( class_exists( 'WPWFW_Core' ) ) {
    WPWFW_Core::register( 'logger', 'WPWFW_Logger' );
 }
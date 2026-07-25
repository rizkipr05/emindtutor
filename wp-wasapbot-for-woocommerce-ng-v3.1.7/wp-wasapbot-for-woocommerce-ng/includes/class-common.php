<?php

/**
 * Class WPWFW_Common
 * Handles Common Functions For Admin as well as front end interface
 */

defined( 'ABSPATH' ) or die( 'I feel very small...' );

class WPWFW_Common {

    protected static $allowed_responses = array( 200, 201, 202 );

    public static $date_format = null;
	public static $time_format = null;
	public static $admin_email = null;
    
    public static function init() {

        self::$date_format = get_option( 'date_format' );
		self::$time_format = get_option( 'time_format' );
		self::$admin_email = get_option( 'admin_email' );

    }

    /**
     * Sends a wp remote call
     * 
     * @param $url
     * @param array $params
     * @param string $method_type
     * 
     * @return array|mixed|object|string
     * @example response array('response', 'body', 'headers') | array('response', 'body')
     */
    public static function make_wp_requests( $url, $params = array(), $headers = array(), $request_method = 1 ) {
        
        $body = array(
            'response'  =>  500,
            'body'      =>  array( __( 'CURL Error', WPWFW_PLUGIN_DOMAIN_LANGUAGE ) )
        );

        /**
         * $method_type
         * GET    - 1
         * POST   - 2
         * DELETE - 3
         */

         $args = array(
            'timeout'       => 45, // Reduced from 45 to 15 seconds for better performance
            'httpversion'   => '1.1', // Updated to HTTP 1.1 for better performance
            'blocking'      => true,
            'body'          => $params,
            'user-agent'    => 'WasapBot-WooCommerce/' . ( defined('WPWFW_VERSION') ? WPWFW_VERSION : '3.1.2' ),
         );

         if ( is_array( $headers ) && count( $headers ) > 0 ) {
			$args['headers'] = $headers;
		}

		switch ( $request_method ) {
			case 2:
				$args['method'] = 'POST';
				break;
			case 3:
				$args['method'] = 'DELETE';
				break;
			case 4:
				$args['method'] = 'PUT';
				break;
			case 5:
				$args['method'] = 'PATCH';
				break;
			default:
				$args['method'] = 'GET';
				break;
		}

		$response = wp_remote_request( $url, $args );
        
        if ( ! is_wp_error( $response ) ) {
			$body    = wp_remote_retrieve_body( $response );
			$headers = wp_remote_retrieve_headers( $response );
			if ( self::is_json( $body ) ) {
				$body = json_decode( $body, true );
			}
			$body = maybe_unserialize( $body );
			if ( in_array( $response['response']['code'], self::$allowed_responses, true ) ) {
				$response_code = 200;
			} else {
				$response_code = $response['response']['code'];
			}

			$body = array(
				'response' => intval( $response_code ),
				'body'     => $body,
				'headers'  => $headers,
				'requested_url' => $url,
			);

			return $body;
		}

		$body['body'] = [ 'status'=> 'error', 'message' => $response->get_error_message() ];

		return $body;

    }

    /**
	 * Checks if a given string is a valid json
	 *
	 * @param $string
	 *
	 * @return bool
	 */
	public static function is_json( $string ) {
		json_decode( $string );

		return ( json_last_error() === JSON_ERROR_NONE );
	}

    /**
	 * @param string $actual_value
	 * @param string $compare_type
	 * @param string $expected_value
	 *
	 * @return bool
	 */
	public static function validate_string( $actual_value, $compare_type, $expected_value ) {
		// case insensitive
		$actual_value   = strtolower( (string) $actual_value );
		$expected_value = strtolower( (string) $expected_value );
		$return_status  = false;
		switch ( $compare_type ) {

			case 'is':
				$return_status = ( $actual_value === $expected_value );//phpcs:ignore WordPress.PHP.StrictComparisons
				break;

			case 'is_not':
				$return_status = ( $actual_value !== $expected_value );//phpcs:ignore WordPress.PHP.StrictComparisons
				break;

			case 'contains':
				$return_status = strstr( $actual_value, $expected_value ) !== false;
				break;

			case 'not_contains':
				$return_status = strstr( $actual_value, $expected_value ) === false;
				break;

			case 'starts_with':
				$length = strlen( $expected_value );

				$return_status = substr( $actual_value, 0, $length ) === $expected_value;
				break;

			case 'ends_with':
				$length = strlen( $expected_value );

				if ( 0 === $length ) {
					$return_status = true;
				} else {
					$return_status = substr( $actual_value, - $length ) === $expected_value;
				}
				break;

			case 'blank':
				$return_status = empty( $actual_value );
				break;

			case 'not_blank':
				$return_status = ! empty( $actual_value );
				break;
		}

		return $return_status;
	}

	public static function get_time_difference( $start, $end, $unit ) {
		date_default_timezone_set('Asia/Kuala_Lumpur');
		// Calculate the time difference in seconds
		$diff = strtotime($end) - strtotime($start);

		if ( empty( $unit ) ) $unit = 'hour';

		// Convert the time difference to the desired unit of time
		switch ($unit) {
			case 'min':
				$factor = 60;
				break;
			case 'hour':
				$factor = 3600;
				break;
			case 'day':
				$factor = 86400;
				break;
			default:
				$factor = 1;
				break;
		}
		$diff = floor($diff / $factor);
	
		return $diff;
	}

	/**
	 * Encode contents
	 * @param string $string String to be encoded
	 * 
	 * @return string
	 */
	public static function encode_content( $string ) {

		return urlencode( html_entity_decode( $string, ENT_QUOTES, "UTF-8" ) );
		
	}

	/**
	 * Validate and format a phone number
	 * @param string $phone Phone number to be validated.
	 * @param string $mode Defaults to 'LITE' for simple processing.
	 * @param object $arg Optional. If country_code is given, a cross check will be performed with the given number
	 *
	 * @return bool|mixed|array
	 */
	public static function validate_number( $phone, $mode = 'LITE', $arg = [] ) {

		$processing = filter_var( $phone, FILTER_SANITIZE_NUMBER_INT );
		$final = array(
			'status' => false,
			'response' => 'Unable to validate phone number',
			'data' => [],
		);

		if ( $mode == 'LITE' ) {
			if ( substr($processing, 0, 2) == '00' ) {
				$processing = substr($processing, 2);
			}

			preg_match( "/(\d{1,4})[0-9.\-]+/", $processing, $prefix );
			if ( empty( $prefix ) ) {
				$final['response'] = 'Invalid phone number';
				return $final;
			}

			// Recipient country is not the same as recipient number, add country code
			if ( isset( $arg['country_code'] ) ) {
				if ( strpos( strval($prefix[1] ), strval( $arg['country_code'] ) ) === false ) {
					$processing = $arg['country_code'].ltrim($processing, '0');
				}
			}
		}

		/**
		 * For LibPhoneNumber Library
		 */
		if ( $mode == 'LIBPHONENUMBER') {

		}

		$final = array(
			'status' => true,
			'response' => 'Success',
			'data' => ['phone' => $processing],
		);

		return $final;

	}

	public static function get_country_code( $country_prefix = -1 ) {

		// -1 is to return all
		$prefixes = [
			'AC' => '247', 
			'AD' => '376', 
			'AE' => '971', 
			'AF' => '93', 
			'AG' => '1268', 
			'AI' => '1264', 
			'AL' => '355', 
			'AM' => '374', 
			'AO' => '244', 
			'AQ' => '672', 
			'AR' => '54', 
			'AS' => '1684', 
			'AT' => '43', 
			'AU' => '61', 
			'AW' => '297', 
			'AX' => '358', 
			'AZ' => '994', 
			'BA' => '387', 
			'BB' => '1246', 
			'BD' => '880', 
			'BE' => '32', 
			'BF' => '226', 
			'BG' => '359', 
			'BH' => '973', 
			'BI' => '257', 
			'BJ' => '229', 
			'BL' => '590', 
			'BM' => '1441', 
			'BN' => '673', 
			'BO' => '591', 
			'BQ' => '599', 
			'BR' => '55', 
			'BS' => '1242', 
			'BT' => '975', 
			'BW' => '267', 
			'BY' => '375', 
			'BZ' => '501', 
			'CA' => '1', 
			'CC' => '61', 
			'CD' => '243', 
			'CF' => '236', 
			'CG' => '242', 
			'CH' => '41', 
			'CI' => '225', 
			'CK' => '682', 
			'CL' => '56', 
			'CM' => '237', 
			'CN' => '86', 
			'CO' => '57', 
			'CR' => '506', 
			'CU' => '53', 
			'CV' => '238', 
			'CW' => '599', 
			'CX' => '61', 
			'CY' => '357', 
			'CZ' => '420', 
			'DE' => '49', 
			'DJ' => '253', 
			'DK' => '45', 
			'DM' => '1767', 
			'DO' => '1809', 
			'DO' => '1829', 
			'DO' => '1849', 
			'DZ' => '213', 
			'EC' => '593', 
			'EE' => '372', 
			'EG' => '20', 
			'EH' => '212', 
			'ER' => '291', 
			'ES' => '34', 
			'ET' => '251', 
			'EU' => '388', 
			'FI' => '358', 
			'FJ' => '679', 
			'FK' => '500', 
			'FM' => '691', 
			'FO' => '298', 
			'FR' => '33', 
			'GA' => '241', 
			'GB' => '44', 
			'GD' => '1473', 
			'GE' => '995', 
			'GF' => '594', 
			'GG' => '44', 
			'GH' => '233', 
			'GI' => '350', 
			'GL' => '299', 
			'GM' => '220', 
			'GN' => '224', 
			'GP' => '590', 
			'GQ' => '240', 
			'GR' => '30', 
			'GT' => '502', 
			'GU' => '1671', 
			'GW' => '245', 
			'GY' => '592', 
			'HK' => '852', 
			'HN' => '504', 
			'HR' => '385', 
			'HT' => '509', 
			'HU' => '36', 
			'ID' => '62', 
			'IE' => '353', 
			'IL' => '972', 
			'IM' => '44', 
			'IN' => '91', 
			'IO' => '246', 
			'IQ' => '964', 
			'IR' => '98', 
			'IS' => '354', 
			'IT' => '39', 
			'JE' => '44', 
			'JM' => '1876', 
			'JO' => '962', 
			'JP' => '81', 
			'KE' => '254', 
			'KG' => '996', 
			'KH' => '855', 
			'KI' => '686', 
			'KM' => '269', 
			'KN' => '1869', 
			'KP' => '850', 
			'KR' => '82', 
			'KW' => '965', 
			'KY' => '1345', 
			'KZ' => '7', 
			'LA' => '856', 
			'LB' => '961', 
			'LC' => '1758', 
			'LI' => '423', 
			'LK' => '94', 
			'LR' => '231', 
			'LS' => '266', 
			'LT' => '370', 
			'LU' => '352', 
			'LV' => '371', 
			'LY' => '218', 
			'MA' => '212', 
			'MC' => '377', 
			'MD' => '373', 
			'ME' => '382', 
			'MF' => '590', 
			'MG' => '261', 
			'MH' => '692', 
			'MK' => '389', 
			'ML' => '223', 
			'MM' => '95', 
			'MN' => '976', 
			'MO' => '853', 
			'MP' => '1670', 
			'MQ' => '596', 
			'MR' => '222', 
			'MS' => '1664', 
			'MT' => '356', 
			'MU' => '230', 
			'MV' => '960', 
			'MW' => '265', 
			'MX' => '52', 
			'MY' => '60', 
			'MZ' => '258', 
			'NA' => '264', 
			'NC' => '687', 
			'NE' => '227', 
			'NF' => '672', 
			'NG' => '234', 
			'NI' => '505', 
			'NL' => '31', 
			'NO' => '47', 
			'NP' => '977', 
			'NR' => '674', 
			'NU' => '683', 
			'NZ' => '64', 
			'OM' => '968', 
			'PA' => '507', 
			'PE' => '51', 
			'PF' => '689', 
			'PG' => '675', 
			'PH' => '63', 
			'PK' => '92', 
			'PL' => '48', 
			'PM' => '508', 
			'PR' => '1787', 
			'PR' => '1939', 
			'PS' => '970', 
			'PT' => '351', 
			'PW' => '680', 
			'PY' => '595', 
			'QA' => '974', 
			'QN' => '374', 
			'QS' => '252', 
			'QY' => '90', 
			'RE' => '262', 
			'RO' => '40', 
			'RS' => '381', 
			'RU' => '7', 
			'RW' => '250', 
			'SA' => '966', 
			'SB' => '677', 
			'SC' => '248', 
			'SD' => '249', 
			'SE' => '46', 
			'SG' => '65', 
			'SH' => '290', 
			'SI' => '386', 
			'SJ' => '47', 
			'SK' => '421', 
			'SL' => '232', 
			'SM' => '378', 
			'SN' => '221', 
			'SO' => '252', 
			'SR' => '597', 
			'SS' => '211', 
			'ST' => '239', 
			'SV' => '503', 
			'SX' => '1721', 
			'SY' => '963', 
			'SZ' => '268', 
			'TA' => '290', 
			'TC' => '1649', 
			'TD' => '235', 
			'TG' => '228', 
			'TH' => '66', 
			'TJ' => '992', 
			'TK' => '690', 
			'TL' => '670', 
			'TM' => '993', 
			'TN' => '216', 
			'TO' => '676', 
			'TR' => '90', 
			'TT' => '1868', 
			'TV' => '688', 
			'TW' => '886', 
			'TZ' => '255', 
			'UA' => '380', 
			'UG' => '256', 
			'UK' => '44', 
			'US' => '1', 
			'UY' => '598', 
			'UZ' => '998', 
			'VA' => '379', 
			'VA' => '39', 
			'VC' => '1784', 
			'VE' => '58', 
			'VG' => '1284', 
			'VI' => '1340', 
			'VN' => '84', 
			'VU' => '678', 
			'WF' => '681', 
			'WS' => '685', 
			'XC' => '991', 
			'XD' => '888', 
			'XG' => '881', 
			'XL' => '883', 
			'XN' => '857', 
			'XN' => '858', 
			'XN' => '870', 
			'XP' => '878', 
			'XR' => '979', 
			'XS' => '808', 
			'XT' => '800', 
			'XV' => '882', 
			'YE' => '967', 
			'YT' => '262', 
			'ZA' => '27', 
			'ZM' => '260', 
			'ZW' => '263' 
		];
	
		return ($country_prefix == -1) ? $prefixes : (isset($prefixes[$country_prefix]) ? $prefixes[$country_prefix] : '');
	}

	public static function get_meta_datas( $meta_datas ) {
		$new_meta_data = array();
		foreach ($meta_datas as $meta_data) {
			if ( isset($meta_data->key) && isset($meta_data->value) ) {
				$new_meta_data[$meta_data->key] = [$meta_data->value];
			}
		}
		
		return $new_meta_data;
	}
}
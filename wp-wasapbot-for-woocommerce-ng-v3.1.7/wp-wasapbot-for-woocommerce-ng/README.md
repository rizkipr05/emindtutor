# WasapBot for WooCommerce

A WordPress plugin that enables WhatsApp order notifications for WooCommerce stores.

## Features

- Send order status notifications via WhatsApp to customers
- Custom message templates for different order statuses
- After-sale follow up messages
- Cart abandonment recovery notifications  
- Support for custom order statuses
- Integration with shipping providers like NinjaVan, DHL, etc.
- Bulk message resend functionality
- Debug mode and logging
- Test message functionality
- Responsive admin dashboard
- Shortcode support for order data

## Installation

1. Download and install the plugin through WordPress admin panel
2. Activate the plugin through the 'Plugins' menu
3. Go to WasapBot settings to configure your API credentials and message templates

## Configuration

1. Enter your WasapBot API credentials (subdomain, access token, instance ID)
2. Customize notification message templates for different order statuses
3. Enable desired notification triggers
4. Configure follow-up message settings if needed
5. Test the connection by sending a sample message

    ## Usage

The plugin will automatically send WhatsApp notifications when:

- New orders are placed
- Order status changes 
- Order notes are added
- Cart is abandoned (if enabled)
- Follow-up time is reached (if enabled)

## Requirements

- WordPress 2.1+
- PHP 7.4+
- WooCommerce
- WasapBot API credentials

## Support

For support and documentation, visit [wasapbot.my](https://wasapbot.my)

## License

This project is licensed under the MIT License.

## Changelog

See [changelog.log](changelog.log) for release history.
<?php
/**
	Plugin Name: WP Desk WooCommerce To Excel
	Plugin URI: https://www.wpdesk.net/products/wp-desk-woocommerce-export/
	Description: WP Desk WooCommerce To Excel
	Product: WP Desk WooCommerce To Excel
	Version: 1.0
	Author: WP Desk
	Author URI: https://www.wpdesk.net/
	Text Domain: wp-desk-woocommerce-export
	Domain Path: /lang/

	@package \WPDesk\WPDeskWooExport

	Copyright 2021 WP Desk Ltd.

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation; either version 2 of the License, or
	(at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

/* THESE TWO VARIABLES CAN BE CHANGED AUTOMATICALLY */
$plugin_version           = '1.0.0';

$plugin_name        = 'WP Desk WooCommerce To Excel';
$plugin_class_name  = '\WPDesk\WPDeskWooExport\Plugin';
$plugin_text_domain = 'wp-desk-woocommerce-export';
$product_id         = 'wp-desk-woocommerce-export';
$plugin_file        = __FILE__;
$plugin_dir         = dirname( __FILE__ );

$requirements = array(
	'php'     => '7.1',
	'wp'      => '5.7',
	'plugins' => array(
		array(
			'name'      => 'woocommerce/woocommerce.php',
			'nice_name' => 'WooCommerce',
			'version'   => '5.3',
		),
	),
);
require __DIR__ . '/vendor_prefixed/wpdesk/wp-plugin-flow/src/plugin-init-php52.php';

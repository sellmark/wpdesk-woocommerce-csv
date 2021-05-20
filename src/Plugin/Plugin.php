<?php
/**
 * Plugin main class.
 *
 * @package WPDesk\WPDeskWooExport
 */

namespace WPDesk\WPDeskWooExport;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\NullLogger;
use WPDeskWooExportVendor\WPDesk_Plugin_Info;
use WPDeskWooExportVendor\WPDesk\PluginBuilder\Plugin\AbstractPlugin;
use WPDeskWooExportVendor\WPDesk\PluginBuilder\Plugin\HookableCollection;
use WPDeskWooExportVendor\WPDesk\PluginBuilder\Plugin\HookableParent;
// use League\Csv\Writer;

use League\Csv\CannotInsertRecord;
use League\Csv\Writer;



/**
 * Main plugin class. The most important flow decisions are made here.
 *
 * @package WPDesk\WPDeskWooExport
 */
class Plugin extends AbstractPlugin implements LoggerAwareInterface, HookableCollection {
	use LoggerAwareTrait;
	use HookableParent;

	/**
	 * Plugin constructor.
	 *
	 * @param WPDesk_Plugin_Info $plugin_info Plugin info.
	 */
	public function __construct( WPDesk_Plugin_Info $plugin_info ) {
		parent::__construct( $plugin_info );
		$this->setLogger( ( new WPDeskLoggerFactory() )->createWPDeskLogger() );
		// $this->setLogger( $this->is_debug_mode() ? ( new WPDeskLoggerFactory() )->createWPDeskLogger() : new NullLogger() );

		$this->plugin_url       = $this->plugin_info->get_plugin_url();
		$this->plugin_namespace = $this->plugin_info->get_text_domain();
	}

	/**
	 * Initializes plugin external state.
	 *
	 * The plugin internal state is initialized in the constructor and the plugin should be internally consistent after creation.
	 * The external state includes hooks execution, communication with other plugins, integration with WC etc.
	 *
	 * @return void
	 */
	public function init() {
		parent::init();
		// echo "ROFLMAO";
	}

	/**
	 * Integrate with WordPress and with other plugins using action/filter system.
	 *
	 * @return void
	 */
	public function hooks() {
		parent::hooks();
		\add_action( 'admin_menu', 'my_plugin_menu' );
	}

	public function register_admin_menu(){
		
		\add_submenu_page( 
			'wpdesk-helper',
			'Export WooCommerce do CSV',
			'manage_options',
			'wpdesk-woocommerce-export',
			'handle_render_wpdesk_export_csv_page'
		);
	}

	public function handle_render_wpdesk_export_csv_page(){
		return "<h1>Jest OK</h1>";
	}
}

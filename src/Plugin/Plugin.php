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

use League\Csv\CannotInsertRecord;
use League\Csv\Writer;
use League\Csv\Reader;
use SplTempFileObject;


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
		$this->setLogger( new NullLogger() );

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
		$this->hooks();
	}

	/**
	 * Integrate with WordPress and with other plugins using action/filter system.
	 *
	 * @return void
	 */
	public function hooks() {
		parent::hooks();
		add_action( 'admin_menu', array( $this, 'register_admin_menu' ), 50 );
	}

	/**
	 * Provide submenu for Export inside WPDesk admin menu
	 *
	 * @return void
	 */
	public function register_admin_menu() {
		add_submenu_page(
			'wpdesk-helper',
			__( 'WooCommerce CSV Export', 'wp-desk-woocommerce-export' ),
			__( 'WooCommerce CSV Export', 'wp-desk-woocommerce-export' ),
			'manage_options',
			'wpdesk-csv-export',
			function () {
				$this->handle_render_wpdesk_export_csv_page();
			}
		);
	}


	/**
	 * Provide submenu for Export inside WPDesk admin menu
	 *
	 * @return void
	 */
	public function handle_render_wpdesk_export_csv_page() {
		$headers[] = array(
			'product_name',
			'product_sku',
			'product_categories',
			'sale_price',
			'regular_price',
		);
		$records = $this->get_woo_products_array();
		$records = array_merge( $headers, $records );
		try {
			$csv = Writer::createFromFileObject( new SplTempFileObject() );
			$csv->setOutputBOM( Reader::BOM_UTF8 );
			$csv->setDelimiter( ';' );
			$csv->insertAll( $records );
			ob_end_clean();
			$csv->output( 'products.csv' );
			die;
		} catch ( CannotInsertRecord $e ) {
			$e->getRecords();
		}
	}
	/**
	 * Gather WooCommerce Products Data
	 *
	 * @return array
	 */
	public function get_woo_products_array() {
		$products_array = array();
		$params = array(
			'posts_per_page' => -1,
			'post_type' => array( 'product', 'product_variation' ),
		);
		$wc_query = new \WP_Query( $params );
		if ( $wc_query->have_posts() ) {
			while ( $wc_query->have_posts() ) {
				$wc_query->the_post();
				$product = wc_get_product( get_the_ID() );
				$product_parent_id = $product->is_type( 'variation' ) ? $product->get_parent_id() : $product->get_id();
				$product_categories = array();
				$product_terms = get_the_terms( $product_parent_id, 'product_cat' );
				if ( ! empty( $product_terms ) ) {
					foreach ( $product_terms as $p_cat ) {
						$product_categories[] = $p_cat->name;
					}
				}
				$products_array[] = array(
					get_the_title(),
					$product->get_sku(),
					implode( ',', $product_categories ),
					$product->get_sale_price(),
					$product->get_regular_price(),
				);
			}
			wp_reset_postdata();
		}

		return $products_array;
	}
}

<?php

/**
 *
 * @link              https://createandcode.com
 * @since             1.0.0
 * @package           WooCommerce_Product_Image_Gallery_Options
 *
 * @wordpress-plugin
 * Plugin Name:       WooCommerce Product Image Gallery Options
 * Plugin URI:        https://createandcode.com/plugins/woocommerce-product-image-gallery-options
 * Description:       Switch on/off WooCommerce 3.0+ Product Image Gallery options on a per product basis.
 * Version:           1.0.2
 * Author:            Create and Code
 * Author URI:        https://createandcode.com
 * Requires at least: 4.7
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       woocommerce-product-image-gallery-options
 * Domain Path:       /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Required minimums and constants
 */
define( 'WC_PIGO_MIN_WC_VER', '3.0.0' );


/**
 * Main WooCommerce_Product_Image_Gallery_Options Class
 *
 * @class WooCommerce_Product_Image_Gallery_Options
 * @version	1.0.0
 * @since 1.0.0
 * @package	WooCommerce_Product_Image_Gallery_Options
 */

if ( ! class_exists( 'WooCommerce_Product_Image_Gallery_Options' ) ) {

	class WooCommerce_Product_Image_Gallery_Options {

		/**
		 * Plugin version.
		 *
		 * @var string
		 */
		const VERSION = '1.0.1';

		/**
		 * Notices (array)
		 *
		 * @var array
		 */
		public $notices = array();

		public function __construct() {
			add_action( 'admin_init', array( $this, 'check_environment' ) );
			add_action( 'admin_notices', array( $this, 'admin_notices' ), 15 );
			add_action( 'plugins_loaded', array( $this, 'init' ) );
			add_action( 'init', array( $this, 'load_plugin_textdomain' ) );
		}

		/**
		 * Init the plugin after plugins_loaded so environment variables are set.
		 */
		public function init() {
			// Don't hook anything else in the plugin if we're in an incompatible environment
			if ( self::get_environment_warning() ) {
				return;
			}

			/**
			 * Load CMB2
			 */
			require_once( dirname( __FILE__ ) . '/includes/cmb2/init.php' );

			/**
			 * Load Custom Fields
			 */
			include_once( dirname( __FILE__ ) . '/includes/custom-fields.php' );

			/**
			 * Check on product pages for image gallery settings
			 */
			add_action( 'wp', array( $this, 'wc_pigo_single_settings' ) );
		}

		/**
		 * The backup sanity check, in case the plugin is activated in a weird way,
		 * or the environment changes after activation.
		 */
		public function check_environment() {
			$environment_warning = self::get_environment_warning();

			if ( $environment_warning && is_plugin_active( plugin_basename( __FILE__ ) ) ) {
				$this->add_admin_notice( 'bad_environment', 'error', $environment_warning );
			}
		}

		/**
		 * Checks the environment for compatibility problems.  Returns a string with the first incompatibility
		 * found or false if the environment has no problems.
		 */
		static function get_environment_warning() {
			if ( ! defined( 'WC_VERSION' ) ) {
				return __( 'WooCommerce Product Image Gallery Options requires WooCommerce 3.0+ to be activated to work.', 'woocommerce-product-image-gallery-options' );
			}

			if ( version_compare( WC_VERSION, WC_PIGO_MIN_WC_VER, '<' ) ) {
				$message = __( 'WooCommerce Product Image Gallery Options - The minimum WooCommerce version required for this plugin is %1$s. You are running %2$s.', 'woocommerce-product-image-gallery-options', 'woocommerce-product-image-gallery-options' );

				return sprintf( $message, WC_PIGO_MIN_WC_VER, WC_VERSION );
			}

			return false;
		}

		/**
		 * Display any notices we've collected thus far
		 */
		public function admin_notices() {
			foreach ( (array) $this->notices as $notice_key => $notice ) {
				echo "<div class='" . esc_attr( $notice['class'] ) . "'><p>";
				echo wp_kses( $notice['message'], array(
					'a' => array(
						'href' => array(),
					),
				) );
				echo '</p></div>';
			}
		}

		/**
		 * Allow this class and other classes to add slug keyed notices (to avoid duplication)
		 */
		public function add_admin_notice( $slug, $class, $message ) {
			$this->notices[ $slug ] = array(
				'class'   => $class,
				'message' => $message,
			);
		}

		/**
		 * Load the plugin text domain for translation.
		 *
		 * @return void
		 */
		public function load_plugin_textdomain() {
			$locale = apply_filters( 'plugin_locale', get_locale(), 'woocommerce-product-image-gallery-options' );
			load_textdomain( 'woocommerce-product-image-gallery-options', trailingslashit( WP_LANG_DIR ) . 'woocommerce-product-image-gallery-options/woocommerce-product-image-gallery-options-' . $locale . '.mo' );
			load_plugin_textdomain( 'woocommerce-product-image-gallery-options', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
		}

		public function wc_pigo_single_settings() {
			global $post;
			if ( is_product() ) {
				$wc_pigo_hide_zoom = get_post_meta( $post->ID, '_wc_pigo_hide_image_zoom', true );
				$wc_pigo_hide_image_lightbox = get_post_meta( $post->ID, '_wc_pigo_hide_image_lightbox', true );
				$wc_pigo_hide_image_slider = get_post_meta( $post->ID, '_wc_pigo_hide_image_slider', true );

				if ( $wc_pigo_hide_zoom ) {
					remove_theme_support( 'wc-product-gallery-zoom' );
				}
				if ( $wc_pigo_hide_image_lightbox ) {
					remove_theme_support( 'wc-product-gallery-lightbox' );
				}
				if ( $wc_pigo_hide_image_slider ) {
					remove_theme_support( 'wc-product-gallery-slider' );
				}
			}
		}

	}

	$WooCommerce_Product_Image_Gallery_Options = new WooCommerce_Product_Image_Gallery_Options();
}// End if().

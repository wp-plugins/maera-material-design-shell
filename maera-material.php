<?php
/*
Plugin Name:         Maera Material Design Shell
Plugin URI:          https://press.codes
Description:         Material Design shell
Version:             0.95.3
Author:              Aristeides Stathopoulos
Author URI:          https://press.codes
Text Domain:         maera_md
*/

define( 'MAERA_MD_VER', '0.95.3' );
define( 'MAERA_MATERIAL_SHELL_URL', plugins_url( '', __FILE__ ) );
define( 'MAERA_MATERIAL_SHELL_PATH', dirname( __FILE__ ) );

/**
 * Include the shell
 */
function maera_shell_material_include( $shells ) {

	// Add our shell to the array of available shells
	$shells[] = array(
		'value' => 'material',
		'label' => 'Material',
		'class' => 'Maera_Material',
	);

	return $shells;

}
add_filter( 'maera/shells/available', 'maera_shell_material_include' );

if ( ! class_exists( 'Maera_Material' ) ) {

	/**
	* The Material Design Shell module
	*/
	class Maera_Material {

		private static $instance;
		public $timber;
		public $widgets;
		public $customizer;
		public $layouts;
		public $styles;
		public $scripts;
		public $metabox;
		public $layout;

		/**
		* Class constructor
		*/
		public function __construct() {

			if ( ! function_exists( 'kirki_get_option' ) ) {
				return;
			}

			if ( ! defined( 'MAERA_SHELL_PATH' ) ) {
				define( 'MAERA_SHELL_PATH', dirname( __FILE__ ) );
			}

			$this->requires();
			$this->required_plugins();

			$this->timber     = new Maera_MD_Timber();
			$this->widgets    = new Maera_MD_Widgets();
			$this->customizer = new Maera_MD_Customizer();
			$this->styles     = new Maera_MD_Styles();
			$this->scripts    = new Maera_MD_Scripts();
			$this->metabox    = new Maera_MD_Post_Metabox();
			$this->layout     = kirki_get_option( 'layout' );

			// Layout modifier
			add_action( 'wp', array( $this, 'layout' ) );

			// Add theme supports
			add_action( 'after_setup_theme', array( $this, 'theme_supports' ) );
			// Add extra nav areas
			add_action( 'after_setup_theme', array( $this, 'nav' ) );

		}

		/**
		 * Singleton
		 */
		public static function get_instance() {

			if ( null == self::$instance ) {
				self::$instance = new self;
			}

			return self::$instance;

		}

		/**
		 * Include required files
		 */
		function requires() {

			require_once( __DIR__ . '/includes/class-maera-md-data.php');
			require_once( __DIR__ . '/includes/class-maera-md-timber.php');
			require_once( __DIR__ . '/includes/class-maera-md-widgets.php');
			require_once( __DIR__ . '/includes/class-maera-md-customizer.php');
			require_once( __DIR__ . '/includes/class-maera-md-scripts.php');
			require_once( __DIR__ . '/includes/class-maera-md-styles.php');
			require_once( __DIR__ . '/includes/class-maera-md-post-metabox.php' );

		}

		/**
		* Build the array of required plugins.
		* You can use the 'maera/required_plugins' filter to add or remove plugins.
		*/
		function required_plugins( $plugins = array() ) {

			if ( ! $plugins || empty( $plugins ) ) {
				$plugins = array();
			}

			$plugins[] = array(
				'name' => 'jQuery Updater',
				'file' => 'jquery-updater.php',
				'slug' => 'jquery-updater',
			);

			$plugins = new Maera_Required_Plugins( $plugins );

		}

		/**
		* Add theme supports
		*/
		function theme_supports() {

			// Add theme support for Custom Header
			add_theme_support( 'custom-header', array(
			'default-image'          => '',
			'width'                  => 0,
			'height'                 => 0,
			'flex-width'             => true,
			'flex-height'            => true,
			'uploads'                => true,
			'random-default'         => true,
			'header-text'            => false,
			'default-text-color'     => '#333333',
			'wp-head-callback'       => '',
			'admin-head-callback'    => '',
			'admin-preview-callback' => '',
			) );

			// add_theme_support( 'infinite-scroll', array(
			// 	'type'           => 'click',
			// 	'footer_widgets' => false,
			// 	'container'      => 'content',
			// 	'wrapper'        => false,
			// 	'render'         => false,
			// 	'posts_per_page' => false,
			// ) );

			add_theme_support( 'site-logo' );

		}

		public static function custom_header_url() {

			$image_url = get_header_image();
			if ( is_singular() && has_post_thumbnail() ) {
				$image_array = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
				$image_url = $image_array[0];
			}

			if ( empty( $image_url ) ) {
				return false;
			} else {
				return $image_url;
			}

		}

		/**
		 * Register additional menus
		 */
		public function nav() {
			register_nav_menus( array(
				'offcanvas'  => __( 'Offcanvas - Left', 'ornea' ),
				'horizontal' => __( 'Horizontal Menu (scrolling)', 'ornea' ),
			) );
		}

		/**
		 * Modify the layout
		 */
		public function layout() {
			// Layout modifier
			global $post;

			if ( ! function_exists( 'kirki' ) ) {
				return '1';
			}

			$default_layout = kirki_get_option( 'layout' );
			$post_types     = get_post_types( array( 'public' => true ), 'names' );

			foreach ( $post_types as $post_type ) {

				if ( is_singular( $post_type ) ) {
					$this->layout = kirki_get_option( $post_type . '_layout' );
				}

			}

			if ( is_singular( 'post' ) ) {

				$custom_layout = get_post_meta( $post->ID, 'maera_md_layout', true );

				if ( 'default' != $custom_layout ) {
					$this->layout = $custom_layout;
				}

			}

		}

	}

}

function maera_material_theme_missing() { ?>

	<?php if ( ! class_exists( 'Maera' ) ) : ?>
		<div class="error">
			<p><?php _e( 'The Maera theme is not active on your site. The Maera Material Shell plugin <strong>requires</strong> the Maera theme. Please download and install it from the <a href="https://press.codes">PressCodes</a> site.', 'maera_md' ); ?></p>
		</div>
	<?php endif;

}
add_action( 'admin_notices', 'maera_material_theme_missing' );

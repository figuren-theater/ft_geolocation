<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://carsten-bach.de
 * @since      1.0.0
 *
 * @package    Ft_geolocation
 * @subpackage Ft_geolocation/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Ft_geolocation
 * @subpackage Ft_geolocation/includes
 * @author     Carsten Bach <mail@carsten-bach.de>
 */
class Ft_geolocation {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Ft_geolocation_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'FT_geolocation_VERSION' ) ) {
			$this->version = FT_geolocation_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'ft_geolocation';
		define('FT_GEOTAX','ft_geolocation');

		$this->load_dependencies();
		$this->set_locale();

		$this->define_global_hooks();



		$this->define_admin_hooks();
#		$this->loader->add_action( 'plugins_loaded', $this, 'define_admin_hooks' );

		$this->define_public_hooks();
#		$this->loader->add_action( 'init', $plugin_cpts, 'define_public_hooks' );

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Ft_geolocation_Loader. Orchestrates the hooks of the plugin.
	 * - Ft_geolocation_i18n. Defines internationalization functionality.
	 * - Ft_geolocation_Admin. Defines all hooks for the admin area.
	 * - Ft_geolocation_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		// require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-ft_geolocation-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
#		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-ft_geolocation-i18n.php';



		/**
		 * The class responsible for registration 
		 * of custom post_types and taxonomies.
		 */
#		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-ft_geolocation-posttypes-taxonomies.php';



		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
#		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-ft_geolocation-admin.php';



		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		// require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-ft_geolocation-acf.php';


		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
#		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-ft_geolocation-sitemeta.php';


		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
#		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-ft_geolocation-wp_lang.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-ft_geolocation-public.php';

		// $this->loader = new Ft_geolocation_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Ft_geolocation_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		// $plugin_i18n = new Ft_geolocation_i18n();
// 
		// $this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
	}



	private function define_global_hooks() {

		// $plugin_cpts = new Ft_geolocation_Posttypes_and_Taxonomies();
// 
		// $this->loader->add_action( 'init', $plugin_cpts, 'register_taxonomy_geolocation' );
		// $this->loader->add_action( 'init', $plugin_cpts, 'ft_add_rewrite_rules_for_shared_tax' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	public function define_admin_hooks() {

#		$plugin_admin = new Ft_geolocation_Admin( $this->get_plugin_name(), $this->get_version() );

#		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
#		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );


		// $plugin_acf = new Ft_geolocation_acf();

		// $this->loader->add_action( 'acf/input/admin_enqueue_scripts', $plugin_acf, 'admin_enqueue_scripts' );



#		$plugin_sitemeta = new Ft_geolocation_sitemeta();

#		$this->loader->add_action( 'init', $plugin_sitemeta, 'ft_acf_add_customizer_panel' );
#		$this->loader->add_action( 'init', $plugin_sitemeta, 'ft_kirki_add_option__site_address' );

		// $this->loader->add_filter('pre_update_option_ft_geo', $plugin_sitemeta, 'ft_pre_update_option_ft_geo', 10, 3 );
#		$this->loader->add_action('update_option_ft_geo', $plugin_sitemeta, 'disable_autoload', 10, 3 );


#		$plugin_wp_lang = new Ft_geolocation_wp_lang();
#		$this->loader->add_action('update_option_ft_geo', $plugin_wp_lang, 'ft_update_option__wp_lang__from__ft_geo', 10, 3 );


		// DEBUG ONLY
#		$this->loader->add_action('admin_init', $plugin_sitemeta, 'ft_get_geojson');
#		$this->loader->add_action('admin_init', $plugin_sitemeta, 'ft_get_geotax_terms');



	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	public function define_public_hooks() {

		$plugin_public = new Ft_geolocation_Public( $this->get_plugin_name(), $this->get_version() );

#		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
#		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

		$this->loader->add_filter( 'LocateAndFilter_frontend_map_query_args', $plugin_public, 'ft_filter__LocateAndFilter_frontend_map_query_args' );
		$this->loader->add_filter( 'locate_anything_marker_params', $plugin_public, 'ft_filter__locate_anything_marker_params', 10, 3 );

		// $this->loader->add_action( 'wp_head', $plugin_public, 'ft_enable_geo_2_twentytwenty_post_meta', 100 );
		// $this->loader->add_action( 'twentytwenty_end_of_post_meta_list', $plugin_public, 'ft_add_geo_2_twentytwenty_post_meta', 10, 3 );


		$this->loader->add_filter('term_link', $plugin_public, 'ft_filter_geolocation_term_link_per_cat', 10, 3);

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Ft_geolocation_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}

<?php

/**
 * 
 *
 * 
 *
 * @link       https://carsten-bach.de
 * @since      1.0.0
 *
 * @package    Ft_geolocation
 * @subpackage Ft_geolocation/includes
 */

/**
 * 
 *
 * 
 *
 * @since      1.0.0
 * @package    Ft_geolocation
 * @subpackage Ft_geolocation/includes
 * @author     Carsten Bach <mail@carsten-bach.de>
 */
class Ft_geolocation_acf {

	public function __construct() {

		// Register options page
		//add_action( 'init', array( $this, 'register_options_page' ) );

		// Register Blocks
		//add_action('acf/init', array( $this, 'register_blocks' ) );

		// Bring Your Own Geocoder (Alpha feature)
		// https://github.com/BrilliantPlugins/geometa-acf#bring-your-own-geocoder-alpha-feature
		define('GEOMETA_ACF_BYOGC', true );

	}



	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function admin_enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Ft_geolocation_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Ft_geolocation_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( self::class.'_geocoder', plugin_dir_url( __FILE__ ) . 'js/ft_geolocation-acf.js', array( 'jquery' ), false, false );
	}

}

<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://carsten-bach.de
 * @since      1.0.0
 *
 * @package    Ft_geolocation
 * @subpackage Ft_geolocation/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Ft_geolocation
 * @subpackage Ft_geolocation/public
 * @author     Carsten Bach <mail@carsten-bach.de>
 */
class Ft_geolocation_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/ft_geolocation-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/ft_geolocation-public.js', array( 'jquery' ), $this->version, false );

	}

	protected function ft_get_query_vars_from_url($url)
	{
		$query = [];
		$_ref = str_replace(site_url( '/' ), '', rtrim( $url, '/') );

		// tags
		if (strpos($_ref, '!!/')) {
			$_ref = str_replace(array('!!/', '/in'), '', $_ref);
			$query['tag'] = $_ref;
		// root
		} elseif ('in' == $_ref) {
			
		// categories
		} else {
			$_ref = str_replace('/in', '', $_ref);
			$query['category_name'] = $_ref;
		}
		return $query;
	}

	public function ft_filter__LocateAndFilter_frontend_map_query_args( $query )
	{
		// because we're inside an ajax call,
		// we dont knoiw anything about our env
		// so the way to go
		// is to act on the ugly, bad boy referer
		$query_vars_from_url = $this->ft_get_query_vars_from_url( $_SERVER['HTTP_REFERER'] );

		if (isset($query['category_name']) && 'blog' == $query['category_name']) {
			$query['post_type'] = array('post');
		} else {
			$query['post_type'] = array('post','event','ft_job');
		}


#		error_log( var_export(array($_SERVER['HTTP_REFERER'],$_ref,$query)) );
		return array_merge( $query, $query_vars_from_url );
	}


	public function ft_filter__locate_anything_marker_params($post_params,$post_id,$mystery_var)
	{
#wp_die( var_export($post_params) );
#wp_die( var_export(array($_SERVER['HTTP_REFERER'],$post_params,$post_id,$mystery_var)) );
		// because we're inside an ajax call,
		// we dont knoiw anything about our env
		// so the way to go
		// is to act on the ugly, bad boy referer
		$query_vars_from_url = $this->ft_get_query_vars_from_url( $_SERVER['HTTP_REFERER'] );

		$_post_type = get_post_type( $post_id );

		// default
		// used for / and /!!/etc
		$_symbol = 'ion-location';

		//
		switch ($_post_type) {
			case 'ft_job':
				$_symbol = 'ion-hammer';
				break;
			case 'post':
			default:
				$_symbol = 'ion-social-rss';
				break;
		}

		// FUNKTIONIERT NICHT VERLÄSSLICH FÜR ROOT/in
		if (isset($query_vars_from_url['category_name']) && 'post' == $_post_type) {
			switch ($query_vars_from_url['category_name']) {
				case 'jobs':
					$_symbol = 'ion-hammer';
					break;
				case 'festivals':
					$_symbol = 'ion-clock';
					break;
				
				case 'blog':
				default:
					$_symbol = 'ion-social-rss';
					break;
			}
		} /**/

		$post_params['locate-anything-marker-symbol'] = $_symbol;

		$post_params['locate-anything-marker-type'] = 'awesomemarker';

#		$post_params['locate-anything-marker-color'] = '#d20394'; // f.t magenta
		$post_params['locate-anything-marker-color'] = 'purple'; // must be color name
#		$post_params['locate-anything-marker-color'] = 'green';

#		$post_params['locate-anything-marker-symbol-color'] = '#d20394';
		$post_params['locate-anything-marker-symbol-color'] = '#ffffff';

#		$post_params['locate-anything-marker-symbol'] = 'ion-location';
#		$post_params['locate-anything-marker-symbol'] = 'ion-hammer';
#		$post_params['locate-anything-marker-symbol'] = 'ion-paper-airplane';
#		$post_params['locate-anything-marker-symbol'] = 'ion-ios-calendar';
#		$post_params['locate-anything-marker-symbol'] = 'ion-social-rss';

		return $post_params;
	}



	/*
	public function ft_enable_geo_2_twentytwenty_post_meta(){

		add_filter('twentytwenty_post_meta_location_single_bottom', function ($metas) {
			return array(
				'tags', // default
				'ft_geo', // NEW
			);
		} );
	}

	public function ft_add_geo_2_twentytwenty_post_meta( $post_id, $post_meta, $location )
	{
		// #var_export( $post_id, $post_meta, $location );


		// var_export($feature);


		// ? >
		// <script>
		//     console.log(<?php echo json_encode(array( $post_id, $post_meta, $location )); ? >);
		// </script>
		// < ?php

		// Tags.
		if ( in_array( 'ft_geo', $post_meta, true ) && get_the_terms( $post_id, FT_GEOTAX ) ) {

			$has_meta = true;
			?>
			<li class="post-tags meta-wrapper">
				<span class="meta-icon">
					<span class="screen-reader-text"><?php _e( 'Location', 'twentytwenty' ); ?></span>
					<?php twentytwenty_the_theme_svg( 'arrow-down' ); ?>
				</span>
				<span class="meta-text">
					<?php #the_terms( $post_id, FT_GEOTAX, '', ', ', '' ); ?>
					<?php $_geo_terms = wp_get_post_terms( $post_id, FT_GEOTAX, array( 'orderby' => 'term_id' ) ); ?>
					<?php echo implode(', ', $this->links_from_term_objs($_geo_terms)); ?>
				</span>
			</li>
			<?php

		}
	}
	protected function links_from_term_objs($terms)
	{
		$links = [];

		foreach ( $terms as $term ) {
			$link = get_term_link( $term, FT_GEOTAX );
			if ( is_wp_error( $link ) ) {
				return $link;
			}
			$links[] = '<a href="' . esc_url( $link ) . '" rel="tag">' . $term->name . '</a>';
		}
		return $links;
	}*/


	public function ft_filter_geolocation_term_link_per_cat( $url, $term, $taxonomy ) {

		if ( is_admin() || FT_GEOTAX != $taxonomy )
			return $url;

		$_new_part = '';

		if (is_category())
			$_new_part = '/'.get_query_var( 'category_name' );

		if (is_tag())
			$_new_part = '/!!/'.get_query_var( 'tag' );


		return str_replace('/in', $_new_part.'/in', $url);

	}


}

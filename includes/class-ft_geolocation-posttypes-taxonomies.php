<?php

/**
 * Define all custom post_types and custom taxonomies
 *
 * ....
 *
 * @link       http://carsten-bach.de
 * @since      2020.06.15
 *
 * @package    Ft_geolocation
 * @subpackage Ft_geolocation/includes
 */

/**
 * ....
 *
 * ....
 *
 * @since      2020.06.15
 * @package    Ft_geolocation
 * @subpackage Ft_geolocation/includes
 * @author     Carsten Bach <mail@carsten-bach.de>
 */
class Ft_geolocation_Posttypes_and_Taxonomies {

	protected $geo_tax__slug;
	protected $geo_tax__pts;
	protected $geo_tax__pts_slugs;

	protected $is_main_site;

	function __construct() {
		$this->geo_tax__slug = 'in';
		$this->geo_tax__pts  = array('post','event','ft_site');
		$this->geo_tax__pts_slugs  = array('blog','events/event');
		$this->is_main_site  = is_main_site();
	}


	// Register Custom Taxonomy
	public function register_taxonomy_geolocation() {

		$labels = array(
			'name'                       => _x( 'Locations', 'Taxonomy General Name', 'ft_GEOLOCATION' ),
			'singular_name'              => _x( 'Location', 'Taxonomy Singular Name', 'ft_GEOLOCATION' ),
			'menu_name'                  => __( 'Locations', 'ft_GEOLOCATION' ),
			'all_items'                  => __( 'All Locations', 'ft_GEOLOCATION' ),
			'parent_item'                => __( 'Parent Location', 'ft_GEOLOCATION' ),
			'parent_item_colon'          => __( 'Parent Location:', 'ft_GEOLOCATION' ),
			'new_item_name'              => __( 'New Location Name', 'ft_GEOLOCATION' ),
			'add_new_item'               => __( 'Add New Location', 'ft_GEOLOCATION' ),
			'edit_item'                  => __( 'Edit Location', 'ft_GEOLOCATION' ),
			'update_item'                => __( 'Update Location', 'ft_GEOLOCATION' ),
			'view_item'                  => __( 'View Location', 'ft_GEOLOCATION' ),
			'separate_items_with_commas' => __( 'Separate locations with commas', 'ft_GEOLOCATION' ),
			'add_or_remove_items'        => __( 'Add or remove locations', 'ft_GEOLOCATION' ),
			'choose_from_most_used'      => __( 'Choose from the most used', 'ft_GEOLOCATION' ),
			'popular_items'              => __( 'Popular Locations', 'ft_GEOLOCATION' ),
			'search_items'               => __( 'Search Locations', 'ft_GEOLOCATION' ),
			'not_found'                  => __( 'Not Found', 'ft_GEOLOCATION' ),
			'no_terms'                   => __( 'No locations', 'ft_GEOLOCATION' ),
			'items_list'                 => __( 'Locations list', 'ft_GEOLOCATION' ),
			'items_list_navigation'      => __( 'Locations list navigation', 'ft_GEOLOCATION' ),
		);
		$rewrite = array(
			'slug'                       => $this->geo_tax__slug,
#			'slug'                       => false,
			'with_front'                 => false,
			'hierarchical'               => true,
		);
		$capabilities = array(
			'manage_terms'               => 'manage_categories',
			'edit_terms'                 => 'manage_categories',
			'delete_terms'               => 'manage_categories',
			'assign_terms'               => 'edit_posts',
		);
		$args = array(
			'labels'                     => $labels,
			'hierarchical'               => true,
			'public'                     => $this->is_main_site,
			'show_ui'                    => (defined('WP_DEBUG') && constant('WP_DEBUG')) ? WP_DEBUG : $this->is_main_site,
			'show_admin_column'          => $this->is_main_site,
			'show_in_nav_menus'          => $this->is_main_site,
			'show_tagcloud'              => $this->is_main_site,
			'rewrite'                    => ( $this->is_main_site ) ? $rewrite : false, // do not depend on WP_DEBUG
#			'rewrite'                    => true, // 
			'capabilities'               => $capabilities,
#			'update_count_callback'      => 'ucc_ft_geo',
			'show_in_rest'               => $this->is_main_site,
		);
		register_taxonomy( FT_GEOTAX, $this->geo_tax__pts, $args );

		foreach ($this->geo_tax__pts as $geo_tax__pt)
			register_taxonomy_for_object_type( FT_GEOTAX, $geo_tax__pt );



	}

	public function ft_add_rewrite_rules_for_shared_tax() {

		if (!$this->is_main_site)
			return;

		global $wp_rewrite;

		// all post_types that share this LOCATION taxonomy
		$post_types = join('|', $this->geo_tax__pts_slugs );
		// rewrite slug of this tax
		$_slug = $this->geo_tax__slug;

		// first try, not bad
#		add_rewrite_rule( '('.$post_types.')/'.$_slug.'/([^/]+)/?', 'index.php?post_type=$matches[1]&'.FT_GEOTAX.'=$matches[2]', 'top' );
#		add_rewrite_rule( '('.$post_types.')/'.$_slug.'/([^/]+)/page/?([0-9]{1,})/?', 'index.php?post_type=$matches[1]&'.FT_GEOTAX.'=$matches[2]&paged=$matches[3]', 'top' );
#		add_rewrite_rule( '('.$post_types.')/'.$_slug.'/([^/]+)/feed/(feed|rdf|rss|rss2|atom)/?', 'index.php?post_type=$matches[1]&'.FT_GEOTAX.'=$matches[2]&feed=$matches[3]', 'top' );
#		add_rewrite_rule( '('.$post_types.')/'.$_slug.'/([^/]+)/(feed|rdf|rss|rss2|atom)/?', 'index.php?post_type=$matches[1]&'.FT_GEOTAX.'=$matches[2]&feed=$matches[3]', 'top' );


##		add_rewrite_rule( '(blog|jobs|festivals)/'.$_slug.'/(.+?)/feed/(feed|rdf|rss|rss2|atom)/?', 'index.php?category_name=$matches[1]&'.FT_GEOTAX.'=$matches[2]&feed=$matches[3]', 'top' );
##		add_rewrite_rule( '(blog|jobs|festivals)/'.$_slug.'/(.+?)/(feed|rdf|rss|rss2|atom)/?', 'index.php?category_name=$matches[1]&'.FT_GEOTAX.'=$matches[2]&feed=$matches[3]', 'top' );
##		add_rewrite_rule( '(blog|jobs|festivals)/'.$_slug.'/(.+?)/embed/?$', 'index.php?category_name=$matches[1]&'.FT_GEOTAX.'=$matches[2]&embed=true', 'top' );
##		add_rewrite_rule( '(blog|jobs|festivals)/'.$_slug.'/(.+?)/seite/?([0-9]{1,})/?', 'index.php?category_name=$matches[1]&'.FT_GEOTAX.'=$matches[2]&paged=$matches[3]', 'top' );
##		// have this last, to make sure that
##		// e.g. '/feed/' doesn't collide with 
##		// its hierachical location terms 
##		add_rewrite_rule( '(blog|jobs|festivals)/'.$_slug.'/(.+?)/?$', 'index.php?category_name=$matches[1]&'.FT_GEOTAX.'=$matches[2]', 'top' );

#wp_die( $wp_rewrite->get_tag_permastruct() );
if ($tag_permastruct = $wp_rewrite->get_tag_permastruct()) {
		// remove rewrite_tag, that will be taken by regex
		$tag_permastruct = str_replace('/%post_tag%', '', $tag_permastruct);

		//
		add_rewrite_rule( $tag_permastruct.'/([^/]+)/'.$_slug.'/(.+?)/feed/(feed|rdf|rss|rss2|atom)/?', 'index.php?tag=$matches[1]&'.FT_GEOTAX.'=$matches[2]&feed=$matches[3]', 'top' );
		add_rewrite_rule( $tag_permastruct.'/([^/]+)/'.$_slug.'/(.+?)/(feed|rdf|rss|rss2|atom)/?', 'index.php?tag=$matches[1]&'.FT_GEOTAX.'=$matches[2]&feed=$matches[3]', 'top' );
		add_rewrite_rule( $tag_permastruct.'/([^/]+)/'.$_slug.'/(.+?)/embed/?$', 'index.php?tag=$matches[1]&'.FT_GEOTAX.'=$matches[2]&embed=true', 'top' );
		add_rewrite_rule( $tag_permastruct.'/([^/]+)/'.$_slug.'/(.+?)/seite/?([0-9]{1,})/?', 'index.php?tag=$matches[1]&'.FT_GEOTAX.'=$matches[2]&paged=$matches[3]', 'top' );
		// have this last, to make sure that
		// e.g. '/feed/' doesn't collide with 
		// its hierachical location terms 
		add_rewrite_rule( $tag_permastruct.'/([^/]+)/'.$_slug.'/(.+?)/?$', 'index.php?tag=$matches[1]&'.FT_GEOTAX.'=$matches[2]', 'top' );
}

		add_rewrite_rule( '(.+?)/'.$_slug.'/(.+?)/feed/(feed|rdf|rss|rss2|atom)/?', 'index.php?category_name=$matches[1]&'.FT_GEOTAX.'=$matches[2]&feed=$matches[3]', 'top' );
		add_rewrite_rule( '(.+?)/'.$_slug.'/(.+?)/(feed|rdf|rss|rss2|atom)/?', 'index.php?category_name=$matches[1]&'.FT_GEOTAX.'=$matches[2]&feed=$matches[3]', 'top' );
		add_rewrite_rule( '(.+?)/'.$_slug.'/(.+?)/embed/?$', 'index.php?category_name=$matches[1]&'.FT_GEOTAX.'=$matches[2]&embed=true', 'top' );
		add_rewrite_rule( '(.+?)/'.$_slug.'/(.+?)/seite/?([0-9]{1,})/?', 'index.php?category_name=$matches[1]&'.FT_GEOTAX.'=$matches[2]&paged=$matches[3]', 'top' );
		// have this last, to make sure that
		// e.g. '/feed/' doesn't collide with 
		// its hierachical location terms 
		add_rewrite_rule( '(.+?)/'.$_slug.'/(.+?)/?$', 'index.php?category_name=$matches[1]&'.FT_GEOTAX.'=$matches[2]', 'top' );



# has a post setup, but redirects to its URL
#add_rewrite_rule( $tag_permastruct.'/([^/]+)/in/?$', 'index.php?tag=$matches[1]&pagename=in&ft_geo_in=1', 'top' );
#add_rewrite_rule( '(.+?)/in/?$', 'index.php?category_name=$matches[1]&pagename=in&ft_geo_in=1', 'top' );
#add_rewrite_rule( 'in/?$', 'index.php?pagename=in&ft_geo_in=1', 'top' );


add_rewrite_rule( $tag_permastruct.'/([^/]+)/in/?$', 'index.php?tag=$matches[1]&ft_geo_in=1', 'top' );
add_rewrite_rule( '(.+?)/in/?$', 'index.php?category_name=$matches[1]&ft_geo_in=1', 'top' );
add_rewrite_rule( 'in/?$', 'index.php?ft_geo_in=1', 'top' );
	}


} // Ft_geolocation_Posttypes_and_Taxonomies
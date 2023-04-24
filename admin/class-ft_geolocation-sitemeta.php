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
class Ft_geolocation_sitemeta {
/*
	public function ft_acf_add_customizer_panel(){

		//
		if ( function_exists('acf_add_customizer_panel') ) {

			// https://github.com/mcguffin/acf-customizer/wiki/acf_add_customizer_panel
			$panel_id = acf_add_customizer_panel(array(
				'title'        => 'Ft_geolocation',
			));

			// https://github.com/mcguffin/acf-customizer/wiki/acf_add_customizer_section
			acf_add_customizer_section(array(
				'title'        => 'Ft_geolocation Basic',
				'storage_type' => 'option',
				'panel'        => $panel_id,
			));
		}
	}*/

	public function __construct() {
	}


	public function ft_kirki_add_option__site_address() {


		// must run on init
		// but please not for everybody
		if (!is_user_logged_in())
			return;

		// 
#		if (is_main_site())
#			return;

		if (!class_exists('Kirki'))
			return;
#wp_die( 'hallo user' );

		Kirki::add_config( 'Ft_geolocation', array(
			'capability'    => 'manage_options',
			'option_type'   => 'option',
			'option_name'   => 'ft_geo',
			'disable_output'=> true,
		) );

		Kirki::add_field( 'Ft_geolocation', array(
			'type'        => 'text',
			'settings'    => 'address',
			'label'       => esc_html__( '🌎 Deine Location', 'ft_GEOLOCATION' ), // this ugly char is a 'globe'-emoji ;)
			'description' => $this->ft_kirki_desc_for__site_address(),
			'section'     => get_stylesheet().'_mod'.'_design',
			'priority'    => 10,
			'transport'   => 'postMessage',
		) );
	}

	public function ft_kirki_desc_for__site_address() {

		$ft_geo = get_option( 'ft_geo','' );
		$_ft_append_desc = '';
		$_term_links = array();

		if (!empty($ft_geo) && isset($ft_geo['tax_terms'])) {

			// we only have thoose taxonomy-terms on the main f.t
			switch_to_blog( 1 );
#		wp_die( print_r($ft_geo['tax_terms'], true ) );

			foreach ($ft_geo['tax_terms'] as $term_obj ) {
// !!!
// Custom taxonomy query not working with switch_to_blog
// https://wordpress.stackexchange.com/questions/305530/custom-taxonomy-query-not-working-with-switch-to-blog
// or
// Tax_query not working on multisite
// https://wordpress.stackexchange.com/questions/284514/tax-query-not-working-on-multisite
//
// !!!!!!!!!!!!!
// @nacin: If you have a taxonomy registered on the original blog, it will be available on the other sites you switch to.
// @see https://core.trac.wordpress.org/ticket/20541
// !!!!!!!!!!!!!
//
// found via GOOGLE: 'switch_to_blog' 'invalid_taxonomy' (which is part of WP_ERROR statement)


#		wp_die( '<pre>'.var_export($term_obj ).'</pre>' );

				$_term_link = get_term_link( $term_obj );
				$_term_links[] = '<a href="'.$_term_link.'" target="_blank">'.$term_obj->name.'</a>';
			}

			//
			restore_current_blog();

			$_ft_append_desc = sprintf(
				'<br><br><strong>%s</strong>: %s',
				__('Your content will be regional promoted for','ft_GEOLOCATION'), 
				implode(', ', $_term_links)
			);

		}
		return 
			esc_html__( 'Deine Adresse, in der Form: Abc-Straße 123, 98765 Stadt, Bundesland, Land', 'ft_GEOLOCATION' ) .
			$_ft_append_desc;
	}

	/**
	 * Filters a specific option before its value is (maybe) serialized and updated.
	 *
	 * The dynamic portion of the hook name, `$option`, refers to the option name.
	 *
	 * @since 2.6.0
	 * @since 4.4.0 The `$option` parameter was added.
	 *
	 * @param mixed  $value     The new, unserialized option value.
	 * @param mixed  $old_value The old option value.
	 * @param string $option    Option name.
	 */
	/**
	 * So we can append some spatial data and our (main blog) location taxonomies
	 */
	public function ft_pre_update_option_ft_geo( $ft_geo, $old_ft_geo, $option ) {

		// something went wrong, huh ??
		if (!isset($ft_geo['address']))
			return $ft_geo;

		// want to delete former address
		if ( 
			(isset($ft_geo['address']) && empty($ft_geo['address']) )
			&&
			(isset($old_ft_geo['address']) && !empty($old_ft_geo['address']) )
		) {
			// delete all related meta of this option
			// so we return only the address field of the 'new - empty -value'
			return array('address'=>'');
		}

		// sanitize incoming address
		$ft_geo['address'] = sanitize_text_field( $ft_geo['address'] );



		// we only want to store thoose transients on the main f.t
		switch_to_blog( 1 );

		# 1. get geojson from Nominatim API
		$geojson = $this->ft_get_geojson( $ft_geo['address'] );
#error_log("geoJSON not OK   ".print_r($geojson, true) );
		// something is wrong with this geojson
		if (false === $geojson) {
			restore_current_blog();
			return $ft_geo;
		}

		# 2. add response to $ft_geo-Array
		$ft_geo['geojson'] = $geojson;

		# 3. Ask f.t for existing taxonomies matching the reponse' and get term-IDs for ...
		# 4. Or create them based on the geolocation-request and save newly created term-IDs
		$ft_geo['tax_terms'] = $this->ft_get_geotax_terms( $geojson['properties']['address'] );

		//
		restore_current_blog();

		return $ft_geo;
	}

	/**
	 * Helper to do the geolocation request
	 * 
	 */
	public function ft_get_geojson( $address, $save_transient = true ) {

		// DEBUG ONLY
		#if (empty($address))
		#	$address = 'Friesenstrasse 8, 06112 Halle (Saale)';

		$_transient_name = 'ft_geo_' . rawurlencode( $address );
		$geojson   = get_transient( $_transient_name );
		if( empty( $geojson ) ) {

			// Make an AJAX call to Nominatim API and get a promise back.
			// almost the same as in js\ft_geolocation-acf.js


				// 'format' : 'geojson',			// This format follows the RFC7946. Every feature includes a bounding box (bbox).
				// 'addressdetails' : 1,			// Include a breakdown of the address into elements. (Default: 0)
				// 'accept-language' : 'de',		// Preferred language order for showing search results, overrides the value specified in the "Accept-Language" HTTP header. Either use a standard RFC2616 accept-language string or a simple comma-separated list of language codes.
				// 'countrycodes' : 'de,at,ch,lu',	// Limit search results to one or more countries. <countrycode> must be the ISO 3166-1alpha2 code
				// 'limit' : 1,						// Limit the number of returned results. (Default: 10, Maximum: 50)
				// 'viewbox' : <x1>,<y1>,<x2>,<y2>	// The preferred area to find search results. Any two corner points of the box are accepted in any order as long as they span a real box. x is longitude, y is latitude. // created with https://boundingbox.klokantech.com/
				// 'polygon_geojson' = 1,			// Output geometry of results as a GeoJSON, KML, SVG or WKT. Only one of these options can be used at a time. (Default: 0)
				// 'email' : 						// If you are making large numbers of request please include an appropriate email address to identify your requests. See Nominatim's Usage Policy for more details.
				// 'q' : ...						// Free-form query string to search for. Free-form queries are processed first left-to-right and then right-to-left if that fails. Commas are optional, but improve performance by reducing the complexity of the search.

			$api_args = array(
				'format' => 'geojson',
				'addressdetails' => 1,
				'accept-language' => 'de',
				'countrycodes' => 'de,at,ch,lu',
				'limit' => 1,
				'viewbox' => '5.0489318371,17.8809630871,55.6603640848,45.3770058216',
				'polygon_geojson' => 1,
				'email' => 'info+nominatim.openstreetmap.org@figuren.theater',
				'q' => $address
			);

			$url  = 'https://nominatim.openstreetmap.org/?'.http_build_query( $api_args );

			$json = wp_remote_get( $url );
			if ( 200 === (int) wp_remote_retrieve_response_code( $json ) ) {
				$body = wp_remote_retrieve_body( $json );
				$json = json_decode( $body, true );
				if (isset($json['features'][0])) {
					//wp_die( '<pre>'.var_export($json['features'][0]).'</pre>' );
					$geojson = $json['features'][0];
#error_log("geoJSON before sanitize:   ".print_r($geojson, true) );

					//
#					$geojson = sanitize_text_field( $geojson );
#error_log("geoJSON after sanitize:   ".print_r($geojson, true) );

					//
					if ( class_exists('WP_GeoUtil') && !WP_GeoUtil::is_geojson( $geojson ))
						return false;

					if (true === $save_transient)
						set_transient( $_transient_name, $geojson, HOUR_IN_SECONDS ); // keep this only a short time, to help on multi-saving etc.
#set_transient( $_transient_name, $geojson, 60 );

				}
			} // repsonse OK ?

		} // has transient
		return $geojson;
	}



	public function ft_get_geotax_terms( $address ) {
#		// DEBUG ONLY
#		if (!$address) {
#			$_addr = $this->ft_get_geojson( 'Donauwörth', false );
#			$address = $_addr['properties']['address'];
#			switch_to_blog( 1 );
#		}


		$_tax_terms = array();

		//
		$terms_to_look_for = array( 'country' => $address['country'] );

		if (isset($address['state']))
			$terms_to_look_for['state'] = $address['state'];

			// prepare for 'Berlin', 'Hamburg', 'Bremen' and similiar ...
			if (!isset($terms_to_look_for['state']) && isset($address['city'])){
				$terms_to_look_for['state'] = $address['city'];
				unset($address['city']); // do not re-use anymore
			}


		//
		if (isset($address['county']) && $address['county'] != $terms_to_look_for['state'])
			$terms_to_look_for['county'] = $address['county'];

			//
			if (!isset($terms_to_look_for['county']) && isset($address['city']))
				$terms_to_look_for['county'] = $address['city'];

			//
			if (!isset($terms_to_look_for['county']) && isset($address['place']))
				$terms_to_look_for['county'] = $address['place'];

#wp_die( var_export($terms_to_look_for) );
		foreach ($terms_to_look_for as $type => $term_to_look_for) {

			// gets WP_TERM object or FALSE
			$_term = get_term_by( 'name', $term_to_look_for, FT_GEOTAX );

			// if term already exists
			// get its ID
			if( false !== $_term) {
				$_tax_terms[$type] = $_term;
			// otherwise
			// create a new tax term
			} else {
				$_tax_terms[$type] = $this->ft_set_geotax_term( $term_to_look_for, $type, $address['country_code'], $_tax_terms );
			}
		}
#wp_die( var_export($_tax_terms) );

		//DEBUG ONLY
#		restore_current_blog();

		return $_tax_terms;
	}



	public function ft_set_geotax_term( $term_to_look_for, $type, $slug = '', $_tax_terms = '' ) {

		$_new_geotax_term_args = array();
		switch ($type) {
			case 'country':
#wp_die( '<pre>'.var_export(array( $term_to_look_for, $type, $slug, $_tax_terms)).'</pre>' );
				$_new_geotax_term_args['slug'] = $slug;
				break;
			case 'state':
#				$_new_geotax_term_args['parent'] = $_tax_terms['country']->term_taxonomy_id;
#wp_die( '<pre>'.var_export(array( $term_to_look_for, $type, $slug, $_tax_terms)).'</pre>' );
				$_new_geotax_term_args['parent'] = $_tax_terms['country']->term_id;
				break;
			case 'county':
#				$_new_geotax_term_args['parent'] = $_tax_terms['state']->term_taxonomy_id;
#wp_die( '<pre>'.var_export(array( $term_to_look_for, $type, $slug, $_tax_terms)).'</pre>' );
				$_new_geotax_term_args['parent'] = $_tax_terms['state']->term_id;
				break;
		}

		//
		$_new_geotax_term = wp_insert_term( $term_to_look_for, FT_GEOTAX, $_new_geotax_term_args );

		// NOT NEEDED AT THE MOMENT
		//update_term_meta( $term_id, $meta_key, $meta_value, $prev_value );

		//
		return get_term( $_new_geotax_term['term_id'], FT_GEOTAX );
	}

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public function disable_autoload( $old_ft_geo, $ft_geo, $option ) {

#error_log("disable_autoload:   ".print_r(array( $old_ft_geo, $ft_geo, $option), true) );

		// lets see
#		$ft_geo = get_option('ft_geo');

		// Everything new and nothing here, yet
#		if( !$ft_geo ){

			// Add this to prevent upcoming 'update_option' 
			// calls to create this option field with autoload activated
#			add_option('ft_geo', '', '', 'no');

		// we already have an option, make sure autoload is disabled for this field
#		} else {

			// by deleting and re-creating it
			delete_option( 'ft_geo' );
			// Add this to prevent upcoming 'update_option' 
			// calls to create this option field with autoload activated
			add_option('ft_geo', $ft_geo, '', 'no');

#		}
	}

}

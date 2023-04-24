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
class Ft_geolocation_wp_lang {

	public function __construct() {
	}


	/**
	 * 'ft_geo' was successfully changed, so lets change wp_lang accordingly
	 */
	public function ft_update_option__wp_lang__from__ft_geo( $old_o, $o, $n ) {

		if (
			!isset($o['geojson']) ||
			!isset($o['geojson']['properties']) ||
			!isset($o['geojson']['properties']['address']) ||
			!isset($o['geojson']['properties']['address']['country_code']) ||
			empty( $o['geojson']['properties']['address']['country_code'])
		)
			return;

		// if everything ok,
		// we have a country code
		$_cc = $o['geojson']['properties']['address']['country_code'];

		$_lang_codes = [];
		// set informal german as default
		$_lang_codes[] = 'de_DE_formal';
		$_lang_codes[] = 'de_DE';

		switch ($_cc) {
			case 'ch':
#				$_new_lang = 'de_'.$cc
				$_lang_codes[] = 'de_CH'; // fallback to
				$_lang_codes[] = 'de_CH_informal'; // make this the default
				break;
			case 'at':
				$_lang_codes[] = 'de_AT'; // 
				break;
/*
			case 'de':
			default:
#				$_new_geotax_term_args['parent'] = $_tax_terms['state']->term_taxonomy_id;
				$_new_lang = 'de_'.$cc
				break;
*/
		}
		update_option( "preferred_languages", join(',',array_reverse($_lang_codes)), "yes" );


	}

}

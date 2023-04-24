jQuery(document).on('geometa-acf/byo-geocode',function(e, origEvent, callback){

/*	// Make an AJAX call to Geocod.io and get a promise back.
 	var geopromise = jQuery.getJSON('https://api.geocod.io/v1/geocode', {
		'street' : jQuery('input[name$="[field_57d19a6b27644]"]').val(),
		'city' : jQuery('input[name$="[field_57d199a227642]"]').val(),
		'state' : jQuery('input[name$="[field_57d199ac27643]"]').val(),
		'postal_code' :  jQuery('input[name$="[field_57d19a7727645]"]').val(),
 		'api_key': myfile.geocodio_api_key // Use wp_localize_script to not hard code your API key into your JavaScript!
 	}, );*/


	// Make an AJAX call to Nominatim API and get a promise back.
		// 'format' : 'geojson',			// This format follows the RFC7946. Every feature includes a bounding box (bbox).
		// 'addressdetails' : 1,			// Include a breakdown of the address into elements. (Default: 0)
		// 'accept-language' : 'de',		// Preferred language order for showing search results, overrides the value specified in the "Accept-Language" HTTP header. Either use a standard RFC2616 accept-language string or a simple comma-separated list of language codes.
		// 'countrycodes' : 'de,at,ch,lu',	// Limit search results to one or more countries. <countrycode> must be the ISO 3166-1alpha2 code
		// 'limit' : 1,						// Limit the number of returned results. (Default: 10, Maximum: 50)
		// 'viewbox' : <x1>,<y1>,<x2>,<y2>	// The preferred area to find search results. Any two corner points of the box are accepted in any order as long as they span a real box. x is longitude, y is latitude. // created with https://boundingbox.klokantech.com/
		// 'polygon_geojson' = 1,			// Output geometry of results as a GeoJSON, KML, SVG or WKT. Only one of these options can be used at a time. (Default: 0)
		// 'email' : 						// If you are making large numbers of request please include an appropriate email address to identify your requests. See Nominatim's Usage Policy for more details.
		// 'q' : ...						// Free-form query string to search for. Free-form queries are processed first left-to-right and then right-to-left if that fails. Commas are optional, but improve performance by reducing the complexity of the search.

	var geopromise = jQuery.getJSON('https://nominatim.openstreetmap.org/', {
		'format' : 'geojson',
		'addressdetails' : 1,
		'accept-language' : 'de',
		'countrycodes' : 'de,at,ch,lu',
		'limit' : 1,
		'viewbox' : '5.0489318371,17.8809630871,55.6603640848,45.3770058216',
		'polygon_geojson' : 1,
		'email' : 'info+nominatim.openstreetmap.org@figuren.theater',
		'q' : jQuery('input[name$="[field_5ee6b2b3dae0a]"]').val()
	}, );
//console.log(geopromise,'geopromise');

	// When the promise has been fulfilled, handle success or failure
	geopromise.then(
		function( success ){
//console.log(success,'success');

			var geojson;

			// On success, if we got results...
			if ( success.features.length > 0 ) {
				var geojson = success.features[0];

/*				// And the accurace is better than state level (1 = rooftop, 5 = city level, 6 = statelevel)
				if ( res.accuracy <= 5 )  {

					// Make a GeoJSON object
					var geojson = {
						'type': 'Feature',
						'geometry': {
							'type' : 'Point',
							'coordinates' : [ res.location.lng, res.location.lat ]
						},
						'properties' : res.address_components
					};
				}*/
			}

			// And pass the object into the callback
			callback( geojson );
		}, function( failure ) {

			// Otherwise pass in anything else (or nothing) to clear the current value,
			// or don't call the callback at all to leave the existing result in place
			callback();
		}
	);
});
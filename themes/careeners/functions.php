<?php
show_admin_bar( false );

/* INLCLUDES 
 * *****************************************************************************************************/
include_once( get_stylesheet_directory() . '/functions/enqueues/enqueues.php' );
include_once( get_stylesheet_directory() . '/functions/get-options/get-options.php' );
include_once( get_stylesheet_directory() . '/functions/show-listing/show-listing.php' );

add_filter( 'register_taxonomy_args', 'my_taxonomy_args', 10, 2  );
 
/* Show Taxonomies in API requests
*****************************************************************************/
function my_taxonomy_args( $args, $taxonomy_name  ) {
	$taxonomies = array( 'location', 'tour', 'band', 'venue', 'lineup' );

	if ( in_array( $taxonomy_name, $taxonomies ) ) :
		$args['show_in_rest'] = true;
 
		// Optionally customize the rest_base or rest_controller_class
		$args['rest_base']             = $taxonomy_name;
		$args['rest_controller_class'] = 'WP_REST_Terms_Controller';
	endif;
 
    return $args;
}

/* Set API to return all $types by default
*****************************************************************************/
add_filter( 'rest_endpoints', function( $endpoints ){
	$types = array('show', 'venue', 'location', 'tour', 'band', 'lineup');

	foreach ( $types as $type ) :
		if ( ! isset( $endpoints["/wp/v2/$type"] ) ) :
			return $endpoints;
		endif;

		$endpoints["/wp/v2/$type"][0]['args']['per_page']['default'] = 1000;
		$endpoints["/wp/v2/$type"][0]['args']['per_page']['maximum'] = 1000;
	endforeach;

    return $endpoints;
});

?>

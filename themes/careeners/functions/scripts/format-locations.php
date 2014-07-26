<?php
	/* LOAD WORDPRESS
	*****************************************************************************/
	$parse_uri = explode( 'wp-content', __FILE__ );
	require_once( $parse_uri[0] . 'wp-load.php' );
	
	/* OPEN STDOUT
	*****************************************************************************/
	$stdout = fopen('php://stdout', 'w');

	$locations = get_terms( 'location' );
	
	foreach( $locations as $location ) :

		fwrite( $stdout, "Inserting display name for $location->name\n" );
		update_field( 'field_53d41c9cc1cda', $location->name, "{$location->taxonomy}_{$location->term_id}" );

		$args = array(
			'name' => str_replace( ',', '', $location->name ),
		);

		fwrite( $stdout, "Updating name for $location->name\n\n" );
		wp_update_term( $location->term_id, 'location', $args );
	endforeach;	

	fwrite( $stdout, 'DONE!!' );
	fclose( $stdout );
?>

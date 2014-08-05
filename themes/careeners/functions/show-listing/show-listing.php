<?php

function get_shows( $args ) {
$defaults = array(
		'post_type' => 'show',
		'post_status' => array( 'publish' ),
		'posts_per_page' => -1,
		'orderby' => 'meta_value',
		'meta_key' => 'date',
		'order' => 'DESC',
	);	

	$args = wp_parse_args( $args, $defaults );
	$shows= new WP_QUERY( $args );

	return $shows;
}

function get_the_bands( $id ) {
	$bands = get_the_terms( $id, 'band' );
	$band_list = '';
	foreach( $bands as $band ) :
		$band_list .= "$band->name, ";
	endforeach;
	$band_list = rtrim( $band_list, ', ' );

	return $band_list;
}


function get_the_venue( $id ) {
	$venues = get_the_terms( $is, 'venue' );
	foreach( $venues as $venue ) :
		$venue = $venue->name; 
	endforeach;

	return $venue;
}

?>

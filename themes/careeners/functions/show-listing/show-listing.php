<?php

function get_shows( $args ) {
$defaults = array(
		'post_type' => 'show',
		'post_status' => array( 'publish' ),
		'posts_per_page' => -1,
		'orderby' => 'meta_value',
		'meta_key' => 'date',
		'order' => 'ASC',
	);	

	$args = wp_parse_args( $args, $defaults );
	$shows= new WP_QUERY( $args );

	return $shows;
}


?>

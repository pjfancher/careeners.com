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

function get_show_date( $id ) {
    $date = date( 'l, F j, Y', strtotime( get_field( 'field_53cac26ff1db1', $id ) ) );

    return $date;
}

function get_the_location( $id ) {
    $locations = get_the_terms( $id, 'location' );
    foreach( $locations as $key => $location_object ) :
        $location = get_field( 'display-name', $location_object );
    endforeach;

    return $location;
}

function get_the_event( $id ) {
    $event = get_field( 'field_53cac29cf1db2', $id );

    return $event;
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
    $venues = get_the_terms( $id, 'venue' );
    foreach( $venues as $venue ) :
        $venue = $venue->name;
    endforeach;

    return $venue;
}

function get_the_lineup( $id ) {
    $lineups = get_the_terms( $id, 'lineup' );
    foreach( $lineups as $lineup ) :
        $lineup = $lineup->name;
    endforeach;

    return $lineup;
}

function get_the_tour( $id ) {
    $tours = get_the_terms( $id, 'tour' );
    foreach( $tours as $tour ) :
        $tour = $tour->name;
    endforeach;

    return $tour;
}

?>

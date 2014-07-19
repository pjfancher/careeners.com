<?php

$shows = get_shows();
echo '<pre>' . print_r($shows, true) . '</pre>';


function get_shows() {
	$shows = array();
	$db = db_connect('showsarchive');
	$result_node = mysql_query( 'SELECT nid, title FROM node' );
	while($row_node = mysql_fetch_array( $result_node ) ) :
		$nid = $row_node['nid'];
		$shows[$nid] = array(
			'old_id' => $nid,
			'title' => $row_node['title'], 
		);

		/* GET THE DATE
		*****************************************************************************/
		$result = mysql_query( "SELECT field_date_value FROM field_data_field_date WHERE entity_id = $nid" );
		while( $row = mysql_fetch_array( $result ) ) :
			$shows[$nid]['date'] = $row['field_date_value'];
		endwhile;

		/* GET THE EVENT
		*****************************************************************************/
		$result = mysql_query( "SELECT field_event_value FROM field_data_field_event WHERE entity_id = $nid" );
		while( $row = mysql_fetch_array( $result ) ) :
			if( isset( $row['field_event_value'] ) && !empty( $row['field_event_value'] ) ) :
				$shows[$nid]['event'] = $row['field_event_value'];
			endif;
		endwhile;

		/* GET THE FLYER
		*****************************************************************************/
		$result = mysql_query( "SELECT field_flyer_fid FROM field_data_field_flyer WHERE entity_id = $nid" );
		while( $row = mysql_fetch_array( $result ) ) :
			if( isset( $row['field_flyer_fid'] ) && !empty( $row['field_flyer_fid'] ) ) :
				$fid = $row['field_flyer_fid'];
				$result_flyer = mysql_query( "SELECT filename FROM file_managed WHERE fid = $fid" );
				while( $row_flyer = mysql_fetch_array( $result_flyer ) ) :
					$shows[$nid]['flyer'] = $row_flyer['filename'];
				endwhile;
			endif;
		endwhile;

		/* GET THE PHOTOS
		*****************************************************************************/
		$result = mysql_query( "SELECT field_photos_fid FROM field_data_field_photos WHERE entity_id = $nid" );
		while( $row = mysql_fetch_array( $result ) ) :
			if( isset( $row['field_photos_fid'] ) && !empty( $row['field_photos_fid'] ) ) :
				$fid = $row['field_photos_fid'];
				$result_photo = mysql_query( "SELECT filename FROM file_managed WHERE fid = $fid" );
				while( $row_photo = mysql_fetch_array( $result_photo ) ) :
					$shows[$nid]['photos'][] = $row_photo['filename'];
				endwhile;
			endif;
		endwhile;

		/* GET THE BANDS
		*****************************************************************************/
		$result = mysql_query( "SELECT field_bands_value FROM field_data_field_bands WHERE entity_id = $nid" );
		while( $row = mysql_fetch_array( $result ) ) :
			if( isset( $row['field_bands_value'] ) && !empty( $row['field_bands_value'] ) ) :
				$bands = explode( ',', $row['field_bands_value'] );
				foreach( $bands as $band ) :
					$shows[$nid]['bands'][] = $band;
				endforeach;
			endif;
		endwhile;

		/* GET THE TAXONOMIES
		*****************************************************************************/
		$result_tax_index = mysql_query( "SELECT tid FROM taxonomy_index WHERE nid = {$row_node['nid']}" ); 
		while( $row_tax_index = mysql_fetch_array( $result_tax_index ) ) :
			$result_tax_term = mysql_query( "SELECT tid, vid, name FROM taxonomy_term_data WHERE tid = {$row_tax_index['tid']}" );
			while( $row_tax_term = mysql_fetch_array( $result_tax_term ) ) :
				$tid = $row_tax_term['tid'];
				$taxonomy = $row_tax_term['vid'];
				$term = $row_tax_term['name'];

				$shows[$nid]['taxonomies'][$tid] = array(
					'taxonomy_id' => $taxonomy,
					'term' => $term,
				);
			endwhile;
		endwhile;
	endwhile;

	return $shows;
}


function import_shows( $shows ) {
	/* LOAD WORDPRESS
	*****************************************************************************/
	$parse_uri = explode( 'wp-content', __FILE__ );
	require_once( $parse_uri[0] . 'wp-load.php' );

	/* OPEN STDOUT
	*****************************************************************************/
	$stdout = fopen('php://stdout', 'w');
	$db = db_connect('careeners');

	foreach( $shows as $show ) :
		$args = array(
			'post_title' => $show['title'],
			'post_status' => 'publish',
			'post_type' => 'show',
		);

		/* INSERT POSTS
		*****************************************************************************/
		$post_id = wp_insert_post( $args );
		fwrite( $stdout, "PUBLISHING {$show['title']}\n" );
	endforeach;
	fclose( $stdout );
}


function db_connect($database)   //connects to database
  {
     $db = @mysql_connect('localhost', 'root', 'root');
     if (!$db)
     {
       exit("Could not connect to Database. Please try again.");
     }
     if (!@mysql_select_db($database))
     {
       exit("Could not connect to Database. Please try again.");
     }
  }
?>


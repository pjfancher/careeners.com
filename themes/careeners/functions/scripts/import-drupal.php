<?php

import_shows( get_shows() );

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

		/* GET THE VENUE
		*****************************************************************************/
		$result = mysql_query( "SELECT field_venue_value FROM field_data_field_venue WHERE entity_id = $nid" );
		while( $row = mysql_fetch_array( $result ) ) :
			if( isset( $row['field_venue_value'] ) && !empty( $row['field_venue_value'] ) ) :
				$shows[$nid]['venue'] = $row['field_venue_value'];
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

				$shows[$nid]['taxonomies'][$taxonomy] = array(
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
	$image_path = 'http://careeners.com/sites/default/files/';


	/* JSON ENCODE AND SAVE TO FILE
	*****************************************************************************/
	$shows_json = json_encode( $shows );
	$json_file_name = get_stylesheet_directory() . '/functions/scripts/json/shows-' . date( 'c' ) . ".json";
	$json_file = fopen( $json_file_name, 'w' );
	fwrite( $json_file, $shows_json );
	fclose( $json_file );

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
	
		/* SET DATA
		*****************************************************************************/
		update_field( 'old-id', $show['old_id'], $post_id );
		update_field( 'event', $show['event'], $post_id );
		update_field( 'date',  date( 'Ymd', strtotime( $show['date'] ) ), $post_id );
		wp_set_post_terms( $post_id, array( $show['venue'] ), 'venue' );


		/* SET THE FLYER
		*****************************************************************************/
		if( isset( $show['flyer'] ) && !empty( $show['flyer'] ) ) :
			set_image( $image_path . $show['flyer'], $post_id );
		endif;

		/* SET THE BANDS
		*****************************************************************************/
		if( isset( $show['bands'] ) && !empty( $show['bands'] ) ) :
			wp_set_post_terms( $post_id, $show['bands'], 'band' ); 
		endif;

		foreach( $show['taxonomies'] as $key => $taxonomy ) :

			/* SET LOCATIONS
			*****************************************************************************/
			if( $key == 2 ) :
				wp_set_post_terms( $post_id, $taxonomy, 'location' ); 
			endif;

			/* SET LINEUPS
			*****************************************************************************/
			if( $key == 5 ) :
				wp_set_post_terms( $post_id, $taxonomy, 'lineup' ); 
			endif;

			/* SET TOURS
			*****************************************************************************/
			if( $key == 4 ) :
				wp_set_post_terms( $post_id, $taxonomy, 'tour' ); 
			endif;
		endforeach;

		/* SET PHOTOS
		*****************************************************************************/
		$photo_ids = array();
		if( isset( $show['photos'] ) && !empty( $show['photos'] ) ) :
			$photo_count = 1;
			foreach( $show['photos'] as $photo ) :
				fwrite( $stdout, "\n\tUPLOADING PHOTO $photo_count for {$show['title']}\n" );
				$photo_ids[] = upload_image( $photo, $image_path );				
				$photo_count++;
			endforeach;
			update_post_meta( $post_id, 'photos', $photo_ids );
			update_post_meta( $post_id, '_photos', 'field_53cc1d10fb407' );
		endif;

		
	endforeach;
	fwrite( $stdout, "\n\nALL DONE!\n" );
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


function set_image( $image, $post_id ) {
	$parse_uri = explode( 'wp-content', __FILE__ );
	require_once( $parse_uri[0] . 'wp-load.php' );
	require_once(ABSPATH . 'wp-admin/includes/media.php');
	require_once(ABSPATH . 'wp-admin/includes/file.php');
	require_once(ABSPATH . 'wp-admin/includes/image.php');

	// magic sideload image returns an HTML image, not an ID
	$media = media_sideload_image($image, $post_id);

	// therefore we must find it so we can set it as featured ID
	if(!empty($media) && !is_wp_error($media)){
		$args = array(
			'post_type' => 'attachment',
			'posts_per_page' => -1,
			'post_status' => 'any',
			'post_parent' => $post_id
		);

		// reference new image to set as featured
		$attachments = get_posts($args);

		if(isset($attachments) && is_array($attachments)){
			foreach($attachments as $attachment){
				// grab source of full size images (so no 300x150 nonsense in path)
				$image = wp_get_attachment_image_src($attachment->ID, 'full');
				// determine if in the $media image we created, the string of the URL exists
				if(strpos($media, $image[0]) !== false){
					// if so, we found our image. set it as thumbnail
					set_post_thumbnail($post_id, $attachment->ID);
					// only want one image
					break;
				}
			}
		}
	}

	return true;
}


function upload_image( $filename, $image_path ) {
	$parse_uri = explode( 'wp-content', __FILE__ );
	require_once( $parse_uri[0] . 'wp-load.php' );
	require_once(ABSPATH . 'wp-admin/includes/media.php');
	require_once(ABSPATH . 'wp-admin/includes/file.php');
	require_once(ABSPATH . 'wp-admin/includes/image.php');

	$uploaddir = wp_upload_dir();
	$uploadfile = $uploaddir['path'] . '/' . $filename;

	$contents= file_get_contents( $image_path . $filename );
	$savefile = fopen($uploadfile, 'w');
	fwrite($savefile, $contents);
	fclose($savefile);

	$wp_filetype = wp_check_filetype(basename($filename), null );

	$attachment = array(
		'post_mime_type' => $wp_filetype['type'],
		'post_title' => $filename,
		'post_content' => '',
		'post_status' => 'inherit'
	);

	$attach_id = wp_insert_attachment( $attachment, $uploadfile );

	$imagenew = get_post( $attach_id );
	$fullsizepath = get_attached_file( $imagenew->ID );
	$attach_data = wp_generate_attachment_metadata( $attach_id, $fullsizepath );
	wp_update_attachment_metadata( $attach_id, $attach_data );

	return $attach_id;

}
?>


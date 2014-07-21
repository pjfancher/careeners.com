<?php
	function get_options_id() {
		$post = get_page_by_path( 'options' );
		return $post->ID;
	}
?>

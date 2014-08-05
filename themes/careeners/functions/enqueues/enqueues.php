<?php
add_action( 'wp_enqueue_scripts', 'theme_styles_and_scripts' );
function theme_styles_and_scripts() {
	// Main Stylesheet
	wp_register_style( 'style-css', get_stylesheet_directory_uri() . '/style.css' );
	wp_enqueue_style( 'style-css' );

}

?>

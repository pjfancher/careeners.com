<!DOCTYPE html>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

	<title>
	<?php
	  global $page, $paged;
	  echo wp_title( '|', false, 'right' );
	?>
	</title>

	<!-- Site Favicon --> 
	<link rel="shortcut icon" href="<?php bloginfo('stylesheet_directory'); ?>/images/favicon.ico" />

	<?php if ( get_home_url() == 'http://careeners.com' ) : ?>
		<script>
	</script>
	<?php endif; ?>

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<header>

<?php $options_id = get_options_id(); ?>
<div class="row">
	<div class="large-8 columns">
		<?php echo wp_get_attachment_image( get_field( 'header-image', $options_id ), 'full', null, array( 'alt' => 'logo', 'title' => 'logo' ) ); ?>
		
	</div><!-- end .large-8 -->
</div><!-- end .row -->	


</header>

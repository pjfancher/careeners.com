<?php
/*
 * Template Name: Home
 */
?>

<?php get_header(); ?>
<?php $shows = get_shows(); ?>

<div class="container">
		<div class="row">
			<div class="large-8 small-12 columns">
				<section class="show-listing">
					<?php while( $shows->have_posts() ) : $shows->the_post(); ?>
						<?php include( locate_template( 'template-parts/show-listing/show-listing.php' ) ); ?>
					<?php endwhile; ?>
				</section>
			</div><!-- end .large-8 -->
			<div class="large-4 columns show-for-medium-up">
				Sidebar	
			</div><!-- end .large-4 -->
		</div><!-- end .row -->	
</div><!-- end .container -->


<?php get_footer(); ?>

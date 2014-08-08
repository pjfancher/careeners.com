<?php
/*
 * Template Name: Show Listing
 */
?>

<?php get_header(); ?>
<?php $shows = get_shows(); ?>

<div class="container">
		<div class="row">
			<div class="large-8 small-12 columns">
				<section class="show-listing">
					<div class="shows-total">
						<?php echo number_format( $shows->post_count ); ?> shows found
					</div><!-- end .shows-total -->
					
					<?php while( $shows->have_posts() ) : $shows->the_post(); ?>
						<?php include( locate_template( 'template-parts/show-listing/show-listing.php' ) ); ?>
					<?php endwhile; ?>
				</section>
			</div><!-- end .large-8 -->
			<div class="large-4 columns show-for-medium-up">
				Search <!-- make into a sidebar template -->	
			</div><!-- end .large-4 -->
		</div><!-- end .row -->	
</div><!-- end .container -->


<?php get_footer(); ?>

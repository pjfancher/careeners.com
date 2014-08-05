<?php
/*
 * Template Name: Home
 */
?>

<?php get_header(); ?>
<?php
	$shows = get_shows();
?>
<div class="container">
		<div class="row">
			<div class="col-md-8">
				<section class="show-listing">
					<?php while( $shows->have_posts() ) : $shows->the_post(); ?>
						<div class="show">
							<div class="show-date">
								<?php echo get_the_title(); ?>		
							</div><!-- end .show-date -->
							<div class="show-location">

							</div><!-- end .show-location -->
							<div class="show-event">
								
							</div><!-- end .show-event -->
							<div class="show-bands">
								
							</div><!-- end .show-bands -->
							<div class="show-lineup">
								
							</div><!-- end .show-line-up -->
							<div class="show-flyer">
								
							</div><!-- end .show-flyer -->
							<div class="show-photos">
								
							</div><!-- end .show-photos -->
						</div><!-- end .show -->
					<?php endwhile; ?>
				</section>
			</div><!-- end .col-md-8 -->
			<div class="col-md-4">
				Sidebar	
			</div><!-- end .col-md-4 -->
		</div><!-- end .row -->	
</div><!-- end .container -->


<?php get_footer(); ?>

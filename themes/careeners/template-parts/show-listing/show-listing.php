<?php
	$locations = get_the_terms( get_the_ID(), 'location' ); 
	foreach( $locations as $key => $location_object ) :
		$location = get_field( 'field_53d41c9cc1cda', get_the_ID() );
	endforeach;
?>
<div class="show">
	<div class="show-date">

	</div><!-- end .show-date -->
	<div class="show-location">

	</div><!-- end .show-location -->
	<div class="show-venue">
		<?php echo get_the_venue( get_the_ID() ); ?>
	</div><!-- end .show-venue -->
	
	<div class="show-event">
		
	</div><!-- end .show-event -->
	<div class="show-bands">
		<?php echo get_the_bands( get_the_ID() ); ?>
	</div><!-- end .show-bands -->
	<div class="show-lineup">
		
	</div><!-- end .show-line-up -->
	<div class="show-flyer">
		
	</div><!-- end .show-flyer -->
	<div class="show-photos">
		
	</div><!-- end .show-photos -->
</div><!-- end .show -->

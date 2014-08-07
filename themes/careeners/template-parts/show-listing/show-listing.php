<?php $show_id = get_the_ID(); ?>
<div class="show">

    <div class="show-date">
        <?php echo get_show_date( $show_id ); ?>
    </div><!-- end .show-date -->

    <div class="show-location">
        <?php echo get_the_location( $show_id ); ?> @ <?php echo get_the_venue( $show_id ); ?>
    </div><!-- end .show-location -->

    <div class="show-event">
        <?php echo get_the_event( $show_id ); ?>
    </div><!-- end .show-event -->

    <div class="show-bands">
        with: <?php echo get_the_bands( $show_id ); ?>
    </div><!-- end .show-bands -->

    <div class="show-lineup">
        <?php echo get_the_lineup( $show_id ); ?>
    </div><!-- end .show-lineup -->

    <div class="show-tour">
        <?php echo get_the_tour( $show_id ); ?>
    </div><!-- end .show-tour -->

    <div class="show-flyer">

    </div><!-- end .show-flyer -->

    <div class="show-photos">

    </div><!-- end .show-photos -->

</div><!-- end .show -->

<?php
/**
 * Displays the sidebar widget area.
 */
if ( is_active_sidebar( 'sidebar-1' ) ) : ?>

  <aside class="position-sticky mb-5<?php echo !empty($args['class'])? ' '.$args['class'] : ''; ?>" style="top: 2rem;">
		<?php dynamic_sidebar( 'sidebar-1' ); ?>
	</aside><!-- .widget-area -->

	<?php
endif;
?>

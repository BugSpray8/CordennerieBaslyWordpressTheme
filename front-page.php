<?php



if ( ! is_page_template() && ! get_theme_mod( 'disable_frontpage_sections', false ) ) {

		get_header();

		do_action( 'basly_header' ); ?>
	<div class="<?php echo esc_attr( basly_layout() ); ?>">
		<?php

		do_action( 'basly_sections', false );

		get_footer();

} else {
	include( get_page_template() );
} ?>

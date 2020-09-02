<?php


get_header();

$default         = basly_get_blog_layout_default();
$sidebar_layout  = apply_filters( 'basly_sidebar_layout', get_theme_mod( 'basly_blog_sidebar_layout', $default ) );
$wrapper_classes = apply_filters( 'basly_filter_archive_content_classes', 'col-md-8 archive-post-wrap' );

do_action( 'basly_before_archive_content' );

?>

<div class="<?php echo basly_layout(); ?>">
	<div class="basly-blogs" data-layout="<?php echo esc_attr( $sidebar_layout ); ?>">
		<div class="container">
			<div class="row">
				<?php
				if ( $sidebar_layout === 'sidebar-left' ) {
					get_sidebar();
				}
				?>
				<div class="<?php echo esc_attr( $wrapper_classes ); ?>">
					<?php
					if ( have_posts() ) :
						while ( have_posts() ) :
							the_post();
							get_template_part( 'template-parts/content' );
						endwhile;
						do_action( 'basly_before_pagination' );
						the_posts_pagination();
						do_action( 'basly_after_pagination' );
					else :
							get_template_part( 'template-parts/content', 'none' );
					endif;
					?>
				</div>
				<?php

				if ( $sidebar_layout === 'sidebar-right' ) {
					get_sidebar();
				}
				?>
			</div>
		</div>
	</div>
	<?php
	get_footer(); ?>

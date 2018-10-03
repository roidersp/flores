<?php
/**
 * Displays content for front page
 *
 * @package WordPress
 * @subpackage giftsshop
 * @since 1.0
 * @version 1.0
 */

?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'ftc-panel ' ); ?> >

	<?php if ( has_post_thumbnail() ) :
		$thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'ftc-featured-image' );

		$post_thumbnail_id = get_post_thumbnail_id( $post->ID );

		$thumbnail_attributes = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'ftc-featured-image' );

		// Calculate aspect ratio: h / w * 100%.
		$ratio = $thumbnail_attributes[2] / $thumbnail_attributes[1] * 100;
		?>

		<div class="panel-image" style="background-image: url(<?php echo esc_url( $thumbnail[0] ); ?>);">
			<div class="panel-image-prop" style="padding-top: <?php echo esc_attr( $ratio ); ?>%"></div>
		</div><!-- .panel-image -->

	<?php endif; ?>

	<div class="panel-content">
		<div class="container">
                    <?php if (!is_home() && !is_front_page()): ?>
			<header class="entry-header">
				<?php the_title( '<h2 class="entry-title">', '</h2>' ); ?>

				<?php ftc_edit_link( get_the_ID() ); ?>

			</header><!-- .entry-header -->
                    <?php endif; ?>    

			<div class="entry-content">
				<?php
					/* translators: %s: Name of current post */
					the_content( sprintf(
						__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'giftsshop' ),
						get_the_title()
					) );
				?>
			</div><!-- .entry-content -->
		<!-- If comments are open or we have at least one comment, load up the comment template. -->
		<?php if ( comments_open() || get_comments_number() ) :
			comments_template();
		endif;?>
		</div><!-- .container -->
	</div><!-- .panel-content -->

</article><!-- #post-## -->

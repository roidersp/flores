<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage giftsshop
 * @since 1.0
 * @version 1.0
 */
global $smof_data;

get_header( $smof_data['ftc_header_layout'] );

$page_column_class = ftc_page_layout_columns_class( $smof_data['ftc_blog_layout'] );
$page_title = esc_html__( 'Blog', 'giftsshop' );
ftc_breadcrumbs_title(true, true, $page_title);
?>

<div class="container">
	<?php if ( is_home() && ! is_front_page() ) : ?>
		<header class="page-header">
			<h1 class="page-title"><?php single_post_title(); ?></h1>
		</header>
	<?php else : ?>
	<header class="page-header">
		<h2 class="page-title"><?php _e( 'Posts', 'giftsshop' ); ?></h2>
	</header>
	<?php endif; ?>

	<div id="primary" class="content-area">
            <div class="row">
                <!-- Left Sidebar -->
                <?php if( $page_column_class['left_sidebar'] ): ?>
                        <aside id="left-sidebar" class="ftc-sidebar <?php echo esc_attr($page_column_class['left_sidebar_class']); ?>">
                        <?php if( is_active_sidebar( $smof_data['ftc_blog_left_sidebar'] ) ): ?>
                                <?php dynamic_sidebar( $smof_data['ftc_blog_left_sidebar'] ); ?>
                        <?php endif; ?>
                        </aside>
                <?php endif; ?>	
		<main id="main" class="site-main col-sm-9"  style="<?php if( $page_column_class['left_sidebar'] || $page_column_class['right_sidebar']) { ?>width: 75%;<?php }else{ ?>width:100%;<?php } ?>">

			<?php
			if ( have_posts() ) :

				/* Start the Loop */
				while ( have_posts() ) : the_post();

					/*
					 * Include the Post-Format-specific template for the content.
					 * If you want to override this in a child theme, then include a file
					 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
					 */
					get_template_part( 'template-parts/post/content', get_post_format() );

				endwhile;

				the_posts_pagination( array(
					'prev_text' => ftc_get_svg( array( 'icon' => 'arrow-left' ) ) . '<span class="screen-reader-text">' . __( 'Previous page', 'giftsshop' ) . '</span>',
					'next_text' => '<span class="screen-reader-text">' . __( 'Next page', 'giftsshop' ) . '</span>' . ftc_get_svg( array( 'icon' => 'arrow-right' ) ),
					'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'giftsshop' ) . ' </span>',
				) );

			else :

				get_template_part( 'template-parts/post/content', 'none' );

			endif;
			?>

		</main><!-- #main -->
                <!-- Right Sidebar -->
                <?php if( $page_column_class['right_sidebar'] ): ?>
                        <aside id="right-sidebar" class="ftc-sidebar <?php echo esc_attr($page_column_class['right_sidebar_class']); ?>">
                        <?php if( is_active_sidebar( $smof_data['ftc_blog_right_sidebar'] ) ): ?>
                                <?php dynamic_sidebar( $smof_data['ftc_blog_right_sidebar'] ); ?>
                        <?php endif; ?>
                        </aside>
                <?php endif; ?>	
            </div>
	</div><!-- #primary -->
	<?php get_sidebar(); ?>
</div><!-- .container -->

<?php get_footer();

<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package WordPress
 * @subpackage giftsshop
 * @since 1.0
 * @version 1.0
 */
global $smof_data, $post;

get_header( $smof_data['ftc_header_layout'] );

$page_column_class = ftc_page_layout_columns_class($smof_data['ftc_blog_details_layout']);
ftc_breadcrumbs_title(true, $smof_data['ftc_blog_details_title'], get_the_title());
?>

<div class="container">
	<div id="primary" class="content-area">
            <div class="row">
                <!-- Left Sidebar -->
                <?php if( $page_column_class['left_sidebar'] ): ?>
                        <aside id="left-sidebar" class="ftc-sidebar <?php echo esc_attr($page_column_class['left_sidebar_class']); ?>">
                        <?php if( is_active_sidebar($smof_data['ftc_blog_details_left_sidebar']) ): ?>
                                <?php dynamic_sidebar( $smof_data['ftc_blog_details_left_sidebar'] ); ?>
                        <?php endif; ?>
                        </aside>
                <?php endif; ?>	
                <!-- end left sidebar -->
                
		<main id="main" class="site-main col-sm-9"  style="<?php if( $page_column_class['left_sidebar'] || $page_column_class['right_sidebar']) { ?>width: 75%;<?php }else{ ?>width:100%;<?php } ?>">

			<?php
				/* Start the Loop */
				while ( have_posts() ) : the_post();

					get_template_part( 'template-parts/post/single-content', get_post_format() );

					the_post_navigation( array(
						'prev_text' => '<span class="screen-reader-text">' . __( 'Previous Post', 'giftsshop' ) . '</span><span aria-hidden="true" class="nav-subtitle">' . __( 'Previous', 'giftsshop' ) . '</span> <span class="nav-title"><span class="nav-title-icon-wrapper">' . ftc_get_svg( array( 'icon' => 'arrow-left' ) ) . '</span>%title</span>',
						'next_text' => '<span class="screen-reader-text">' . __( 'Next Post', 'giftsshop' ) . '</span><span aria-hidden="true" class="nav-subtitle">' . __( 'Next', 'giftsshop' ) . '</span> <span class="nav-title">%title<span class="nav-title-icon-wrapper">' . ftc_get_svg( array( 'icon' => 'arrow-right' ) ) . '</span></span>',
					) );

				endwhile; // End of the loop.
			?>
                    
		</main><!-- #main -->
                
                <!-- Right Sidebar -->
                <?php if( $page_column_class['right_sidebar'] ): ?>
                        <aside id="right-sidebar" class="ftc-sidebar <?php echo esc_attr($page_column_class['right_sidebar_class']); ?>">
                        <?php if( is_active_sidebar($smof_data['ftc_blog_details_right_sidebar']) ): ?>
                                <?php dynamic_sidebar( $smof_data['ftc_blog_details_right_sidebar'] ); ?>
                        <?php endif; ?>
                        </aside>
                <?php endif; ?>	
                <!-- end right sidebar -->
            </div>
	</div><!-- #primary -->
	<?php get_sidebar(); ?>
</div><!-- .container -->

<?php get_footer();

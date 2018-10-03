<?php
/**
 * The template for displaying archive pages
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
$page_title = '';
switch( true ){
	case is_day():
		$page_title = esc_html__( 'Day: ', 'giftsshop' ) . get_the_date();
	break;
	case is_month():
		$page_title = esc_html__( 'Month: ', 'giftsshop' ) . get_the_date( esc_html_x( 'F Y', 'monthly archives date format', 'giftsshop' ) );
	break;
	case is_year():
		$page_title = esc_html__( 'Year: ', 'giftsshop' ) . get_the_date( esc_html_x( 'Y', 'yearly archives date format', 'giftsshop' ) );
	break;
	case is_search():
		$page_title = esc_html__( 'Search Results for: ', 'giftsshop' ) . get_search_query();
	break;
	case is_tag():
		$page_title = esc_html__( 'Tag: ', 'giftsshop' ) . single_tag_title( '', false );
	break;
	case is_category():
		$page_title = esc_html__( 'Category: ', 'giftsshop' ) . single_cat_title( '', false );
	break;
	case is_404():
		$page_title = esc_html__( 'OOPS! FILE NOT FOUND', 'giftsshop' );
	break;
	default:
		$page_title = esc_html__( 'Archives', 'giftsshop' );
	break;
}

ftc_breadcrumbs_title(true, true, $page_title);

?>

<div class="container">

	<?php if ( have_posts() ) : ?>
		<header class="page-header">
			<?php
				the_archive_title( '<h1 class="page-title">', '</h1>' );
				the_archive_description( '<div class="taxonomy-description">', '</div>' );
			?>
		</header><!-- .page-header -->
	<?php endif; ?>

	<div id="primary" class="content-area">
            
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
		if ( have_posts() ) : ?>
			<?php
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

		endif; ?>

		</main><!-- #main -->
                
                
                <!-- Right Sidebar -->
                <?php if( $page_column_class['right_sidebar'] ): ?>
                        <aside id="right-sidebar" class="ftc-sidebar <?php echo esc_attr($page_column_class['right_sidebar_class']); ?>">
                        <?php if( is_active_sidebar( $smof_data['ftc_blog_right_sidebar'] ) ): ?>
                                <?php dynamic_sidebar( $smof_data['ftc_blog_right_sidebar'] ); ?>
                        <?php endif; ?>
                        </aside>
                <?php endif; ?>
	</div><!-- #primary -->
	<?php get_sidebar(); ?>
</div><!-- .container -->

<?php get_footer();

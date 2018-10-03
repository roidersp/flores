<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage giftsshop
 * @since 1.0
 * @version 1.0
 */
global $ftc_page_datas, $smof_data;

get_header( $smof_data['ftc_header_layout'] ); 

$show_breadcrumb = ( !is_home() && !is_front_page() && isset($ftc_page_datas['ftc_show_breadcrumb']) && absint($ftc_page_datas['ftc_show_breadcrumb']) == 1 );
$show_page_title = ( !is_home() && !is_front_page() && absint($ftc_page_datas['ftc_show_page_title']) == 1 );
if( function_exists('is_bbpress') && is_bbpress() ){
	$show_page_title = true;
	$show_breadcrumb = true;
}
if( ($show_breadcrumb || $show_page_title) && isset($smof_data['ftc_breadcrumb_layout']) ){
	$extra_class = 'show_breadcrumb_'.$smof_data['ftc_breadcrumb_layout'];
}

ftc_breadcrumbs_title($show_breadcrumb, $show_page_title, get_the_title());
?>

<div class="container">
	<div id="primary" class="content-area">
		<main id="main" class="site-main" >

			<?php
			while ( have_posts() ) : the_post();

				get_template_part( 'template-parts/page/content', 'page' );

				// If comments are open or we have at least one comment, load up the comment template.
				if ( comments_open() || get_comments_number() ) :
					comments_template();
				endif;

			endwhile; // End of the loop.
			?>

		</main><!-- #main -->
	</div><!-- #primary -->
</div><!-- .container -->

<?php get_footer();

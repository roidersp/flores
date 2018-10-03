<?php
/**
 * The Template for displaying all single products.
 *
 * Override this template by copying it to yourtheme/woocommerce/single-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $smof_data;

get_header( $smof_data['ftc_header_layout'] ); 

$extra_class = "";
$page_column_class = ftc_page_layout_columns_class($smof_data['ftc_prod_layout']);

$show_page_title = $smof_data['ftc_prod_title'];
ftc_breadcrumbs_title(true, $show_page_title, get_the_title());

?>
<div class="page-container <?php echo esc_attr($extra_class) ?>">

	<div id="main-content" class="container">
		<div class="row">
			<!-- Left Sidebar -->
			<?php if( $page_column_class['left_sidebar'] ): ?>
				<aside id="left-sidebar" class="ftc-sidebar <?php echo esc_attr($page_column_class['left_sidebar_class']); ?>">
					<?php if( is_active_sidebar($smof_data['ftc_prod_left_sidebar']) ): ?>
						<?php dynamic_sidebar( $smof_data['ftc_prod_left_sidebar'] ); ?>
					<?php endif; ?>
				</aside>
			<?php endif; ?>		
			<div id="primary" class="site-content col-sm-9" style="<?php if( $page_column_class['left_sidebar'] || $page_column_class['right_sidebar']) { ?>width: 75%;<?php }else{ ?>width:100%;<?php } ?>">
				<?php
		/**
		 * woocommerce_before_main_content hook
		 *
		 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
		 * @hooked woocommerce_breadcrumb - 20
		 */
		do_action( 'woocommerce_before_main_content' );
		?>

		<?php while ( have_posts() ) : the_post(); ?>

			<?php wc_get_template_part( 'content', 'single-product' ); ?>

		<?php endwhile; // end of the loop. ?>

		<?php
		/**
		 * woocommerce_after_main_content hook
		 *
		 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
		 */
		do_action( 'woocommerce_after_main_content' );
		?>

	</div>
	
	<!-- Right Sidebar -->
	<?php if( $page_column_class['right_sidebar'] ): ?>
		<aside id="right-sidebar" class="ftc-sidebar <?php echo esc_attr($page_column_class['right_sidebar_class']); ?>">
			<?php if( is_active_sidebar($smof_data['ftc_prod_right_sidebar']) ): ?>
				<?php dynamic_sidebar( $smof_data['ftc_prod_right_sidebar'] ); ?>
			<?php endif; ?>
		</aside>
	<?php endif; ?>

	<?php
		/**
		 * woocommerce_after_single_product_summary hook.
		 *
		 * @hooked woocommerce_output_product_data_tabs - 10
		 * @hooked woocommerce_upsell_display - 15
		 * @hooked woocommerce_output_related_products - 20
		 */
		do_action( 'woocommerce_after_single_product_summary1' );
		?>

	</div>
</div>
</div>
<?php get_footer( 'shop' ); ?>
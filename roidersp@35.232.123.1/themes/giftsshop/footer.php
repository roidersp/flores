<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package WordPress
 * @subpackage giftsshop
 * @since 1.0
 * @version 1.0
 */

?>

		</div><!-- #content -->
                <div class="container top-footer">
                <?php
		if ( is_active_sidebar( 'footer-top' ) ) { ?>
			<div class="widget-column footer-top">
				<?php dynamic_sidebar( 'footer-top' ); ?>
			</div>
		<?php } ?>
                </div>  
		<footer id="colophon" class="site-footer">
				<?php
				get_template_part( 'template-parts/footer/footer', 'widgets' );
				?>
		</footer><!-- #colophon -->
	</div><!-- .site-content-contain -->
</div><!-- #page -->
<div class="ftc-close-popup"></div>
<?php
global $smof_data, $woocommerce;
if ($smof_data['ftc_mobile_layout']): 
	?>
	<div class="footer-mobile">
		<div class="mobile-home">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" >
				<i class="fa fa-home"></i>
				<?php esc_html_e('Home','giftsshop'); ?>
			</a>   
		</div>  
		<div class="mobile-view-cart" >
			<a href="<?php echo esc_url( wc_get_cart_url() ); ?>" >
				<i class="fa fa-shopping-bag"></i>
				<?php esc_html_e('View Cart','giftsshop'); ?>
				<?php echo sprintf( '(%d)', $woocommerce->cart->get_cart_contents_count() );?>
			</a>   
		</div>
		<div class="mobile-wishlist">
			<?php
			$wishlist_page = YITH_WCWL()->get_wishlist_url();
			$count = yith_wcwl_count_products();
			?>
			<a href="<?php echo esc_url($wishlist_page); ?>">
				<i class="fa fa-heart"></i>
				<?php esc_html_e('Wishlist', 'giftsshop'); ?> <?php echo '(' . ($count > 0 ? zeroise($count, 2) : '0') . ')'; ?>
			</a>
		</div>
		<div class="mobile-account">
			<a href="<?php echo esc_url( get_permalink( get_option('woocommerce_myaccount_page_id') ) ); ?>" title="<?php esc_html_e('Login','giftsshop'); ?>">
				<i class="fa fa-user"></i>
				<?php esc_html_e('Account','giftsshop'); ?>
			</a>
		</div>
	</div>
<?php endif; ?>
<?php 
global $smof_data;
if( ( !wp_is_mobile() && $smof_data['ftc_back_to_top_button'] ) || ( wp_is_mobile() && $smof_data['ftc_back_to_top_button_on_mobile'] ) ): 
?>
<div id="to-top" class="scroll-button">
	<a class="scroll-button" href="javascript:void(0)" title="<?php esc_html_e('Back to Top', 'giftsshop'); ?>"><?php esc_html_e('Back to Top', 'giftsshop'); ?></a>
</div>
<?php endif; ?>
<?php wp_footer(); ?>

</body>
</html>

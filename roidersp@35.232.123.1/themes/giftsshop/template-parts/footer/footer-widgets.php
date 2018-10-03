<?php
/**
 * Displays footer widgets if assigned
 *
 * @package WordPress
 * @subpackage giftsshop
 * @since 1.0
 * @version 1.0
 */

?>

<?php
if ( is_active_sidebar( 'footer-top' ) ||
	 is_active_sidebar( 'footer-center' ) ||
	 is_active_sidebar( 'footer-bottom' ) ) :
?>

		<?php
		if ( is_active_sidebar( 'footer-middle' ) ) { ?>
			<div class="widget-column footer-middle">
                            <div class="container">
				<?php dynamic_sidebar( 'footer-middle' ); ?>
                            </div>
			</div>
		<?php }
		if ( is_active_sidebar( 'footer-bottom' ) ) { ?>
			<div class="widget-column footer-bottom">
                            <div class="container">
				<?php dynamic_sidebar( 'footer-bottom' ); ?>
                            </div>
			</div>
		<?php } ?>

<?php endif; ?>

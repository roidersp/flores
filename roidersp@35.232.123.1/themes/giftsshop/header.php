<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package WordPress
 * @subpackage giftsshop
 * @since 1.0
 * @version 1.0
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js no-svg">
<head>
	<?php global $smof_data; ?>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="http://gmpg.org/xfn/11">

    <?php 
    ftc_theme_favicon();
    wp_head();
    $_user_logged = is_user_logged_in();
    ?>
</head>
<?php
$header_classes = array();
if( isset($smof_data['ftc_enable_sticky_header']) && $smof_data['ftc_enable_sticky_header'] ){
    $header_classes[] = 'header-sticky';
}
?>
<body <?php body_class(); ?>>
    <?php giftsshop_header_mobile_navigation(); ?>
    <div id="page" class="site">
     <a class="skip-link screen-reader-text" href="#content"><?php _e( 'Skip to content', 'giftsshop' ); ?></a>

     <header id="masthead" class="site-header">

        <div class="header-ftc header-v1 header-<?php echo esc_attr($smof_data['ftc_header_layout']); ?>">
            <div class="header-nav">
                <div class="header-content-head">
                <div class="header-content-sticky <?php echo esc_attr(implode(' ', $header_classes)); ?>">
                <div class="container">
                    <div class="mobile-button">
                                <div class="mobile-nav">
                                    <i class="fa fa-bars"></i>
                                </div>
                            </div>
                    <div class="nav-left">
                        <div class="hidden-phone">
                             <?php if( $smof_data['ftc_header_language'] ): ?>
                            <div class="ftc-sb-language"><?php echo ftc_wpml_language_selector(); ?></div>
                        <?php endif; ?>
                            <?php if( $smof_data['ftc_header_currency'] ): ?>
                            <div class="header-currency"><?php echo ftc_woocommerce_multilingual_currency_switcher(); ?></div>
                        <?php endif; ?>
                        <?php if( !$_user_logged ): ?>
                            <?php if( $smof_data['ftc_enable_tiny_account'] ): ?>
                                <div class="ftc-sb-account"><?php echo ftc_tiny_account(); ?></div>
                            <?php endif; ?>
                        <?php endif; ?>    
                            <?php if( class_exists('YITH_WCWL') && $smof_data['ftc_enable_tiny_wishlist'] ): ?>
                                <div class="ftc-my-wishlist"><?php echo ftc_tini_wishlist(); ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="nav-center">
                        <div class="ftc-logo"><?php echo ftc_theme_logo(); ?></div>
                    </div>
                    <div class="nav-right">
                        <div class="hidden-phone">
                        <?php if( $smof_data['ftc_enable_search'] ): ?>
                            <div class="ftc-search-product"><?php ftc_get_search_form_by_category(); ?></div>
                        <?php endif; ?>

                        <?php if( $smof_data['ftc_enable_tiny_shopping_cart'] ): ?>
                            <div class="ftc-shop-cart"><?php echo ftc_tiny_cart(); ?></div>
                        <?php endif; ?>                            
                        </div>
                </div>
            </div>
            <?php if ( has_nav_menu( 'primary' ) ) : ?>
                <div class="navigation-primary">
                    <div class="container">
                        <?php get_template_part( 'template-parts/navigation/navigation', 'primary' ); ?>
                    </div><!-- .container -->
                </div><!-- .navigation-top -->
            <?php endif; ?>
        </div>
        </div>
     </div>
    </div>
    </header><!-- #masthead -->

    <div class="site-content-contain">
      <div id="content" class="site-content">

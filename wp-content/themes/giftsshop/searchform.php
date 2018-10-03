<?php
/**
 * Template for displaying search forms in giftsshop
 *
 * @package WordPress
 * @subpackage giftsshop
 * @since 1.0
 * @version 1.0
 */
?>

<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
    <label for="<?php echo esc_attr( uniqid( 'search-form-' ) ); ?>">
		<span class="screen-reader-text"><?php echo _x( 'Search for:', 'label', 'giftsshop' ); ?></span>
	</label>
	<input type="search" id="<?php echo esc_attr( uniqid( 'search-form-' ) ); ?>" class="search-field" placeholder="<?php echo esc_attr_x( 'Search &hellip;', 'placeholder', 'giftsshop' ); ?>" value="<?php echo get_search_query(); ?>" name="s" />
	<button type="submit" class="search-submit"><?php echo ftc_get_svg( array( 'icon' => 'search' ) ); ?><span class="screen-reader-text"><?php echo _x( 'Search', 'submit button', 'giftsshop' ); ?></span></button>
</form>


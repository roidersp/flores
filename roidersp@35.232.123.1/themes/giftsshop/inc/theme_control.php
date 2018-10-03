<?php 
/*** Template Redirect ***/
add_action('template_redirect', 'ftc_template_redirect');
function ftc_template_redirect(){
	global $wp_query, $post, $ftc_page_datas, $smof_data;
	
	/* Get Page Options */
	if( is_page() || is_tax( get_object_taxonomies( 'product' ) ) || is_post_type_archive('product') ){
		if( is_page() ){
			$page_id = $post->ID;
		}
		if( is_tax( get_object_taxonomies( 'product' ) ) || is_post_type_archive('product') ){
			$page_id = get_option('woocommerce_shop_page_id', 0);
		}
		$post_custom = get_post_custom( $page_id );
		if( !is_array($post_custom) ){
			$post_custom = array();
		}
		foreach( $post_custom as $key => $value ){
			if( isset($value[0]) ){
				$ftc_page_datas[$key] = $value[0];
			}
		}
		$page_option_default = array(
							'ftc_page_layout'						=> '0-1-0'
							,'ftc_left_sidebar'						=> ''
							,'ftc_right_sidebar'						=> ''
							,'ftc_left_right_padding'				=> 0
							,'ftc_full_page'							=> 0
							,'ftc_header_layout'						=> 'default'
							,'ftc_header_transparent'				=> 0
							,'ftc_header_text_color'					=> 'default'
							,'ftc_menu_id'							=> 0
							,'ftc_breadcrumb_layout'					=> 'default'
							,'ftc_breadcrumb_bg_parallax'			=> 'default'
							,'ftc_bg_breadcrumbs'					=> ''
							,'ftc_logo'								=> ''
							,'ftc_logo_mobile'						=> ''
							,'ftc_logo_sticky'						=> ''
							,'ftc_show_breadcrumb'					=> 1
							,'ftc_show_page_title'					=> 1
							,'ftc_page_slider'						=> 0
							,'ftc_page_slider_position'				=> 'before_main_content'
							,'ftc_rev_slider'						=> 0
							);
							
		$ftc_page_datas = ftc_array_atts($page_option_default, $ftc_page_datas);
		
		
		if( $ftc_page_datas['ftc_header_layout'] != 'default' ){
			$smof_data['ftc_header_layout'] = $ftc_page_datas['ftc_header_layout'];
		}
		
		if( $ftc_page_datas['ftc_breadcrumb_layout'] != 'default' ){
			$smof_data['ftc_breadcrumb_layout'] = $ftc_page_datas['ftc_breadcrumb_layout'];
		}
		
		if( $ftc_page_datas['ftc_breadcrumb_bg_parallax'] != 'default' ){
			$smof_data['ftc_breadcrumb_bg_parallax'] = $ftc_page_datas['ftc_breadcrumb_bg_parallax'];
		}
		
		if( trim($ftc_page_datas['ftc_bg_breadcrumbs']) != '' ){
			$smof_data['ftc_bg_breadcrumbs']['url'] = $ftc_page_datas['ftc_bg_breadcrumbs'];
		}
		
		if( trim($ftc_page_datas['ftc_logo']) != '' ){
			$smof_data['ftc_logo']['url'] = $ftc_page_datas['ftc_logo'];
		}
		
		if( trim($ftc_page_datas['ftc_logo_mobile']) != '' ){
			$smof_data['ftc_logo_mobile'] = $ftc_page_datas['ftc_logo_mobile'];
		}
		
		if( trim($ftc_page_datas['ftc_logo_sticky']) != '' ){
			$smof_data['ftc_logo_sticky'] = $ftc_page_datas['ftc_logo_sticky'];
		}
		
		if( $ftc_page_datas['ftc_menu_id'] ){
			add_filter('wp_nav_menu_args', 'ftc_filter_wp_nav_menu_args');
		}
		
		if( $ftc_page_datas['ftc_full_page'] ){
			add_filter('body_class', create_function('$classes', '$classes[] = "full-page"; return $classes;'));
		}
		
		if( $ftc_page_datas['ftc_left_right_padding'] && is_page_template('page-templates/fullwidth-template.php') ){
			add_filter('body_class', create_function('$classes', '$classes[] = "fullwidth-template-padding"; return $classes;'));
		}
		
	}
	
	/* Archive - Category product */
	if( is_tax( get_object_taxonomies( 'product' ) ) || is_post_type_archive('product') || (function_exists('dokan_is_store_page') && dokan_is_store_page()) ){
		ftc_set_header_breadcrumb_layout_woocommerce_page( 'shop' );
	
		add_action( 'wp_enqueue_scripts', 'ftc_grid_list_desc_style', 1000 );
		
		ftc_remove_hooks_from_shop_loop();
		
		if( function_exists('dokan_is_store_page') && dokan_is_store_page() && !$smof_data['ftc_prod_cat_grid_desc'] ){
			remove_action('woocommerce_after_shop_loop_item', 'ftc_template_loop_short_description', 60);
		}
		
		/* Update product category layout */
		if( is_tax('product_cat') ){
			$term = $wp_query->queried_object;
			if( !empty($term->term_id) ){
				$bg_breadcrumbs_id = get_term_meta($term->term_id, 'bg_breadcrumbs_id', true);
				$layout = get_term_meta($term->term_id, 'layout', true);
				$left_sidebar = get_term_meta($term->term_id, 'left_sidebar', true);
				$right_sidebar = get_term_meta($term->term_id, 'right_sidebar', true);
				
				if( $bg_breadcrumbs_id != '' ){
					$bg_breadcrumbs_src = wp_get_attachment_url( $bg_breadcrumbs_id );
					if( $bg_breadcrumbs_src !== false ){
						$smof_data['ftc_bg_breadcrumbs']['url'] = $bg_breadcrumbs_src;
					}
				}
				if( $layout != '' ){
					$smof_data['ftc_prod_cat_layout'] = $layout;
				}
				if( $left_sidebar != '' ){
					$smof_data['ftc_prod_cat_left_sidebar'] = $left_sidebar;
				}
				if( $right_sidebar != '' ){
					$smof_data['ftc_prod_cat_right_sidebar'] = $right_sidebar;
				}
			}
		}
	}
	
	/* single post */
	if( is_singular('post') ){
		$post_data = array();
		$post_custom = get_post_custom();
		foreach( $post_custom as $key => $value ){
			if( isset($value[0]) ){
				$post_data[$key] = $value[0];
			}
		}
		
		$smof_data['ftc_blog_details_layout'] = (isset($post_data['ftc_post_layout']) && $post_data['ftc_post_layout']!='0')?$post_data['ftc_post_layout']:$smof_data['ftc_blog_details_layout'];
		$smof_data['ftc_blog_details_left_sidebar'] = (isset($post_data['ftc_post_left_sidebar']) && $post_data['ftc_post_left_sidebar']!='0')?$post_data['ftc_post_left_sidebar']:$smof_data['ftc_blog_details_left_sidebar'];
		$smof_data['ftc_blog_details_right_sidebar'] = (isset($post_data['ftc_post_right_sidebar']) && $post_data['ftc_post_right_sidebar']!='0')?$post_data['ftc_post_right_sidebar']:$smof_data['ftc_blog_details_right_sidebar'];
		
		/* Update Post Views Count */
		if( !isset( $_COOKIE['ftc_post_view_'.$post->ID] ) && !ftc_crawler_detect() ){
			setcookie('ftc_post_view_'.$post->ID, '1', time()+86400, '/'); /* set cookie 1 day */
			$views_count = get_post_meta($post->ID, '_ftc_post_views_count', true);
			if( $views_count ){
				$views_count++;
				update_post_meta($post->ID, '_ftc_post_views_count', $views_count);
			}
			else{
				update_post_meta($post->ID, '_ftc_post_views_count', 1);
			}
		}
		
		/* Breadcrumb */
		$bg_breadcrumbs = get_post_meta($post->ID, 'ftc_bg_breadcrumbs', true);
		if( !empty($bg_breadcrumbs) ){
			$smof_data['ftc_bg_breadcrumbs']['url'] = $bg_breadcrumbs;
		}
	}
	
	/* Single product */
	if( is_singular('product') ){
		
		/* Add vertical thumbnail class */
		$vertical_thumbnail = isset($smof_data['ftc_prod_thumbnails_style']) && $smof_data['ftc_prod_thumbnails_style'] == 'vertical';
		if( $vertical_thumbnail ){
			add_filter('post_class', create_function('$classes', '$classes[] = "vertical-thumbnail"; return $classes;'));
		}
		
		/* Remove hooks on Related and Up-Sell products */
		ftc_remove_hooks_from_shop_loop();
		if( ! $smof_data['ftc_prod_cat_grid_desc'] ){
			remove_action('woocommerce_after_shop_loop_item', 'ftc_template_loop_short_description', 40);
		}
	
		$prod_data = array();
		$post_custom = get_post_custom();
		foreach( $post_custom as $key => $value ){
			if( isset($value[0]) ){
				$prod_data[$key] = $value[0];
			}
		}
		
		$smof_data['ftc_prod_layout'] = (isset($prod_data['ftc_prod_layout']) && $prod_data['ftc_prod_layout']!='0')?$prod_data['ftc_prod_layout']:$smof_data['ftc_prod_layout'];
		$smof_data['ftc_prod_left_sidebar'] = (isset($prod_data['ftc_prod_left_sidebar']) && $prod_data['ftc_prod_left_sidebar']!='0')?$prod_data['ftc_prod_left_sidebar']:$smof_data['ftc_prod_left_sidebar'];
		$smof_data['ftc_prod_right_sidebar'] = (isset($prod_data['ftc_prod_right_sidebar']) && $prod_data['ftc_prod_right_sidebar']!='0')?$prod_data['ftc_prod_right_sidebar']:$smof_data['ftc_prod_right_sidebar'];
		
		if( !$smof_data['ftc_prod_thumbnail'] ){
			remove_action('woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20);
		}
		
		if( $smof_data['ftc_prod_title'] && isset($smof_data['ftc_prod_title_in_content']) && $smof_data['ftc_prod_title_in_content'] ){
			$smof_data['ftc_prod_title'] = 0; /* remove title above breadcrumb */
			add_action('woocommerce_single_product_summary', 'woocommerce_template_single_title', 1);
		}
		
		if( !$smof_data['ftc_prod_label'] ){
			remove_action('ftc_before_product_image', 'ftc_template_loop_product_label', 10);
		}
		
		if( !$smof_data['ftc_prod_rating'] ){
			remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_rating', 5);
		}
		
		if( !$smof_data['ftc_prod_sku'] ){
			remove_action('woocommerce_single_product_summary', 'ftc_template_single_sku', 6);
		}
		
		if( !$smof_data['ftc_prod_availability'] ){
			remove_action('woocommerce_single_product_summary', 'ftc_template_single_availability', 3);
		}
		
		if( !$smof_data['ftc_prod_excerpt'] ){
			remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20);
		}
		
		if( !$smof_data['ftc_prod_count_down'] ){
			remove_action('woocommerce_single_product_summary', 'ftc_template_loop_time_deals', 20);
		}
		
		if( !$smof_data['ftc_prod_price'] ){
			remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 2);
			remove_action('woocommerce_single_variation', 'woocommerce_single_variation', 10);
		}
		
		if( !$smof_data['ftc_prod_add_to_cart'] || $smof_data['ftc_enable_catalog_mode'] ){
			$terms        = get_the_terms( $post->ID, 'product_type' );
			$product_type = ! empty( $terms ) ? sanitize_title( current( $terms )->name ) : 'simple';
			if( $product_type != 'variable' ){
				remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30);
			}
			else{
				remove_action( 'woocommerce_single_variation', 'woocommerce_single_variation_add_to_cart_button', 20 );
			}
		}
		
		if( !$smof_data['ftc_prod_sharing'] ){
			remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 70);
		}
		
		if( !$smof_data['ftc_prod_upsells'] ){
			remove_action('woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15);
		}
		
		if( !$smof_data['ftc_prod_related'] ){
			remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);
		}
		
		if( isset($smof_data['ftc_prod_tabs_position']) && $smof_data['ftc_prod_tabs_position'] == 'inside_summary' ){
			remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10);
			add_action('woocommerce_single_product_summary', 'woocommerce_output_product_data_tabs', 50);
		}
		
		/* Breadcrumb */
		$bg_breadcrumbs = get_post_meta($post->ID, 'ftc_bg_breadcrumbs', true);
		if( !empty($bg_breadcrumbs) ){
			$smof_data['ftc_bg_breadcrumbs']['url'] = $bg_breadcrumbs;
		}
		
		/* Fix cloudzoom for WP 4.4 */
		add_filter('wp_get_attachment_image_attributes', function($attr) {
			if( isset($attr['sizes']) ){
				unset($attr['sizes']);
			}
			if( isset($attr['srcset']) ){
				unset($attr['srcset']);
			}
			return $attr;
		}, 9999);
		add_filter('wp_calculate_image_sizes', '__return_false', 9999);
		add_filter('wp_calculate_image_srcset', '__return_false', 9999);
		remove_filter('the_content', 'wp_make_content_images_responsive');
		
	}
	
	/* Single Portfolio */
	if( is_singular('ftc_portfolio') ){
		$portfolio_data = array();
		$post_custom = get_post_custom();
		foreach( $post_custom as $key => $value ){
			if( isset($value[0]) ){
				$portfolio_data[$key] = $value[0];
			}
		}
		
		if( isset($portfolio_data['ftc_portfolio_custom_field']) && $portfolio_data['ftc_portfolio_custom_field'] == 1 ){
			$smof_data['ftc_portfolio_custom_field_title'] = isset($portfolio_data['ftc_portfolio_custom_field_title'])?$portfolio_data['ftc_portfolio_custom_field_title']:$smof_data['ftc_portfolio_custom_field_title'];
			$smof_data['ftc_portfolio_custom_field_content'] = isset($portfolio_data['ftc_portfolio_custom_field_content'])?$portfolio_data['ftc_portfolio_custom_field_content']:$smof_data['ftc_portfolio_custom_field_content'];
		}
	}
	
	/* WooCommerce - Other pages */
	if( ftc_has_woocommerce() ){
		if( is_cart() ){
			ftc_set_header_breadcrumb_layout_woocommerce_page( 'cart' );
			
			ftc_remove_hooks_from_shop_loop();
			
			if( ! $smof_data['ftc_prod_cat_grid_desc'] ){
				remove_action('woocommerce_after_shop_loop_item', 'ftc_template_loop_short_description', 60);
			}
		}
		
		if( is_checkout() ){
			ftc_set_header_breadcrumb_layout_woocommerce_page( 'checkout' );
		}
		
		if( is_account_page() ){
			ftc_set_header_breadcrumb_layout_woocommerce_page( 'myaccount' );
		}
	}

	/* Right to left */
	if( is_rtl() ){
		$smof_data['ftc_enable_rtl'] = 1;
	}
	
	/* Remove bbpress style if not in any bbpress page */
	if( function_exists('is_bbpress') && !is_bbpress() ){
		add_filter('bbp_default_styles', create_function('', 'return array();'));
		add_filter('bbp_default_scripts', create_function('', 'return array();'));
	}
	
	/* Remove background image if not necessary */
	$load_bg = true;
	if( is_page_template('page-templates/fullwidth-template.php') ){
		$load_bg = false;
	}
	
	if( !$load_bg ){
		add_filter('theme_mod_background_image', create_function('', 'return "";'));
	}
}

function ftc_filter_wp_nav_menu_args( $args ){
	global $post;
	if( is_page() && !is_admin() && !empty($args['theme_location']) && $args['theme_location'] == 'primary' ){
		$menu = get_post_meta($post->ID, 'ftc_menu_id', true);
		if( $menu ){
			$args['menu'] = $menu;
		}
	}
	return $args;
}

add_filter('single_template', 'ftc_change_single_portfolio_template');
function ftc_change_single_portfolio_template( $single_template ){
	
	if( is_singular('ftc_portfolio') && locate_template('single-portfolio.php') ){
		$single_template = locate_template('single-portfolio.php');
	}
	
	return $single_template;
}

function ftc_remove_hooks_from_shop_loop(){
	global $smof_data;
	
	if( ! $smof_data['ftc_prod_cat_thumbnail'] ){
		remove_action('woocommerce_before_shop_loop_item_title', 'ftc_template_loop_product_thumbnail', 10);
	}
	if( ! $smof_data['ftc_prod_cat_label'] ){
		remove_action('woocommerce_after_shop_loop_item_title', 'ftc_template_loop_product_label', 1);
	}
	if( ! $smof_data['ftc_prod_cat_cat'] ){
		remove_action('woocommerce_after_shop_loop_item', 'ftc_template_loop_categories', 10);
	}
	if( ! $smof_data['ftc_prod_cat_title'] ){
		remove_action('woocommerce_after_shop_loop_item', 'ftc_template_loop_product_title', 20);
	}
	if( ! $smof_data['ftc_prod_cat_sku'] ){
		remove_action('woocommerce_after_shop_loop_item', 'ftc_template_loop_product_sku', 30);
	}
	if( ! $smof_data['ftc_prod_cat_rating'] ){
		remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_rating', 60);
	}
	if( ! $smof_data['ftc_prod_cat_price'] ){
		remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_price', 50);
	}
	if( ! $smof_data['ftc_prod_cat_add_to_cart'] ){
		remove_action('woocommerce_after_shop_loop_item', 'ftc_template_loop_add_to_cart', 70); 
		remove_action('woocommerce_after_shop_loop_item_title', 'ftc_template_loop_add_to_cart', 10001 );
	}
		
}

function ftc_grid_list_desc_style(){
	$custom_css = ".products.list .short-description.list{display: inline-block !important;}";
	$custom_css .= ".products.grid .short-description.grid{display: inline-block !important;}";
    wp_add_inline_style('ftc-reset', $custom_css);
}

function ftc_set_header_breadcrumb_layout_woocommerce_page( $page = 'shop' ){
	global $smof_data;
	/* Header Layout */
	$header_layout = get_post_meta(wc_get_page_id( $page ), 'ftc_header_layout', true);
	if( $header_layout != 'default' && $header_layout != '' ){
		$smof_data['ftc_header_layout'] = $header_layout;
	}
	
	/* Breadcrumb Layout */
	$breadcrumb_layout = get_post_meta(wc_get_page_id( $page ), 'ftc_breadcrumb_layout', true);
	if( $breadcrumb_layout != 'default' && $breadcrumb_layout != '' ){
		$smof_data['ftc_breadcrumb_layout'] = $breadcrumb_layout;
	}
}

function ftc_crawler_detect(){
	if( isset($_SERVER['HTTP_USER_AGENT']) ){
		$user_agent = $_SERVER['HTTP_USER_AGENT'];
		$crawlers = array(
			'Google' 			=> 'Google'
			,'MSN' 				=> 'msnbot'
			,'Rambler' 			=> 'Rambler'
			,'Yahoo' 			=> 'Yahoo'
			,'AbachoBOT' 		=> 'AbachoBOT'
			,'accoona' 			=> 'Accoona'
			,'AcoiRobot' 		=> 'AcoiRobot'
			,'ASPSeek' 			=> 'ASPSeek'
			,'CrocCrawler' 		=> 'CrocCrawler'
			,'Dumbot' 			=> 'Dumbot'
			,'FAST-WebCrawler' 	=> 'FAST-WebCrawler'
			,'GeonaBot' 		=> 'GeonaBot'
			,'Gigabot' 			=> 'Gigabot'
			,'Lycos spider' 	=> 'Lycos'
			,'MSRBOT' 			=> 'MSRBOT'
			,'Altavista robot' 	=> 'Scooter'
			,'AltaVista robot' 	=> 'Altavista'
			,'ID-Search Bot' 	=> 'IDBot'
			,'eStyle Bot' 		=> 'eStyle'
			,'Scrubby robot' 	=> 'Scrubby'
			,'Facebook' 		=> 'facebookexternalhit'
			,'robot' 			=> 'robot'
			,'spider' 			=> 'spider'
			,'crawler' 			=> 'crawler'
			,'curl' 			=> 'curl'
		);
		$crawlers_agents = implode('|', $crawlers);
		
		if( preg_match('/'.$crawlers_agents.'/i', $user_agent) ){
			return true;
		}
		return false;
	}
	return false;
}
?>
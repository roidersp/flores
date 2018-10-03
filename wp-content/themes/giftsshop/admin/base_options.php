<?php
class FTC_Basic_Options {

	function __construct() {

		if ( ! class_exists( 'ReduxFramework' ) ) {
			$this->_load_base_options();
		}
	}

	/** Load basic options if redux is not installed * */
	function _load_base_options() {
		global $smof_data;
		$smof_data = array(
			'ftc_logo'  => array(
				'url' => get_template_directory_uri(). '/assets/images/logo.png'
			),
			'ftc_favicon'   => array(
				'url' => get_template_directory_uri(). '/assets/images/favicon.ico'
			),
			'ftc_text_logo' => 'Gift Shop',
			'ftc_header_layout' => 'layout2',
			'ftc_header_contact_information'    => '',
			'ftc_middle_header_content'         => '',
			'ftc_header_currency'               => 1,
			'ftc_header_language'               => 1,
			'ftc_enable_tiny_shopping_cart'     => 1,
			'ftc_enable_search'                 => 1,
			'ftc_enable_tiny_account'           => 1,
			'ftc_enable_tiny_checkout'			=> 1,
			'ftc_enable_tiny_wishlist'			=> 1,
			'ftc_bg_breadcrumbs'                => array(
				'url' => get_template_directory_uri(). '/assets/images/banner-shop.jpg'
			),
			'ftc_enable_breadcrumb_background_image'    => 1,
			'ftc_back_to_top_button'            => true,
			'ftc_back_to_top_button_on_mobile'  => false,
			'ftc_primary_color'                 => '#527fa4',
			'ftc_secondary_color'               => '#333333',
			'ftc_body_background_color'         => '#ffffff',
			'ftc_body_font_enable_google_font'  => 1,
			'ftc_body_font_family'              => 'Arial',
			'ftc_body_font_google'              => array(
				'color'			=> "#000000",
				'google'		=> true,
				'font-family'	=> 'Playfair Display'

			),
			'ftc_secondary_body_font_enable_google_font'    => 1,
			'ftc_secondary_body_font_google'       =>   array(
				'color'         =>"#000000",
				'google'        =>true,
				'font-family'   =>'Lato'                            
			),
			'ftc_font_size_body'               => '15',
			'ftc_line_height_body'             => '24',
			'ftc_product_sale_label_text'            => 'Sale',
			'ftc_product_feature_label_text'    => 'New',
			'ftc_product_out_of_stock_label_text'   => 'Sold out',
			'ftc_show_sale_label_as'            => 'text',
			'ftc_effect_hover_product_style'    => 'style-1',
			'ftc_effect_product'                => '1',
			'ftc_product_gallery_number'        =>  3,
			'ftc_prod_lazy_load'                => 1,
			'ftc_prod_placeholder_img'          => array(
				'url' => get_template_directory_uri(). '/assets/images/prod_loading.gif'
			),
			'ftc_enable_quickshop'              => '1',
			'ftc_enable_catalog_mode'           => '0',
			'ftc_ajax_search'                   => '1',
			'ftc_ajax_search_number_result'     => 3,
			'ftc_prod_cat_layout'               => '0-1-0',
			'ftc_prod_cat_left_sidebar'         =>  'product-category-sidebar',
			'ftc_prod_cat_right_sidebar'        =>  'product-category-sidebar',
			'ftc_prod_cat_columns'              => '3',
			'ftc_prod_cat_per_page'             => 12,
			'ftc_prod_cat_top_content'          => 1,
			'ftc_prod_cat_thumbnail'            => 1,
			'ftc_prod_cat_label'                => 1,
			'ftc_prod_cat_cat'                  => 0,
			'ftc_prod_cat_title'                => 1,
			'ftc_prod_cat_sku'                  => 0,
			'ftc_prod_cat_rating'               => 1,
			'ftc_prod_cat_price'                => 1,
			'ftc_prod_cat_add_to_cart'          => 1,
			'ftc_prod_cat_grid_desc'            => 0,
			'ftc_prod_cat_grid_desc_words'      => 30,
			'ftc_prod_cat_list_desc'            => 1,
			'ftc_prod_cat_list_desc_words'      => 28,
			'ftc_prod_layout'                   => '0-1-0',
			'ftc_prod_left_sidebar'             =>'product-detail-sidebar',
			'ftc_prod_right_sidebar'            =>'product-detail-sidebar',
			'ftc_prod_cloudzoom'                => 1,
			'ftc_prod_attr_dropdown'            => 1,
			'ftc_prod_thumbnail'                => 1,
			'ftc_prod_label'                    => 1,
			'ftc_prod_title'                    => 1,
			'ftc_prod_title_in_content'         => 0,
			'ftc_prod_rating'                   => 1,
			'ftc_prod_sku'                      => 0,
			'ftc_prod_availability'             => 1,
			'ftc_prod_excerpt'                  => 1,
			'ftc_prod_count_down'               => 1,
			'ftc_prod_price'                    => 1,
			'ftc_prod_add_to_cart'              => 1,
			'ftc_prod_cat'                      => 1,
			'ftc_prod_tag'                      => 1,
			'ftc_prod_sharing'                  => 1,
			'ftc_prod_thumbnails_style'        => 'horizontal',
			'ftc_prod_tabs'                     => 1,
			'ftc_prod_tabs_position'            => 'after_summary',
			'ftc_prod_custom_tab'               => 0,
			'ftc_prod_custom_tab_title'         => 'Custom Tab',
			'ftc_prod_custom_tab_content'       => 'Your custom content goes here. You can add the content for individual product',
			'ftc_prod_ads_banner'               => 0,
			'ftc_prod_ads_banner_content'       => '',
			'ftc_prod_upsells'                  => 0,
			'ftc_prod_related'                  => 1,
			'ftc_blog_layout'                   => '0-1-1',
			'ftc_blog_left_sidebar'             => 'blog-sidebar',
			'ftc_blog_right_sidebar'             => 'blog-sidebar',
			'ftc_blog_thumbnail'                => 1,
			'ftc_blog_date'                     => 1,
			'ftc_blog_title'                    => 1,
			'ftc_blog_author'                   => 1,
			'ftc_blog_comment'                  => 0,
			'ftc_blog_count_view'               => 0,
			'ftc_blog_read_more'                => 1,
			'ftc_blog_categories'               => 1,
			'ftc_blog_excerpt'                  => 1,
			'ftc_blog_excerpt_strip_tags'       => 0,
			'ftc_blog_excerpt_max_words'        => -1,
			'ftc_blog_details_layout'           => '0-1-1',
			'ftc_blog_details_left_sidebar'     =>'blog-detail-sidebar',
			'ftc_blog_details_right_sidebar'     =>'blog-detail-sidebar',
			'ftc_blog_details_thumbnail'        => 1,
			'ftc_blog_details_date'             => 1,
			'ftc_blog_details_title'            => 1,
			'ftc_blog_details_title'            => 1,
			'ftc_blog_details_content'          => 1,
			'ftc_blog_details_tags'             => 1,
			'ftc_blog_details_count_view'       => 0,
			'ftc_blog_details_categories'       => 1,
			'ftc_blog_details_author_box'       => 0,
			'ftc_blog_details_related_posts'    => 1,
			'ftc_blog_details_comment_form'     => 1,
		);
}
}
global $ftc_basic_option;
$ftc_basic_option = new FTC_Basic_Options();
?>
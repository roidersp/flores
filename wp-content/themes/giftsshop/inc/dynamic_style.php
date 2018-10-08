<?php 
global $smof_data;
if( !isset($data) ){
	$data = $smof_data;
}

$data = ftc_array_atts(
   array(
    /* FONTS */
    'ftc_body_font_enable_google_font'					=> 1
    ,'ftc_body_font_family'								=> "Arial"
    ,'ftc_body_font_google'								=> "Dosis"

    ,'ftc_secondary_body_font_enable_google_font'		=> 1
    ,'ftc_secondary_body_font_family'					=> "Arial"
    ,'ftc_secondary_body_font_google'					=> "Raleway"

    /* COLORS */
    ,'ftc_primary_color'									=> "#e74c3c"

    ,'ftc_secondary_color'								=> "#444444"

    ,'ftc_body_background_color'								=> "#ffffff"
    /* RESPONSIVE */
    ,'ftc_responsive'									=> 1
    ,'ftc_layout_fullwidth'								=> 0
    ,'ftc_enable_rtl'									=> 0

    /* FONT SIZE */
    /* Body */
    ,'ftc_font_size_body'								=> 13
    ,'ftc_line_height_body'								=> 24

    /* Custom CSS */
    ,'ftc_custom_css_code'								=> ''
    ), $data);		

$data = apply_filters('ftc_custom_style_data', $data);

extract( $data );

/* font-body */
if( $data['ftc_body_font_enable_google_font'] ){
	$ftc_body_font				= $data['ftc_body_font_google']['font-family'];
}
else{
	$ftc_body_font				= $data['ftc_body_font_family'];
}

if( $data['ftc_secondary_body_font_enable_google_font'] ){
	$ftc_secondary_body_font		= $data['ftc_secondary_body_font_google']['font-family'];
}
else{
	$ftc_secondary_body_font		= $data['ftc_secondary_body_font_family'];
}

?>	

/*
1. FONT FAMILY
2. GENERAL COLORS
*/


/* ============= 1. FONT FAMILY ============== */


html, 
body,
.widget-title.title_sub,.subscribe-email .button.button-secondary,
#mega_main_menu.primary ul li .mega_dropdown > li.sub-style > .item_link .link_text,
body .hot-new .widget.ftc-items-widget h2.widgettitle
{
  font-family: <?php echo esc_html($ftc_body_font) ?>;
}

#mega_main_menu.primary ul li .mega_dropdown > li.sub-style > ul.mega_dropdown,
#mega_main_menu li.multicolumn_dropdown > .mega_dropdown > li .mega_dropdown > li,
#mega_main_menu.primary ul li .mega_dropdown > li > .item_link .link_text,
.info-open,
.info-phone,
.ftc-sb-account .ftc_login > a,
.ftc-sb-account,
.ftc-my-wishlist *,
.dropdown-button span > span,
body p,
.wishlist-empty,
div.product .social-sharing li a,
.ftc-search form,
.ftc-shop-cart,
.conditions-box,
.item-description .title_sub,
.item-description .price,
.testimonial-content .content,
.testimonial-content .byline,
.widget ul.product-categories ul.children li a,
.widget:not(.widget-product-categories):not(.widget_product_categories):not(.ftc-products-widget) :not(.widget-title),
.ftc-products-category-tabs-block ul.tabs li span.title,
.woocommerce-pagination,
.woocommerce-result-count,
.woocommerce .products.list .product h3.product-name > a,
.woocommerce-page .products.list .product h3.product-name > a,
.woocommerce .products.list .product .price .amount,
.woocommerce-page .products.list .product .price .amount,
.products.list .short-description.list,
div.product .single_variation_wrap .amount,
div.product div[itemprop="offers"] .price .amount,
.orderby-title,
.blogs .excerpt,
.blog .entry-info .entry-summary .short-content,
.single-post .entry-info .entry-summary .short-content,
.single-post article .post-info .info-post,
#comments .comments-title,
#comments .comment-metadata a,
.post-navigation .nav-previous,
.post-navigation .nav-next,
.woocommerce div.product .product_title,
.woocommerce-review-link,
.feature-excerpt,
.woocommerce div.product p.stock,
.woocommerce div.product .summary div[itemprop="description"],
.woocommerce div.product p.price,
.woocommerce div.product .woocommerce-tabs .panel,
.woocommerce div.product form.cart .group_table td.label,
.woocommerce div.product form.cart .group_table td.price,
footer,
footer a,
.blogs article .smooth-thumbnail:before,
.blogs article a.gallery .owl-item:after
{
  font-family: <?php echo esc_html($ftc_secondary_body_font) ?>;
}
body,
.site-footer,
.woocommerce div.product form.cart .group_table td.label,
.woocommerce .product .conditions-box span,
.item-description .meta_info .button-in.wishlist a, .item-description .meta_info .button-in.compare a,
ul.product_list_widget li > a, h3.product-name > a,
h3.product-name, 
.single-navigation a .product-info span,
.info-company li i,
.social-icons .ftc-tooltip:before,
.widget ul.product-categories ul.children li,
.tagcloud a,
.details_thumbnails .owl-nav > div:before,
div.product .summary .yith-wcwl-add-to-wishlist a:before,
.pp_woocommerce div.product .summary .compare:before,
.woocommerce div.product .summary .compare:before,
.woocommerce-page div.product .summary .compare:before,
.woocommerce #content div.product .summary .compare:before,
.woocommerce-page #content div.product .summary .compare:before,
.woocommerce div.product form.cart .variations label,
.woocommerce-page div.product form.cart .variations label,
.pp_woocommerce div.product form.cart .variations label,
.ftc-products-category-tabs-block ul.tabs li span.title,
blockquote,
.ftc-count-milestone h3.subject,
.woocommerce .widget_price_filter .price_slider_amount,
.wishlist-empty,
.woocommerce div.product form.cart .button,
.woocommerce table.wishlist_table,
#mega_main_menu.primary ul li .mega_dropdown > li > .item_link, #mega_main_menu.primary ul li .mega_dropdown > li > .item_link .link_text, #mega_main_menu.primary ul li .mega_dropdown, #mega_main_menu.primary > .menu_holder > .menu_inner > ul > li .post_details > .post_description,
.header-v2 .lang_selected, .header-v2 .ftc-currency-selector, .header-v2 .ftc_language:after, .header-v2 .header-currency:after, .header-v2 .info-desc > span, .header-v2 .ftc-sb-account .ftc_login > a, .header-v2 .info-desc span, .header-v2 .ftc-sb-account, .header-v2 .ftc-my-wishlist *, .header-v2 .ftc_cart:before, .header-v2 .ftc-search-product .ftc_search_ajax:before,.header-currency ul li, .ftc-sb-language li
{
    font-size: <?php echo esc_html($ftc_font_size_body) ?>px;
}
/* ========== 2. GENERAL COLORS ========== */
/* ========== Primary color ========== */
.header-currency:hover .ftc-currency > a,
.header-currency:hover:after,
.ftc-sb-language:hover li .lang_selected,
.ftc-sb-language:hover .ftc_language:after,
.woocommerce a.remove:hover,
.dropdown-container .cart-list-footer > a.button.view-cart:hover,
.ftc-my-wishlist a:hover,
.ftc-sb-account .ftc_login > a:hover,
.header-currency .ftc-currency ul li:hover,
.dropdown-button span:hover,
body.wpb-js-composer .vc_general.vc_tta-tabs .vc_tta-tab.vc_active > a,
body.wpb-js-composer .vc_general.vc_tta-tabs .vc_tta-tab > a:hover,
#mega_main_menu.primary > .menu_holder.sticky_container > .menu_inner > ul > li > .item_link:hover *,
#mega_main_menu.primary > .menu_holder.sticky_container > .menu_inner > ul > li.current-menu-item > .item_link *,
#mega_main_menu.primary > .menu_holder > .menu_inner > ul > li.current-menu-ancestor > .item_link,
#mega_main_menu.primary > .menu_holder > .menu_inner > ul > li.current-menu-ancestor > .item_link *,
#mega_main_menu.primary > .menu_holder > .menu_inner > ul > li:hover > .item_link *,
#mega_main_menu.primary .mega_dropdown > li > .item_link:hover *,
#mega_main_menu.primary .mega_dropdown > li.current-menu-item > .item_link *,
#mega_main_menu.primary > .menu_holder > .menu_inner > ul > li.current-menu-item > .item_link *,
.woocommerce .products .product .price,
.woocommerce div.product p.price,
.woocommerce div.product span.price,
.woocommerce .products .star-rating,
.woocommerce-page .products .star-rating,
.woocommerce .star-rating span::before,
div.product div[itemprop="offers"] .price .amount,
div.product .single_variation_wrap .amount,
.pp_woocommerce .star-rating span::before,
.woocommerce .star-rating span::before,
.woocommerce-page .star-rating span::before,
.woocommerce-product-rating .star-rating span,
ins .amount,
.ftc-wg-meta .price ins,
.ftc-wg-meta .star-rating,
.ul-style.circle li:before,
.woocommerce form .form-row .required,
.blogs .comment-count i,
.blog .comment-count i,
.single-post .comment-count i,
.single-post article .post-info .info-post,
.single-post article .post-info .info-post .cat-links a,
.single-post article .post-info .info-post .vcard.author a,
.ftc-breadcrumb-title .breadcrumbs-container,
.ftc-breadcrumb-title .breadcrumbs-container span.current,
.ftc-breadcrumb-title .breadcrumbs-container a:hover,
.woocommerce .product .item-description .meta_info a:hover,
.woocommerce-page .product .item-description .meta_info a:hover,
.ftc-wg-meta.item-description .meta_info a:hover,
.ftc-wg-meta.item-description .meta_info .button-in.wishlist a:hover,
.ftc-gridlist-toggle-icon a.active,
.ftc-quickshop-wrapper .owl-nav > div.owl-next:hover,
.ftc-quickshop-wrapper .owl-nav > div.owl-prev:hover,
.shortcode-icon .vc_icon_element.vc_icon_element-outer .vc_icon_element-inner.vc_icon_element-color-orange .vc_icon_element-icon,
.comment-reply-link .icon,
body table.compare-list tr.remove td > a .remove:hover:before,
a:hover,
a:focus,
.vc_toggle_title h4:hover,
.vc_toggle_title h4:before,
.blogs article h3.title_sub a:hover,.footer-top .icon li a i:hover,
body.wpb-js-composer .vc_general.vc_tta-tabs.default .vc_tta-tabs-container .vc_tta-tab.vc_active:before,
#mega_main_menu.primary ul .mega_dropdown > li > .item_link:focus,
.item-image .group-button-product > a span:before,
.item-image .group-button-product > div a span:before,
.woocommerce-account .woocommerce-MyAccount-navigation li.is-active a,.sale_price,
.woocommerce-page .products.list .product h3.product-name a:hover,
.post_list_widget .post-title h4:hover,
.widget .post_list_widget .post-meta .entry-date i.fa, .widget .post_list_widget .post-meta .comment i.fa,.vcard.author a,.entry-info .caftc-link .cat-links a,.header-v2 .ftc_lang:hover,.tag-links a,.woocommerce-info::before,article a.button-readmore,.contact_info_map .info_contact .info_column ul:before,
.ftc-blogs-widget-wrapper .post-meta .author:hover,
.footer-mobile i,
.footer-mobile .mobile-wishlist a.tini-wishlist:hover,
p.woocommerce-mini-cart__buttons.buttons > a.button.wc-forward:hover,
.off-can-vas-inner span.woocommerce-Price-amount.amount,
#dokan-seller-listing-wrap ul.dokan-seller-wrap li .store-content .store-info .store-data h2 a:hover,
.header-ftc a.tini-wishlist:hover i,
.header-ftc a.tini-wishlist:hover span,
.ftc-enable-ajax-search .meta .price,
.woocommerce .product .images .ftc-product-video-button span:hover,
.contact_info_map .info_contact .info_column.email ul li:hover,
.widget_categories ul li:hover,
.widget_categories ul li:hover a,
.widget.widget-product-categories ul.product-categories li a:hover,
p.note,
.text-deal p,
.countdown-product .ftc-countdown .counter-wrapper > div .number-wrapper .number,
.countdown-product .owl-nav > div.owl-prev:hover:before,
.countdown-product .owl-nav > div.owl-next:hover:before,
.blog-home.home5 .owl-nav > div.owl-prev:hover:before,
.blog-home.home5 .owl-nav > div.owl-next:hover:before,
.slider-home5 .wpb_wrapper .tp-leftarrow.tparrows:hover:before,
.slider-home5 .wpb_wrapper .tp-rightarrow.tparrows:hover:before,
.testimonial-home5 .ftc-testimonial-wrapper .testimonial-content .name a:hover,
footer .widget .logo-footer ul>li a:hover,
.site-footer .strong-info,
.info_footer p a:hover,
.testimonial-home5 .owl-nav > div.owl-next:hover:before,
.testimonial-home5 .owl-nav > div.owl-prev:hover:before,
.ftc-sb-brandslider .owl-nav > div.owl-prev:hover:before,
.ftc-sb-brandslider .owl-nav > div.owl-next:hover:before,
.text_description p.note,
.testimonial-home5.home6 .ftc-testimonial-wrapper .active.center .testimonial-content .name a,
.ftc-feature_1 .ftc-feature-wrp > a:hover i,
.product-special.home7 .owl-nav > div:hover:before,
.img-home8 .text-home4 a.ftc-button:hover,
.img-home8 .text-home5 a.ftc-button:hover,
.choose-us p.pick-us,
.choose-us ul li:before,
.text-deal-product p,
.product-special .woocommerce .product .conditions-box span.onsale:before,
.deal-product-1 .woocommerce .product .conditions-box span.onsale:before,
.deal-product-1 .owl-nav > div:hover,
.product-special.home6 .owl-nav > div:hover:before,
.countdown-product .woocommerce div.product h3 a:hover,
.hot-new .woocommerce ul.product_list_widget li a:hover,
.ftc-blogs-widget-wrapper .post-meta .author:hover a
{
color: <?php echo esc_html($ftc_primary_color) ?>;
}
.footer-mobile .mobile-wishlist a.tini-wishlist i,
.woocommerce a.remove:hover,
body table.compare-list tr.remove td > a .remove:hover:before{
    color: <?php echo esc_html($ftc_primary_color) ?> !important;
}
.dropdown-container .cart-list-footer > a.button.checkout:hover,
.woocommerce .widget_price_filter .price_slider_amount .button:hover,
.woocommerce-page .widget_price_filter .price_slider_amount .button:hover,
body input.wpcf7-submit:hover,
.woocommerce .products.list .product .item-description .meta_info a:not(.quickview):hover,
.woocommerce .products.list .product .item-description .quickview :hover,
.counter-wrapper > div,
.tp-bullets .tp-bullet:after,
.woocommerce .product .conditions-box .onsale,
.woocommerce #respond input#submit:hover, 
.woocommerce a.button:hover,
.woocommerce button.button:hover, 
.woocommerce input.button:hover,
.woocommerce .products .product .item-image .button-in:hover a:hover,
.vc_color-orange.vc_message_box-solid,
.woocommerce nav.woocommerce-pagination ul li span.current,
.woocommerce-page nav.woocommerce-pagination ul li span.current,
.woocommerce nav.woocommerce-pagination ul li a.next:hover,
.woocommerce-page nav.woocommerce-pagination ul li a.next:hover,
.woocommerce nav.woocommerce-pagination ul li a.prev:hover,
.woocommerce-page nav.woocommerce-pagination ul li a.prev:hover,
.woocommerce nav.woocommerce-pagination ul li a:hover,
.woocommerce-page nav.woocommerce-pagination ul li a:hover,
.woocommerce .form-row input.button:hover,
.load-more-wrapper .button:hover,
body .vc_general.vc_tta-tabs.vc_tta-tabs-position-left .vc_tta-tab:hover,
body .vc_general.vc_tta-tabs.vc_tta-tabs-position-left .vc_tta-tab.vc_active,
.woocommerce div.product form.cart .button:hover,
.woocommerce div.product div.summary p.cart a:hover,
div.product .summary .yith-wcwl-add-to-wishlist a:hover,
.woocommerce #content div.product .summary .compare:hover,
div.product .social-sharing li a:hover,
.woocommerce div.product .woocommerce-tabs ul.tabs li.active,
.tagcloud a:hover,
.woocommerce .wc-proceed-to-checkout a.button.alt:hover,
.woocommerce .wc-proceed-to-checkout a.button:hover,
.woocommerce-cart table.cart input.button:hover,
.owl-dots > .owl-dot span:hover,
.owl-dots > .owl-dot.active span,
footer .style-3 .feedburner-subscription .button.button-secondary.transparent,
.woocommerce .widget_price_filter .ui-slider .ui-slider-range,
body .vc_tta.vc_tta-accordion .vc_tta-panel.vc_active .vc_tta-panel-title > a,
body .vc_tta.vc_tta-accordion .vc_tta-panel .vc_tta-panel-title > a:hover,
body div.pp_details a.pp_close:hover:before,
.vc_toggle_title h4:after,
body.error404 .page-header a,
body .button.button-secondary,
.pp_woocommerce div.product form.cart .button,
.shortcode-icon .vc_icon_element.vc_icon_element-outer .vc_icon_element-inner.vc_icon_element-background-color-orange.vc_icon_element-background,
.style1 .ftc-countdown .counter-wrapper > div,
.style2 .ftc-countdown .counter-wrapper > div,
.style3 .ftc-countdown .counter-wrapper > div,
#cboxClose:hover,
body > h1,
table.compare-list .add-to-cart td a:hover,
.vc_progress_bar.wpb_content_element > .vc_general.vc_single_bar > .vc_bar,
div.product.vertical-thumbnail .details-img .owl-controls div.owl-prev:hover,
div.product.vertical-thumbnail .details-img .owl-controls div.owl-next:hover,
ul > .page-numbers.current,
ul > .page-numbers:hover,
#mega_main_menu.primary > .menu_holder > .menu_inner > ul > li.current-menu-item > .item_link,
#mega_main_menu > .menu_holder > .menu_inner > ul > li:hover,
.footer-top .vc_row-fluid,.time,.blog  article .post-img .entry-date,
.single-post article .post-img .entry-date,.blogs article .post-info .entry-date,
.header-v2 .number_cart,.item-image .group-button-product > div a i:hover,
.item-image .group-button-product > a span, .item-image .group-button-product > div a span,
#mega_main_menu.primary > .menu_holder > .menu_inner > ul > li.current-menu-ancestor,
.woocommerce div.product div.summary p.cart a, .woocommerce div.product form.cart .button,
.single.single-product.woocommerce div.product .quickview .fa:hover,
.details_thumbnails .owl-nav .owl-prev:hover, .details_thumbnails .owl-nav .owl-next:hover,
.blogs article .post-info .entry-date,.related-posts .entry-date,
div.product .summary form .button-in.wishlist a i:hover,
div.product .summary .group_button_out_of_stock .button-in.wishlist a i:hover,
.item-image .group-button-product > a:hover,
.pp_woocommerce div.product .summary .compare:hover,
.woocommerce div.product .summary .compare:hover,
.woocommerce-page div.product .summary .compare:hover,
.woocommerce #content div.product .summary .compare:hover,
.woocommerce-page #content div.product .summary .compare:hover,
div.product .summary .yith-wcwl-add-to-wishlist a:hover,
.woocommerce .products.list .product .item-description a.add-to-cart,
.woocommerce .products.list .product .item-description .meta_info a:not(.quickview):hover, 
.woocommerce .products.list .product .item-description .quickview i:hover,
div.product .summary form .yith-wcwl-add-to-wishlist a i:hover,div.product .summary .group_button_out_of_stock .yith-wcwl-add-to-wishlist a i:hover,
body > h1:first-child,table.compare-list .add-to-cart td.odd a.add-to-cart:hover,.vc_toggle_title h4:before,.vc_toggle_active .vc_toggle_title h4:before,.text_service a,.tag article .post-img .entry-date,
.author article .post-img .entry-date,.page-numbers.current,.pp_inline div.product.product-type-external p.cart,.single.single-product.woocommerce div.product .summary .quickview:hover .fa,table.compare-list .add-to-cart td a:hover,.widget table#wp-calendar tbody tr td a,
.woocommerce .products.list .product .item-description .add-to-cart a,
.form-row.place-order button.button.alt:hover,
.woocommerce .wishlist_table td.product-add-to-cart a:hover,
.cookies-buttons a.btn.btn-size-small.btn-color-primary.cookies-accept-btn,
p.woocommerce-mini-cart__buttons.buttons > a.button.checkout.wc-forward:hover,
.cookies-buttons a.cookies-more-btn,
input[type="submit"].dokan-btn-theme, a.dokan-btn-theme, .dokan-btn-theme,
a.dokan-btn-theme:active, .dokan-btn-theme:active,
input[type="submit"].dokan-btn-theme,
.dokan-btn-theme,
#to-top a:hover,
.woocommerce td.actions .coupon button.button:hover,
.woocommerce td.actions button.button:disabled:hover,
.dokan-single-store .dokan-store-tabs ul li a:hover,
.mfp-close-btn-in .mfp-close:hover,
.single-portfolio .related .owl-nav > div:hover,
.ftc-portfolio-wrapper .portfolio-inner .item .thumbnail .figcaption .zoom-img:hover,
.ftc-portfolio-wrapper .item .figcaption ul:hover:before,
.ftc-portfolio-wrapper .filter-bar  li.current,
.ftc-portfolio-wrapper .filter-bar li:hover,
.text_description .ftc-button-wrapper a.ftc-button-1,
.brand-description .ftc-button:hover,
.countdown-product .wpb_column.vc_column_container.vc_col-sm-2,
.testimonial-home5 .ftc-testimonial-wrapper .active .testimonial-content .content:before,
.ftc-button-wrapper a.ftc-button-1:hover,
.header-layout7 .navigation-primary,
.header-layout7 #mega_main_menu.direction-horizontal > .menu_holder.sticky_container > .mmm_fullwidth_container,
.collection-home3 .ftc-button-wrapper a.ftc-button-1,
.text_description .ftc-button,
.wpcf7 .wpcf7-form input[type="submit"]:hover,
.woocommerce div.address a.button:hover,
.woocommerce-page div.address a.button:hover,
.comments-area .comment-respond .form-submit input[type="submit"]:hover
{
    background-color: <?php echo esc_html($ftc_primary_color) ?>;
}
.btn-danger,
.deal-product-1 .item-image .group-button-product > div a:hover,
.product-special .item-image .group-button-product > div a:hover,
.btn-danger:hover{
    background-color: <?php echo esc_html($ftc_primary_color) ?> !important;
}
.dropdown-container .cart-list-footer > a.button.view-cart:hover,
.dropdown-container .cart-list-footer > a.button.checkout:hover,
.woocommerce .widget_price_filter .price_slider_amount .button:hover,
.woocommerce-page .widget_price_filter .price_slider_amount .button:hover,
body input.wpcf7-submit:hover,
.counter-wrapper > div,
.woocommerce .products .product:hover,
.woocommerce-page .products .product:hover,
#right-sidebar .product_list_widget:hover li,
.woocommerce .product .item-description .meta_info a:hover,
.woocommerce-page .product .item-description .meta_info a:hover,
.ftc-wg-meta.item-description .meta_info a:hover,
.ftc-wg-meta.item-description .meta_info .button-in.wishlist a:hover,
.woocommerce .products .product:hover,
.woocommerce-page .products .product:hover,
.ftc-products-category-tabs-block ul.tabs li:hover,
.ftc-products-category-tabs-block ul.tabs li.current,
body .vc_tta.vc_tta-accordion .vc_tta-panel.vc_active .vc_tta-panel-title > a,
body .vc_tta.vc_tta-accordion .vc_tta-panel .vc_tta-panel-title > a:hover,
body div.pp_details a.pp_close:hover:before,
.wpcf7 p input:focus,
.wpcf7 p textarea:focus,
.woocommerce form .form-row .input-text:focus,
body .button.button-secondary,
.ftc-quickshop-wrapper .owl-nav > div.owl-next:hover,
.ftc-quickshop-wrapper .owl-nav > div.owl-prev:hover,
#cboxClose:hover,.ftc-shop-cart .dropdown-container,
.single.single-product .tabs.wc-tabs li.active,.single.single-product.woocommerce div.product .quickview .fa:hover,
.details_thumbnails .owl-nav .owl-prev:hover, .details_thumbnails .owl-nav .owl-next:hover,
.woocommerce-account .woocommerce-MyAccount-navigation li.is-active,.woocommerce .product .item-image:hover,
p.woocommerce-mini-cart__buttons.buttons > a.button.wc-forward:hover,
p.woocommerce-mini-cart__buttons.buttons > a.button.checkout.wc-forward:hover,
input[type="submit"].dokan-btn-theme, a.dokan-btn-theme, .dokan-btn-theme,
a.dokan-btn-theme:hover, .dokan-btn-theme:hover,
a.dokan-btn-theme:active, .dokan-btn-theme:active,
.single-portfolio .related .owl-nav > div:hover,
.ftc-portfolio-wrapper .portfolio-inner .item .thumbnail .figcaption .zoom-img:hover,
.ftc-portfolio-wrapper .item .figcaption ul:hover:before,
.deal-product-1 .item-image .group-button-product > div a:hover,
.product-special .item-image .group-button-product > div a:hover,
.subscribe_comingsoon .feedburner-subscription input[type="text"]:focus,body .subscribe_comingsoon .subscribe-email .button.button-secondary:hover
{
    border-color: <?php echo esc_html($ftc_primary_color) ?>;
}

.btn-danger,
#mega_main_menu.primary li.default_dropdown > .mega_dropdown > .menu-item > .item_link:hover:before,
.btn-danger:hover
{
    border-color: <?php echo esc_html($ftc_primary_color) ?> !important;
}
.ftc_language ul ul,
.header-currency ul,
.ftc-account .dropdown-container,
.ftc-shop-cart .dropdown-container,
#mega_main_menu.primary > .menu_holder > .menu_inner > ul > li.current_page_item,
#mega_main_menu > .menu_holder > .menu_inner > ul > li:hover,
#mega_main_menu.primary > .menu_holder > .menu_inner > ul > li.current-menu-ancestor > .item_link,
#mega_main_menu > .menu_holder > .menu_inner > ul > li.current_page_item > a:first-child:after,
#mega_main_menu > .menu_holder > .menu_inner > ul > li > a:first-child:hover:before,
#mega_main_menu.primary > .menu_holder > .menu_inner > ul > li.current-menu-ancestor > .item_link:before,
#mega_main_menu.primary > .menu_holder > .menu_inner > ul > li.current_page_item > .item_link:before,
#mega_main_menu.primary > .menu_holder > .menu_inner > ul > li > .mega_dropdown,
.woocommerce .product .conditions-box .onsale:before,
.woocommerce .product .conditions-box .featured:before,
span.page-load-status p.infinite-scroll-request:after,
.header-layout5 #dropdown-list,
.header-layout6 #dropdown-list,
.woocommerce .product .conditions-box .out-of-stock:before,.woocommerce-info
{
    border-top-color: <?php echo esc_html($ftc_primary_color) ?>;
}
.woocommerce .products.list .product:hover .item-description:after,
.woocommerce-page .products.list .product:hover .item-description:after
{
    border-left-color: <?php echo esc_html($ftc_primary_color) ?>;
}
footer#colophon .ftc-footer .widget-title:before,
.woocommerce div.product .woocommerce-tabs ul.tabs,
#customer_login h2 span:before,
#dokan-seller-listing-wrap ul.dokan-seller-wrap li .store-content .store-info .store-data h2 a:hover,
.cart_totals  h2 span:before,article a.button-readmore:hover
{
    border-bottom-color: <?php echo esc_html($ftc_primary_color) ?>;
}

/* ========== Secondary color ========== */
body,
.ftc-shoppping-cart a.ftc_cart:hover,
#mega_main_menu.primary ul li .mega_dropdown > li.sub-style > .item_link .link_text,
.woocommerce a.remove,
body.wpb-js-composer .vc_general.vc_tta-tabs.vc_tta-tabs-position-left .vc_tta-tab,
.woocommerce .products .star-rating.no-rating,
.woocommerce-page .products .star-rating.no-rating,
.star-rating.no-rating:before,
.pp_woocommerce .star-rating.no-rating:before,
.woocommerce .star-rating.no-rating:before,
.woocommerce-page .star-rating.no-rating:before,
.woocommerce .product .item-image .group-button-product > a,
.vc_progress_bar .vc_single_bar .vc_label,
.vc_btn3.vc_btn3-size-sm.vc_btn3-style-outline,
.vc_btn3.vc_btn3-size-sm.vc_btn3-style-outline-custom,
.vc_btn3.vc_btn3-size-md.vc_btn3-style-outline,
.vc_btn3.vc_btn3-size-md.vc_btn3-style-outline-custom,
.vc_btn3.vc_btn3-size-lg.vc_btn3-style-outline,
.vc_btn3.vc_btn3-size-lg.vc_btn3-style-outline-custom,
.style1 .ftc-countdown .counter-wrapper > div .ref-wrapper,
.style2 .ftc-countdown .counter-wrapper > div .ref-wrapper,
.style3 .ftc-countdown .counter-wrapper > div .ref-wrapper,
.style4 .ftc-countdown .counter-wrapper > div .number-wrapper .number,
.style4 .ftc-countdown .counter-wrapper > div .ref-wrapper,
body table.compare-list tr.remove td > a .remove:before,
.woocommerce-page .products.list .product h3.product-name a
{
    color: <?php echo esc_html($ftc_secondary_color) ?>;
}
.dropdown-container .cart-list-footer > a.button.checkout,
.pp_woocommerce div.product form.cart .button:hover,
.info-company li i,
body .button.button-secondary:hover,
div.pp_default .pp_close, body div.pp_woocommerce.pp_pic_holder .pp_close,
body div.ftc-product-video.pp_pic_holder .pp_close,
body .ftc-lightbox.pp_pic_holder a.pp_close,
#cboxClose
{
    background-color: <?php echo esc_html($ftc_secondary_color) ?>;
}
.dropdown-container .cart-list-footer > a.button.checkout,
.pp_woocommerce div.product form.cart .button:hover,
body .button.button-secondary:hover,
#cboxClose
{
    border-color: <?php echo esc_html($ftc_secondary_color) ?>;
}

/* ========== Body Background color ========== */
body
{
    background-color: <?php echo esc_html($ftc_body_background_color) ?>;
}
/* Custom CSS */
<?php 
if( !empty($ftc_custom_css_code) ){
  echo html_entity_decode( trim( $ftc_custom_css_code ) );
}
?>
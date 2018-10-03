<?php 
	global $post;
	$per_page = 4;
	$cat_list = get_the_category($post->ID);
	$cat_ids = array();
	foreach( $cat_list as $cat ){
		$cat_ids[] = $cat->term_id;
	}
	$cat_ids = implode(',', $cat_ids);
	
	if( strlen($cat_ids) > 0 ){
		$arg = array(
			'post_type' => $post->post_type
			,'cat' => $cat_ids
			,'post__not_in' => array($post->ID)
			,'posts_per_page' => $per_page
		);
	}
	else{
		$arg = array(
			'post_type' => $post->post_type
			,'post__not_in' => array($post->ID)
			,'posts_per_page' => $per_page
		);
	}
	
	$related_posts = new WP_Query($arg);
	
if( $related_posts->have_posts() ){
	$is_slider = true;
	if( isset($related_posts->post_count) && $related_posts->post_count <= 1 ){
		$is_slider = false;
	}
	
	$rand_id = 'related-portfolios-'.rand(0, 100000);
?>
	<div class="related-posts related related-portfolio <?php echo ($is_slider)?'loading':'' ?>" id="<?php echo esc_attr($rand_id); ?>">
		<header class="theme-title">
			<div class="sub-title">
				<span class="left">.</span><span class="content-title">Hot trend</span><span class="right">.</span>
			</div>
			<h3 class="heading-title"><?php esc_html_e('Related Portfolio', 'osapa'); ?></h3>
                        <p class="portfolio-title">
                            Donec nec justo eget felis facilisis fermentum. Aliquam porttitor mauris sit amet orci. Aenean dignissim pellentesque felis.
                        </p>
		</header>
		<div class="content-wrapper">
			<div class="blogs">
				<?php while( $related_posts->have_posts() ): $related_posts->the_post(); ?>
				<article>
					<a class="thumbnail" href="<?php the_permalink() ?>">
						<figure>
							<?php 
						
							if( has_post_thumbnail() ){
								the_post_thumbnail(); 
							}
							
							?>
						</figure>
						<div class="effect-thumbnail"></div>
					</a>
				</article>
				<?php endwhile; ?>
			</div>
		</div>
	</div>
	
	<?php if( $is_slider ): ?>
	<script type="text/javascript">
		jQuery(document).ready(function($){
			"use strict";
			var _this = jQuery('#<?php echo esc_js($rand_id); ?>');
			var slider_data = {
				loop : true
				,nav : true
				,navText : [,]
				,dots : false
				,navSpeed: 1000
				,slideBy: 1
				,rtl: jQuery('body').hasClass('rtl')
				,margin : 0
				,navRewind: false
				,autoplay: false
				,autoplayTimeout: 5000
				,autoplayHoverPause: true
				,autoplaySpeed: false
				,mouseDrag: true
				,touchDrag: true
				,responsiveBaseElement: _this
				,responsiveRefreshRate: 400
				,responsive:{
							0:{
								items : 1
							},
							500:{
								items : 2
							},
							800:{
								items : 3
							},
							1100:{
								items : <?php echo esc_js($per_page) - 1; ?>
							},
							1400:{
								items : <?php echo esc_js($per_page); ?>
							}
						}
				,onInitialized: function(){
					_this.addClass('loaded').removeClass('loading');
				}
			};
			var owl = _this.find('.content-wrapper .blogs').addClass('owl-carousel').owlCarousel(slider_data);
		});
	</script>
	<?php endif; ?>
<?php 
	wp_reset_postdata();
}
?>
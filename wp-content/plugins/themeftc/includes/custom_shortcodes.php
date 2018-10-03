<?php 
/************************************
*** Custom Post Type Shortcodes
*************************************/
/*** Shortcode Feature ***/
function ftc_feature_shortcode( $atts ){
	extract(shortcode_atts(array(
		'style'				=> 'feature-horizontal'
		,'class_icon' 		=> ''
		,'style_icon'		=> 'icon-default'
		,'img_id'			=> ''
		,'img_url'			=> ''
		,'title' 			=> ''
		,'excerpt' 			=> ''
		,'link' 			=> ''		
		,'target' 			=> '_blank'
		,'extra_class'		=> ''
		,'on_header'		=> 0
		), $atts ));
	
	ob_start();
	
	$classes = array();
	$classes[] = 'ftc-feature-wrp';
	$classes[] = $extra_class;
	$classes[] = $style;
	$classes[] = $style_icon;
	if( strlen($img_id) > 0 || strlen($img_url) > 0 ){
		$classes[] = 'has-image';
	}
	if( $link == '' ){
		$classes[] = 'no-link';
	}
	?>
	<div class="<?php echo esc_attr(implode(' ', $classes)) ?>">

		<?php if( (strlen($class_icon) > 0) && ( strlen($img_id) <= 0 ) && (strlen($img_url) <= 0 ) ): ?>
			<a target="<?php echo esc_attr($target); ?>" class="feature-icon" href="<?php echo ($link != '')?esc_url($link):'javascript: void(0)' ?>">
				<i class="fa <?php echo esc_attr($class_icon); ?>"></i>
			</a>
		<?php endif; ?>

		<?php if(( strlen($img_id) > 0 )|| (strlen($img_url) > 0 )) : ?>

			<a target="<?php echo esc_attr($target); ?>" class="feature-thumbnail" href="<?php echo ($link != '')?esc_url($link):'javascript: void(0)' ?>" >
				<?php 
				if( $img_url != '' ){
					?>
					<img title="<?php echo esc_attr($title) ?>" alt="<?php echo esc_attr($title) ?>" src="<?php echo esc_url($img_url) ?>" />
					<?php
				}
				else{
					if( apply_filters('ftc_page_intro_feature_filter', false) ){
						$image_loading = get_template_directory_uri() . '/images/prod_loading.gif';
						$image_src = wp_get_attachment_image_src($img_id, 'full');
						if( is_array($image_src) ){
							?>
							<img src="<?php echo esc_url($image_loading) ?>" data-src="<?php echo esc_url($image_src[0]) ?>" alt="<?php echo esc_attr($title) ?>" width="<?php echo esc_attr($image_src[1]) ?>" height="<?php echo esc_attr($image_src[2]) ?>" class="img lazy-loading" />
							<?php
						}
					}
					else{
						echo wp_get_attachment_image($img_id, 'full', 0, array('class'=>'img'));
					}
				}
				?> 
				<span class="overlay"></span>
			</a>

		<?php endif; ?>

		<?php if( !$on_header ): ?>
			<div class="feature-header">
			<?php else: ?>
				<div class="feature-header">
				<?php endif; ?>

				<?php if( strlen($title) > 0 ): ?>
					<h3 class="feature-title title_sub entry-title">
						<a target="<?php echo esc_attr($target); ?>" href="<?php echo ($link != '')?esc_url($link):'javascript: void(0)' ?>"><?php echo esc_html($title); ?></a>
					</h3>
				<?php endif; ?>

				<?php if( strlen($excerpt) > 0 ): ?>
					<p class="feature-excerpt">
						<?php echo esc_html($excerpt); ?>
					</p>
				<?php endif; ?>

				<?php if( !$on_header ): ?>
				</div>
			<?php else: ?>
			</div>
		<?php endif; ?>
	</div>
	<?php
	
	return ob_get_clean();
}
add_shortcode('ftc_feature', 'ftc_feature_shortcode');

/*** Shortcode Portfolio ***/
if( !function_exists('ftc_portfolio_shortcode') ){
	function ftc_portfolio_shortcode( $atts ){
		extract(shortcode_atts(array(
			'columns'			=> 2
			,'per_page'			=> 8
			,'categories'		=> ''
			,'orderby'			=> 'none'
			,'order'			=> 'DESC'
			,'show_filter_bar'	=> 1
			,'show_title'		=> 1
			,'show_date'		=> 1
			,'show_load_more'	=> 1
			,'load_more_text'	=> 'Show more'

		), $atts ));
		
		$args = array(
			'post_type'				=> 'ftc_portfolio'
			,'posts_per_page'		=> $per_page
			,'post_status'			=> 'publish'
			,'ignore_sticky_posts'	=> 1
			,'orderby'				=> $orderby
			,'order'				=> $order
		);	
		$categories = str_replace(' ', '', $categories);
		if( strlen($categories) > 0 ){
			$ar_categories = explode(',', $categories);
			if( is_array($ar_categories) && count($ar_categories) > 0 ){
				$field_name = is_numeric($ar_categories[0])?'term_id':'slug';
				$args['tax_query']	= array(
					array(
						'taxonomy'	=> 'ftc_portfolio_cat'
						,'field'	=> $field_name
						,'terms'	=> $ar_categories
					)
				);
			}
		}
		ob_start();
		global $post, $wp_query, $ftc_portfolios;
		$posts = new WP_Query( $args );
		if( $posts->have_posts() ){
			$atts = compact('columns', 'per_page', 'categories', 'orderby', 'order', 'show_filter_bar', 'show_title', 'show_date');
			?>
			<div class="ftc-portfolio-wrapper columns-<?php echo $columns; ?>" data-atts="<?php echo htmlentities(json_encode($atts)); ?>">
				<?php
				/* Get filter bar */
				if( $show_filter_bar ){
					$terms = array();
					foreach( $posts->posts as $p ){
						$post_terms = wp_get_post_terms($p->ID, 'ftc_portfolio_cat');
						if( is_array($post_terms) ){
							foreach( $post_terms as $term ){
								$terms[$term->slug] = $term->name;
							}
						}
					}

					if( !empty($terms) ){
						?>
						<ul class="filter-bar">
							<li data-filter="*" class="current"><?php esc_html_e('All', 'themesky'); ?></li>
							<?php
							foreach( $terms as $slug => $name ){
								?>
								<li data-filter="<?php echo '.'.$slug; ?>"><?php echo esc_attr($name) ?></li>
								<?php
							}
							?>
						</ul>
						<?php
					}
				}
				?>

				<div class="portfolio-inner">
					<?php
					ftc_get_portfolio_items_content_shortcode($atts, $posts);
				?>
				</div>
				<?php if( $show_load_more ){ ?>
				<div class="load-more-wrapper">
					<a href="#" class="load-more button" data-paged="2"><?php echo esc_html($load_more_text) ?></a>
				</div>
				<?php } ?>
			</div>

			<?php
		}

		wp_reset_postdata();
		return ob_get_clean();
	}
}
add_shortcode('ftc_portfolio', 'ftc_portfolio_shortcode');

add_action('wp_ajax_ftc_portfolio_load_items', 'ftc_get_portfolio_items_content_shortcode');
add_action('wp_ajax_nopriv_ftc_portfolio_load_items', 'ftc_get_portfolio_items_content_shortcode');
if( !function_exists('ftc_get_portfolio_items_content_shortcode') ){
	function ftc_get_portfolio_items_content_shortcode($atts, $posts = null){
		
		global $post;
		
		if( defined( 'DOING_AJAX' ) && DOING_AJAX ){
			if( !isset($_POST['atts']) ){
				die('0');
			}
			$atts = $_POST['atts'];
			$paged = isset($_POST['paged'])?absint($_POST['paged']):1;
			
			extract($atts);
			
			$args = array(
				'post_type'				=> 'ftc_portfolio'
				,'posts_per_page'		=> $per_page
				,'post_status'			=> 'publish'
				,'ignore_sticky_posts'	=> 1
				,'paged' 				=> $paged
				,'orderby'				=> $orderby
				,'order'				=> $order
			);	
			$categories = str_replace(' ', '', $categories);
			if( strlen($categories) > 0 ){
				$categories = explode(',', $categories);
				if( is_array($categories) ){
					$field_name = is_numeric($categories[0])?'term_id':'slug';
					$args['tax_query']	= array(
								array(
									'taxonomy'	=> 'ftc_portfolio_cat'
									,'field'	=> $field_name
									,'terms'	=> $categories
								)
							);
				}
			}
			$posts = new WP_Query( $args );
			ob_start();
		}
		
		extract($atts);
		
		if( $posts->have_posts() ):
			while( $posts->have_posts() ): $posts->the_post();
				$classes = '';
				$post_terms = wp_get_post_terms($post->ID, 'ftc_portfolio_cat');
				if( is_array($post_terms) ){
					foreach( $post_terms as $term ){
						$classes .= $term->slug . ' ';
					}
				}
					$link = get_permalink();
				
				?>
				<div class="item <?php echo esc_attr($classes) ?>">
					<div class="thumbnail">
                                                <figure>
							<?php 
							if( has_post_thumbnail() ){
								the_post_thumbnail('');
							}
							?>							
							<div class="figcaption">
                                                            <div class="text1">
                                                                <div class="text11">
                                                                    <?php if( $show_title ){ ?>
                                                                    <h3>
                                                                            <a href="<?php echo esc_url($link); ?>">
                                                                                    <?php echo get_the_title(); ?>
                                                                            </a>
                                                                    </h3>
                                                                    <?php } ?>
                                                                    <?php if( $show_date ){ ?>
                                                                            <span class="date-time">
                                                                                    <?php echo get_the_time(get_option('date_format')); ?>
                                                                            </span>
                                                                    <?php } ?>

                                                                        <?php
                                                                    $categories_list = get_the_term_list($post->ID, 'ftc_portfolio_cat', '', ', ', '');
                                                                    if ( $categories_list ):
                                                                            ?>
                                                                            <div class="portfolio-info">
                                                                                    <span class="cat-links"><?php echo  $categories_list; ?></span>
                                                                            </div>
                                                                    <?php endif; ?> 
                                                                </div>
                                                                <div class="text12">
                                                                        <a href="<?php echo esc_url(wp_get_attachment_url( get_post_thumbnail_id($post->ID))); ?>" rel="prettyPhoto" class="zoom-img"></a>
                                                                        <ul class="ftc-social-sharing">
                                                                        <li class="twitter">
                                                                                <a href="https://twitter.com/share?url=<?php echo esc_url(get_permalink()); ?>" target="_blank"><i class="fa fa-twitter"></i> Tweet</a>
                                                                        </li>

                                                                        <li class="facebook">
                                                                                <a href="https://www.facebook.com/sharer.php?u=<?php echo esc_url(get_permalink()); ?>" target="_blank"><i class="fa fa-facebook"></i> Share</a>
                                                                        </li>

                                                                        <li class="google-plus">
                                                                                <a href="https://plus.google.com/share?url=<?php echo esc_url(get_permalink()); ?>" target="_blank"><i class="fa fa-google-plus"></i> Google+</a>
                                                                        </li>

                                                                        <li class="pinterest">
                                                                                <?php $image_link = wp_get_attachment_url(get_post_thumbnail_id()); ?>
                                                                                <a href="https://pinterest.com/pin/create/button/?url=<?php echo esc_url(get_permalink()); ?>&amp;media=<?php echo esc_url($image_link); ?>" target="_blank"><i class="fa fa-pinterest"></i> Pinterest</a>
                                                                        </li>

                                                                         </ul>
                                                                </div>
                                                               
                                                            </div>
							

                                                            <div class="icon-group">
                                                                <a href="<?php echo esc_url($link); ?>" class="link"></a>
                                                                
                                                            </div>
                                                            
							</div>		
						</figure>
                                            
						
						
						
					</div>
				</div>
			<?php
			endwhile;
		endif;
		
		wp_reset_postdata();
		
		if( defined( 'DOING_AJAX' ) && DOING_AJAX ){
			die(ob_get_clean());
		}
		
	}
}

/*** Shortcode Testimonial ***/
function ftc_testimonial_shortcode($atts){
	extract(shortcode_atts(array(
		'categories'			=> ''
		,'per_page'				=> 4
		,'show_avatar'			=> 1
		,'text_color_style'		=> 'text-default'
		,'ids'					=> ''
		,'excerpt_words'		=> 50
		,'is_slider'			=> 1
		,'show_nav'				=> 1
		,'show_dots'			=> 1
		,'auto_play'			=> 1
		), $atts ));
	
	if( !is_numeric($excerpt_words) ){
		$excerpt_words = 50;
	}
	
	if( $show_dots ){
		$show_nav = 0;
	}
	
	ob_start();
	
	global $post, $ftc_testimonials;
	
	$args = array(
		'post_type'				=> 'ftc_testimonial'
		,'post_status'			=> 'publish'
		,'ignore_sticky_posts'	=> true
		,'posts_per_page' 		=> $per_page
		,'orderby' 				=> 'date'
		,'order' 				=> 'desc'
		,'columns' 				=> 3
		);

	$categories = str_replace(' ', '', $categories);
	if( strlen($categories) > 0 ){
		$categories = explode(',', $categories);
	}
	
	if( is_array($categories) && count($categories) > 0 ){
		$field_name = is_numeric($categories[0])?'term_id':'slug';
		$args['tax_query'] = array(
			array(
				'taxonomy' => 'ftc_testimonial_cat',
				'terms' => $categories,
				'field' => $field_name,
				'include_children' => false
				)
			);
	}
	
	if( strlen(trim($ids)) > 0 ){
		$ids = array_map('trim', explode(',', $ids));
		if( is_array($ids) && count($ids) > 0 ){
			$args['post__in'] = $ids;
		}
	}
	
	$testimonials = new WP_Query($args);
	if( $testimonials->have_posts() ){
		if( isset($testimonials->post_count) && $testimonials->post_count <= 1 ){
			$is_slider = false;
		}
		?>
		<div class="ftc-testimonial-wrapper owl-carousel <?php echo esc_attr($text_color_style) ?> <?php echo ($show_nav || $show_dots)?'show-navi':''; ?> <?php echo ($is_slider)?'ftc-slider loading':''; ?>" 
			data-nav="<?php echo esc_attr($show_nav) ?>" data-dots="<?php echo esc_attr($show_dots) ?>" data-autoplay="<?php echo esc_attr($auto_play) ?>">
			<?php
			while( $testimonials->have_posts() ){
				$testimonials->the_post();
				if( function_exists('ftc_the_excerpt_max_words') ){
					$content = ftc_the_excerpt_max_words($excerpt_words, $post, true, '', false);
				}
				else{
					$content = substr(wp_strip_all_tags($post->post_content), 0, 300);
				}
				$byline = get_post_meta($post->ID, 'ftc_byline', true);
				$url = get_post_meta($post->ID, 'ftc_url', true);
				if( $url == '' ){
					$url = '#';
				}
				$rating = get_post_meta($post->ID, 'ftc_rating', true);
				$rating_percent = '0';
				if( $rating != '-1' && $rating != '' ){
					$rating_percent = $rating * 100 / 5;
				}

				$gravatar_email = get_post_meta($post->ID, 'ftc_gravatar_email', true);
				$has_image = false;
				if( has_post_thumbnail() || ($gravatar_email != '' && is_email($gravatar_email)) ){
					$has_image = true;
				}
				?>
				<div class="item <?php echo (($has_image) && ($show_avatar))?'has-image':'no-image'; ?>">
					<div class="testimonial-content">
						<div class="content">
							<?php echo esc_html($content); ?>
						</div>

						<?php if( ($has_image) && ($show_avatar) ): ?>
							<div class="image">
								<?php echo $ftc_testimonials->get_image($post->ID); ?>
							</div>
						<?php endif; ?>

						<h4 class="name">
							<a href="<?php echo esc_url($url); ?>" target="_blank">
								<?php echo get_the_title($post->ID); ?>
							</a>
						</h4>

						<?php if( $byline ): ?>
							<div class="byline">
								<?php echo esc_html($byline); ?>
							</div>
						<?php endif; ?>

						<?php if( $rating != '-1' && $rating != '' ): ?>
							<div class="rating" title="<?php printf(esc_html__('Rated %s out of 5', 'themeftc'), $rating); ?>">
								<span style="width: <?php echo $rating_percent.'%'; ?>"><?php printf(esc_html__('Rated %s out of 5', 'themeftc'), $rating); ?></span>
							</div>
						<?php endif; ?>

					</div>
				</div>
				<?php
			}
			?>
		</div>
		<?php
	}
	
	wp_reset_postdata();
	return ob_get_clean();
}
add_shortcode('ftc_testimonial', 'ftc_testimonial_shortcode');

/*** Shortcode Banner ***/
function ftc_banner_shortcode( $atts, $content = '' ){
	extract(shortcode_atts(array(
		'bg_id'					=> ''
		,'bg_url'				=> ''
		,'bg_color'				=> '#ffffff'
		,'position_content'		=> 'left-top'
		,'opacity_bg_device'	=> 0
		,'link' 				=> ''
		,'style_smooth'			=> 'ftc-background-scale'
		,'responsive_size'		=> 1
		,'link_title' 			=> ''						
		,'target' 				=> '_blank'
		,'extra_class'			=> ''
		), $atts ));

	static $ftc_banner_counter = 1;
	$unique_class = 'ftc-banner-'.$ftc_banner_counter;
	$selector = '.' . $unique_class;
	$ftc_banner_counter++;
	
	
	ob_start();
	
	?>
	<div class="ftc-banner <?php echo esc_attr($unique_class) ?> <?php echo esc_attr($style_smooth) ?> <?php echo ($opacity_bg_device)?'opacity-bg-device':'' ?> <?php echo ($responsive_size)?'responsive-size':'' ?> <?php echo esc_attr($position_content) ?> <?php echo esc_attr($extra_class) ?>">
		
		<?php if( $link != '' ): ?>
			<a title="<?php echo esc_attr($link_title) ?>" target="<?php echo esc_attr($target); ?>" class="banner-link" href="<?php echo esc_url($link) ?>" ></a>
		<?php endif;?>
		
		<div class="ftc-banner-wrapper">
			<span class="ftc-banner-bg">
				<?php 
				if( $bg_url != '' ){
					?>
					<img alt="<?php echo esc_attr($link_title) ?>" title="<?php echo esc_attr($link_title) ?>" class="img" src="<?php echo esc_url($bg_url); ?>">
					<?php
				}
				else{
					echo wp_get_attachment_image($bg_id, 'full', 0, array('class'=>'img'));
				}
				?>
			</span>
			<div class="ftc-banner-content">
				<?php 
				$content = wpautop( preg_replace( '/<\/?p\>/', "\n", $content ) . "\n" );
				echo do_shortcode( shortcode_unautop( $content ) );
				?>
			</div>
		</div>
	</div>
	<?php
	
	return ob_get_clean();
}
add_shortcode('ftc_banner', 'ftc_banner_shortcode');

/*** Shortcode Single Image ***/
if( !function_exists('ftc_single_image_shortcode') ){
	function ftc_single_image_shortcode( $atts ){
		extract(shortcode_atts(array(
			'img_id'			=> ''
			,'img_url'			=> ''
			,'img_size'			=> ''
			,'style_smooth'		=> 'smooth-image'
			,'link' 			=> ''
			,'link_title' 		=> ''						
			,'target' 			=> '_blank'
			), $atts ));

		if( $img_size == '' ){
			$img_size = 'full';
		}
		
		ob_start();
		?>
		
		<?php if( $link != '' ):?>
			<a class="ftc-smooth-image <?php echo esc_attr($style_smooth) ?>" href="<?php echo esc_url($link) ?>"  target="<?php echo esc_attr($target); ?>" title="<?php echo esc_attr($link_title) ?>">
				<div class="ftc-smooth">
					<?php 
					if( $img_url != '' ){
						?>
						<img alt="<?php echo esc_attr($link_title) ?>" title="<?php echo esc_attr($link_title) ?>" class="img" src="<?php echo esc_url($img_url); ?>">
						<?php
					}
					else{
						echo wp_get_attachment_image($img_id, $img_size, 0, array('class'=>'img'));
					}
					?> 
				</div>
			</a>
		<?php else: ?>
			<div class="ftc-smooth-image <?php echo esc_attr($style_smooth) ?>">
				<div class="ftc-smooth" >
					<?php 
					if( $img_url != '' ){
						?>
						<img alt="<?php echo esc_attr($link_title) ?>" title="<?php echo esc_attr($link_title) ?>" class="img" src="<?php echo esc_url($img_url); ?>">
						<?php
					}
					else{
						echo wp_get_attachment_image($img_id, $img_size, 0, array('class'=>'img'));
					}
					?> 
				</div>
			</div>
		<?php endif;?>
		<?php
		
		return ob_get_clean();
	}
}
add_shortcode('ftc_single_image', 'ftc_single_image_shortcode');

/*** Shortcode Instagram ***/
function ftc_instagram_shortcode( $atts ){
	extract(shortcode_atts(array(
		'title'				=> 'Instagram'
		,'username'     => ''
		,'number'         => '9'
		,'column'			=> '3'
		,'size'			=> 'large'
		,'target'			=> '_self'
		,'cache_time'			=> '12'
	), $atts ));

	$instance = array(
		'title'					=> $title
		,'username'			=> $username
		,'number'			=> $number
		,'column'					=> $column
		,'size'				=> $size
		,'target' 		=> $target
		,'cache_time' 		=> $cache_time
	);
	
	ob_start();
	the_widget('Ftc_Instagram_Widget', $instance);
	return ob_get_clean();
}
add_shortcode('ftc_instagram', 'ftc_instagram_shortcode');
/*** Shortcode Brand ***/
if( !function_exists('ftc_brands_slider_shortcode') ){
	function ftc_brands_slider_shortcode( $atts, $content = null ){
		extract(shortcode_atts(array(
			'title'				=> ''
			,'categories' 		=> ''
			,'style_brand'		=> 'style-default'
			,'per_page' 		=> 7
			,'rows' 			=> 1
			,'active_link'		=> 1
			,'show_nav' 		=> 1
			,'auto_play' 		=> 1
			,'margin_image'		=> 32
			), $atts));
		if( !class_exists('Ftc_Brands') )
			return;
		
		$args = array(
			'post_type'				=> 'ftc_brand'
			,'post_status'			=> 'publish'
			,'ignore_sticky_posts'	=> 1
			,'posts_per_page' 		=> $per_page
			,'orderby' 				=> 'date'
			,'order' 				=> 'desc'
			);
		
		$categories = str_replace(' ', '', $categories);
		if( strlen($categories) > 0 ){
			$categories = explode(',', $categories);
		}
		if( is_array($categories) && count($categories) > 0 ){
			$field_name = is_numeric($categories[0])?'term_id':'slug';
			$args['tax_query'] = array(
				array(
					'taxonomy' => 'ftc_brand_cat'
					,'terms' => $categories
					,'field' => $field_name
					,'include_children' => false
					)
				);
		}
		
		$brands = new WP_Query($args);
		
		global $post;
		ob_start();
		if( $brands->have_posts() ):
			$count_posts = $brands->post_count;

		$classes = array();
		$classes[] = $style_brand;
		if( strlen($title) <= 0 ){
			$classes[] = 'no-title';
		}
		else{
			$classes[] = 'has-title';
		}
		if( $count_posts > 1 && $count_posts > $rows ){
			$classes[] = 'loading';
		}
		if( $show_nav ){
			$classes[] = 'show-nav';
		}

		$settings_option = get_option('ftc_brand_setting', array());
		$data_break_point = isset($settings_option['responsive']['break_point'])?$settings_option['responsive']['break_point']:array();
		$data_item = isset($settings_option['responsive']['item'])?$settings_option['responsive']['item']:array();

		$data_attr = array();
		$data_attr[] = 'data-margin="'.$margin_image.'"';
		$data_attr[] = 'data-nav="'.$show_nav.'"';
		$data_attr[] = 'data-auto_play="'.$auto_play.'"';
		$data_attr[] = 'data-break_point="'.htmlentities(json_encode( $data_break_point )).'"';
		$data_attr[] = 'data-item="'.htmlentities(json_encode( $data_item )).'"';
		?>
		<div class="ftc-brand-slider-block ftc-slider ftc-shortcode <?php echo esc_attr( implode(' ', $classes) ); ?>" <?php echo implode(' ', $data_attr); ?>>
			<?php if( strlen($title) > 0 ): ?>
				<header class="header-title">
					<h3 class="title_sub"><span class="bg-heading"><span><?php echo esc_html($title); ?></span></span></h3>
				</header>
			<?php endif; ?>
			<div class="meta-slider">
				<div class="brands owl-carousel">
					<?php 
					$count = 0;
					while( $brands->have_posts() ): $brands->the_post(); 
					if( $rows > 1 && $count % $rows == 0 ){
						echo '<div class="brand-group">';
					}
					?>
					<div class="item">
						<?php if( $active_link ):
						$brand_url = get_post_meta($post->ID, 'ftc_brand_url', true);
						$brand_target = get_post_meta($post->ID, 'ftc_brand_target', true);
						?>
						<a href="<?php echo esc_url($brand_url); ?>" target="<?php echo esc_attr($brand_target); ?>">
						<?php endif; ?>
						<?php 
						if( has_post_thumbnail() ){
							the_post_thumbnail('ftc_brand_thumb');
						}
						?>
						<?php if( $active_link ): ?>
						</a>
					<?php endif; ?>
				</div>
				<?php 
				if( $rows > 1 && ($count % $rows == $rows - 1 || $count == $count_posts - 1) ){
					echo '</div>';
				}
				$count++;
				endwhile; 
				?>
			</div>
		</div>
	</div>
	<?php
	endif;
	wp_reset_postdata();
	return ob_get_clean();
}
}
add_shortcode('ftc_brands_slider', 'ftc_brands_slider_shortcode');

/************************************
*** Element Shortcodes
*************************************/

/*** Shortcode Button ***/
function ftc_button_shortcode($atts, $content=null){
	extract(shortcode_atts(array(	
		'link'					=> '#'
		,'bg_color'				=> '#40bea7'
		,'bg_color_hover'		=> '#3f3f3f'
		,'border_color'			=> '#e8e8e8'
		,'border_color_hover'	=> '#40bea7'
		,'border_width'			=> '0'
		,'text_color'			=> '#ffffff'
		,'text_color_hover'		=> '#ffffff'
		,'font_icon'			=> ''
		,'target'				=> '_self' /* _self, _blank */
		,'size'					=> 'small' /* small, medium, large, x-large */
		,'popup'				=> 0
		,'popup_content'		=> ''
		), $atts));
	static $ftc_button_counter = 1;		
	$style = '';
	$style_hover = '';
	$selector = '.ftc-button-wrapper a.ftc-button-'.$ftc_button_counter;
	
	if( $bg_color ){
		$style .= 'background:'.$bg_color.';';
	}
	if( $border_color ){
		$style .= 'border-color:'.$border_color.';';
	}
	if( $border_width != '' ){
		$style .= 'border-width:'.absint($border_width).'px ;';
	}
	if( $text_color ){
		$style .= 'color:'.$text_color.';';
	}
	$style .= 'border-radius:0;';

	if( $bg_color_hover ){
		$style_hover .= 'background:'.$bg_color_hover.';';
	}
	if( $border_color_hover ){
		$style_hover .= 'border-color:'.$border_color_hover.';';
	}
	if( $text_color_hover ){
		$style_hover .= 'color:'.$text_color_hover.';';
	}
	
	$html = '<div class="ftc-button-wrapper">';
	$html .= '<style type="text/css" scoped>';
	$html .= $selector.'{';
	$html .= $style;
	$html .= '}';
	
	$html .= $selector.':hover{';
	$html .= $style_hover;
	$html .= '}';
	$html .= '</style>';
	
	if( $font_icon ){
		$font_icon = 'fa '.$font_icon;
	}
	
	$is_popup = ($popup && !empty($popup_content))?true:false;
	
	$extra_class = '';
	
	if( $is_popup ){
		$extra_class = ' ftc-button-popup ';
		$link = '#ftc-button-popup-'.$ftc_button_counter;
	}
	else{
		$link = esc_url($link);
	}
	
	$html .= '<a href="'.$link.'" target="'.$target.'" class="ftc-button ftc-button-'.$ftc_button_counter.' '.$size.' '.$font_icon.$extra_class.' ">'.do_shortcode($content).'</a>';
	$html .= '</div>';
	
	if( $is_popup ){
		$html .= '<div id="ftc-button-popup-'.$ftc_button_counter.'" style="display: none">';
		$html .= do_shortcode(stripslashes(urldecode(base64_decode($popup_content))));
		$html .= '</div>';
	}
	
	$ftc_button_counter++;
	return $html;
}
add_shortcode('ftc_button', 'ftc_button_shortcode');

if( !function_exists('ftc_feedburner_subscription_shortcode') ){
	function ftc_feedburner_subscription_shortcode( $atts ){
		extract(shortcode_atts(array(	
			'title'					=> 'Newsletter'
			,'intro_text'			=> ''
			,'button_text'			=> 'Subscribe'
			,'placeholder_text'		=> 'Enter your email address'
			,'feedburner_id'		=> ''
			,'style'				=> 'style-1'
			), $atts));

		if( !class_exists('Ftc_Feedburner_Subscription_Widget') ){
			return;
		}
		
		$instance = compact('title', 'intro_text', 'button_text', 'placeholder_text', 'feedburner_id');
		
		ob_start();
		
		echo '<div class="ftc-feedburner-subscription '.$style.'">';
		
		the_widget('Ftc_Feedburner_Subscription_Widget', $instance);
		
		echo '</div>';
		
		return ob_get_clean();
	}
}
add_shortcode('ftc_feedburner_subscription', 'ftc_feedburner_subscription_shortcode');

/*** Shortcode Dropcap ***/
function ftc_dropcap_shortcode($atts, $content=null){
	extract(shortcode_atts(array(	
		'style'					=> '1'
		), $atts));
	return '<span class="ftc-dropcap'.' style-'.$style.'">' .do_shortcode($content). '</span>';
}
add_shortcode('ftc_dropcap', 'ftc_dropcap_shortcode');

/*** Shortcode Quote ***/
function ftc_quote_shortcode($atts, $content=null){
	return '<span class="ftc-quote">'.do_shortcode($content).'</span>';
}
add_shortcode('ftc_quote', 'ftc_quote_shortcode');

/*** Shortcode Heading ***/
if( !function_exists('ftc_heading_shortcode') ){
	function ftc_heading_shortcode($atts, $content = null){
		extract(shortcode_atts(array(
			'size' 				=> '1'
			,'text' 			=> ''
			,'style' 			=> 'style-1'
			), $atts));
		return '<div class="ftc-heading heading-'.$size.' '.$style.'"><h'.$size.'>'.do_shortcode($text).'</h'.$size.'>'.'</div>';
	}
}
add_shortcode('ftc_heading', 'ftc_heading_shortcode');

/*** Shortcode Blog ***/
if( !function_exists('ftc_blogs_shortcode') ){
	function ftc_blogs_shortcode( $atts, $content = null){
		extract(shortcode_atts(array(
			'title'				=> ''
			,'columns'			=> 1
			,'categories'		=> ''
			,'per_page'			=> 5
			,'orderby'			=> 'none'
			,'order'			=> 'DESC'
			,'style'			=> 1
			,'show_title'		=> 1
			,'show_thumbnail'	=> 1
			,'show_author'		=> 0
			,'show_date'		=> 1
			,'show_comment'		=> 1
			,'show_excerpt'		=> 1
			,'show_readmore'	=> 1
			,'excerpt_words'	=> 20
			,'layout'			=> 'grid'
			,'show_nav'			=> 1
			,'auto_play'		=> 1
			,'margin'			=> 30
			,'show_load_more'	=> 0
			,'load_more_text'	=> 'Show more'
			,'desksmall_items'	=> '1'
                                        ,'tablet_items'     	=> '1'
                                        ,'tabletmini_items'	=> '1'
                                        ,'mobile_items'	=> '1'
                                        ,'mobilesmall_items'	=> '1'
			), $atts));
		
		if( !is_numeric($excerpt_words) ){
			$excerpt_words = 20;
		}
		
		$is_slider = 0;
		$is_masonry = 0;
                $is_grid=0;
		if( $layout == 'slider' ){
			$is_slider = 1;
		}
		if( $layout == 'masonry' ){
			$is_masonry = 1;
		}
                if( $layout == 'grid' ){
			$is_grid = 1;
		}
		
		$columns = absint($columns);
		if( !in_array($columns, array(1, 2, 3, 4, 6)) ){
			$columns = 4;
		}
		
		$args = array(
			'post_type' 			=> 'post'
			,'post_status' 			=> 'publish'
			,'ignore_sticky_posts' 	=> 1
			,'posts_per_page'		=> $per_page
			,'orderby'				=> $orderby
			,'order'				=> $order
			);
		
		$categories = str_replace(' ', '', $categories);
		if( strlen($categories) > 0 ){
			$ar_categories = explode(',', $categories);
			if( is_array($ar_categories) && count($ar_categories) > 0 ){
				$field_name = is_numeric($ar_categories[0])?'term_id':'slug';
				$args['tax_query'] = array(
					array(
						'taxonomy' => 'category'
						,'terms' => $ar_categories
						,'field' => $field_name
						,'include_children' => false
						)
					);
			}
		}
		
		global $post;
		$posts = new WP_Query($args);
		
		ob_start();
		if( $posts->have_posts() ):
			if( $posts->post_count <= 1 ){
				$is_slider = 0;
			}
			if( $is_slider ){
				$show_load_more = 0;
			}
			
			$classes = array();
			$classes[] = 'ftc-sb-blogs';
			if( $is_slider ){
				$classes[] = 'ftc-slider loading';
			}
			if( $is_masonry ){
				$classes[] = 'ftc-masonry';
			}

			$data_attr = array();
			$data_attr[] = 'data-margin="'.$margin.'"';
			$data_attr[] = 'data-nav="'.$show_nav.'"';
			$data_attr[] = 'data-auto_play="'.$auto_play.'"';
                        $data_attr[] = 'data-columns="'.$columns.'"';
                        $data_attr[] = 'data-masonry="'.$is_masonry.'"';
                        $data_attr[] = 'data-slider="'.$is_slider.'"';
                        $data_attr[] = 'data-slider="'.$is_grid.'"';
                        $data_attr[] = 'data-desksmall_items="'.$desksmall_items.'"';
                        $data_attr[] = 'data-tablet_items="'.$tablet_items.'"';
                        $data_attr[] = 'data-tabletmini_items="'.$tabletmini_items.'"';
                        $data_attr[] = 'data-mobile_items="'.$mobile_items.'"';
                        $data_attr[] = 'data-mobilesmall_items="'.$mobilesmall_items.'"';
			
			$atts = compact('masonry', 'title', 'columns', 'categories', 'per_page', 'orderby', 'order'
				,'style', 'show_title', 'show_thumbnail', 'show_author'
				,'show_date', 'show_comment', 'show_excerpt', 'show_readmore', 'excerpt_words'
				,'is_slider', 'show_nav', 'auto_play', 'margin', 'is_masonry', 'show_load_more' ,'is_grid',
                                'desksmall_items', 'tablet_items', 'tabletmini_items', 'mobile_items', 'mobilesmall_items');
				?>
				<div class="<?php echo esc_attr(implode(' ', $classes)); ?> ftc-shortcode"  data-atts="<?php echo htmlentities(json_encode($atts)); ?>" >
					<?php if( strlen($title) > 0 ): ?>

						<header class="header-title">
							<h3 class="title_sub"><span class="bg-heading"><span><?php echo esc_html($title); ?></span></span></h3>
						</header>
					<?php endif; ?>
					<div class="meta-slider">
						<div class="blogs <?php if (!$is_masonry){ ?> owl-carousel <?php } ?>">
							<?php ftc_get_blog_items_content_shortcode($atts, $posts); ?>
						</div>
						<?php if( $show_load_more ): ?>
							<div class="load-more-wrapper">
								<a href="#" class="load-more button" data-paged="2"><?php echo esc_html($load_more_text) ?></a>
							</div>
						<?php endif; ?>
					</div>
				</div>
				<?php
				endif;
				wp_reset_postdata();
				return ob_get_clean();
			}	
		}
		add_shortcode('ftc_blogs', 'ftc_blogs_shortcode');

		add_action('wp_ajax_ftc_blogs_load_items', 'ftc_get_blog_items_content_shortcode');
		add_action('wp_ajax_nopriv_ftc_blogs_load_items', 'ftc_get_blog_items_content_shortcode');
		if( !function_exists('ftc_get_blog_items_content_shortcode') ){
			function ftc_get_blog_items_content_shortcode($atts, $posts = null){

				global $post;

				if( defined( 'DOING_AJAX' ) && DOING_AJAX ){
					if( !isset($_POST['atts']) ){
						die('0');
					}
					$atts = $_POST['atts'];
					$paged = isset($_POST['paged'])?absint($_POST['paged']):1;

					extract($atts);

					$args = array(
						'post_type' 			=> 'post'
						,'post_status' 			=> 'publish'
						,'ignore_sticky_posts' 	=> 1
						,'posts_per_page'		=> $per_page
						,'orderby'				=> $orderby
						,'order'				=> $order
						,'paged'				=> $paged
						);

					$categories = str_replace(' ', '', $categories);
					if( strlen($categories) > 0 ){
						$categories = explode(',', $categories);
					}
					if( is_array($categories) && count($categories) > 0 ){
						$field_name = is_numeric($categories[0])?'term_id':'slug';
						$args['tax_query'] = array(
							array(
								'taxonomy' => 'category'
								,'terms' => $categories
								,'field' => $field_name
								,'include_children' => false
								)
							);
					}

					$posts = new WP_Query($args);
					ob_start();
				}

				extract($atts);

				if( $posts->have_posts() ):
					$item_class = '';
				if( !$is_slider ){
					$item_class = 12/(int)$columns;
					$item_class = 'col-sm-'.$item_class;
				}
				$key = -1;
				while( $posts->have_posts() ): $posts->the_post();

				$post_format = get_post_format(); /* Video, Audio, Gallery, Quote */
				if( $is_slider && $post_format == 'gallery' ){ /* Remove Slider in Slider */
					$post_format = false;
				}
				
				$key++;
				$item_extra_class = ($key % $columns == 0)?'first-child':(($key % $columns == $columns - 1)?'last-child':''); ?>
				<article class="post-wrapper <?php echo esc_attr($post_format); ?> <?php echo esc_attr($item_class.' '.$item_extra_class) ?>">
					<header class="post-img">
						<?php if( $show_thumbnail ){ ?>
						<?php 
						if( $post_format == 'gallery' || $post_format === false || $post_format == 'standard' ){
							?>
							<a class="thumbnail <?php echo esc_attr($post_format); ?> <?php echo ($post_format == 'gallery')?'loading':''; ?>" href="<?php echo ($post_format == 'gallery')?'javascript: void(0)':get_permalink() ?>">
								<span>
									<?php 
									
									if( $post_format == 'gallery' ){
										$gallery = get_post_meta($post->ID, 'ftc_gallery', true);
										$gallery_ids = explode(',', $gallery);
										if( is_array($gallery_ids) && has_post_thumbnail() ){
											array_unshift($gallery_ids, get_post_thumbnail_id());
										}
										foreach( $gallery_ids as $gallery_id ){
											echo wp_get_attachment_image( $gallery_id, 'ftc_blog_shortcode_thumb' );
										}
									}
									
									if( $post_format === false || $post_format == 'standard' ){
										if( has_post_thumbnail() ){
											the_post_thumbnail('ftc_blog_shortcode_thumb'); 
										}
										else{
											?>
											<img title="noimage" src="<?php echo get_template_directory_uri(); ?>/assets/images/no-image-blog.jpg" alt="" />
											<?php 
										}
									}
									
									?>
								</span>
								<div class="smooth-thumbnail"></div>
							</a>


							<?php 
						}

						if( $post_format == 'video' ){
							$video_url = get_post_meta($post->ID, 'ftc_video_url', true);
							echo do_shortcode('[ftc_video src="'.$video_url.'"]');
						}

						if( $post_format == 'audio' ){
							$audio_url = get_post_meta($post->ID, 'ftc_audio_url', true);
							if( strlen($audio_url) > 4 ){
								$file_format = substr($audio_url, -3, 3);
								if( in_array($file_format, array('mp3', 'ogg', 'wav')) ){
									echo do_shortcode('[audio '.$file_format.'="'.$audio_url.'"]');
								}
								else{
									echo do_shortcode('[ftc_soundcloud url="'.$audio_url.'" width="100%" height="122"]');
								}
							}
						}

					}
					?>

				</header>

				<?php if( $post_format != 'quote' ): ?>

					<div class="post-info">
						<!-- Blog Date -->
						<?php if( $show_date && $show_thumbnail && ( $post_format == 'gallery' || $post_format === false || $post_format == 'standard' ) ): ?>
							<div class="entry-date">
								<span><?php echo get_the_time('d'); ?></span>
								<span><?php echo get_the_time('M'); ?></span>
							</div>
						<?php endif; ?>
						<div class="main-content-blog">
							<header>
								<!-- Blog Title -->
								<?php if( $show_title ): ?>
									<h3 class="title_sub blog-title entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
								<?php endif; ?>

								<!-- Blog Date Time -->
								<?php if( $show_date && ( !$show_thumbnail || ( $post_format != 'gallery' && $post_format !== false && $post_format != 'standard' ) ) ): ?>
									<div class="entry-date entry-date-meta">
										<i class="fa fa-calendar"></i> <?php echo get_the_time(get_option('date_format')); ?>
									</div>
								<?php endif; ?>
							</header>

							<?php if( $show_excerpt && function_exists('ftc_the_excerpt_max_words') ): ?>
								<p class="excerpt"><?php ftc_the_excerpt_max_words($excerpt_words, '', true, '', true); ?></p>
							<?php endif; ?>

						</div>

						<?php if( $show_readmore ): ?>
							<a href="<?php the_permalink(); ?>" class="button-readmore"><?php esc_html_e('Read More','themeftc') ?></a>
						<?php endif; ?>
						
						<!-- Blog Author -->
						<?php if( $show_author ): ?>
							<span class="author"><?php esc_html_e('Post by ','themeftc') ?><?php the_author_posts_link(); ?></span>
						<?php endif; ?>
						
						<!-- Blog Comment -->
						<?php if( $show_comment ): ?>
							<span class="comment-count">
								<i class="fa fa-comments-o"></i>
								<span class="number">
									<?php $comments_count = wp_count_comments($post->ID); if($comments_count->approved < 10 && $comments_count->approved > 0){ echo '0'; } echo esc_html($comments_count->approved); ?>
								</span>
							</span>
						<?php endif; ?>
						
					</div>

				<?php else: /* Post format is quote */ ?>
					<div class="ftc-blockquote">
						<blockquote class="blockquote-bg">
							<?php 
							$quote_content = get_the_excerpt();
							if( !$quote_content ){
								$quote_content = get_the_content();
							}
							echo do_shortcode($quote_content);
							?>
						</blockquote>
						<div class="blockquote-meta">
							<?php if( $show_date ): ?>
								<span class="entry-date">
									<i class="fa fa-calendar"></i>
									<?php echo get_the_time(get_option('date_format')); ?>
								</span>
							<?php endif; ?>

							<?php if( $show_author ): ?>
								<span class="author"><i class="fa fa-user"></i><?php the_author_posts_link(); ?></span>
							<?php endif; ?>
						</div>
					</div>

				<?php endif; ?>

			</article>
			<?php 
			endwhile;
			endif;

			wp_reset_postdata();

			if( defined( 'DOING_AJAX' ) && DOING_AJAX ){
				die(ob_get_clean());
			}

		}
	}

	/* FTC Google Map shortcode */
	if( !function_exists('ftc_google_map_shortcode') ){
		function ftc_google_map_shortcode($atts, $content = null){
			extract(shortcode_atts(array(
				'address'			=> ''
				,'height'			=> 360
				,'zoom'				=> 12
				,'map_type'			=> 'ROADMAP'
				,'title'			=> ''
				), $atts));

			ob_start();	
			wp_enqueue_script('gmap-api');
			?>
			<div class="google-map-container" style="height:<?php echo esc_attr($height); ?>px" 
				data-address="<?php echo esc_attr($address) ?>" data-zoom="<?php echo esc_attr($zoom) ?>" data-map_type="<?php echo esc_attr($map_type) ?>" data-title="<?php echo esc_attr($title) ?>">
				<div style="height:<?php echo esc_attr($height); ?>px"></div>
			</div>
			<?php
			return ob_get_clean();
		}
	}
	add_shortcode('ftc_google_map', 'ftc_google_map_shortcode');

	/* Countdown shortcode */
	if( !function_exists('ftc_countdown_shortcode') ){
		function ftc_countdown_shortcode( $atts ){
			extract( shortcode_atts(array(
				'day'				=> ''
				,'month'			=> ''
				,'year'				=> ''
				,'text_color_style'	=> 'text-default'
				), $atts)
			);

			if( empty($month) || empty($day) || empty($year) ){
				return;
			}

			if( !checkdate($month, $day, $year) ){
				return;
			}

			$date = mktime(0, 0, 0, $month, $day, $year);
			$current_time = current_time('timestamp');
			$delta = $date - $current_time;

			if( $delta <= 0 ){
				return;
			}

			$time_day = 60 * 60 * 24;
			$time_hour = 60 * 60;
			$time_minute = 60;

			$day = floor( $delta / $time_day );
			$delta -= $day * $time_day;

			$hour = floor( $delta / $time_hour );
			$delta -= $hour * $time_hour;

			$minute = floor( $delta / $time_minute );
			$delta -= $minute * $time_minute;

			if( $delta > 0 ){
				$second = $delta;
			}
			else{
				$second = 0;
			}

			$day = zeroise($day, 2);
			$hour = zeroise($hour, 2);
			$minute = zeroise($minute, 2);
			$second = zeroise($second, 2);

			ob_start();
			?>
			<div class="ftc-countdown <?php echo esc_attr($text_color_style) ?>">
				<div class="counter-wrapper days-<?php echo strlen($day); ?>">
					<div class="days">
						<div class="number-wrapper">
							<span class="number"><?php echo esc_html($day); ?></span>
						</div>
						<div class="ref-wrapper">
							<?php esc_html_e('Days', 'themeftc'); ?>
						</div>
					</div>
					<div class="hours">
						<div class="number-wrapper">
							<span class="number"><?php echo esc_html($hour); ?></span>
						</div>
						<div class="ref-wrapper">
							<?php esc_html_e('Hours', 'themeftc'); ?>
						</div>
					</div>
					<div class="minutes">
						<div class="number-wrapper">
							<span class="number"><?php echo esc_html($minute); ?></span>
						</div>
						<div class="ref-wrapper">
							<?php esc_html_e('Minutes', 'themeftc'); ?>
						</div>
					</div>
					<div class="seconds">
						<div class="number-wrapper">
							<span class="number"><?php echo esc_html($second); ?></span>
						</div>
						<div class="ref-wrapper">
							<?php esc_html_e('Seconds', 'themeftc'); ?>
						</div>
					</div>
				</div>
			</div>
			<?php
			return ob_get_clean();
		}
	}
	add_shortcode('ftc_countdown', 'ftc_countdown_shortcode');

	/* Milestone shortcode */
	if( !function_exists('ftc_milestone_shortcode') ){
		function ftc_milestone_shortcode( $atts ){
			extract( shortcode_atts(array(
				'number'			=> 0
				,'subject'			=> ''
				,'text_color_style'	=> 'text-default'
				), $atts)
			);

			if( !is_numeric($number) ){
				$number = 0;
			}

			ob_start();
			?>
			<div class="ftc-count-milestone <?php echo esc_attr($text_color_style) ?>" data-number="<?php echo esc_attr($number); ?>">
				<span class="number">
					<?php echo esc_html($number); ?>
				</span>
				<h3 class="subject">
					<?php echo esc_html($subject); ?>
				</h3>
			</div>
			<?php
			return ob_get_clean();
		}
	}
	add_shortcode('ftc_milestone', 'ftc_milestone_shortcode');


	/******  Woo shortcodes  ******/
	function ftc_remove_product_hooks_shortcode( $options = array() ){
		if( isset($options['show_image']) && !$options['show_image'] ){
			remove_action('woocommerce_before_shop_loop_item_title', 'ftc_template_loop_product_thumbnail', 10);
		}
		if( isset($options['show_title']) && !$options['show_title'] ){
			remove_action('woocommerce_after_shop_loop_item', 'ftc_template_loop_product_title', 20);
		}
		if( isset($options['show_sku']) && !$options['show_sku'] ){
			remove_action('woocommerce_after_shop_loop_item', 'ftc_template_loop_product_sku', 30);
		}
		if( isset($options['show_price']) && !$options['show_price'] ){
			remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_price', 50);
		}
		if( isset($options['show_short_desc']) && !$options['show_short_desc'] ){
			remove_action('woocommerce_after_shop_loop_item', 'ftc_template_loop_short_description', 60);
		}
		if( isset($options['show_rating']) && !$options['show_rating'] ){
			remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_rating', 40);
		}
		if( isset($options['show_label']) && !$options['show_label'] ){
			remove_action('woocommerce_after_shop_loop_item_title', 'ftc_template_loop_product_label', 1);
		}
		if( isset($options['show_categories']) && !$options['show_categories'] ){
			remove_action('woocommerce_after_shop_loop_item', 'ftc_template_loop_categories', 10);
		}
		if( isset($options['show_add_to_cart']) && !$options['show_add_to_cart'] ){
			remove_action('woocommerce_after_shop_loop_item', 'ftc_template_loop_add_to_cart', 70);
			remove_action('woocommerce_after_shop_loop_item_title', 'ftc_template_loop_add_to_cart', 10001 );
		}
	}

	function ftc_restore_product_hooks_shortcode(){
		add_action('woocommerce_after_shop_loop_item_title', 'ftc_template_loop_product_label', 1);
		add_action('woocommerce_before_shop_loop_item_title', 'ftc_template_loop_product_thumbnail', 10);

		add_action('woocommerce_after_shop_loop_item', 'ftc_template_loop_categories', 10);
		add_action('woocommerce_after_shop_loop_item', 'ftc_template_loop_product_title', 20);
		add_action('woocommerce_after_shop_loop_item', 'ftc_template_loop_product_sku', 30);
		add_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_rating', 50);
		add_action('woocommerce_after_shop_loop_item', 'ftc_template_loop_short_description', 60); 
		add_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_price', 40);
		// add_action('woocommerce_after_shop_loop_item', 'ftc_template_loop_add_to_cart', 70); 
		add_action('woocommerce_after_shop_loop_item_title', 'ftc_template_loop_add_to_cart', 10001 );
	}

	function ftc_filter_product_by_product_type( &$args = array(), $product_type = 'recent' ){
		switch( $product_type ){
			case 'sale':
			$args['meta_query'][] = array(
				'key' 			=> '_sale_price'
				,'value' 		=>  0
				,'compare'   	=> '>'
				,'type'      	=> 'NUMERIC'
				);
			break;
			case 'featured':
			$args['meta_query'][] = array(
				'key' 			=> '_featured'
				,'value' 		=> 'yes'
				);
			break;
			case 'best_selling':
			$args['meta_key'] 	= 'total_sales';
			$args['orderby'] 	= 'meta_value_num';
			$args['order'] 		= 'desc';
			break;
			case 'top_rated':			
			break;
			case 'mixed_order':
			$args['orderby'] 	= 'rand';
			break;
			default: /* Recent */
			$args['orderby'] 	= 'date';
			$args['order'] 		= 'desc';
			break;
		}
	}

	function ftc_template_loop_product_meta_left_open(){
		echo '<div class="meta-left">';
	}

	function ftc_template_loop_product_meta_right_open(){
		echo '<div class="meta-right">';
	}

	function ftc_template_loop_product_meta_close(){
		echo '</div>';
	}

	/*** Products Shortcode ***/

	if( !function_exists('ftc_products_shortcode') ){

		function ftc_products_shortcode($atts, $content){

			extract(shortcode_atts(array(
				'product_type'			=> 'recent'
				,'custom_order'			=> 0
				,'orderby'				=> 'none'
				,'order'				=> 'DESC'
				,'columns' 				=> 5
				,'per_page' 			=> 5
				,'product_cats'			=> ''
				,'ids'					=> ''
				,'title' 				=> ''
				,'meta_position' 		=> 'bottom'
				,'show_image' 			=> 1
				,'show_title' 			=> 1
				,'show_sku' 			=> 0
				,'show_price' 			=> 1
				,'show_short_desc'  	=> 0
				,'show_rating' 			=> 1
				,'show_label' 			=> 1	
				,'show_categories'		=> 0	
				,'show_add_to_cart' 	=> 1

				), $atts));
			if ( !class_exists('WooCommerce') ){
				return;
			}
			
			$options = array(
				'show_image'		=> $show_image
				,'show_label'		=> $show_label
				,'show_title'		=> $show_title
				,'show_sku'			=> $show_sku
				,'show_price'		=> $show_price
				,'show_short_desc'	=> $show_short_desc
				,'show_categories'	=> $show_categories
				,'show_rating'		=> $show_rating
				,'show_add_to_cart'	=> $show_add_to_cart
				);
			ftc_remove_product_hooks_shortcode( $options );
			
			if( $meta_position == 'on-thumbnail' ){
				add_action('woocommerce_after_shop_loop_item', 'ftc_template_loop_product_meta_left_open', 1);
				add_action('woocommerce_after_shop_loop_item', 'ftc_template_loop_product_meta_close', 35);
				add_action('woocommerce_after_shop_loop_item', 'ftc_template_loop_product_meta_right_open', 35);
				add_action('woocommerce_after_shop_loop_item', 'ftc_template_loop_product_meta_close', 65);
			}
			
			$args = array(
				'post_type'				=> 'product'
				,'post_status' 			=> 'publish'
				,'ignore_sticky_posts'	=> 1
				,'posts_per_page' 		=> $per_page
				,'orderby' 				=> 'date'
				,'order' 				=> 'desc'
				,'meta_query' 			=> WC()->query->get_meta_query()
				,'tax_query'           	=> WC()->query->get_tax_query()
				);	

			if( $custom_order ){
				$args['orderby'] = $orderby;
				$args['order'] = $order;
			}
			else{
				ftc_filter_product_by_product_type($args, $product_type);
			}

			$product_cats = str_replace(' ', '', $product_cats);
			if( strlen($product_cats) > 0 ){
				$product_cats = explode(',', $product_cats);
			}
			if( is_array($product_cats) && count($product_cats) > 0 ){
				$field_name = is_numeric($product_cats[0])?'term_id':'slug';
				$args['tax_query'] = array(
					array(
						'taxonomy' => 'product_cat'
						,'terms' => $product_cats
						,'field' => $field_name
						,'include_children' => false
						)
					);
			}
			
			$ids = str_replace(' ', '', $ids);
			if( strlen($ids) > 0 ){
				$ids = explode(',', $ids);
				if( is_array($ids) && count($ids) > 0 ){
					$args['post__in'] = $ids;
					if( count($ids) == 1 ){
						$columns = 1;
					}
				}
			}
			
			ob_start();
			global $woocommerce_loop;
			if( (int)$columns <= 0 ){
				$columns = 5;
			}
			$old_woocommerce_loop_columns = $woocommerce_loop['columns'];
			$woocommerce_loop['columns'] = $columns;	

			if( $product_type == 'top_rated' && !$custom_order ){
				add_filter('posts_clauses', array(WC()->query, 'order_by_rating_post_clauses'));	
			}

			$products = new WP_Query( $args );	

			if( $product_type == 'top_rated' && !$custom_order ){
				remove_filter('posts_clauses', array(WC()->query, 'order_by_rating_post_clauses'));
			}
			
			$classes = array();
			$classes[] = 'ftc-product-wrapper ftc-shortcode ftc-product';
			$classes[] = $product_type;
			$classes[] = 'meta-'.$meta_position;

			$rand_id = 'ftc-product-wrapper-'.rand(0, 10000);
			if( $products->have_posts() ): 
				?>
			<div class="<?php echo esc_attr(implode(' ', $classes)); ?>" id="<?php echo esc_attr($rand_id); ?>">
				<header class="header-title">
					<?php if( strlen($title) > 0 ): ?>
						<h3 class="title_sub"><span class="bg-heading"><span><?php echo esc_html($title); ?></span></span></h3>
					<?php endif; ?>
				</header>
				<div class="meta-slider">
					<?php woocommerce_product_loop_start(); ?>				

					<?php while( $products->have_posts() ): $products->the_post(); ?>				
						<?php wc_get_template_part( 'content', 'product' ); ?>							
					<?php endwhile; ?>	

					<?php woocommerce_product_loop_end(); ?>
				</div>
			</div>
			<?php
			endif;
			
			wp_reset_postdata();

			/* restore hooks */
			ftc_restore_product_hooks_shortcode();
			
			if( $meta_position == 'on-thumbnail' ){
				remove_action('woocommerce_after_shop_loop_item', 'ftc_template_loop_product_meta_left_open', 1);
				remove_action('woocommerce_after_shop_loop_item', 'ftc_template_loop_product_meta_close', 35);
				remove_action('woocommerce_after_shop_loop_item', 'ftc_template_loop_product_meta_right_open', 35);
				remove_action('woocommerce_after_shop_loop_item', 'ftc_template_loop_product_meta_close', 65);
			}

			$woocommerce_loop['columns'] = $old_woocommerce_loop_columns;
			return '<div class="woocommerce columns-'.$columns.'">' . ob_get_clean() . '</div>';
		}	
	}
	add_shortcode('ftc_products', 'ftc_products_shortcode');

	/** Products Widget **/
	if( !function_exists('ftc_products_widget_shortcode') ){
		function ftc_products_widget_shortcode($atts, $content){
			
			if( !class_exists('Ftc_Products_Widget') ){
				return;
			}
			
			extract(shortcode_atts(array(
				'product_type'   => 'recent'
				,'rows'     => 3
				,'per_page'    => 6
				,'product_cats'   => ''
				,'title'     => ''
				,'show_image'    => 1
				,'thumbnail_size'               => 'shop_thumbnail'
				,'show_title'    => 1
				,'show_price'    => 1
				,'show_rating'    => 1 
				,'show_categories'  => 0 
				,'is_slider'   => 0
				,'show_nav'    => 1
				,'auto_play'   => 1
				), $atts)); 
			if( trim($product_cats) != '' ){
				$product_cats = array_map('trim', explode(',', $product_cats));
			}
			
			$instance = array(
				'title'     => $title
				,'product_type'   => $product_type
				,'product_cats'   => $product_cats
				,'row'     => $rows
				,'limit'    => $per_page
				,'show_thumbnail'   => $show_image
				,'thumbnail_size'   => $thumbnail_size
				,'show_categories'   => $show_categories
				,'show_product_title'  => $show_title
				,'show_price'    => $show_price
				,'show_rating'    => $show_rating
				,'is_slider'   => $is_slider
				,'show_nav'    => $show_nav
				,'auto_play'    => $auto_play
				);
			
			ob_start();
			the_widget('Ftc_Products_Widget', $instance);
			return ob_get_clean();
		}
	}
	add_shortcode('ftc_products_widget', 'ftc_products_widget_shortcode');
	/*** Products Slider Shortcode ***/

	if( !function_exists('ftc_products_slider_shortcode') ){
		function ftc_products_slider_shortcode($atts, $content){

			extract(shortcode_atts(array(
				'product_type'			=> 'recent'
				,'custom_order'			=> 0
				,'orderby'				=> 'none'
				,'order'				=> 'DESC'
				,'columns' 				=> 5
				,'rows' 				=> 1
				,'per_page' 			=> 6
				,'product_cats'			=> ''
				,'title' 				=> ''
				,'meta_position' 		=> 'bottom'
				,'show_image' 			=> 1
				,'show_title' 			=> 1
				,'show_sku' 			=> 0
				,'show_price' 			=> 1
				,'show_short_desc'  	=> 0
				,'show_rating' 			=> 1
				,'show_label' 			=> 1	
				,'show_categories'		=> 0	
				,'show_add_to_cart' 	=> 1
				,'show_nav'				=> 1
				,'position_nav'			=> 'nav-top'
				,'margin'				=> 20
				,'desksmall_items'	=> '1'
                        ,'tablet_items'     	=> '1'
                        ,'tabletmini_items'	=> '1'
                        ,'mobile_items'	=> '1'
                        ,'mobilesmall_items'	=> '1'
				), $atts));			

			if ( !class_exists('WooCommerce') ){
				return;
			}
			
			$options = array(
				'show_image'		=> $show_image
				,'show_label'		=> $show_label
				,'show_title'		=> $show_title
				,'show_sku'			=> $show_sku
				,'show_price'		=> $show_price
				,'show_short_desc'	=> $show_short_desc
				,'show_categories'	=> $show_categories
				,'show_rating'		=> $show_rating
				,'show_add_to_cart'	=> $show_add_to_cart
				);
			ftc_remove_product_hooks_shortcode( $options );

			if( $meta_position == 'on-thumbnail' ){
				add_action('woocommerce_after_shop_loop_item', 'ftc_template_loop_product_meta_left_open', 1);
				add_action('woocommerce_after_shop_loop_item', 'ftc_template_loop_product_meta_close', 35);
				add_action('woocommerce_after_shop_loop_item', 'ftc_template_loop_product_meta_right_open', 35);
				add_action('woocommerce_after_shop_loop_item', 'ftc_template_loop_product_meta_close', 65);
			}

			$args = array(
				'post_type'				=> 'product',
				'post_status' 			=> 'publish',
				'ignore_sticky_posts'	=> 1,
				'posts_per_page' 		=> $per_page,
				'orderby' 				=> 'date',
				'order' 				=> 'desc'
				,'meta_query' 			=> WC()->query->get_meta_query()
				,'tax_query'           	=> WC()->query->get_tax_query()
				);
			
			if( $custom_order ){
				$args['orderby'] = $orderby;
				$args['order'] = $order;
			}
			else{
				ftc_filter_product_by_product_type($args, $product_type);
			}

			$product_cats = str_replace(' ', '', $product_cats);
			if( strlen($product_cats) > 0 ){
				$product_cats = explode(',', $product_cats);
			}
			if( is_array($product_cats) && count($product_cats) > 0 ){
				$field_name = is_numeric($product_cats[0])?'term_id':'slug';
				$args['tax_query'] = array(
					array(
						'taxonomy' => 'product_cat',
						'terms' => $product_cats,
						'field' => $field_name,
						'include_children' => false
						)
					);
			}			

			ob_start();
			global $woocommerce_loop;
			if( (int)$columns <= 0 ){
				$columns = 5;
			}
			$old_woocommerce_loop_columns = $woocommerce_loop['columns'];
			$woocommerce_loop['columns'] = $columns;			

			if( $product_type == 'top_rated' && !$custom_order ){
				add_filter('posts_clauses', array(WC()->query, 'order_by_rating_post_clauses'));
			}

			$products = new WP_Query( $args );			

			if( $product_type == 'top_rated' && !$custom_order ){
				remove_filter('posts_clauses', array(WC()->query, 'order_by_rating_post_clauses'));	
			}

			$is_slider = true;
			if( isset($products->post_count) && ( $products->post_count <= 1 || $products->post_count <= $rows ) ){
				$is_slider = false;
			}
			
			$classes = array();
			$classes[] = 'ftc-product-slider ftc-slider ftc-shortcode';

			if( $products->have_posts() ): 
				?>
			<div class="<?php echo esc_attr(implode(' ', $classes)); ?>" data-slider="<?php echo esc_attr((int)$is_slider) ?>" data-columns="<?php echo esc_attr($columns) ?>" data-autoplay="0"
				data-nav="<?php echo esc_attr($show_nav) ?>" data-margin="<?php echo esc_attr($margin) ?>" data-desksmall_items="<?php echo esc_attr($desksmall_items)?>"
                        data-tablet_items="<?php echo esc_attr($tablet_items) ?>" data-tabletmini_items="<?php echo esc_attr($tabletmini_items) ?>" data-mobile_items="<?php echo esc_attr($mobile_items) ?>"
                        data-mobilesmall_items="<?php echo esc_attr($mobilesmall_items) ?>">
				<header class="header-title">
					<?php if( strlen($title) > 0 ): ?>
						<h3 class="title_sub"><span class="bg-heading"><span><?php echo esc_html($title); ?></span></span></h3>
					<?php endif; ?>
				</header>
				<div class="meta-slider <?php echo ($is_slider)?'loading':''; ?>">
					<?php woocommerce_product_loop_start(); ?>				

					<?php 
					$count = 0;
					while( $products->have_posts() ): $products->the_post();
					if( $rows > 1 && $count % $rows == 0 ){
						echo '<div class="ftc-products">';
					}
					wc_get_template_part( 'content', 'product' );
					if( $rows > 1 && ($count % $rows == $rows - 1 || $count == $products->post_count - 1) ){
						echo '</div>';
					}
					$count++;
					endwhile; 
					?>			

					<?php woocommerce_product_loop_end(); ?>
				</div>
			</div>
			<?php
			endif;
			
			wp_reset_postdata();			

			/* restore hooks */
			ftc_restore_product_hooks_shortcode();
			
			if( $meta_position == 'on-thumbnail' ){
				remove_action('woocommerce_after_shop_loop_item', 'ftc_template_loop_product_meta_left_open', 1);
				remove_action('woocommerce_after_shop_loop_item', 'ftc_template_loop_product_meta_close', 35);
				remove_action('woocommerce_after_shop_loop_item', 'ftc_template_loop_product_meta_right_open', 35);
				remove_action('woocommerce_after_shop_loop_item', 'ftc_template_loop_product_meta_close', 65);
			}

			$woocommerce_loop['columns'] = $old_woocommerce_loop_columns;
			
			return '<div class="woocommerce">' . ob_get_clean() . '</div>';
		}	
	}
	add_shortcode('ftc_products_slider', 'ftc_products_slider_shortcode');
	

	/* Product Category Slider */

	if( !function_exists('ftc_product_categories_slider_shortcode') ){
		function ftc_product_categories_slider_shortcode($atts, $content){
			extract(shortcode_atts(array(
				'title'				=> ''
				,'per_page' 		=> 5
				,'columns' 			=> 4
				,'rows' 			=> 1
				,'parent' 			=> ''
				,'child_of' 		=> 0
				,'ids'	 			=> ''
				,'hide_empty'		=> 1
				,'show_title'		=> 1
				,'show_nav' 		=> 1
				,'auto_play' 		=> 1
				,'margin'			=> 0
				),$atts));

			if ( !class_exists('WooCommerce') ){
				return;
			}	

			add_filter('subcategory_archive_thumbnail_size', 'ftc_product_cat_thumbnail_size_filter', 10);

			$args = array(
				'orderby'     => 'name'
				,'order'      => 'ASC'
				,'hide_empty' => $hide_empty
				,'include'    => array_map('trim', explode(',', $ids))
				,'pad_counts' => true
				,'parent'     => $parent
				,'child_of'   => $child_of
				,'number'     => $per_page
				);
			$product_categories = get_terms('product_cat', $args);	
			global $woocommerce_loop;
			$old_woocommerce_loop_columns = $woocommerce_loop['columns'];
			$woocommerce_loop['columns'] = $columns;	

			ob_start();

			if( count($product_categories) > 0 ):
				$data_attr = array();
			$data_attr[] = 'data-nav="'.$show_nav.'"';
			$data_attr[] = 'data-autoplay="'.$auto_play.'"';
			$data_attr[] = 'data-margin="'.$margin.'"';
			$data_attr[] = 'data-columns="'.$columns.'"';
			?>
			<div class="ftc-product-category-slider-wrapper ftc-slider ftc-shortcode <?php echo ($show_nav)?'show-navi':''; ?>" <?php echo implode(' ', $data_attr); ?>>
				<header class="header-title">
					<?php if( strlen($title) > 0 ): ?>
						<h3 class="title_sub"><span class="bg-heading"><span><?php echo esc_html($title); ?></span></span></h3>
					<?php endif; ?>
				</header>
				<div class="meta-slider loading">
					<?php 
					woocommerce_product_loop_start();
					$count_all = count($product_categories);
					$count = 0;
					foreach ( $product_categories as $category ) {
						if( $rows > 1 && $count % $rows == 0 ){
							echo '<div class="product-cat-group">';
						}
						wc_get_template( 'content-product_cat.php', array(
							'category' 		=> $category
							,'show_title' 	=> $show_title
							) );
						if( $rows > 1 && ($count % $rows == $rows - 1 || $count == $count_all - 1) ){
							echo '</div>';
						}
						$count++;
					}
					woocommerce_product_loop_end();
					woocommerce_reset_loop();
					?>
				</div>
			</div>
			<?php
			endif;

			remove_filter('subcategory_archive_thumbnail_size', 'ftc_product_cat_thumbnail_size_filter', 10);
			$woocommerce_loop['columns'] = $old_woocommerce_loop_columns;

			return '<div class="woocommerce">' . ob_get_clean() . '</div>';			
		}
	}
	add_shortcode('ftc_product_categories_slider', 'ftc_product_categories_slider_shortcode');

	if( !function_exists('ftc_product_cat_thumbnail_size_filter') ){
		function ftc_product_cat_thumbnail_size_filter( $size ){
			return 'ftc_product_cat_thumb';
		}
	}

	/* FTC Product Deals Slider */
	if( !function_exists('ftc_product_deals_slider_shortcode') ){
		function ftc_product_deals_slider_shortcode($atts, $content = null){

			extract(shortcode_atts(array(
				'title' 				=> ''
				,'layout' 				=> 'grid'
				,'columns' 				=> 4
				,'per_page' 			=> 5
				,'product_cats'			=> ''
				,'product_type'			=> 'recent'
				,'show_counter'			=> 1
				,'counter_position'		=> 'bottom' /* bottom - on-thumbnail */
				,'gallery_position'		=> 'bottom-out' /* bottom out line */
				,'show_image' 			=> 1
				,'show_gallery' 		=> 1
				,'show_title' 			=> 1
				,'show_sku' 			=> 0
				,'show_price' 			=> 1
				,'show_short_desc'  	=> 0
				,'show_rating' 			=> 1
				,'show_label' 			=> 1	
				,'show_categories'		=> 0	
				,'show_add_to_cart' 	=> 1
				,'show_nav'				=> 1
				,'auto_play'			=> 1
				,'margin'				=> 20
				), $atts));			

			if ( !class_exists('WooCommerce') ){
				return;
			}
			
			if( $layout == 'list' ){
				$counter_position = 'bottom';
			}
			
			add_filter('ftc_loop_product_thumbnail', 'ftc_product_deals_thumbnail_filter', 10);
			
			if( $layout == 'list' && $show_short_desc ){
				add_action('woocommerce_after_shop_loop_item', 'ftc_template_loop_short_description', 65);
				$show_short_desc = 0;
			}
			
			if( $show_counter ){
				if( $counter_position == 'bottom' ){
					add_action('woocommerce_after_shop_loop_item', 'ftc_template_loop_time_deals', 65);			
				}
				else{
					add_action('woocommerce_after_shop_loop_item_title', 'ftc_template_loop_time_deals', 99);
				}
			}
			if( $show_gallery ){
				if( $counter_position == 'bottom-out' ){
					add_action('ftc_after_shop_loop_item', 'ftc_template_loop_thumb_list', 999);			
				}
				else{
					add_action('woocommerce_after_shop_loop_item_title', 'ftc_template_loop_thumb_list', 999);
				}
			}
			/* Remove hook */
			$options = array(
				'show_image'		=> $show_image
				,'show_label'		=> $show_label
				,'show_title'		=> $show_title
				,'show_sku'			=> $show_sku
				,'show_price'		=> $show_price
				,'show_short_desc'	=> $show_short_desc
				,'show_categories'	=> $show_categories
				,'show_rating'		=> $show_rating
				,'show_add_to_cart'	=> $show_add_to_cart
				);
			ftc_remove_product_hooks_shortcode( $options );

			$args = array(
			'post_type'				=> array('product', 'product_variation')
			,'post_status' 			=> 'publish'
			,'ignore_sticky_posts'	=> 1
			,'posts_per_page' 		=> -1
			,'orderby' 				=> 'date'
			,'order' 				=> 'desc'
			,'meta_query' => array(
				array(
					'key'		=> '_sale_price_dates_to'
					,'value'	=> current_time( 'timestamp', true )
					,'compare'	=> '>'
					,'type'		=> 'numeric'
				)
				,array(
					'key'		=> '_sale_price_dates_from'
					,'value'	=> current_time( 'timestamp', true )
					,'compare'	=> '<'
					,'type'		=> 'numeric'
				)
			)
			,'tax_query'		=> array()
		);

			ftc_filter_product_by_product_type($args, $product_type);
			
			$array_product_cats = array();

			$product_cats = str_replace(' ', '', $product_cats);
			if( strlen($product_cats) > 0 ){
				$array_product_cats = explode(',', $product_cats);
			}			

			ob_start();
			global $woocommerce_loop, $post, $product;
			if( (int)$columns <= 0 ){
				$columns = 5;
			}
			$old_woocommerce_loop_columns = $woocommerce_loop['columns'];
			$woocommerce_loop['columns'] = $columns;
			
			$product_ids_on_sale = array();
			
			$products = new WP_Query( $args );

			if( $products->have_posts() ){
			while( $products->have_posts() ){
				$products->the_post();
				if( $post->post_type == 'product' ){
					$_product = wc_get_product( $post->ID );
					if( is_object( $_product ) && $_product->is_visible() ){
						if( !empty($array_product_cats) ){
							$field_name = is_numeric($array_product_cats[0])?'ids':'slug';
							$post_terms = wp_get_post_terms($post->ID, 'product_cat', array('fields' => $field_name));
							if( is_array($post_terms) ){
								$array_intersect = array_intersect($post_terms, $array_product_cats);
								if( !empty($array_intersect) ){
									$product_ids_on_sale[] = $post->ID;
								}
							}
						}
						else{
							$product_ids_on_sale[] = $post->ID;
						}
					}
				}
				else{ /* Variation product */
					$post_parent_id = $post->post_parent;
					$parent_product = wc_get_product( $post_parent_id );
					if( is_object( $parent_product ) && $parent_product->is_visible() ){
						if( !empty($array_product_cats) ){
							$field_name = is_numeric($array_product_cats[0])?'ids':'slug';
							$post_terms = wp_get_post_terms($post_parent_id, 'product_cat', array('fields' => $field_name));
							if( is_array($post_terms) ){
								$array_intersect = array_intersect($post_terms, $array_product_cats);
								if( !empty($array_intersect) ){
									$product_ids_on_sale[] = $post_parent_id;
								}
							}
						}
						else{
							$product_ids_on_sale[] = $post_parent_id;
						}
					}
				}
				$product_ids_on_sale = array_unique($product_ids_on_sale);
				if( count($product_ids_on_sale) == $per_page ){
					break;
				}
			}
		}
			$product_ids_on_sale = array_unique($product_ids_on_sale);
			$product_ids_on_sale = array_slice($product_ids_on_sale, 0, $per_page);
			
			if( count($product_ids_on_sale) == 0 ){
				$product_ids_on_sale = array(0);
			}
			
			$args = array(
			'post_type'				=> 'product'
			,'post_status' 			=> 'publish'
			,'ignore_sticky_posts'	=> 1
			,'posts_per_page' 		=> $per_page
			,'orderby' 				=> 'date'
			,'order' 				=> 'desc'
			,'post__in'				=> $product_ids_on_sale
			,'meta_query' 			=> WC()->query->get_meta_query()
			,'tax_query'           	=> WC()->query->get_tax_query()
		);
			ftc_filter_product_by_product_type($args, $product_type);

			$products = new WP_Query($args);
			
			$is_slider = ( isset($products->post_count) && $products->post_count > 1 )?true:false;
			
			if( $products->have_posts() ): 
				$classes = array();
			$classes[] = $layout;
			$classes[] = 'counter-' . $counter_position;
			if( $show_nav ){
				$classes[] = 'show-navi';
			}

			$data_attr = array();
			$data_attr[] = 'data-nav="'.esc_attr($show_nav).'"';
			$data_attr[] = 'data-autoplay="'.esc_attr($auto_play).'"';
			$data_attr[] = 'data-margin="'.esc_attr($margin).'"';
			$data_attr[] = 'data-columns="'.esc_attr($columns).'"';
			$data_attr[] = 'data-is_slider="'.esc_attr($is_slider).'"';
			?>
			<div class="ftc-product-time-deal ftc-slider ftc-shortcode" <?php echo implode(' ', $data_attr); ?>>
				<header class="header-title">
					<?php if( strlen($title) > 0 ): ?>
						<h3 class="title_sub"><span class="bg-heading"><span><?php echo esc_html($title); ?></span></span></h3>
					<?php endif; ?>
				</header>
				<div class="meta-slider <?php echo ($is_slider)?'loading':''; ?>">
					<?php woocommerce_product_loop_start(); ?>				

					<?php while( $products->have_posts() ): $products->the_post(); ?>
						<?php wc_get_template_part( 'content', 'product' ); ?>							
					<?php endwhile; ?>			

					<?php woocommerce_product_loop_end(); ?>
				</div>
			</div>
			<?php
			endif;
			
			wp_reset_postdata();			

			/* restore hooks */
			remove_filter('ftc_loop_product_thumbnail', 'ftc_product_deals_thumbnail_filter', 10);
			
			if( $layout == 'list' ){
				remove_action('woocommerce_after_shop_loop_item', 'ftc_template_loop_short_description', 65);
			}
			
			if( $show_counter ){
				if( $counter_position == 'bottom' ){
					remove_action('woocommerce_after_shop_loop_item', 'ftc_template_loop_time_deals', 65);			
				}
				else{
					remove_action('woocommerce_after_shop_loop_item_title', 'ftc_template_loop_time_deals', 99);
				}
			}

			if( $show_gallery ){
				if( $counter_position == 'bottom-out' ){
					remove_action('ftc_after_shop_loop_item', 'ftc_template_loop_thumb_list', 999);			
				}
				else{
					remove_action('woocommerce_after_shop_loop_item_title', 'ftc_template_loop_thumb_list', 999);
				}
			}

			ftc_restore_product_hooks_shortcode();

			$woocommerce_loop['columns'] = $old_woocommerce_loop_columns;
			
			return '<div class="woocommerce">' . ob_get_clean() . '</div>';
		}
	}
	add_shortcode('ftc_product_deals_slider', 'ftc_product_deals_slider_shortcode');

	if( !function_exists('ftc_product_deals_thumbnail_filter') ){
		function ftc_product_deals_thumbnail_filter(){
			return 'ftc_product_deal_thumb';
		}
	}

	if( !function_exists('ftc_template_loop_thumb_list') ){
		function ftc_template_loop_thumb_list(){
			global $product, $smof_data;
			$prod_galleries = $product->get_gallery_image_ids();

			$num_product_gallery = (isset($smof_data['ftc_product_gallery_number']) && (int)$smof_data['ftc_product_gallery_number'] > 0)?(int)$smof_data['ftc_product_gallery_number']-1:2;
			if( $num_product_gallery > count($prod_galleries) ){
				$num_product_gallery = count($prod_galleries);
			}
			if( !is_array($prod_galleries) || ( is_array($prod_galleries) && count($prod_galleries) == 0 ) ){
				$has_list_image = false;
			}

			if( wp_is_mobile() ){
				$has_list_image = false;
			}

			$image_size = apply_filters('ftc_loop_product_thumbnail', 'shop_catalog');
//                var_dump($image_size);
			$image_thumb_size = apply_filters('single_product_small_thumbnail_size', 'shop_thumbnail');


			$dimensions = wc_get_image_size( $image_size );

			$dimensions_thumb = wc_get_image_size( $image_thumb_size );

			$front_img_src_thumb = '';
			if( has_post_thumbnail( $product->get_id() ) ){
				$post_thumbnail_id = get_post_thumbnail_id($product->get_id());
				$image_obj = wp_get_attachment_image_src($post_thumbnail_id, $image_size, 0);
				$image_obj_thumb = wp_get_attachment_image_src($post_thumbnail_id, $image_thumb_size, 0);
				if( isset($image_obj_thumb[0]) ){
					$front_img_src_thumb = $image_obj_thumb[0];
				}
				if( isset($image_obj[0]) ){
					$front_img_src = $image_obj[0];
				}
				$alt = trim(strip_tags( get_post_meta($post_thumbnail_id, '_wp_attachment_image_alt', true) ));
			}
			else if( wc_placeholder_img_src() ){
				$front_img_src_thumb = wc_placeholder_img_src();
			}
			echo '<div class="thum_list_image"><ul>';
			echo '<li><img src="'.esc_url($front_img_src_thumb).'" data-hover="'.esc_url($front_img_src).'" class="ftc_thumb_list_hover ftc-lazy-load" alt="'.esc_attr($alt).'" width="'.$dimensions['width'].'" height="'.$dimensions['height'].'" />';
			for ((int)$i = 0; $i < $num_product_gallery; $i++) {
				$list_img_src = '';
				$alt = '';
				$image_obj = wp_get_attachment_image_src($prod_galleries[$i], $image_thumb_size, 0);
				$image_obj_hover = wp_get_attachment_image_src($prod_galleries[$i], $image_size, 0);
				if( isset($image_obj[0]) ){
					$list_img_src = $image_obj[0];
					$list_img_src_hover = $image_obj_hover[0];

					$alt = trim(strip_tags( get_post_meta($prod_galleries[$i], '_wp_attachment_image_alt', true) ));
				}
				else if( wc_placeholder_img_src() ){
					$list_img_src = wc_placeholder_img_src();
				}

				echo '<li><img src="'.esc_url($list_img_src).'" data-hover="'.esc_url($list_img_src_hover).'" class="ftc_thumb_list_hover ftc-lazy-load" alt="'.esc_attr($alt).'" width="'.$dimensions_thumb['width'].'" height="'.$dimensions_thumb['height'].'" /></li>';
			}
			echo '</ul></div>';
		}
	}

	if( !function_exists('ftc_template_loop_time_deals') ){
		function ftc_template_loop_time_deals(){
			global $product;
			$date_to = '';
			if( $product->get_type() == 'variable' ){
				$children = $product->get_children();
				if( is_array($children) && count($children) > 0 ){
					foreach( $children as $children_id ){
						$date_to = get_post_meta($children_id, '_sale_price_dates_to', true);
						if( $date_to != '' ){
							break;
						}
					}
				}
			}
			else{
				$date_to = get_post_meta($product->get_id(), '_sale_price_dates_to', true);
			}

			if( $date_to == '' ){
				return;
			}

			$current_time = current_time('timestamp');
			$delta = $date_to - $current_time;
			?>
			<div class="ftc-timer-circles" data-timer="<?php echo esc_attr($delta) ?>"></div>
			<?php
		}
	}

	if( !function_exists('ftc_list_of_product_categories_shortcode') ){
		function ftc_list_of_product_categories_shortcode( $atts ){
			extract(shortcode_atts(array(
				'title' 				=> ''
				,'bg_image'				=> ''
				,'product_cat'			=> ''
				,'include_parent'		=> 1
				,'limit_sub_cat'		=> 6
				,'include_cats'			=> ''
				), $atts));

			if( empty($product_cat) && empty($include_cats) ){
				return;
			}

			ob_start();

			$bg_image_url = wp_get_attachment_url( $bg_image );

			$list_categories = array();

			if( !empty($product_cat) ){
				$list_categories = get_terms('product_cat', array('child_of' => $product_cat, 'number' => $limit_sub_cat));
			}
			else if( !empty($include_cats) ){
				$include_parent = false;
				$include_cats = array_map('trim', explode(',', $include_cats));
				$list_categories = get_terms('product_cat', array('include' => $include_cats, 'orderby' => 'none'));
			}
			?>
			<div class="ftc-list-of-product-categories-wrapper ftc-shortcode" style="background-image: url(<?php echo esc_url($bg_image_url); ?>)">
				<?php if( $title ): ?>
					<h3 class="title_sub"><?php echo esc_html($title) ?></h3>
				<?php endif; ?>
				<ul class="list-categories">
					<?php 
					if( $include_parent ){
						$parent_obj = get_term($product_cat, 'product_cat');
						if( !is_wp_error($parent_obj) && $parent_obj != null ){
							?>
							<li><a href="<?php echo get_term_link($parent_obj, 'product_cat'); ?>"><?php echo esc_html($parent_obj->name) ?></a></li>
							<?php 
						}
					}
					if( !empty($list_categories) && is_array($list_categories) ){
						foreach( $list_categories as $cat ){
							?>
							<li><a href="<?php echo get_term_link($cat, 'product_cat'); ?>"><?php echo esc_html($cat->name) ?></a></li>
							<?php
						}
					}
					?>
				</ul>
			</div>
			<?php
			return ob_get_clean();
		}	
	}
	add_shortcode('ftc_list_of_product_categories', 'ftc_list_of_product_categories_shortcode');


	/* Product in category tabs 2 */
	if( !function_exists('ftc_products_category_tabs_shortcode') ){
		function ftc_products_category_tabs_shortcode($atts, $content){

			extract(shortcode_atts(array(
				'product_type'					=> 'recent'
				,'custom_order'					=> 0
				,'orderby'						=> 'none'
				,'order'						=> 'DESC'
				,'bg_color'						=> '#f7f6f4'
				,'columns' 						=> 3
				,'per_page' 					=> 6
				,'product_cats'					=> ''
				,'parent_cat'					=> ''
				,'include_children'				=> 0
				,'active_tab'					=> 1
				,'show_image' 					=> 1
				,'show_title' 					=> 1
				,'show_sku' 					=> 0
				,'show_price' 					=> 1
				,'show_short_desc'  			=> 0
				,'show_rating' 					=> 1
				,'show_label' 					=> 1	
				,'show_categories'				=> 0	
				,'show_add_to_cart' 			=> 1
				,'show_counter'					=> 1
				,'is_slider' 					=> 0
				,'rows' 						=> 1
				,'show_nav' 					=> 0
				,'auto_play' 					=> 1
				), $atts));
			if ( !class_exists('WooCommerce') ){
				return;
			}

			if( empty($product_cats) && empty($parent_cat) ){
				return;
			}

			if( empty($product_cats) ){
				$sub_cats = get_terms('product_cat', array('parent' => $parent_cat, 'fields' => 'ids', 'orderby' => 'none'));
				if( is_array($sub_cats) && !empty($sub_cats) ){
					$product_cats = implode(',', $sub_cats);
				}
				else{
					return;
				}
			}

			$atts = compact('product_type', 'custom_order', 'orderby', 'order', 'columns', 'rows', 'per_page', 'product_cats', 'include_children', 'active_tab'
				,'show_image', 'show_title', 'show_sku', 'show_price', 'show_short_desc', 'show_rating', 'show_label'
				,'show_categories', 'show_add_to_cart', 'show_counter', 'is_slider', 'show_nav', 'auto_play');

			$product_cats = array_map('trim', explode(',', $product_cats));

			$classes = array();
			$classes[] = 'ftc-products-category-tabs-block ftc-shortcode ftc-product';
			$classes[] = $product_type;

			$classes[] = 'count-'.count($product_cats);

			if( $is_slider ){
				$classes[] = 'has-slider';
			}

			$rand_id = 'ftc-products-category-tabs-'.rand();
			$selector = '#'.$rand_id;

			$inline_style = '<style type="text/css" scoped>';
			$inline_style .= $selector.'{background-color:'.$bg_color.'}';
			$inline_style .= $selector.' ul.tabs li.current,'.$selector.' ul.tabs li:hover{background-color:'.$bg_color.'}';
			$inline_style .= $selector.' ul.tabs li.current:after,'.$selector.' ul.tabs li:hover:after{border-bottom-color:'.$bg_color.'}';
			$inline_style .= $selector.' .row-content.loading:before{background-color:'.$bg_color.'}';
			foreach( $product_cats as $k => $product_cat ){
				$border_top_color = get_term_meta((int) $product_cat, 'shortcode_border_top_color', true);
				if( !empty($border_top_color) ){
					$inline_style .= $selector.' ul.tabs li.current.product-cat-'.$product_cat.':before,'.$selector.' ul.tabs li.product-cat-'.$product_cat.':hover:before{border-top-color:'.$border_top_color.'}';
				}
			}
			$inline_style .= '</style>';
			ob_start();

			?>

			<div class="<?php echo esc_attr(implode(' ', $classes)); ?>" id="<?php echo esc_attr($rand_id) ?>" data-atts="<?php echo htmlentities(json_encode($atts)); ?>">

				<div class="row-tabs">
					<ul class="tabs">
						<?php 
						foreach( $product_cats as $k => $product_cat ):
							$term = get_term_by('term_id', $product_cat, 'product_cat');
						if( !isset($term->name) ){
							continue;
						}
						$has_icon = false;
						$icon_id = get_term_meta($term->term_id, 'shortcode_icon_id', true);
						if( !empty($icon_id) ){
							$has_icon = true;
						}
						?>
						<li class="tab-item <?php echo ($has_icon)?'has-icon':'no-icon'; ?> product-cat-<?php echo esc_attr($product_cat) ?>" data-product_cat="<?php echo esc_attr($product_cat) ?>">
							<span class="icon"><?php echo ($has_icon)?wp_get_attachment_image($icon_id):''; ?></span>
							<span class="title"><?php echo esc_html($term->name) ?></span>
						</li>
						<?php
						endforeach;
						?>
					</ul>
				</div>
				<div class="row-content">

				</div>
			</div>
			<?php

			return ob_get_clean();
		}	
	}
	add_shortcode('ftc_products_category_tabs', 'ftc_products_category_tabs_shortcode');

	add_action('wp_ajax_ftc_get_product_content_in_category_tab_2', 'ftc_get_product_content_in_category_tab_2');
	add_action('wp_ajax_nopriv_ftc_get_product_content_in_category_tab_2', 'ftc_get_product_content_in_category_tab_2');
	if( !function_exists('ftc_get_product_content_in_category_tab_2') ){
		function ftc_get_product_content_in_category_tab_2( $atts ){
			if( empty($_POST['atts']) || empty($_POST['product_cat']) ){
				die('0');
			}
			$atts = $_POST['atts'];
			$product_cat = $_POST['product_cat'];

			ob_start();
			extract($atts);

			$options = array(
				'show_image'		=> $show_image
				,'show_label'		=> $show_label
				,'show_title'		=> $show_title
				,'show_sku'			=> $show_sku
				,'show_price'		=> $show_price
				,'show_short_desc'	=> $show_short_desc
				,'show_categories'	=> $show_categories
				,'show_rating'		=> $show_rating
				,'show_add_to_cart'	=> $show_add_to_cart
				);
			ftc_remove_product_hooks_shortcode( $options );

			if( $show_counter ){
				add_action('ftc_after_shop_loop_item', 'ftc_template_loop_time_deals', 10);
			}

			add_filter('ftc_display_add_to_cart_button_on_thumbnail', '__return_false', 10);

			$args = array(
				'post_type'				=> 'product'
				,'post_status' 			=> 'publish'
				,'ignore_sticky_posts'	=> 1
				,'posts_per_page' 		=> $per_page
				,'orderby' 				=> 'date'
				,'order' 				=> 'desc'
				,'meta_query' 			=> WC()->query->get_meta_query()
				,'tax_query'           	=> WC()->query->get_tax_query()
				);
			if( $custom_order ){
				$args['orderby'] = $orderby;
				$args['order'] = $order;
			}
			else{
				ftc_filter_product_by_product_type($args, $product_type);
			}

			$args['tax_query'] = array(
				array(
					'taxonomy' => 'product_cat'
					,'terms' => explode(',', $product_cat)
					,'field' => 'term_id'
					,'include_children' => $include_children
					)
				);


			global $woocommerce_loop;
			if( (int)$columns <= 0 ){
				$columns = 3;
			}
			$old_woocommerce_loop_columns = $woocommerce_loop['columns'];
			$woocommerce_loop['columns'] = $columns;	

			if( $product_type == 'top_rated' && !$custom_order ){
				add_filter('posts_clauses', array(WC()->query, 'order_by_rating_post_clauses'));
			}

			$products = new WP_Query( $args );	

			if( $product_type == 'top_rated' && !$custom_order ){
				remove_filter('posts_clauses', array(WC()->query, 'order_by_rating_post_clauses'));
			}

			$count = 0;

			echo '<div class="column-products woocommerce columns-'.esc_attr($columns).'">';
			woocommerce_product_loop_start();
			if( $products->have_posts() ){	

				if( isset($products->post_count) && $products->post_count == 0 ){
					echo '<div class="hidden flag-no-product"></div>';
				}

				while( $products->have_posts() ){ 
					$products->the_post();

					if( $is_slider && $count % $rows == 0 ){
						echo '<div class="ftc-products">';
					}

					wc_get_template_part( 'content', 'product' );

					if( $is_slider && ($count % $rows == $rows - 1 || $count == $products->post_count - 1) ){
						echo '</div>';
					}
					$count++;
				}

			}
			woocommerce_product_loop_end();
			echo '</div>';
			?>
			<div class="column-banners">
				<figure>
					<?php 
					$banner_id = get_term_meta((int) $product_cat, 'shortcode_banner_id', true);
					if( !empty($banner_id) ){
						$link = get_term_link((int) $product_cat, 'product_cat');
						echo '<a href="'.( is_string($link)? esc_url($link): 'javascript: void(0)' ).'">';
						echo wp_get_attachment_image($banner_id, 'full');
						echo '</a>';
					}
					else{
						echo '<div class="hidden flag-no-banner"></div>';
					}
					?>
				</figure>
			</div>
			<?php

			wp_reset_postdata();

			/* restore hooks */
			ftc_restore_product_hooks_shortcode();

			if( $show_counter ){
				remove_action('ftc_after_shop_loop_item', 'ftc_template_loop_time_deals', 10);
			}

			remove_filter('ftc_display_add_to_cart_button_on_thumbnail', '__return_false', 10);

			$woocommerce_loop['columns'] = $old_woocommerce_loop_columns;

			die(ob_get_clean());
		}
	}
        /* Shortcode Video - Support Youtube and Vimeo video */
if( !function_exists('ftc_video_shortcode') ){
	function ftc_video_shortcode($atts){
		extract( shortcode_atts(array(
			'src' 		=> '',
			'height' 	=> '450',
			'width' 	=> '800'
		), $atts
	));
		if( $src == '' ){
			return;
		}

		$extra_class = '';
		if( !isset($atts['height']) || !isset($atts['width']) ){
			$extra_class = 'auto-size';
		}

		$src = ftc_parse_video_link($src);
		ob_start();
		?>
		<div class="ftc-video <?php echo esc_attr($extra_class); ?>" style="width:<?php echo esc_attr($width) ?>px; height:<?php echo esc_attr($height) ?>px;">
			<iframe width="<?php echo esc_attr($width) ?>" height="<?php echo esc_attr($height) ?>" src="<?php echo esc_url($src); ?>" allowfullscreen></iframe>
		</div>
		<?php
		return ob_get_clean();
	}
}
add_shortcode('ftc_video', 'ftc_video_shortcode');

if( !function_exists('ftc_parse_video_link') ){
	function ftc_parse_video_link( $video_url ){
		if( strstr($video_url, 'youtube.com') || strstr($video_url, 'youtu.be') ){
			preg_match('%(?:youtube\.com/(?:user/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $video_url, $match);
			if( count($match) >= 2 ){
				return '//www.youtube.com/embed/' . $match[1];
			}
		}
		elseif( strstr($video_url, 'vimeo.com') ){
			preg_match('~^http://(?:www\.)?vimeo\.com/(?:clip:)?(\d+)~', $video_url, $match);
			if( count($match) >= 2 ){
				return '//player.vimeo.com/video/' . $match[1];
			}
			else{
				$video_id = explode('/', $video_url);
				if( is_array($video_id) && !empty($video_id) ){
					$video_id = $video_id[count($video_id) - 1];
					return '//player.vimeo.com/video/' . $video_id;
				}
			}
		}
		return $video_url;
	}
}
/* Shortcode SoundCloud */
if( !function_exists('ftc_soundcloud_shortocde') ){
	function ftc_soundcloud_shortocde( $atts, $content ){
		extract(shortcode_atts(array(
			'params'		=> "color=ff5500&auto_play=false&hide_related=false&show_comments=true&show_user=true&show_reposts=false"
			,'url'			=> ''
			,'width'		=> '100%'
			,'height'		=> '166'
			,'iframe'		=> 1
		),$atts));

		$atts = compact( 'params', 'url', 'width', 'height', 'iframe' );

		if( $iframe ){
			return ftc_soundcloud_iframe_widget( $atts );
		}
		else{ 
			return ftc_soundcloud_flash_widget( $atts );
		}
	}
}
add_shortcode('ftc_soundcloud','ftc_soundcloud_shortocde');


function ftc_soundcloud_iframe_widget($options) {
	$url = 'https://w.soundcloud.com/player/?url=' . $options['url'] . '&' . $options['params'];
	$unique_class = 'ftc-soundcloud-'.rand();
	$style = '.'.$unique_class.' iframe{width: '.$options['width'].'; height:'.$options['height'].'px;}';
	$style = '<style type="text/css" scoped>'.$style.'</style>';
	return '<div class="ftc-soundcloud '.$unique_class.'">'.$style.'<iframe src="'.esc_url( $url ).'"></iframe></div>';
}

function ftc_soundcloud_flash_widget( $options ){
	$url = 'https://player.soundcloud.com/player.swf?url=' . $options['url'] . '&' . $options['params'];

	return preg_replace('/\s\s+/', '', sprintf('<div class="ftc-soundcloud"><object width="%s" height="%s">
		<param name="movie" value="%s"></param>
		<param name="allowscriptaccess" value="always"></param>
		<embed width="%s" height="%s" src="%s" allowscriptaccess="always" type="application/x-shockwave-flash"></embed>
		</object></div>', $options['width'], $options['height'], esc_url( $url ), $options['width'], $options['height'], esc_url( $url )));
}
	?>
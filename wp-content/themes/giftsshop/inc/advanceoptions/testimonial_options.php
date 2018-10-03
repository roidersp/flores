<?php 
$options = array();

$options[] = array(
				'id'		=> 'gravatar_email'
				,'label'	=> esc_html__('Gravatar Email Address', 'giftsshop')
				,'desc'		=> esc_html__('Enter in an e-mail address, to use a Gravatar, instead of using the "Featured Image". You have to remove the "Featured Image".', 'giftsshop')
				,'type'		=> 'text'
			);
			
$options[] = array(
				'id'		=> 'byline'
				,'label'	=> esc_html__('Byline', 'giftsshop')
				,'desc'		=> esc_html__('Enter a byline for the customer giving this testimonial (for example: "CEO of ThemeFTC").', 'giftsshop')
				,'type'		=> 'text'
			);
			
$options[] = array(
				'id'		=> 'url'
				,'label'	=> esc_html__('URL', 'giftsshop')
				,'desc'		=> esc_html__('Enter a URL that applies to this customer (for example: http://themeftc.com/).', 'giftsshop')
				,'type'		=> 'text'
			);
			
$options[] = array(
				'id'		=> 'rating'
				,'label'	=> esc_html__('Rating', 'giftsshop')
				,'desc'		=> ''
				,'type'		=> 'select'
				,'options'	=> array(
						'-1'	=> esc_html__('no rating', 'giftsshop')
						,'0'	=> esc_html__('0 star', 'giftsshop')
						,'0.5'	=> esc_html__('0.5 star', 'giftsshop')
						,'1'	=> esc_html__('1 star', 'giftsshop')
						,'1.5'	=> esc_html__('1.5 star', 'giftsshop')
						,'2'	=> esc_html__('2 stars', 'giftsshop')
						,'2.5'	=> esc_html__('2.5 stars', 'giftsshop')
						,'3'	=> esc_html__('3 stars', 'giftsshop')
						,'3.5'	=> esc_html__('3.5 stars', 'giftsshop')
						,'4'	=> esc_html__('4 stars', 'giftsshop')
						,'4.5'	=> esc_html__('4.5 stars', 'giftsshop')
						,'5'	=> esc_html__('5 stars', 'giftsshop')
				)
			);
?>
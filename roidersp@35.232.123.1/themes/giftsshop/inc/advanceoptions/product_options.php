<?php 
$options = array();
global $ftc_default_sidebars;
$sidebar_options = array(
				'0'	=> esc_html__('Default', 'giftsshop')
				);
foreach( $ftc_default_sidebars as $key => $_sidebar ){
	$sidebar_options[$_sidebar['id']] = $_sidebar['name'];
}

$options[] = array(
				'id'		=> 'prod_layout_heading'
				,'label'	=> esc_html__('Product Layout', 'giftsshop')
				,'desc'		=> ''
				,'type'		=> 'heading'
			);
			
$options[] = array(
				'id'		=> 'prod_layout'
				,'label'	=> esc_html__('Product Layout', 'giftsshop')
				,'desc'		=> ''
				,'type'		=> 'select'
				,'options'	=> array(
									'0'			=> esc_html__('Default', 'giftsshop')
									,'0-1-0'  	=> esc_html__('Fullwidth', 'giftsshop')
									,'1-1-0' 	=> esc_html__('Left Sidebar', 'giftsshop')
									,'0-1-1' 	=> esc_html__('Right Sidebar', 'giftsshop')
									,'1-1-1' 	=> esc_html__('Left & Right Sidebar', 'giftsshop')
								)
			);
			
$options[] = array(
				'id'		=> 'prod_left_sidebar'
				,'label'	=> esc_html__('Left Sidebar', 'giftsshop')
				,'desc'		=> ''
				,'type'		=> 'select'
				,'options'	=> $sidebar_options
			);
			
$options[] = array(
				'id'		=> 'prod_right_sidebar'
				,'label'	=> esc_html__('Right Sidebar', 'giftsshop')
				,'desc'		=> ''
				,'type'		=> 'select'
				,'options'	=> $sidebar_options
			);

$options[] = array(
				'id'		=> 'prod_custom_tab_heading'
				,'label'	=> esc_html__('Custom Tab', 'giftsshop')
				,'desc'		=> ''
				,'type'		=> 'heading'
			);
			
$options[] = array(
				'id'		=> 'prod_custom_tab'
				,'label'	=> esc_html__('Custom Tab', 'giftsshop')
				,'desc'		=> ''
				,'type'		=> 'select'
				,'options'	=> array(
									'0'		=> esc_html__('Default', 'giftsshop')
									,'1'	=> esc_html__('Override', 'giftsshop')
								)
			);
			
$options[] = array(
				'id'		=> 'prod_custom_tab_title'
				,'label'	=> esc_html__('Custom Tab Title', 'giftsshop')
				,'desc'		=> ''
				,'type'		=> 'text'
			);
			
$options[] = array(
				'id'		=> 'prod_custom_tab_content'
				,'label'	=> esc_html__('Custom Tab Content', 'giftsshop')
				,'desc'		=> ''
				,'type'		=> 'textarea'
			);

$options[] = array(
	'id'		=> 'prod_size_chart_heading'
	,'label'	=> esc_html__('Product Size Chart', 'giftsshop')
	,'desc'		=> ''
	,'type'		=> 'heading'
			);

$options[] = array(
	'id'		=> 'prod_size_chart'
	,'label'	=> esc_html__('Size Chart Image', 'giftsshop')
	,'desc'		=> esc_html__('You can upload size chart image for all product on Theme Option', 'giftsshop')
	,'type'		=> 'upload'
			);

$options[] = array(
				'id'		=> 'prod_breadcrumb_heading'
				,'label'	=> esc_html__('Breadcrumbs', 'giftsshop')
				,'desc'		=> ''
				,'type'		=> 'heading'
			);

$options[] = array(
				'id'		=> 'bg_breadcrumbs'
				,'label'	=> esc_html__('Breadcrumb Background Image', 'giftsshop')
				,'desc'		=> ''
				,'type'		=> 'upload'
			);
			
$options[] = array(
				'id'		=> 'prod_video_heading'
				,'label'	=> esc_html__('Video', 'giftsshop')
				,'desc'		=> ''
				,'type'		=> 'heading'
			);

$options[] = array(
				'id'		=> 'prod_video_url'
				,'label'	=> esc_html__('Video URL', 'giftsshop')
				,'desc'		=> esc_html__('Enter Youtube or Vimeo video URL', 'giftsshop')
				,'type'		=> 'text'
			);		
?>
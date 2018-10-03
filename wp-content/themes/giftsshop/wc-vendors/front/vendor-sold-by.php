<?php 
/*
 * The template for displaying the vendor sold by on the shop loop
 *
 * Override this template by copying it to yourtheme/wc-vendors/front/
 *
 * @package    WCVendors
 * @version    2.0.0
 * 		
 * Template Variables available 
 *  
 * $vendor_id  : current vendor id for customization 
 * $sold_by_label : sold by label 
 * $sold_by : sold by 
 *
 *
 */
?>

<div class="wcvendors_sold_by_in_loop"><?php echo apply_filters('wcvendors_sold_by_in_loop', $sold_by_label ); ?> <?php echo $sold_by; ?></div>
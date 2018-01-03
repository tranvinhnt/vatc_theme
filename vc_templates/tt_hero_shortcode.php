<?php
/*
 * Templatation.com
 *
 * Hero block box shortcode for VC
 *
 */

$imgrepeat = $sepatator_icon_fa = $ins_separator = $block_link = $block_padding_top = $block_padding_bottom = $min_height = $el_class = $ins_button = $button_link = $heading = $subheading = $image = $highlight_text = $enable_parallax = $color = '';
$imgsize = 'auto';
$imgpos = 'center center';
$highlight_chk = false;
$text_appear = 'dark';
$yoast_bdcmp = false;

$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );
/*vc_icon_element_fonts_enqueue( $type );*/


$imgg = array(); if( !empty($image) ) $imgg = wp_get_attachment_image_src( $image, 'full' );
if( !$imgg && !empty($image) ) { $imgg[0] = $image; } // if user entered URL of an external image.

// generate padding
$block_padding_top="50";
$block_padding_bottom="15";
if($el_class == 'type2') { $block_padding_top="54"; $block_padding_bottom="30"; }
$block_padding_top = !empty($block_padding_top) ? 'padding-top:'.$block_padding_top.'px;' : '';
$block_padding_bottom = !empty($block_padding_bottom) ? 'padding-bottom:'.$block_padding_bottom.'px;' : '';
$block_padding = $block_padding_top.$block_padding_bottom;

$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ), $this->settings['base'], $atts );
?>

<section <?php if(!isset($imgg[0]) ) echo 'style="'.esc_attr($block_padding).'"'; ?> class="sub-bnr tt-herosc <?php echo esc_attr($text_appear). ' '.esc_attr($el_class).' '.esc_attr($css_class); ?>" data-stellar-background-ratio="0.5" >
		<?php if(isset($imgg[0]) ) { ?>
		<div class="blog-banner-bg <?php if( $enable_parallax ) echo 'tt-parallax' ?>" style="
		<?php if( $imgpos != 'center center' ) echo 'background-position: '. $imgpos.';'; ?>
		<?php if( $imgsize != 'auto' ) echo 'background-size: '. $imgsize.';'; ?>
		<?php if( $imgrepeat != 'repeat' ) echo 'background-repeat: '. $imgrepeat.';'; ?>
		background-image: url(<?php echo esc_url($imgg[0]); ?>); <?php echo esc_attr($block_padding); ?>;height: 390px;
		">
		<?php } ?>
		<div class="overlay-clr" style="<?php echo 'background-color: '. $color.';'; ?>"></div>

		<div class="heroheading">
		  <div class="container">
		    <?php if(!empty($heading) ) { ?><h1><?php echo htmlspecialchars_decode($heading); ?></h1><?php } ?>
			<?php if(!empty($highlight_text) ) { ?>
			<div class="description">
				<?php echo wpautop(do_shortcode(htmlspecialchars_decode($content))); ?>
			</div>
		    <?php }
			// Breadcrumb
			 if ( $yoast_bdcmp && function_exists('yoast_breadcrumb') ) {
				 yoast_breadcrumb('<div class="breadcrumb">','</div>');
			 }
			?>
		  </div>
		</div>
	</div>
</section>

<?php
/*
 * Templatation.com
 *
 * Infobox shortcode for VC
 *
 */
$background_color = $background_style = $subtitle = $icon_br = $title_tag = $custom_icon = $title_weight = $title = $title_text_transform = $img_bdr_cust_color = $subheading = $insert_graphic = $title_link = $ins_button = $button_text = $button_link = $button_link_target = $icon_link = $el_class = '';
$alignment = 'left'; $title_link_target = 'self'; //TODO
$ins_button = false; $text_appear = 'dark';
$content_color = $title_color = $icon_color = $show_link = $link = $type_info = $box_bg = $icon_bg = $bor_bg = '';
$add_title = $add_text = $add_image = $add_btn_1 = $add_btn_2 = $add_btn_link_1 = $add_btn_link_2 = $add_align = '';
$atts = vc_map_get_attributes( $this->getShortcode(), $atts );
extract( $atts );

$prefix = 'pre_';
$rand = rand(9, 999).'_';
$uid = uniqid($prefix.$rand);

$img_add = (is_numeric($add_image) && !empty($add_image)) ? wp_get_attachment_url($add_image) : '';

// This code is from VC plugin
// For images

$default_src = vc_asset_url( 'vc/no_image.png' );
$img_style = ( '' !== $img_style ) ? $img_style : '';
$img_border_color = ( '' !== $img_border_color ) ? ' vc_box_border_' . $img_border_color : '';

$img_id = preg_replace( '/[^\d]/', '', $image );

switch ( $source ) {
	case 'media_library':
		$img = wpb_getImageBySize( array(
			'attach_id' => $img_id,
			'thumb_size' => $img_size,
			'class' => 'vc_single_image-img',
		) );

		break;

	case 'external_link':
		$dimensions = vcExtractDimensions( $img_size );
		$hwstring = $dimensions ? image_hwstring( $dimensions[0], $dimensions[1] ) : '';

		$custom_src = $custom_src ? esc_attr( $custom_src ) : $default_src;

		$img = array(
			'thumbnail' => '<img class="vc_single_image-img" ' . $hwstring . ' src="' . $custom_src . '" />',
		);
		break;

	default:
		$img = false;
}

if ( ! $img ) {
	$img['thumbnail'] = '<img class="vc_single_image-img" src="' . $default_src . '" />';
}

$wrapperClass = 'vc_single_image-wrapper ' . $img_style . ' ' . $img_border_color;
$img_link = vc_gitem_create_link( $atts, $wrapperClass );
if( !empty( $img_border_cust_color ))
	$img_bdr_cust_color = 'style="background-color:' . esc_attr( $img_border_cust_color ) . ' !important";';

$image_string = ! empty( $img_link ) ? '<' . $img_link . '>' . $img['thumbnail'] . '</a>' : '<div ' . $img_bdr_cust_color . ' class="' . $wrapperClass . '"> ' . $img['thumbnail'] . ' </div>';

// For icons
// Enqueue needed icon font.
vc_icon_element_fonts_enqueue( $type );
//$icon_link = vc_gitem_create_link( $atts, 'vc_icon_element-link' );

$has_style = false;
if ( strlen( $background_style ) > 0 ) {
	$has_style = true;
	if ( false !== strpos( $background_style, 'outline' ) ) {
		$background_style .= ' vc_icon_element-outline'; // if we use outline style it is border in css
	} else {
		$background_style .= ' vc_icon_element-background';
	}
}

$style = '';
if ( 'custom' === $background_color ) {
	if ( false !== strpos( $background_style, 'outline' ) ) {
		$style = 'border-color:' . $custom_background_color;
	} else {
		$style = 'background-color:' . $custom_background_color;
	}
}
$style = $style ? 'style="' . esc_attr( $style ) . '"' : '';

?>
<!-- end of code from VC -->

<?php
$align = ($alignment == 'left') ? 'textleft' : ($alignment == 'right' ? 'textright' : '' );
if( empty($title_tag) || $title_tag == 'default' ) $title_tag = 'h4';
$title_tag = ($title_tag == 'div') ? 'div' : $title_tag ;
$title_tag = esc_attr( $title_tag );
$title = esc_attr( $title );
$title = empty( $title_link ) ? $title : ('<a href='. esc_url( $title_link ) .' target=_'. $title_link_target .'>'. $title .'</a>');

$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ), $this->settings['base'], $atts );

?>


<div class="best-services">
	<div class="list">


<div data-content="#<?php print esc_html($uid); ?>"  style="<?php if( !empty($box_bg)) print 'background-color: '. esc_html($box_bg).';'; ?>" class="services tt-sf-about-icon-box-sc single-welcome <?php  print esc_attr( $title_weight ). ' '. esc_attr( $type_info ). ' '. esc_attr( $css_class ). ' '. esc_attr( $el_class ). ' ' . esc_attr( $text_appear ). ' ' . esc_attr( $alignment ). ' '. esc_attr( $title_text_transform ). ' size-'. esc_attr( $title_tag ); ?>">
	<!-- image settings -->
	<article class="thumb">
		<?php if($show_link == 'yes') { ?>
		<a class="button colio-link" href="#">
			<?php } ?>
		<?php
	if( ! (strpos($insert_graphic, 'image') === false) ) { ?>
		<div class="wpb_single_image">
			<figure class="wpb_wrapper vc_figure">
				<?php print $image_string ?>
			</figure>
		</div>
	<?php } ?>
	<div class="customText">
	<!-- icon settings -->
	<?php if( ! (strpos($insert_graphic, 'icon' ) === false) ) { ?>

	<?php if(!empty( $custom_icon ) ) {
		print '<div class="icon"><i class="'.$custom_icon.' "></i></div>';
	}else { ?>
			<div style="<?php if( !empty($icon_br)) print 'border:1px solid '. esc_html($icon_br).';'; ?><?php if( !empty($bor_bg)) print 'border-color: '. esc_html($bor_bg).';'; ?><?php if( !empty($icon_bg)) print 'background-color: '. esc_html($icon_bg).';'; ?>" class="icon-box vc_icon_element-inner vc_icon_element-color-<?php print esc_attr( $icon_color ); ?> <?php if ( $has_style ) { print 'vc_icon_element-have-style-inner'; } ?> vc_icon_element-size-<?php print esc_attr( $size ); ?>  vc_icon_element-style-<?php print esc_attr( $background_style ); ?> vc_icon_element-background-color-<?php print esc_attr( $background_color ); ?>" <?php print $style ?>>
				<i style="<?php if( !empty($icon_color)) print 'color: '. esc_html($icon_color).';'; ?>"
					class="vc_icon_element-icon <?php print esc_attr( ${'icon_' . $type} ); ?>" <?php print( 'custom' === $icon_color ? 'style="color:' . esc_attr( $icon_custom_color ) . ' !important"' : '' ); ?>></i><?php
				if ( strlen( $icon_link ) > 0 ) {
					print '<' . $icon_link . '></a>';
				}
				?> </div>
		<?php } ?>
	<?php } ?>
	<div class="icon-box-title" style="<?php if( !empty($title_color)) print 'color: '. esc_html($title_color).';'; ?>">
		<?php print '<'. $title_tag .' class="info_title" >' . $title .  '</'. $title_tag .'>' ?>
	</div>

	<?php if( ! empty( $content )) { ?>
		<div  class="p-wrapp">
			<?php print wpautop(do_shortcode(htmlspecialchars_decode($content))); ?>
		</div>
	<?php } ?>
			<?php if($show_link == 'yes') { ?>
		</a>
	<?php } ?>
	</div>
	</article>
</div>
	</div>
</div>
<?php if($show_link == 'yes') { ?>
<!-- Analytics Tab Open -->
<div id="<?php print esc_html($uid); ?>" class="colio-content">
	<div class="main">
		<div class="container">
			<div class="inside-colio">
				<?php if($add_align == 'left') { ?>
				<div class="row">
					<div class="col-md-8">
						<!-- Heading -->
						<div class="heading text-left margin-bottom-40">
							<?php if (!empty($add_title)) { ?>
								<h4><?php print esc_html($add_title); ?></h4>
							<?php } ?>
						</div>
						<?php if (!empty($add_text)) { ?>
							<p><?php print do_shortcode($add_text); ?></p>
						<?php } ?>
						<?php if (!empty($add_btn_1)) { ?>
							<a href="<?php print esc_html($add_btn_link_1); ?>" class="btn btn-1 margin-top-30 margin-right-20"><?php print esc_html($add_btn_1); ?> <i class="fa fa-caret-right"></i></a>
						<?php } ?>
						<?php if (!empty($add_btn_2)) { ?>
						<a href="<?php print esc_html($add_btn_link_2); ?>" class="btn btn-1 margin-top-30"><?php print esc_html($add_btn_2); ?><i class="fa fa-caret-right"></i></a>
						<?php } ?>
					</div>
					<div class="col-md-4 text-right"> <img class="img-responsive" src="<?php print esc_html($img_add); ?>" alt=""> </div>
				</div>
				<?php } ?>
				<?php if($add_align == 'right') { ?>
					<div class="row">
						<div class="col-md-4 text-right"> <img class="img-responsive" src="<?php print esc_html($img_add); ?>" alt=""> </div>
						<div class="col-md-8">
							<!-- Heading -->
							<div class="heading text-left margin-bottom-40">
								<?php if (!empty($add_title)) { ?>
									<h4><?php print esc_html($add_title); ?></h4>
								<?php } ?>
							</div>
							<?php if (!empty($add_text)) { ?>
								<p><?php print do_shortcode($add_text); ?></p>
							<?php } ?>
							<?php if (!empty($add_btn_1)) { ?>
								<a href="<?php print esc_html($add_btn_link_1); ?>" class="btn btn-1 margin-top-30 margin-right-20"><?php print esc_html($add_btn_1); ?> <i class="fa fa-caret-right"></i></a>
							<?php } ?>
							<?php if (!empty($add_btn_2)) { ?>
							<a href="<?php print esc_html($add_btn_link_2); ?>" class="btn btn-1 margin-top-30"><?php print esc_html($add_btn_2); ?><i class="fa fa-caret-right"></i></a>
						<?php } ?>
						</div>
					</div>
				<?php } ?>
			</div>
		</div>
	</div>
</div>
<?php } ?>


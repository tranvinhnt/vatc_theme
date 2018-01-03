<?php
/*
 * Templatation.com
 *
 * Block with image and effect shortcode for VC
 *
 */
$name =  $link = $el_class  = $image = '';

$atts = vc_map_get_attributes($this->getShortcode(), $atts);
extract($atts);
$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ), $this->settings['base'], $atts );

$img = (is_numeric($image) && !empty($image)) ? wp_get_attachment_url($image) : '';

?>

<?php if(!empty($link)) { ?>
<div class="broc  <?php print esc_html($css_class); ?> <?php print esc_html($el_class); ?>">
    <img class="img-responsive" src="<?php print do_shortcode($img); ?>" alt="">
    <!--a href="<?php print esc_html($link); ?>" class="icon-down" download><i class="fa fa-download"></i></a-->
    <a href="<?php print esc_html($link); ?>" class="icon-file" download><i class="fa fa-file-pdf-o"></i>
        <?php print esc_html($name); ?></a>
</div>
<?php } ?>





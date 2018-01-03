<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * Footer Template
 *
 * Here we setup all logic and XHTML that is required for the footer section of all screens.
 *
 * @package ttFramework
 * @subpackage Template
 */
	$total = iodtheme_fw_get_option('footer_sidebars', '3');
?>
	<footer id="footer-wrap">

<?php
	if ( ( is_active_sidebar( 'footer-1' ) ||
		   is_active_sidebar( 'footer-2' ) ||
		   is_active_sidebar( 'footer-3' ) ||
		   is_active_sidebar( 'footer-4' ) ) && $total > 0 ) {
		   $BTcols = 4;
		   if ( $total == 4) $BTcols = 3; if ( $total == 3) $BTcols = 4; if ( $total == 2) $BTcols = 6; if ( $total == 1) $BTcols = 12;

?>
    <div class="container">
        <div class="row">

		<?php $i = 0; while ( $i < $total ) { $i++; ?>
			<?php if ( is_active_sidebar( 'footer-' . $i ) ) { ?>

			<div class="col-md-<?php print esc_attr($BTcols); ?> col-sm-6 footer-widget-<?php print esc_attr($i); ?>">
	            <?php dynamic_sidebar( 'footer-' . $i ); ?>
			</div>

	        <?php } ?>
		<?php } // End WHILE Loop ?>

		</div>
    </div>
	<?php } // End IF Statement ?>
	</footer><!--/.footer-wrap-->

	<!-- RIGHTS -->
	<div class="rights">
	<div class="container">
	  <div class="row">
	    <div class="col-md-6">
					<?php if( iodtheme_fw_get_option('footer_left', 1) ) {
							echo do_shortcode( wp_kses(iodtheme_fw_get_option('footer_left_text'), iodtheme_tt_allowed_tags()));
						} else {
							esc_html_e( 'Developed by', 'iodtheme' ); ?> <a href="http://templatation.com">Templatation</a>
					<?php } ?>
	    </div>
	    <div class="col-md-6 text-right">
					<?php if( iodtheme_fw_get_option('footer_menu', 0) ) {
						if ( function_exists( 'has_nav_menu' ) && has_nav_menu( 'footer-menu' ) ) {
						    wp_nav_menu( array( 'depth'          => 1,
						                        'sort_column'    => 'menu_order',
						                        'container'      => 'ul',
						                        'menu_class'     => 'navigation',
						                        'theme_location' => 'footer-menu'
						    ) );
						} else {
							print "Please assign footer menu in wp-admin->Appearance->Menus";
						}
					} elseif ( iodtheme_fw_get_option('footer_right', 1) ) {
						echo do_shortcode( wp_kses(iodtheme_fw_get_option('footer_right_text'), iodtheme_tt_allowed_tags()));
					} else {
					    bloginfo(); ?> &copy; <?php print date( 'Y' ) . esc_html_e( 'All Rights Reserved.', 'iodtheme' );
					} ?>
	    </div>
	  </div>
	</div>
	</div>

</div><!-- Wrap -->
<a href="#" class="scrollup"></a>
<?php wp_footer(); ?>

</body>
</html>

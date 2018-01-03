<?php
if ( ! defined( 'ABSPATH' ) ) exit;

/*-----------------------------------------------------------------------------------
 *
 * This file contains required functions for the theme.
 * @templatation.com
 *
-----------------------------------------------------------------------------------*/

add_action( 'wp_head', 'iodtheme_tt_wp_head', 9 );
if ( ! function_exists( 'iodtheme_tt_wp_head' ) ) {
/**
 * Output the default ttFramework "head" markup in the "head" section.
 * @since  2.0.0
 * @return void
 */
function iodtheme_tt_wp_head() {
	do_action( 'iodtheme_tt_wp_head_before' );

	// Output custom favicon icons
	if ( function_exists( 'iodtheme_tt_custom_favicon' ) && ! function_exists( 'wp_site_icon' ) )
		iodtheme_tt_custom_favicon();
	// Output custom styles to Head.
	if ( function_exists( 'iodtheme_tt_custom_styling' ) )
		iodtheme_tt_custom_styling();

	do_action( 'iodtheme_tt_wp_head_after' );
} // End iodtheme_tt_wp_head()
}

/*-----------------------------------------------------------------------------------*/
/* tt_get_dynamic_value() */
/* Replace values in a provided array with theme options, if available. */
/*
/* $settings array should resemble: $settings = array( 'theme_option_without_tt' => 'default_value' );
/*
/* @since 4.4.4 */
/*-----------------------------------------------------------------------------------*/
if( !function_exists( 'iodtheme_fw_opt_values' )) {
	function iodtheme_fw_opt_values( $settings ) {
		global $tt_temptt_opt;

		if ( is_array( $tt_temptt_opt ) ) {
			foreach ( $settings as $k => $v ) {

				if ( is_array( $v ) ) {
					foreach ( $v as $k1 => $v1 ) {
						if ( isset( $tt_temptt_opt[ 'tt_' . $k ][ $k1 ] ) && ( $tt_temptt_opt[ 'tt_' . $k ][ $k1 ] != '' ) ) {
							$settings[ $k ][ $k1 ] = $tt_temptt_opt[ 'tt_' . $k ][ $k1 ];
						}
					}
				} else {
					if ( isset( $tt_temptt_opt[ 'tt_' . $k ] ) && ( $tt_temptt_opt[ 'tt_' . $k ] != '' ) ) {
						$settings[ $k ] = $tt_temptt_opt[ 'tt_' . $k ];
					}
				}
			}
		}

		return $settings;
	} // End iodtheme_fw_opt_values()
}


/*-----------------------------------------------------------------------------------*/
/* iodtheme_fw_get_option() */
/* Replace values in a provided variable with theme options, if available. */
/*
 * field id should be without tt_
 */
/* TT @since 6.0 */
/*-----------------------------------------------------------------------------------*/
if ( ! function_exists( 'iodtheme_fw_get_option' ) ) {
	function iodtheme_fw_get_option( $var, $default = false ) {
		global $tt_temptt_opt;

		if ( isset( $tt_temptt_opt[ 'tt_' . $var ] ) ) {
			$var = $tt_temptt_opt[ 'tt_' . $var ];
		} else {
			$var = $default;
		}

		return $var;
	} // End iodtheme_fw_get_option()
}

/*-----------------------------------------------------------------------------------*/
/* Post/page title
/*-----------------------------------------------------------------------------------*/
// returns title based on the requirement.

if (!function_exists( 'iodtheme_fw_post_title')) {
function iodtheme_fw_post_title( $tag='' ){

	global $wp_query;
	$tt_post_id = $single_item_layout = $tt_lay_content = $tt_lay_sidebar = $single_data2 = '';
	if( empty($tag)) $tag = 'h3';

		if ( ! is_404() && ! is_search() ) {
			if ( ! empty( $wp_query->post->ID ) ) {
				$tt_post_id = $wp_query->post->ID;
			}
		}
		if ( ! empty( $tt_post_id ) ) {
			$single_data2 = get_post_meta( $tt_post_id, '_tt_meta_page_opt', true );
		}
		if ( is_array( $single_data2 ) ) {
			if ( !empty( $single_data2['_hide_title_display'] ) ) {
				return '';
			}
		}

	return '<div class="single-bolg-title"><'.$tag. ' class="ml-title">'. get_the_title() .'</'.$tag.'></div>';
}
}

/*-----------------------------------------------------------------------------------*/
/* Add a class to body_class if fullwidth slider to appear. */
/*-----------------------------------------------------------------------------------*/

add_filter( 'body_class','iodtheme_tt_body_class', 10 );// Add layout to body_class output

if ( ! function_exists( 'iodtheme_tt_body_class' ) ) {
function iodtheme_tt_body_class( $classes ) {

	global $wp_query;
	$current_page_template = $single_data2 = $tt_post_id = $single_enable_headline = '';

	//setting up defaults.
	$settings6 = iodtheme_fw_opt_values( array(  'enable_sticky' => '1',
												'img_corner'    => '1',
											 ) );

	if ( !is_404() && !is_search() ) {
		if ( ! empty( $wp_query->post->ID ) ) {
			$tt_post_id = $wp_query->post->ID;
		}
	}
	$single_data2 = get_post_meta( $tt_post_id, '_tt_meta_page_opt', true );
	if( is_array($single_data2) ) {
		if ( isset( $single_data2['_single_enable_headline'] ) ) $single_enable_headline = $single_data2['_single_enable_headline'];
		if ( isset( $single_data2['_single_enable_bpadding'] ) ) $single_enable_bpadding = $single_data2['_single_enable_bpadding'];
		if ( isset( $single_data2['_single_enable_tpadding'] ) ) $single_enable_tpadding = $single_data2['_single_enable_tpadding'];
	}
	// setting defaults
	if ( !is_page() && (!isset($single_enable_headline) || empty($single_enable_headline)) ) $single_enable_headline = false;
	if ( !isset($single_enable_bpadding) ) $single_enable_bpadding = true;
	if ( !isset($single_enable_tpadding) ) $single_enable_tpadding = true;

	// fetching which page template is being used to render current post/page.
	if ( !empty($tt_post_id) ) { $current_page_template = get_post_meta($tt_post_id, '_wp_page_template', true); }
	if ( is_singular()) { $classes[] = 'tt-single'; } // add a custom class if single item is displayed except blog template is being used, for styling purpose.
	if ( $single_enable_headline ) { $classes[] = 'hdline_set'; }
	if ( $settings6['enable_sticky'] ) { $classes[] = 'header-sticky'; }
	if ( ! $single_enable_bpadding && ! is_page('blog') ) { $classes[] = 'no-bpadd'; }
	if ( ! $single_enable_tpadding && ! is_page('blog') ) { $classes[] = 'no-tpadd'; }

	$classes[] = 'no-ttfmwrk'; // this class will be removed by templatation framework plugin when its active.
	return $classes;
  }
}

/*-----------------------------------------------------------------------------------*/
/* Load custom logo. */
/*-----------------------------------------------------------------------------------*/
if ( ! function_exists( 'iodtheme_tt_logo' ) ) {
function iodtheme_tt_logo () {
	global $tt_temptt_opt;
	$width = $height = $style = $logo = '';
	$settingsl = array(
					'retina_favicon' => '0',
					'texttitle' => '0',
					'logo' => '',
					'retina_logo_wh' => '',
				);
	$settingsl = iodtheme_fw_opt_values( $settingsl );

	if ( $settingsl['texttitle'] == '1' ) return; // Get out if we're not displaying the logo.

	$logo = esc_url( IODTHEME_FW_THEME_DIRURI . 'images/logo.png' );
	if ( isset( $tt_temptt_opt['tt_logo']['url'] ) && $tt_temptt_opt['tt_logo']['url'] != '' ) { $logo = $tt_temptt_opt['tt_logo']['url'] ; }
	if ( is_ssl() ) { $logo = str_replace( 'http://', 'https://', $logo ); }
?>

	<a class="logo" href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'description' ) ); ?>">
	 <?php if ( '0' == $settingsl['retina_favicon'] ) { ?>
		<img src="<?php echo esc_url( $logo ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" />
	 <?php } else {
		if( !empty($settingsl['retina_logo_wh']['width']) ) {
			if (strpos($settingsl['retina_logo_wh']['width'],'px') !== false) {
				$width = 'width:'.$settingsl['retina_logo_wh']['width'].';';
			}
			else{
				$width = 'width:'.$settingsl['retina_logo_wh']['width'].'px;';
			}
		}
		if( !empty($settingsl['retina_logo_wh']['height']) ) {
			if (strpos($settingsl['retina_logo_wh']['height'],'px') !== false) {
				$height = 'height:'.$settingsl['retina_logo_wh']['height'].';';
			}
			else{
				$height = 'height:'.$settingsl['retina_logo_wh']['height'].'px;';
			}
		}

		if ( !empty($width) ) $style .= $width;
		if ( !empty($height) ) $style .= $height ;
		if ( !empty($style) ) $style = 'style="'.$style.'"';
		?>
		<img src="<?php echo esc_url( $logo ); ?>" <?php echo wp_strip_all_tags( $style ); ?> alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" />
	 <?php } ?>
	</a>
<?php
} // End iodtheme_tt_logo()
}

/*-----------------------------------------------------------------------------------*/
/* Header classes. */
/*-----------------------------------------------------------------------------------*/
if ( ! function_exists( 'iodtheme_fw_hdr_class' ) ) {
function iodtheme_fw_hdr_class () {
	global $wp_query; $tt_post_id = $single_data2 = '';
	$class = '';
	if ( !is_404() && !is_search() ) {
		if ( ! empty( $wp_query->post->ID ) ) {
			$tt_post_id = $wp_query->post->ID;
		}
	}
	if( isset($tt_post_id) ) $single_data2 = get_post_meta( $tt_post_id, '_tt_meta_page_opt', true );
	if( is_array($single_data2) ) {
		// coming soon
	}
	$class .= ' hdr ';
	if ( iodtheme_fw_get_option( 'enable_sticky', 1 )) $class .= ' sticky ';

	return $class;

} // End iodtheme_fw_hdr_class()
}




/*-----------------------------------------------------------------------------------*/
/* Topnav function */
/*-----------------------------------------------------------------------------------*/
if ( ! function_exists( 'iodtheme_topnav_content' ) ) {
function iodtheme_topnav_content () {

	if ( iodtheme_fw_get_option( 'enable_topbar', 0 ) == '0' ) return; // do nothing if top nav bar is disabled in themeoptions.

	// Fetch left side content
	$top_nav_left_layout = iodtheme_fw_get_option('top_nav_left_layout', '');
	$nav_left_layout = $top_nav_left_layout['enabled'];

	// Fetch right side content
	$top_nav_right_layout = iodtheme_fw_get_option('top_nav_right_layout', '');
	$nav_right_layout = $top_nav_right_layout['enabled'];

$output = '
<section class="top-info hidden-xs">
	<div class="container">';

$output .= '
	<!-- Start left side content -->
	<div class="left-content">';
	$output .= iodtheme_topnav_content_render( $nav_left_layout );
	$output .=
	'</div><!-- .left-content -->';

$output .= '
	<!-- Start right side content -->
	<div class="right-content">';
	$output .= iodtheme_topnav_content_render( $nav_right_layout );
	$output .=
	'</div><!-- .right-content -->';

$output .= '
	</div>
</section>';

	$output = apply_filters('iodtheme_fw_topnav_content_before_output', $output);

	return  $output;
} // End iodtheme_topnav_content()
}


/*-----------------------------------------------------------------------------------*/
/* Fetch ALT tags for images
/*-----------------------------------------------------------------------------------*/
// returns title based on the requirement.

if (!function_exists( 'iodtheme_fw_img_alt')) {
function iodtheme_fw_img_alt( $imgid = '', $postid = '' ){
$alt = '';
if( '' == $imgid && '' != $postid ) // if only post id is given, fetch imgid from it.
$imgid = get_post_thumbnail_id( $postid );

if($imgid) $alt = get_post_meta( $imgid, '_wp_attachment_image_alt', true);

return htmlspecialchars($alt);

}
}


/*-----------------------------------------------------------------------------------*/
/* Topnav render function */
/*-----------------------------------------------------------------------------------*/
if ( ! function_exists( 'iodtheme_topnav_content_render' ) ) {
function iodtheme_topnav_content_render ( $nav_layout = null ) {
	global $tt_temptt_opt;
	$output =  "";
	$topsettings = array(
					'enable_topbar'         => '1',
					'ttext_icon'            => '',
					'ttext_text'            => '',
					'contact_number'        => '',
					'contactform_email'     => '',
					'top_nav_menu'          => '',
					'top_nav_email_icon'    => '1',
					'top_nav_phone_icon'    => '1',
					);

	$topsettings = iodtheme_fw_opt_values( $topsettings );

ob_start();

// available content blocks.
// email, text_icon, text, phone_icon, phone, wpml_lang, search, social

if ($nav_layout): foreach ($nav_layout as $key=>$value) {

    switch($key) {

        case 'email':

			if ( !empty( $topsettings['contactform_email'] )) { ?>
			<span>
				<a href="mailto:<?php echo sanitize_email($topsettings['contactform_email']); ?>"><i class="fa fa-envelope-o"></i><?php echo sanitize_email($topsettings['contactform_email']); ?></a>
			</span>
			<?php }


        break;

        case 'teaser_text':

			if ( $topsettings['ttext_text'] != '' ) { echo '<span class="tt-top-teaser">'. stripslashes( esc_attr( $topsettings['ttext_text'] )) .'</span>';  }

        break;

        case 'phone':

			if ( !empty( $topsettings['contact_number'] )) { ?>
			<span>
				<a href="tel:<?php echo preg_replace('/(\W*)/', '', $topsettings['contact_number']); ?>"><i class="fa fa-phone"></i><?php echo esc_attr($topsettings['contact_number']); ?></a>
			</span>
			<?php }

        break;

        case 'wpml_lang':

						do_action('icl_language_selector');

        break;

        case 'custommenu':

		            if ( !empty( $topsettings['top_nav_menu'] ) ) {
		                wp_nav_menu( array( 'depth'          => 1,
		                                    'sort_column'    => 'menu_order',
		                                    'container'      => 'ul',
		                                    'menu_class'     => 'top-nav sup-nav',
		                                    'menu'        => $topsettings['top_nav_menu'],
		                ) );
		            }

        break;

        case 'social':
					?>
					<div class="social-icons">
						<?php get_template_part( 'inc/templates/tt-social' ); ?>
					</div>
					<?php
        break;

    }

}

endif;

	$output = ob_get_clean();
	return $output;

} //iodtheme_topnav_content_render
}

/*-----------------------------------------------------------------------------------*/
/* Load 404 Page banner image. */
/*-----------------------------------------------------------------------------------*/
if ( ! function_exists( 'tt_error_page' ) ) {
function tt_error_page() {
	global $tt_temptt_opt;
	$error_page = '';
	$settingsl = array(
					'error_page' => '',
				);

	$settingsl = iodtheme_fw_opt_values( $settingsl );
	$error_page = esc_url( IODTHEME_FW_THEME_DIRURI . 'images/breadcrumb-bg.jpg' );
	if ( isset( $tt_temptt_opt['tt_error_page']['url'] ) && $tt_temptt_opt['tt_error_page']['url'] != '' ) { $error_page = $tt_temptt_opt['tt_error_page']['url'] ; }
	if ( is_ssl() ) { $error_page = str_replace( 'http://', 'https://', $error_page ); }
?>
	
		<img src="<?php echo esc_url( $error_page ); ?>" alt="error_img" style="width: 100%; height: auto;" />

<?php
} // End iodtheme_tt_logo()
}

/*-----------------------------------------------------------------------------------*/
/* return excerpt with given charlent.                                               */
/*-----------------------------------------------------------------------------------*/
// source https://codex.wordpress.org/Function_Reference/get_the_excerpt
if (!function_exists( 'iodtheme_tt_excerpt_charlength')) {
	function iodtheme_tt_excerpt_charlength( $charlength ) {
		$excerpt = get_the_excerpt();
		$charlength ++;

		if ( mb_strlen( $excerpt ) > $charlength ) {
			$subex   = mb_substr( $excerpt, 0, $charlength - 5 );
			$exwords = explode( ' ', $subex );
			$excut   = - ( mb_strlen( $exwords[ count( $exwords ) - 1 ] ) );
			if ( $excut < 0 ) {
				echo mb_substr( $subex, 0, $excut );
			} else {
				echo esc_attr($subex);
			}
			echo '...';
		} else {
			echo esc_attr($excerpt);
		}
	}
}
/*-----------------------------------------------------------------------------------*/
/* Templatation , render featured post thumb function.                               */
/*-----------------------------------------------------------------------------------*/
if ( !function_exists( 'iodtheme_tt_post_thumb') ) {
	function iodtheme_tt_post_thumb( $width = null, $height = null, $lightbox = true, $crop = true, $srconly = false, $id = '' ) {
		global $tt_temptt_opt, $post;
		if( empty($id) ) $id = get_the_ID();
		if( empty($id) ) return ''; // no id, nothing left to do.
		$output = $single_w = $single_h = $featuredimg = '';

		$featuredimg =  get_post_thumbnail_id($id);
		$featuredimg = wp_get_attachment_image_src( $featuredimg, 'full' );
		$featuredimg = $featuredimg[0];

		if( !isset( $featuredimg ) || empty ( $featuredimg ) ) return ''; // no image found, return.

		// if width and height is not given, we dont need to resize so output the full image.
		if( empty( $width ) || empty ( $height ) ) {

			if( $srconly ) { $returnimg = wp_get_attachment_image_src( get_post_thumbnail_id($id), 'full' ); $returnimg = $returnimg[0]; }
			else $returnimg = get_the_post_thumbnail($id, 'full');

			return $returnimg;
		}

		$featuredimg = iodtheme_tt_aq_resize( $featuredimg, $width, $height, $crop, false ); // this returns an array if image was cropped, and url otherwise.

		if( is_array( $featuredimg )) {
			$output = '
				<a href="'.$featuredimg[0].'">
						<img width="'.$featuredimg[1].'" height="'.$featuredimg[2].'" class="img-responsive" alt="" src="'.$featuredimg[0].'">
				</a>
			';
			if ( !$lightbox ) $output = '<img width="'.$featuredimg[1].'" height="'.$featuredimg[2].'" class="img-responsive" alt="" src="'.$featuredimg[0].'">'; // if no lightbox needed, turn the link off.
		}
		else $output = '<img class="img-responsive" alt="" src="'.$featuredimg.'">'; // image was not cropped so AQ returned URL.

		if( $srconly )  $output = isset($featuredimg[0]) ? $featuredimg[0] : $featuredimg;
		return $output;
	}
}

/*-----------------------------------------------------------------------------------*/
/* Misc small functions for visuals.                                                 */
/*-----------------------------------------------------------------------------------*/
// Adding span tag in category widgets for styling purpose
add_filter('wp_list_categories', 'iodtheme_tt_cat_count');
function iodtheme_tt_cat_count($links) {
  $links = str_replace('</a> (', ' <span>(', $links);
  $links = str_replace(')', ')</span></a>', $links);
  return $links;
}


// adding filter to add class in previous/next post button.
add_filter('next_post_link', 'iodtheme_tt_post_link_attr');
add_filter('previous_post_link', 'iodtheme_tt_post_link_attr');
function iodtheme_tt_post_link_attr($output) {
    $injection = 'class="button"';
    return str_replace('<a href=', '<a '.$injection.' href=', $output);
}

// modifying search form.
function iodtheme_tt_search_form( $form ) {
    $form = '<form method="get" class="searchform search-form" action="' . esc_url(home_url( '/' )) . '" >
    <div><label class="screen-reader-text">' . esc_html__( 'Search for:', 'iodtheme' ) . '</label>
    <input class="search-field" type="text" value="' . get_search_query() . '" name="s" placeholder="'. esc_html__( 'Search&hellip;', 'iodtheme' ) .'"  />
    <input type="submit" class="searchsubmit"  value="'. esc_attr__( 'Go', 'iodtheme' ) .'" />
    </div>
    </form>';

    return $form;
}

add_filter( 'get_search_form', 'iodtheme_tt_search_form', 100 );
/*-----------------------------------------------------------------------------------*/
/* Breadcrumb display */
/*-----------------------------------------------------------------------------------*/
// use Yoast

/*-----------------------------------------------------------------------------------*/
/* Comment Form Fields */
/*-----------------------------------------------------------------------------------*/

	add_filter( 'comment_form_default_fields', 'iodtheme_tt_cmntform_fields' );

	if ( ! function_exists( 'iodtheme_tt_cmntform_fields' ) ) {
		function iodtheme_tt_cmntform_fields ( $fields ) {

			$commenter = wp_get_current_commenter();

			$required_text = ' <span class="required">(' . esc_html__( 'Required', 'iodtheme' ) . ')</span>';

			$req = get_option( 'require_name_email' );
			$aria_req = ( $req ? " aria-required='true'" : '' );
			$fields =  array(
				'author' => '<p class="comment-form-author">' .
							'<input id="author" placeholder="'.esc_html__( "Name", 'iodtheme'  ) . ( $req ? '*' : '' ).'" class="txt" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30"' . $aria_req . ' />' .
							'</p>',
				'email'  => '<p class="comment-form-email">' .
				            '<input id="email" class="txt" name="email" placeholder="' . esc_html__( 'Email', 'iodtheme'  ) . ( $req ? '*' : '' ) . '" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30"' . $aria_req . ' />' .
				            '</p>',
				'url'    => '<p class="comment-form-url">' .
				            '<input id="url" class="txt" name="url" placeholder="' . esc_html__( 'Website', 'iodtheme'  ) . '" type="text" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" />' .
				            '</p>',
			);

			return $fields;

		} // End iodtheme_tt_cmntform_fields()
	}

/*-----------------------------------------------------------------------------------*/
/* Comment Form Settings */
/*-----------------------------------------------------------------------------------*/

	add_filter( 'comment_form_defaults', 'iodtheme_tt_cmntform_settings' );

	if ( ! function_exists( 'iodtheme_tt_cmntform_settings' ) ) {
		function iodtheme_tt_cmntform_settings ( $settings ) {

			$settings['comment_notes_before'] = '';
			$settings['comment_notes_after'] = '';
			$settings['label_submit'] = esc_html__( 'Submit Comment', 'iodtheme' );
			$settings['cancel_reply_link'] = esc_html__( 'Click here to cancel reply.', 'iodtheme' );
  // redefine your own textarea (the comment body)
        $settings['comment_field']  = '<p class="comment-form-comment"><textarea id="comment" name="comment" aria-required="true" placeholder="' . _x( 'Comment', 'noun', 'iodtheme' ) . '"></textarea></p>';
			return $settings;

		} // End iodtheme_tt_cmntform_settings()
	}

/*-----------------------------------------------------------------------------------*/
/**
 * iodtheme_tt_archive_desc()
 *
 * Display a description, if available, for the archive being viewed (category, tag, other taxonomy).
 *
 * @since V1.0.0
 * @uses do_atomic(), get_queried_object(), term_description()
 * @echo string
 * @filter iodtheme_tt_archive_desc
 */

if ( ! function_exists( 'iodtheme_tt_archive_desc' ) ) {
	function iodtheme_tt_archive_desc ( $echo = true ) {
		do_action( 'iodtheme_tt_archive_desc' );
		$description = '';
		// Archive Description, if one is available.
		$term_obj = get_queried_object();
		if(is_array($term_obj))
		$description = term_description( $term_obj->term_id, $term_obj->taxonomy );

		if ( $description != '' ) {
			// Allow child themes/plugins to filter here ( 1: text in DIV and paragraph, 2: term object )
			$description = apply_filters( 'iodtheme_tt_archive_desc', '<div class="archive-description">' . $description . '</div><!--/.archive-description-->', $term_obj );
		}

		if ( $echo != true ) { return $description; }

		echo esc_attr($description);
	} // End iodtheme_tt_archive_desc()
}


/*-----------------------------------------------------------------------------------*/
/* Check if WooCommerce is activated */
/*-----------------------------------------------------------------------------------*/
if ( ! function_exists( 'iodtheme_tt_is_wc' ) ) {
	function iodtheme_tt_is_wc() {
		if ( class_exists( 'woocommerce' ) ) { return true; } else { return false; }
	}
}

/*-----------------------------------------------------------------------------------*/
/* Truncate */
/*-----------------------------------------------------------------------------------*/
if ( ! function_exists( 'iodtheme_tt_truncate' ) ) {
	function iodtheme_tt_truncate($text, $limit, $sep='...') {
		if (str_word_count($text, 0) > $limit) {
			$words = str_word_count($text, 2);
			$pos = array_keys($words);
			$text = strip_tags( $text );
			$text = substr($text, 0, $pos[$limit]) . $sep;
		}
		return $text;
	}
}


/*-----------------------------------------------------------------------------------*/
/* Fixing the font size for the tag cloud widget.                                    */
/*-----------------------------------------------------------------------------------*/
add_filter( 'widget_tag_cloud_args', 'iodtheme_tt_tag_cloud_args' );
if (!function_exists( 'iodtheme_tt_tag_cloud_args')) {
	function iodtheme_tt_tag_cloud_args($args) {
	$args['number'] = 10; //adding a 0 will display all tags
	$args['largest'] = 13; //largest tag
	$args['smallest'] = 13; //smallest tag
	$args['unit'] = 'px'; //tag font unit
	$args['format'] = 'list'; //ul with a class of wp-tag-cloud
	return $args;
	}
}

/*-----------------------------------------------------------------------------------*/
/**
 * Filters wp_title to print a neat <title> tag based on what is being viewed.
 *
 * @param string $title Default title text for current view.
 * @param string $sep Optional separator.
 * @return string The filtered title.
 */
function iodtheme_tt_wp_title( $title, $sep ) {
	if ( is_feed() ) {
		return $title;
	}

	global $page, $paged;
	// Add the blog name
	$title .= $sep." ".get_bloginfo( 'name', 'display' );

	// Add the blog description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) ) {
		$title .= " $sep $site_description";
	}

	// Add a page number if necessary: , commenting cause below results in an warning.
/*	if ( ( $paged >= 2 || $page >= 2 ) && ! is_404() ) {
		$title .= " $sep " . sprintf( esc_html__( 'Page %s', '_s' ), max( $paged, $page ) );
	}*/

	return $title;
}
//add_filter( 'wp_title', 'iodtheme_tt_wp_title', 10, 2 );



/*-----------------------------------------------------------------------------------*/
/* Function for Adding Retina support, thanks to C.bavota                            */
/*-----------------------------------------------------------------------------------*/
add_filter( 'wp_generate_attachment_metadata', 'iodtheme_tt_retina_attchmt_meta', 10, 2 );
/**
 * Retina images
 *
 * This function is attached to the 'wp_generate_attachment_metadata' filter hook.
 */
if ( ! function_exists( 'iodtheme_tt_retina_attchmt_meta' ) ) {
function iodtheme_tt_retina_attchmt_meta( $metadata, $attachment_id ) {
    foreach ( $metadata as $key => $value ) {
        if ( is_array( $value ) ) {
            foreach ( $value as $image => $attr ) {
                if ( is_array( $attr ) )
                    iodtheme_tt_retina_create_images( get_attached_file( $attachment_id ), $attr['width'], $attr['height'], true );
            }
        }
    }

    return $metadata;
}
}
/**
 * Create retina-ready images
 *
 * Referenced via iodtheme_tt_retina_attchmt_meta().
 */
if ( ! function_exists( 'iodtheme_tt_retina_create_images' ) ) {
function iodtheme_tt_retina_create_images( $file, $width, $height, $crop = false ) {
    if ( $width || $height ) {
        $resized_file = wp_get_image_editor( $file );
        if ( ! is_wp_error( $resized_file ) ) {
            $filename = $resized_file->generate_filename( $width . 'x' . $height . '@2x' );
 
            $resized_file->resize( $width * 2, $height * 2, $crop );
            $resized_file->save( $filename );
 
            $info = $resized_file->get_size();
 
            return array(
                'file' => wp_basename( $filename ),
                'width' => $info['width'],
                'height' => $info['height'],
            );
        }
    }
    return false;
}
}
add_filter( 'delete_attachment', 'iodtheme_tt_delete_retina_images' );
/**
 * Delete retina-ready images
 *
 * This function is attached to the 'delete_attachment' filter hook.
 */
if ( ! function_exists( 'iodtheme_tt_delete_retina_images' ) ) {
function iodtheme_tt_delete_retina_images( $attachment_id ) {
    $meta = wp_get_attachment_metadata( $attachment_id );
    $upload_dir = wp_upload_dir();
	if (isset($meta["file"])) {
		$path = pathinfo( $meta["file"] );
		foreach ( $meta as $key => $value ) {
			if ( "sizes" === $key ) {
				foreach ( $value as $sizes => $size ) {
					$original_filename = $upload_dir['basedir'] . '/' . $path['dirname'] . '/' . $size['file'];
					$retina_filename = substr_replace( $original_filename, '@2x.', strrpos( $original_filename, '.' ), strlen( '.' ) );
					if ( file_exists( $retina_filename ) )
						unlink( $retina_filename );
				}
			}
		}
	}
}
}


/*-----------------------------------------------------------------------------------*/
/* iodtheme_tt_prev_post function */
/*-----------------------------------------------------------------------------------*/
if ( ! function_exists( 'iodtheme_tt_prev_post' ) ) {
	function iodtheme_tt_prev_post() {
		$output    = '';
		$prev_post = get_adjacent_post( true, '', true );
		if ( is_a( $prev_post, 'WP_Post' ) ) {
			$output = '<div class="fl button3"><a class="tt_prev_post tt-button" title="'. get_the_title( $prev_post->ID ) .'" href="' . get_permalink( $prev_post->ID ) . '"><i class="fa fa-long-arrow-left"></i>Tin cũ hơn</a></div>';
		}

		return $output;
	} // End iodtheme_tt_prev_post()
}
/*-----------------------------------------------------------------------------------*/
/* iodtheme_tt_next_post function */
/*-----------------------------------------------------------------------------------*/
if ( ! function_exists( 'iodtheme_tt_next_post' ) ) {
	function iodtheme_tt_next_post() {
		$output    = '';
		$prev_post = get_adjacent_post( true, '', false );
		if ( is_a( $prev_post, 'WP_Post' ) ) {
			$output = '<div class="fr button3"><a class="tt_next_post tt-button" title="'. get_the_title( $prev_post->ID ) .'" href="' . get_permalink( $prev_post->ID ) . '">Tin mới hơn<i class="fa fa-long-arrow-right"></i></a></div>';
		}

		return $output;
	} // End iodtheme_tt_next_post()
}

/*-----------------------------------------------------------------------------------*/
/* iodtheme_tt_post_title function */
/*-----------------------------------------------------------------------------------*/
if ( ! function_exists( 'iodtheme_tt_post_title' ) ) {
function iodtheme_tt_post_title ( ) {

	$hide_title_display = $tt_page_title = $titlesettings = ''; $id = get_the_ID();
	$hide_title_display = get_post_meta( $id, '_tt_meta_page_opt', true );
	if( isset($hide_title_display['_hide_title_display'])) $titlesettings = $hide_title_display['_hide_title_display'];

	ob_start(); ?>
		<header class="post-header lc_tt_title">
			<ul>
				<li><?php the_time( 'M j, Y' ); ?></li>
				<li><?php if(!comments_open($id)) echo 'Comments Off'; else comments_popup_link( esc_html__( 'Zero comments', 'iodtheme' ), esc_html__( '1 Comment', 'iodtheme' ), esc_html__( '% Comments', 'iodtheme' ) ); ?></li>
			</ul>
			<h1><?php if( strlen( get_the_title()) > 1 ) { ?> <a href="<?php the_permalink(); ?>" title="<?php esc_attr_e( 'Continue Reading &rarr;', 'iodtheme' ); ?>"><?php the_title(); ?></a> <?php } ?></h1>
		</header>
		<?php

	$tt_post_title = ob_get_clean();
	if( is_singular() ){
		if( empty($titlesettings) || $titlesettings == '0' ) {
			echo esc_attr($tt_post_title);
		} // display title if not being hidden in single post.
	}
	else { echo esc_attr($tt_post_title); }
	} // End iodtheme_tt_post_title()
}

/*-----------------------------------------------------------------------------------*/
/* iodtheme_tt_page_title function */
/*-----------------------------------------------------------------------------------*/
if ( ! function_exists( 'iodtheme_tt_page_title' ) ) {
function iodtheme_tt_page_title ( ) {

	$hide_title_display = $tt_page_title = $titlesettings = '';
	$hide_title_display = get_post_meta( get_the_ID(), '_tt_meta_page_opt', true );
	if( isset($hide_title_display['_hide_title_display'])) $titlesettings = $hide_title_display['_hide_title_display'];

	ob_start();
	if ( empty($titlesettings) || $titlesettings == '0' ) { ?>
		<header class="post-header">
			<h1><?php if( strlen( get_the_title()) > 1 ) the_title(); ?></h1>
		</header>
		<?php
	} // display title if not being hidden in single page/post.

	$tt_page_title = ob_get_clean();
	echo esc_attr($tt_page_title);

	} // End iodtheme_tt_page_title()
}


function iodtheme_tt_lc_search_sc() {
	global $post;
	$id = get_the_ID();
	$show_fake = false;

	if ( get_post_type( $id ) == 'tt_misc' ) {
		$show_fake = true;
	}

	if ( $show_fake ) {
		return '<div class="dslc-notification dslc-red">Search Form appears here.</div>';
	}
	else {

		ob_start();
		get_search_form();

		$output = ob_get_clean();
		if ( get_post_type( $id ) == 'tt_misc' ) {
			$output = '[tt_lc_search] Shortcode';
		}

		return $output;
	}
}


function iodtheme_tt_lc_title(){
	global $tt_temptt_opt, $post;
	$post_id = get_the_ID();
	$show_fake = true;

	$hide_title_display = $titlesettings = "";
	if ( is_singular() ) {
		$show_fake = false;
	}

	if ( get_post_type( $post_id ) == 'dslc_templates' ) {
		$show_fake = true;
	}

	if ( $show_fake ) {
		return '<div class="dslc-notification dslc-red">Post Title Appears Here.</div>';
	}
	else {
		$hide_title_display = get_post_meta( $post_id, '_tt_meta_post_opt', true );
		if ( is_array( $hide_title_display ) ) {
			$titlesettings = $hide_title_display['_hide_title_display'];
		}
		if ( ! isset( $titlesettings ) || $titlesettings == '0' ) {
			ob_start(); ?>
			<header class="post-header lc_tt_title">
				<ul>
					<li><?php the_time( 'M j, Y' ); ?></li>
					<li><?php comments_popup_link( esc_html__( 'Zero comments', 'iodtheme' ), esc_html__( '1 Comment', 'iodtheme' ), esc_html__( '% Comments', 'iodtheme' ) ); ?></li>
				</ul>
				<h1><?php the_title(); ?></h1>
			</header>
		<?php } // display title if not being hidden in single page/post.
		$output = ob_get_clean();

		return $output;
	}
}

function iodtheme_tt_tt_numComments() {
	$tt_numComments = get_comments_number(); // get_comments_number returns only a numeric value

	if ( $tt_numComments == 0 ) {
		$comments = esc_html__('No comments yet.', 'iodtheme');
	} elseif ( $tt_numComments > 1 ) {
		$comments = $num_comments . esc_html__(' Comments', 'iodtheme');
	} else {
		$comments = esc_html__('1 Comment', 'iodtheme');
	}

	$output = $comments;

	return '<h2><span>'. $output .'</span></h2>';
}


/*-----------------------------------------------------------------------------------*/
/* Add Custom Styling to HEAD */
/*-----------------------------------------------------------------------------------*/
// this is hooked into wp_head.

if ( ! function_exists( 'iodtheme_tt_custom_styling' ) ) {
	function iodtheme_tt_custom_styling( $force = false ) {
		global $tt_temptt_opt;
		$output = $woo_hdr_class = $body_image = '';
		// Get options
		$settings = array(
						'top_nav_color' => '',
						'main_acnt_clr' => '',
						'custom_css' => ''
						);
		$settings = iodtheme_fw_opt_values( $settings );

		if($force) { // we have been forced to show specific colors.
			$settings['main_acnt_clr'] = $tt_temptt_opt['tt_main_acnt_clr'];
		}


		// Type Check for Array
		if ( is_array($settings) ) {

		if ( ! ( $settings['main_acnt_clr'] == '' )) { // if usr changed!
			$output .= '


.checkbox-primary input[type="checkbox"]:checked + label::before,
 .checkbox-primary input[type="radio"]:checked + label::before,
 .cbp:after, .cbp-lazyload:after, .cbp-popup-loadingBox:after, .cbp-popup-singlePageInline:after,
 .cbp-l-filters-buttonCenter .cbp-filter-item.cbp-filter-item-active,
 .cbp-l-filters-buttonCenter .cbp-filter-counter:after,
 .cbp-l-filters-list .cbp-filter-item,
 .ui-state-highlight, .ui-widget-content .ui-state-highlight,
.ui-widget-header .ui-state-highlight,
.portfolio .top-detail a, #ajax-work-filter .cbp-filter-item,
.blog .owl-dot.active span, .pagination>li>a:hover, .pagination>li>a.current,
.contact-form input:focus,  .contact-form textarea:focus,
.btn-dark:hover, #ajax-work-filter
.cbp-filter-item, .blog .owl-dot.active span,.cbp-l-filters-buttonCenter .cbp-filter-counter:after, input:focus, select:focus, textarea:focus
{ border-color: '.$settings['main_acnt_clr'].'; }


.page .widget_search .searchsubmit, .contact-form .wpcf7-submit,.colio-link:hover,div.job_listings .load_more_jobs,
.single_job_listing .application .application_button,  .blog-post-date h1 b,
 .fieldset-company_logo + p input[type=submit],.ownmenu ul.dropdown li:hover > a,
 .colio-theme-black .colio-close:before, .colio-theme-black .colio-navigation a,
 .checkbox-primary input[type="checkbox"]:checked + label::before,
 .checkbox-primary input[type="radio"]:checked + label::before,
 .cbp-l-caption-buttonLeft, .cbp-l-caption-buttonRight,
 .cbp-l-filters-buttonCenter .cbp-filter-counter,
  .cbp-l-filters-list .cbp-filter-item.cbp-filter-item-active,
  .cbp-popup-singlePage .cbp-popup-navigation-wrap,
  .cbp-slider-next, .cbp-slider-prev,
  .cbp-l-grid-masonry-projects .cbp-caption-activeWrap,
  .cbp-l-grid-mosaic-projects .cbp-caption-activeWrap,
  .cost-price-content .noUi-handle-lower, .cost-price-content .noUi-handle-upper,
  .ui-datepicker .ui-datepicker-header, .ui-state-highlight, .ui-widget-content .ui-state-highlight,
.ui-widget-header .ui-state-highlight, .btn, .btn-1:hover i, header .navbar li a:hover, header .navbar li.active a,
.search-icon a:hover, .services .icon, .services li:hover article a,.services li.colio-active-item article a,
.services-in .icon,.inside-colio .nav-stacked a:hover, .inside-colio .nav-stacked>li.active,
#ajax-work-filter .cbp-filter-item:hover,  .cbp-l-filters-buttonCenter .cbp-filter-counter,
.plan .price, .blog .date,.list-style-featured .icon, .pagination>li>a:hover, .pagination>li>a.current,
.job-content .share-info .social li a:hover, .social li a:hover,
.social li a:hover, .team-filter li a:hover,  .team-filter li a.active, .counter,.counter .count:before,
.tags li a,.sm-tags, .nav-pills>li>a, .job-content .share-info .social li a:hover,.job-skills .progress-bar,
.job-skills li h6 i, .job-tittle .date, .social li a:hover,.team-filter li a:hover, .team-filter li a.active,
.counter, .counter .count:before, .history #timeline #dates li a.selected:before,
.portfolio-in:before, .btn-dark:hover,
.services li:hover article a,
#ajax-work-filter .cbp-filter-item:hover,
#ajax-work-filter .cbp-filter-item-active,
.cbp-l-filters-buttonCenter .cbp-filter-counter,
.services li.colio-active-item article a,
.colio-theme-black .colio-navigation a,
.colio-theme-black .colio-close:before,
header .navbar li.active a, header .navbar li a:hover,
.search-icon a:hover, .top-info .social li a:hover,
.portfolio .top-detail a:hover, .btn-1:hover i,
.blog .date, .list-style-featured .icon, .counter,
.plan .price, .history #timeline #dates li a.selected:before,
.job-tittle .date, .job-skills li h6 i, .job-skills .progress-bar,
.job-content .share-info .social li a:hover, .social li a:hover,
.pagination>li>a.current, .pagination>li>a:hover,
.cost-price-content .noUi-handle-lower,
.cost-price-content .noUi-handle-upper,
.services-in .icon, .nav-pills>li>a,
.team-filter li a.active, .team-filter li a:hover, .btn, .tags li a, .sm-tags, .scrollup, .wp-tag-cloud li a, .tags li a,
 .portfolio .over-detail, .top-info .social-icons li a:hover, button, html input[type="button"], input[type="reset"], input[type="submit"],
  .singletags a, .blog .postdate
{ background-color:'.$settings['main_acnt_clr'].'; }

a:hover, a:focus,
.screen-reader-text:focus, .education-year h2,.single-blog-post .content ul.meta.type_2 li a,
  .single-blog-post .content ul.meta.type_2 li:after,.job_types li label:hover,
  .cbp-l-filters-alignRight .cbp-filter-item:hover,
  .cbp-l-filters-buttonCenter .cbp-filter-item.cbp-filter-item-active,
  .cbp-l-grid-work-title, .cbp-l-grid-blog-comments, .cbp-l-grid-masonry-projects-title,
  .cbp-l-grid-masonry-projects-title:hover, .cbp-l-grid-team-name, .cbp-l-grid-team-name:hover,
  .ui-state-default a, .ui-state-default a:link, .ui-state-default a:visited,a:hover, a:focus,
  .primary-color, .sub-bnr .breadcrumb li a,.services article i,.services li.colio-active-item:before,
  .who-we li i, #ajax-work-filter .cbp-filter-item,  #ajax-work-filter .cbp-filter-item-active,
  .widget ul.result li:before, .job-skills .progress-bar, .job-skills li h6 i,
.job-tittle .date, .team article .social li a:hover,  footer .tweet a,  footer .tweet li:before,
.team article .social li a:hover,  .widget ul li a:hover,
.comments .media-heading a, .post-content .tag, #ajax-work-filter .cbp-filter-item,
.services article i, .services li.colio-active-item:before,
.primary-color, .team article .social li a:hover,
footer .tweet li:before, .who-we li i,
.history #timeline #dates li a.selected:before,
.sub-bnr .breadcrumb li a, .comments .media-heading a,
.widget ul.result li:before, .widget ul li a:hover, #footer-wrap .widget_temptt-twitter-widget .tt-tweets ul li a, .widget_temptt-twitter-widget .tt-tweets ul li a, #footer-wrap .widget.widget_temptt-twitter-widget ul li::before, .widget.widget_temptt-twitter-widget ul li::before, .widget.null-instagram-feed a,.sub-bnr .breadcrumb a
{ color: '.$settings['main_acnt_clr'].'; }

{ outline: 1px solid '.$settings['main_acnt_clr'].'; }
						';
		}

		} // End If Statement

			if ( $settings['custom_css'] != '' ) {
				$output .= $settings['custom_css'];
			}

		// Output styles
		if ( isset( $output ) && $output != '' ) {
			$output = strip_tags( $output );
			// Remove space after colons
			$output = str_replace(': ', ':', $output);
			// Remove whitespace
			$output = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '   ', '    '), '', $output);

			$output = "\n" . "<!-- Theme Custom Styling -->\n<style type=\"text/css\">\n" . $output . "</style>\n";
			echo  $output; // its already sanitized by redux.
		}

	} // End iodtheme_tt_custom_styling()
}




/*-----------------------------------------------------------------------------------*/
/* Function for color switcher and lang switcher. */
/*-----------------------------------------------------------------------------------*/
/*
 * it only works on demo websites, where its needed by the way.
 */
if( strpos(get_site_url(),'livedemos.wpengine') || strpos(get_site_url(),'livedemos.staging.wpengine') ) {
	add_action('iodtheme_fw_after_body', 'iodtheme_fw_clr_switcher');
	add_action('wp_head', 'iodtheme_fw_clr_switcher_scripts');
	add_filter('iodtheme_fw_topnav_content_before_output', 'iodtheme_fw_add_demo_switcher',100);
}

if (!function_exists('iodtheme_fw_clr_switcher_scripts')) {
	function iodtheme_fw_clr_switcher_scripts() {
		$output = '';
		ob_start(); ?>
	<script type="text/javascript" src="<?php  echo get_template_directory_uri(); ?>/inc/switcher/jquery.cookie.js"></script>
	<script type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/inc/switcher/jscript_styleswitcher.js"></script>
	<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/inc/switcher/styleswitcher.css" />

	<?php
		$output = ob_get_clean();
		print $output; // its safe to output, all variables already escaped above.
}}


if (!function_exists('iodtheme_fw_add_demo_switcher')) {
	function iodtheme_fw_add_demo_switcher($output) {
$output = '';
	ob_start(); ?>
<section class="top-info hidden-xs">
	<div class="container">
		<!-- Start left side content -->
		<div class="left-content">
			<span>
					<a href="tel:11234567890"><i class="fa fa-phone"></i> +1 123 456 7890 </a>
			</span>
			<span class="tt-top-teaser">Welcome to IODtheme</span>
			<span>
				<a href="mailto:dummy@dummy.com"><i class="fa fa-envelope-o"></i>dummy@dummy.com</a>
			</span>
		</div><!-- .left-content -->
		<!-- Start right side content -->
		<div class="right-content">
			<div id="lang_sel">
			    <ul>
			        <li>
			            <a class="lang_sel_sel icl-en" href="#">
			                <img title="English" alt="en" src="<?php echo get_template_directory_uri();?>/inc/switcher/flags/en.png" class="iclflag">
											&nbsp;
			                <span class="icl_lang_sel_current icl_lang_sel_native">English</span>
			            </a>
			            <ul>
			                <li class="icl-fr">
			                    <a href="<?php echo get_site_url(); ?>/?lang=fr">
			                        <img title="French" alt="fr" src="<?php echo get_template_directory_uri();?>/inc/switcher/flags/fr.png" class="iclflag">&nbsp;
			                        <span class="icl_lang_sel_native">French</span>
			                    </a>
			                </li>
			                <li class="icl-fr">
			                    <a href="<?php echo get_site_url(); ?>/?lang=fr">
			                        <img title="French" alt="fr" src="<?php echo get_template_directory_uri();?>/inc/switcher/flags/de.png" class="iclflag">&nbsp;
			                        <span class="icl_lang_sel_native">German</span>
			                    </a>
			                </li>
			                <li class="icl-fr">
			                    <a href="<?php echo get_site_url(); ?>/?lang=fr">
			                        <img title="French" alt="fr" src="<?php echo get_template_directory_uri();?>/inc/switcher/flags/it.png" class="iclflag">&nbsp;
			                        <span class="icl_lang_sel_native">Italian</span>
			                    </a>
			                </li>
			                <li class="icl-fr">
			                    <a href="<?php echo get_site_url(); ?>/?lang=fr">

			                        <span class="icl_lang_sel_native">Demo Only</span>
			                    </a>
			                </li>
			            </ul>
			        </li>
			    </ul>
			</div>
			<div class="social-icons">
				<ul>
					<li><a title="Twitter" class="twitter" href="#"><i class="fa fa-twitter"></i></a></li>
					<li><a title="Facebook" class="facebook" href="#"><i class="fa fa-facebook"></i></a></li>
					<li><a title="Pinterest" class="pinterest" href="#"><i class="fa fa-pinterest"></i></a></li>
					<li><a title="Instagram" class="instagram" href="#"><i class="fa fa-instagram"></i></a></li>
				</ul>
			</div>
		</div><!-- .right-content -->
	</div>
</section>

<?php	$output = ob_get_clean();
		return $output;
	}

}


if (!function_exists('iodtheme_fw_clr_switcher')) {
	function iodtheme_fw_clr_switcher( ) {
		$ttcookie1 = $ttcookie2 = '' ;
		if(isset($_COOKIE['ttcookie1'])) {
			$ttcookie1 = '#' . $_COOKIE['ttcookie1'];
		} else {
			$ttcookie1 = '#1193d4';
		}
		if(isset($_COOKIE['ttcookie2'])) {
			$ttcookie2 = '#' . $_COOKIE['ttcookie2'];
		} else {
			$ttcookie2 = '#FE5454';
		}
		ob_start(); ?>

<!-------------------------------------------------------------------/
Color switcher for demo, to be removed from live website.
<!------------------------------------------------------------------->

	<?php
	if( $ttcookie1 != '#1193d4' /*|| $ttcookie2 != 'FE5454'*/ ) { // only trigger following if user actually changed colors
		global $tt_temptt_opt;
		$tt_temptt_opt['tt_main_acnt_clr'] = $ttcookie1;
		$tt_temptt_opt['tt_second_color']  = $ttcookie2;
		iodtheme_tt_custom_styling(true);

	}
	?>
<!-- ADD Switcher -->
<div class="demo_changer">
	<div class="demo-icon"><i class="fa fa-gear"></i></div>
	<div class="form_holder">
		<p class="color-title"><?php esc_attr_e('THEME OPTIONS', 'iodtheme'); ?></p>

		<div class="predefined_styles">
			<div class="clear"></div>
		</div>
		<p><?php esc_attr_e('Change color', 'iodtheme'); ?></p>
		<div class="color-box">
			<div class="col-col">
				<div  id="colorSelector">
					<div class="inner-color" style="background-color: <?php  echo esc_attr($ttcookie1);?>"></div>
				</div>
				<p><?php esc_attr_e('Select base Color', 'iodtheme'); ?></p>
			</div>
<!--			<div class="col-col">
				<div  id="colorSelector_2">
					<div class="inner-color" style="background-color: <?php /* echo esc_attr($ttcookie2);*/?>"></div>
				</div>
				<p><?php /*esc_attr_e('Color 2', 'iodtheme'); */?></p>
			</div>
-->		</div>
		<span class="switcherbutton clear switchspan"><a rel="stylesheet" class="switchapply switchinner" href=""><?php esc_attr_e('APPLY COLOR', 'iodtheme'); ?></a></span>
		<span class="switcherbutton switcherreset clear switchspan"><a rel="stylesheet" class="switcherreset switchinner" href=""><?php esc_attr_e('RESET TO DEFAULT', 'iodtheme'); ?></a></span>
<!--		<span class="clear switchspan"><a rel="stylesheet" class="normallink" href="http://plumberwp.wpengine.com/"><?php esc_attr_e('Back to landing page', 'iodtheme'); ?></a></span>
        <span class="clear switchspan"><a rel="stylesheet" class="buy normallink" href="http://themeforest.net/item/iodtheme-plumber-and-construction-wordpress-theme/14036883"><?php esc_attr_e('Purchase Webapp', 'iodtheme'); ?></a></span>
-->        <span class="clear switchspan alignl"><?php esc_attr_e('Note: Some colors are controlled by Slider & Pagebuilder.', 'iodtheme'); ?></span>
	</div>
</div>

<!-- END Switcher -->
<!-------------------------------------------------------------------/
EOF Color switcher for demo, to be removed from live website.
<!------------------------------------------------------------------->
<?php
		$output = ob_get_clean();
		print $output; // its safe to output, all variables already escaped above.
	}
}



if ( ! function_exists( 'iodtheme_tt_custom_favicon' ) ) {
/**
 * Output the favicon HTML.
 * @since  2.0.0
 * @return void
 */
function iodtheme_tt_custom_favicon () {
	global $tt_temptt_opt;
	// Get options
	$settings = array(
					'custom_favicon' => array( 'url' => '' ),
					'fvcn_57x57' => array( 'url' => '' ),
					'fvcn_114x114' => array( 'url' => '' ),
					'fvcn_72x72' => array( 'url' => '' ),
					'fvcn_144x144' => array( 'url' => '' ),
	);
	$settings = iodtheme_fw_opt_values( $settings );
	$favicon = apply_filters( 'tt_custom_favicon', $settings['custom_favicon']['url'] );

	// custom favicon now can be managed from customizer since wp 4.3
	ob_start();
	if( '' != $favicon ) echo "\n" . '<link rel="shortcut icon" href="' .  esc_url( $favicon )  . '"/>' . "\n";

	if( '' != $settings['fvcn_57x57']['url'] ): ?>
	<!-- For iPhone -->
	<link rel="apple-touch-icon-precomposed" href="<?php echo esc_url($settings['fvcn_57x57']['url']); ?>">
	<?php endif;

	if( '' != $settings['fvcn_114x114']['url'] ): ?>
	<!-- For iPhone 4 Retina display -->
	<link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?php echo esc_url($settings['fvcn_114x114']['url']); ?>">
	<?php endif;

	if( '' != $settings['fvcn_72x72']['url'] ): ?>
	<!-- For iPad -->
	<link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?php echo esc_url($settings['fvcn_72x72']['url']); ?>">
	<?php endif;

	if( '' != $settings['fvcn_144x144']['url'] ): ?>
	<!-- For iPad Retina display -->
	<link rel="apple-touch-icon-precomposed" sizes="144x144" href="<?php echo esc_url($settings['fvcn_144x144']['url']); ?>">
	<?php endif; 
	$favicon = ob_get_clean();
	echo '<!-- Custom Favicon -->' .$favicon;

} // End iodtheme_tt_custom_favicon()
}


/*-----------------------------------------------------------------------------------*/
/* Function for showing google map inside container.                                 */
/*-----------------------------------------------------------------------------------*/
if (!function_exists('iodtheme_tt_gmap')) {
	function iodtheme_tt_gmap() {
 	global $tt_temptt_opt;
	$settings7 = $gmap = "";

	ob_start();

	   if ( isset($tt_temptt_opt['tt_contactform_map_coords']) && $tt_temptt_opt['tt_contactform_map_coords'] != '' ) { $geocoords = $tt_temptt_opt['tt_contactform_map_coords']; }  else { $geocoords = ''; } ?>
		<?php if ($geocoords != '') { ?>
		<?php iodtheme_tt_maps_contact_output("geocoords=$geocoords"); ?>
		<?php }

	$gmap =  ob_get_clean();
	return $gmap;

}
}

/*-----------------------------------------------------------------------------------*/
/* Google Maps */
/*-----------------------------------------------------------------------------------*/
// Thanks Adii.

function iodtheme_tt_maps_contact_output($args){
	global $tt_temptt_opt;

	if ( !is_array($args) )
		parse_str( $args, $args );

	extract($args);
	$mode = '';
	$streetview = 'off';
	$map_height = $tt_temptt_opt['tt_maps_single_height'];
//	$featured_w = $tt_temptt_opt['tt_home_featured_w']['width'];
//	$featured_h = $tt_temptt_opt['tt_home_featured_h']['height'];
	$zoom = $tt_temptt_opt['tt_maps_default_mapzoom'];
	$type = $tt_temptt_opt['tt_maps_default_maptype'];
	$marker_title = $tt_temptt_opt['tt_contact_title'];
	if ( $zoom == '' ) { $zoom = 6; }
//	$lang = $tt_temptt_opt['tt_maps_directions_locale'];
	$locale = '';
	if(!empty($lang)){
		$locale = ',locale :"'.$lang.'"';
	}
	$extra_params = ',{travelMode:G_TRAVEL_MODE_WALKING,avoidHighways:true '.$locale.'}';

	if(empty($map_height)) { $map_height = 250;}

	if(is_home() && !empty($featured_h) && !empty($featured_w)){
	?>
    <div id="single_map_canvas" style="width:<?php echo esc_attr($featured_w); ?>px; height: <?php echo esc_attr($featured_h); ?>px"></div>
    <?php } else { ?>
    <div id="single_map_canvas" style="width:100%; height: <?php echo esc_attr($map_height); ?>px"></div>
    <?php } ?>
    <script type="text/javascript">
		jQuery(document).ready(function(){
			function initialize() {


			<?php if($streetview == 'on'){ ?>


			<?php } else { ?>

			  	<?php switch ($type) {
			  			case 'G_NORMAL_MAP':
			  				$type = 'ROADMAP';
			  				break;
			  			case 'G_SATELLITE_MAP':
			  				$type = 'SATELLITE';
			  				break;
			  			case 'G_HYBRID_MAP':
			  				$type = 'HYBRID';
			  				break;
			  			case 'G_PHYSICAL_MAP':
			  				$type = 'TERRAIN';
			  				break;
			  			default:
			  				$type = 'ROADMAP';
			  				break;
			  	} ?>

			  	var myLatlng = new google.maps.LatLng(<?php echo esc_attr($geocoords); ?>);
				var myOptions = {
				  zoom: <?php echo esc_attr($zoom); ?>,
				  center: myLatlng,
				  mapTypeId: google.maps.MapTypeId.<?php echo esc_attr($type); ?>
				};
				<?php if( $tt_temptt_opt['tt_maps_scroll'] == '1'){ ?>
			  	myOptions.scrollwheel = false;
			  	<?php } ?>
			  	var map = new google.maps.Map(document.getElementById("single_map_canvas"),  myOptions);

				<?php if($mode == 'directions'){ ?>
			  	directionsPanel = document.getElementById("featured-route");
 				directions = new GDirections(map, directionsPanel);
  				directions.load("from: <?php echo esc_attr($from); ?> to: <?php echo esc_attr($to); ?>" <?php if($walking == 'on'){ echo esc_attr($extra_params);} ?>);
			  	<?php
			 	} else { ?>

			  		var point = new google.maps.LatLng(<?php echo esc_attr($geocoords); ?>);
	  				var root = "<?php echo constant('IODTHEME_FW_THEME_DIRURI'); ?>";
	  				var callout = '<?php echo preg_replace("/[\n\r]/","<br/>", $tt_temptt_opt['tt_maps_callout_text']); ?>';
	  				var the_link = '<?php echo get_permalink(get_the_id()); ?>';
	  				<?php $title = str_replace(array('&#8220;','&#8221;'),'"', $marker_title); ?>
	  				<?php $title = str_replace('&#8211;','-',$title); ?>
	  				<?php $title = str_replace('&#8217;',"`",$title); ?>
	  				<?php $title = str_replace('&#038;','&',$title); ?>
	  				var the_title = '<?php echo html_entity_decode($title) ?>';

	  			<?php
			 	if(is_page()){
/*			 		$custom = $tt_temptt_opt['tt_cat_custom_marker_pages'];
					if(!empty($custom)){
						$color = $custom;
					}
					else {
						$color = $tt_temptt_opt['tt_cat_colors_pages'];
						if (empty($color)) {
							$color = 'red';
						}
					}*/
					$color = 'default';
			 	?>
			 		var color = '<?php echo esc_attr($color); ?>';
			 		createMarker(map,point,root,the_link,the_title,color,callout);
			 	<?php } else { ?>
			 		var color = '<?php echo esc_attr($tt_temptt_opt['tt_cat_colors_pages']); ?>';
	  				createMarker(map,point,root,the_link,the_title,color,callout);
				<?php
				}
					if(isset($_POST['tt_maps_directions_search'])){ ?>

					directionsPanel = document.getElementById("featured-route");
 					directions = new GDirections(map, directionsPanel);
  					directions.load("from: <?php echo htmlspecialchars($_POST['tt_maps_directions_search']); ?> to: <?php echo esc_textarea($address); ?>" <?php if($walking == 'on'){ echo esc_attr($extra_params);} ?>);



					directionsDisplay = new google.maps.DirectionsRenderer();
					directionsDisplay.setMap(map);
    				directionsDisplay.setPanel(document.getElementById("featured-route"));

					<?php if($walking == 'on'){ ?>
					var travelmodesetting = google.maps.DirectionsTravelMode.WALKING;
					<?php } else { ?>
					var travelmodesetting = google.maps.DirectionsTravelMode.DRIVING;
					<?php } ?>
					var start = '<?php echo htmlspecialchars($_POST['tt_maps_directions_search']); ?>';
					var end = '<?php echo esc_textarea($address); ?>';
					var request = {
       					origin:start,
        				destination:end,
        				travelMode: travelmodesetting
    				};
    				directionsService.route(request, function(response, status) {
      					if (status == google.maps.DirectionsStatus.OK) {
        					directionsDisplay.setDirections(response);
      					}
      				});

  					<?php } ?>
				<?php } ?>
			<?php } ?>


			  }
			  function handleNoFlash(errorCode) {
				  if (errorCode == FLASH_UNAVAILABLE) {
					alert("Error: Flash doesn't appear to be supported by your browser");
					return;
				  }
				 }

		initialize();

		});
	jQuery(window).load(function(){

		var newHeight = jQuery('#featured-content').height();
		newHeight = newHeight - 5;
		if(newHeight > 300){
			jQuery('#single_map_canvas').height(newHeight);
		}

	});

	</script>

<?php
}

/*-----------------------------------------------------------------------------------*/
/* Function to retrieve tags. */
/*-----------------------------------------------------------------------------------*/
if( !function_exists( 'iodtheme_tt_get_tags' )) {
	function iodtheme_tt_get_tags(){
		$tags = get_the_tags();
		$tags_count = 0;
	    $html = '<p class=tagtitle>'. esc_html__('tags ', 'iodtheme') .'&nbsp;</p>';
		if( !is_array($tags) ) return;
	    foreach ($tags as $tag){
		    $tags_count ++;
	        $tag_link = get_tag_link($tag->term_id);
						if ( $tags_count > 1 ) {
							$html .= ' '; // tag separator here
						}

	        $html .= "<a href='{$tag_link}' title='{$tag->name} Tag' class='tag {$tag->slug}'>";
	        $html .= "{$tag->name}</a>";
	    }
	    echo '<div class="detail-tags">'. $html .'</div>';
	}
}

/*-----------------------------------------------------------------------------------*/
/* Function to retrieve categories. */
/*-----------------------------------------------------------------------------------*/
/*
 * it can either return single category or all categories separated by comma.
 * by default it returns all category separated by comma but if single category is required, just pass 'single' into the fn.
 *
 */
if (!function_exists('iodtheme_tt_get_cats')) {
	function iodtheme_tt_get_cats( $return='' ) {
		global $post, $wp_query;
		$output = '';
		$post_type_taxonomies = get_object_taxonomies( get_post_type(), 'objects' );
		foreach ( $post_type_taxonomies as $taxonomy ) {
			if ( $taxonomy->hierarchical == true ) {

				$cats       = get_the_terms( get_the_ID(), $taxonomy->name );
				$cats_count = 0;
				if ( $cats ) {
					foreach ( $cats as $cat ) {
						$cats_count ++;
						if ( $cats_count > 1 && $return == 'single' ) {
							break;
						}
						if ( $cats_count > 1 ) {
							$output .= ', ';
						}
						$output .= '<a class="tt_cats" href="' . get_term_link( $cat, $taxonomy->name ) . '">' . $cat->name . '</a>';
					}
				}
			}
		}
		return $output;
	}
}

/*-----------------------------------------------------------------------------------*/
/* Subscribe / Connect */
/*-----------------------------------------------------------------------------------*/
// Thanks Adii
if (!function_exists( 'iodtheme_tt_subs_connect')) {
	function iodtheme_tt_subs_connect($widget = 'false', $title = '', $form = '', $social = '', $contact_template = 'false') {

		//Setup default variables, overriding them if the "Theme Options" have been saved.
		global $settings;

		$settings = array(
						'connect' => '0',
						'connect_title' => esc_html__('Subscribe' , 'iodtheme'),
						'connect_related' => '0',
						'connect_content' => esc_html__( 'Subscribe to our profiles on the following social networks.', 'iodtheme' ),
						'connect_newsletter_id' => '',
						'connect_mailchimp_list_url' => '',
						'feed_url' => '',
						'connect_rss' => '',
						'connect_twitter' => '',
						'connect_facebook' => '',
						'connect_youtube' => '',
						'connect_flickr' => '',
						'connect_linkedin' => '',
						'connect_pinterest' => '',
						'connect_instagram' => '',
						'connect_googleplus' => ''
						);
		$settings = iodtheme_fw_opt_values( $settings );

		// Setup title
		if ( $widget != 'true' )
			$title = $settings[ 'connect_title' ];

		// Setup related post (not in widget)
		$related_posts = '';
		if ( $settings[ 'connect_related' ] == "1" AND $widget != "true" )
			$related_posts = do_shortcode( '[related_posts limit="5"]' );

?>
	<?php if ( $settings[ 'connect' ] == "1" OR $widget == 'true' ) : ?>
		<h4 class="title"><?php if ( $title ) echo apply_filters( 'widget_title', $title ); else esc_html_e('Subscribe','iodtheme'); ?></h4>

		<div <?php if ( $related_posts != '' ) echo 'class="col-left"'; ?>>
			<?php if ($settings[ 'connect_content' ] != '' AND $contact_template == 'false') echo '<p>' . stripslashes($settings[ 'connect_content' ]) . '</p>'; ?>

			<?php if ( $settings[ 'connect_newsletter_id' ] != "" AND $form != 'on' ) : ?>
			<form class="newsletter-form<?php if ( $related_posts == '' ) echo ' fl'; ?>" action="http://feedburner.google.com/fb/a/mailverify" method="post" target="popupwindow" onsubmit="window.open( 'http://feedburner.google.com/fb/a/mailverify?uri=<?php echo esc_attr($settings[ 'connect_newsletter_id' ]); ?>', 'popupwindow', 'scrollbars=yes,width=550,height=520' );return true">
				<input class="email" type="text" name="email" value="<?php esc_attr_e( 'E-mail', 'iodtheme' ); ?>" onfocus="if (this.value == '<?php esc_html_e( 'E-mail', 'iodtheme' ); ?>') {this.value = '';}" onblur="if (this.value == '') {this.value = '<?php esc_html_e( 'E-mail', 'iodtheme' ); ?>';}" />
				<input type="hidden" value="<?php echo esc_attr($settings[ 'connect_newsletter_id' ]); ?>" name="uri"/>
				<input type="hidden" value="<?php bloginfo( 'name' ); ?>" name="title"/>
				<input type="hidden" name="loc" value="en_US"/>
				<input class="submit email-submit" type="submit" name="submit" value="<?php esc_html_e( 'Submit', 'iodtheme' ); ?>" />
			</form>
			<?php endif; ?>

			<?php if ( $settings['connect_mailchimp_list_url'] != "" AND $form != 'on' AND $settings['connect_newsletter_id'] == "" ) : ?>
			<!-- Begin MailChimp Signup Form -->
			<div id="mc_embed_signup">
				<form class="newsletter-form" action="<?php echo esc_url($settings['connect_mailchimp_list_url']); ?>" method="post" target="popupwindow" onsubmit="window.open('<?php echo esc_url($settings['connect_mailchimp_list_url']); ?>', 'popupwindow', 'scrollbars=yes,width=650,height=520');return true">
					<input type="text" name="EMAIL" class="required email" value="<?php esc_html_e('E-mail','iodtheme'); ?>"  id="mce-EMAIL" onfocus="if (this.value == '<?php esc_html_e('E-mail','iodtheme'); ?>') {this.value = '';}" onblur="if (this.value == '') {this.value = '<?php esc_html_e('E-mail','iodtheme'); ?>';}">
					<input type="submit" value="<?php esc_html_e('Submit', 'iodtheme'); ?>" name="subscribe" id="mc-embedded-subscribe" class="btn submit button">
				</form>
			</div>
			<!--End mc_embed_signup-->
			<?php endif; ?>

			<?php if ( $social != 'on' ) : ?>
			<div class="social<?php if ( $related_posts == '' AND $settings['connect_newsletter_id' ] != "" ) echo ' fr'; ?>">
		   		<?php if ( $settings['connect_rss' ] == "1" ) { ?>
		   		<a href="<?php if ( $settings['feed_url'] ) { echo esc_url( $settings['feed_url'] ); } else { echo get_bloginfo_rss('rss2_url'); } ?>" class="subscribe" title="RSS"></a>

		   		<?php } if ( $settings['connect_twitter' ] != "" ) { ?>
		   		<a href="<?php echo esc_url( $settings['connect_twitter'] ); ?>" class="twitter" title="Twitter"></a>

		   		<?php } if ( $settings['connect_facebook' ] != "" ) { ?>
		   		<a href="<?php echo esc_url( $settings['connect_facebook'] ); ?>" class="facebook" title="Facebook"></a>

		   		<?php } if ( $settings['connect_youtube' ] != "" ) { ?>
		   		<a href="<?php echo esc_url( $settings['connect_youtube'] ); ?>" class="youtube" title="YouTube"></a>

		   		<?php } if ( $settings['connect_flickr' ] != "" ) { ?>
		   		<a href="<?php echo esc_url( $settings['connect_flickr'] ); ?>" class="flickr" title="Flickr"></a>

		   		<?php } if ( $settings['connect_linkedin' ] != "" ) { ?>
		   		<a href="<?php echo esc_url( $settings['connect_linkedin'] ); ?>" class="linkedin" title="LinkedIn"></a>

		   		<?php } if ( $settings['connect_pinterest' ] != "" ) { ?>
		   		<a href="<?php echo esc_url( $settings['connect_pinterest'] ); ?>" class="pinterest" title="Pinterest"></a>

		   		<?php } if ( $settings['connect_instagram' ] != "" ) { ?>
		   		<a href="<?php echo esc_url( $settings['connect_instagram'] ); ?>" class="instagram" title="Instagram"></a>

		   		<?php } if ( $settings['connect_googleplus' ] != "" ) { ?>
		   		<a href="<?php echo esc_url( $settings['connect_googleplus'] ); ?>" class="googleplus" title="Google+"></a>

				<?php } ?>
			</div>
			<?php endif; ?>

		</div><!-- col-left -->

	<?php endif; ?>
<?php
	}
}


/*-----------------------------------------------------------------------------------*/
/* Adding hero images for pages                                                      */
/*-----------------------------------------------------------------------------------*/

if( !function_exists('iodtheme_tt_hero_section') ) {
	function iodtheme_tt_hero_section() {
	global $tt_temptt_opt, $wp_query;
	$el_class = $single_display_breadcrumbs = $single_hdline_type = $tt_post_id = $single_enable_headline = $single_headline_title = $single_text_align = $single_page_color = $single_page_img = $single_text_apprnce = $single_hero_breadcrumbs = $hide_title_display = $single_display_breadcrumbs = '';
	if ( is_404() || is_search() ) return;
	if( isset($wp_query->post->ID)) $tt_post_id = $wp_query->post->ID;
	if ( is_home() ) { $tt_post_id = get_option( 'page_for_posts' ); }
	//if woocommerce pages
	if ( class_exists( 'woocommerce' ) ) {
		if ( is_shop() ) {
			$tt_post_id = get_option( 'woocommerce_shop_page_id' );
		}
		if ( is_account_page() ) {
			$tt_post_id = get_option( 'woocommerce_myaccount_page_id' );
		}
		if ( is_checkout() ) {
			$tt_post_id = get_option( 'woocommerce_checkout_page_id' );
		}
		if ( is_cart() ) {
			$tt_post_id = get_option( 'woocommerce_cart_page_id' );
		}
	}


	if( empty($tt_post_id) ) return; // nothing left to do!
	if ( !shortcode_exists('tt_hero_shortcode') ) return; // nothing left to do!

	// if the current page template is contact page.
	//$current_page_template = get_post_meta($tt_post_id, '_wp_page_template', true);


	// fetching value from single posts .
	$single_data2 = get_post_meta( $tt_post_id, '_tt_meta_page_opt', true );
	if( is_array($single_data2) ) {
		if ( isset( $single_data2['_single_enable_headline'] ) ) $single_enable_headline = $single_data2['_single_enable_headline'];
		if ( isset( $single_data2['_single_headline_title'] ) ) $single_headline_title = esc_attr($single_data2['_single_headline_title']);
		if ( isset( $single_data2['_single_text_align'] ) ) $single_text_align = esc_attr($single_data2['_single_text_align']);
		if ( isset( $single_data2['_single_page_img'] ) ) $single_page_img = esc_textarea($single_data2['_single_page_img']);
		if ( isset( $single_data2['_single_page_color'] ) ) $single_page_color = esc_attr($single_data2['_single_page_color']);
		if ( isset( $single_data2['_single_text_apprnce'] ) ) $single_text_apprnce = esc_attr($single_data2['_single_text_apprnce']);
		if ( isset( $single_data2['_single_hero_breadcrumbs'] ) ) $single_hero_breadcrumbs = esc_attr($single_data2['_single_hero_breadcrumbs']);
		if ( isset( $single_data2['_single_hdline_type'] ) ) $single_hdline_type = esc_attr($single_data2['_single_hdline_type']);
	}

	if ( !$single_enable_headline ) return; // user dont want it!

	// if title is not entered , grab the default page title.
	if( '' == $single_headline_title ) $single_headline_title = get_the_title($tt_post_id);

	if( $single_hdline_type == 'type2' ) $el_class = 'type2';

	echo do_shortcode('[tt_hero_shortcode
	heading="'. $single_headline_title .'"
	image="'. iodtheme_fw_get_image_id($single_page_img) .'"
	color="'. $single_page_color .'"
	text_appear="'. $single_text_apprnce .'"
	yoast_bdcmp="'. $single_hero_breadcrumbs .'"
	el_class="'. $el_class .'"

	]'). '' ; // all variables in html blcok are already escaped. we can output directly.
	}
}
add_action( 'tt_before_mainblock', 'iodtheme_tt_hero_section' );


// source: https://pippinsplugins.com/retrieve-attachment-id-from-image-url/
if( !function_exists('iodtheme_fw_get_image_id')) {
	function iodtheme_fw_get_image_id( $image_url ) {
		global $wpdb;
		$attachment = $wpdb->get_col( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE guid='%s';", $image_url ) );
		if( is_array($attachment) && isset($attachment[0]) )
		return $attachment[0];
		else return $image_url;
	}
}
/*-----------------------------------------------------------------------------------*/
/* Allowed tags                                                                      */
/*-----------------------------------------------------------------------------------*/

if(!( function_exists('iodtheme_tt_allowed_tags') )){
	function iodtheme_tt_allowed_tags(){
		return array(
		    'img' => array(
		        'src' => array(),
		        'alt' => array(),
		        'class' => array(),
		        'style' => array(),
		    ),
		    'a' => array(
		        'href' => array(),
		        'title' => array(),
		        'class' => array(),
		        'target' => array()
		    ),
		    'br' => array(),
		    'div' => array(
		        'class' => array(),
		        'style' => array(),
		    ),
		    'span' => array(
		        'class' => array(),
		        'style' => array(),
		    ),
		    'h1' => array(
		        'class' => array(),
		        'style' => array(),
		    ),
		    'h2' => array(
		        'class' => array(),
		        'style' => array(),
		    ),
		    'h3' => array(
		        'class' => array(),
		        'style' => array(),
		    ),
		    'h4' => array(
		        'class' => array(),
		        'style' => array(),
		    ),
		    'h5' => array(
		        'class' => array(),
		        'style' => array(),
		    ),
		    'h6' => array(
		        'class' => array(),
		        'style' => array(),
		    ),
		    'style' => array(),
		    'em' => array(),
		    'strong' => array(),
		    'p' => array(
		    	'class' => array(),
		        'style' => array(),
		    ),
		);
	}
}
function iodtheme_tt_css_allow($allowed_attr) {

    if (!is_array($allowed_attr)) {
        $allowed_attr = array();
    }

    $allowed_attr[] = 'display';
    $allowed_attr[] = 'background-image';
    $allowed_attr[] = 'url';

    return $allowed_attr;
} add_filter('safe_style_css','iodtheme_tt_css_allow');

/*-----------------------------------------------------------------------------------*/
/* Image Resizer script for on the fly resizing                                     */
/*-----------------------------------------------------------------------------------*/
// Source: https://github.com/syamilmj/Aqua-Resizer

if( ! class_exists('iodtheme_tt_Aq_Resize') ) {

	/**
	 * Image resizing class
	 *
	 * @since 1.0
	 */
	class iodtheme_tt_Aq_Resize {

		/**
		 * The singleton instance
		 */
		static private $instance = null;

		/**
		 * No initialization allowed
		 */
		private function __construct() {}

		/**
		 * No cloning allowed
		 */
		private function __clone() {}

		/**
		 * For your custom default usage you may want to initialize an Aq_Resize object by yourself and then have own defaults
		 */
		static public function getInstance() {
			if(self::$instance == null) {
				self::$instance = new self;
			}

			return self::$instance;
		}

		/**
		 * Run, forest.
		 */
		public function process( $url, $width = null, $height = null, $crop = null, $single = true, $upscale = true ) {

			// Validate inputs.
			if ( ! $url || ( ! $width && ! $height ) ) return false;

			$upscale = true;

			// Caipt'n, ready to hook.
			if ( true === $upscale ) add_filter( 'image_resize_dimensions', array($this, 'aq_upscale'), 10, 6 );

			// Define upload path & dir.
			$upload_info = wp_upload_dir();
			$upload_dir = $upload_info['basedir'];
			$upload_url = $upload_info['baseurl'];

			$http_prefix = "http://";
			$https_prefix = "https://";

			/* if the $url scheme differs from $upload_url scheme, make them match
			   if the schemes differe, images don't show up. */
			if(!strncmp($url,$https_prefix,strlen($https_prefix))){ //if url begins with https:// make $upload_url begin with https:// as well
				$upload_url = str_replace($http_prefix,$https_prefix,$upload_url);
			}
			elseif(!strncmp($url,$http_prefix,strlen($http_prefix))){ //if url begins with http:// make $upload_url begin with http:// as well
				$upload_url = str_replace($https_prefix,$http_prefix,$upload_url);
			}


			// Check if $img_url is local.
			if ( false === strpos( $url, $upload_url ) ) return false;

			// Define path of image.
			$rel_path = str_replace( $upload_url, '', $url );
			$img_path = $upload_dir . $rel_path;

			// Check if img path exists, and is an image indeed.
			if ( ! file_exists( $img_path ) or ! getimagesize( $img_path ) ) return false;

			// Get image info.
			$info = pathinfo( $img_path );
			$ext = $info['extension'];
			list( $orig_w, $orig_h ) = getimagesize( $img_path );

			// Get image size after cropping.
			$dims = image_resize_dimensions( $orig_w, $orig_h, $width, $height, $crop );
			$dst_w = $dims[4];
			$dst_h = $dims[5];

			// Return the original image only if it exactly fits the needed measures.
			if ( ! $dims && ( ( ( null === $height && $orig_w == $width ) xor ( null === $width && $orig_h == $height ) ) xor ( $height == $orig_h && $width == $orig_w ) ) ) {
				$img_url = $url;
				$dst_w = $orig_w;
				$dst_h = $orig_h;
			} else {
				// Use this to check if cropped image already exists, so we can return that instead.
				$suffix = "{$dst_w}x{$dst_h}";
				$dst_rel_path = str_replace( '.' . $ext, '', $rel_path );
				$destfilename = "{$upload_dir}{$dst_rel_path}-{$suffix}.{$ext}";

				if ( ! $dims || ( true == $crop && false == $upscale && ( $dst_w < $width || $dst_h < $height ) ) ) {
					// Can't resize, so return false saying that the action to do could not be processed as planned.
					return $url;
				}
				// Else check if cache exists.
				elseif ( file_exists( $destfilename ) && getimagesize( $destfilename ) ) {
					$img_url = "{$upload_url}{$dst_rel_path}-{$suffix}.{$ext}";
				}
				// Else, we resize the image and return the new resized image url.
				else {

					$editor = wp_get_image_editor( $img_path );

					if ( is_wp_error( $editor ) || is_wp_error( $editor->resize( $width, $height, $crop ) ) )
						return $url;

					$resized_file = $editor->save();

					if ( ! is_wp_error( $resized_file ) ) {
						$resized_rel_path = str_replace( $upload_dir, '', $resized_file['path'] );
						$img_url = $upload_url . $resized_rel_path;
					} else {
						return $url;
					}

				}
			}

			// Okay, leave the ship.
			if ( true === $upscale ) remove_filter( 'image_resize_dimensions', array( $this, 'aq_upscale' ) );

			// Return the output.
			if ( $single ) {
				// str return.
				$image = $img_url;
			} else {
				// array return.
				$image = array (
					0 => $img_url,
					1 => $dst_w,
					2 => $dst_h
				);
			}

			return $image;
		}

		/**
		 * Callback to overwrite WP computing of thumbnail measures
		 */
		function aq_upscale( $default, $orig_w, $orig_h, $dest_w, $dest_h, $crop ) {
			if ( ! $crop ) return null; // Let the wordpress default function handle this.

			// Here is the point we allow to use larger image size than the original one.
			$aspect_ratio = $orig_w / $orig_h;
			$new_w = $dest_w;
			$new_h = $dest_h;

			if ( ! $new_w ) {
				$new_w = intval( $new_h * $aspect_ratio );
			}

			if ( ! $new_h ) {
				$new_h = intval( $new_w / $aspect_ratio );
			}

			$size_ratio = max( $new_w / $orig_w, $new_h / $orig_h );

			$crop_w = round( $new_w / $size_ratio );
			$crop_h = round( $new_h / $size_ratio );

			$s_x = floor( ( $orig_w - $crop_w ) / 2 );
			$s_y = floor( ( $orig_h - $crop_h ) / 2 );

			return array( 0, 0, (int) $s_x, (int) $s_y, (int) $new_w, (int) $new_h, (int) $crop_w, (int) $crop_h );
		}

	}

}


if ( ! function_exists('iodtheme_tt_aq_resize') ) {

	/**
	 * Resize an image using iodtheme_tt_Aq_Resize Class
	 *
	 * @since 1.0
	 *
	 * @param string $url     The URL of the image
	 * @param int    $width   The new width of the image
	 * @param int    $height  The new height of the image
	 * @param bool   $crop    To crop or not to crop, the question is now
	 * @param bool   $single  If true only returns the URL, if false returns array
	 * @param bool   $upscale If image not big enough for new size should it upscale
	 * @return mixed If $single is true return new image URL, if it is false return array
	 *               Array contains 0 = URL, 1 = width, 2 = height
	 */
	function iodtheme_tt_aq_resize( $url, $width = null, $height = null, $crop = null, $single = true, $upscale = false ) {
		$aq_resize = iodtheme_tt_Aq_Resize::getInstance();
		return $aq_resize->process( $url, $width, $height, $crop, $single, $upscale );
	}

}

if( !function_exists( 'iodtheme_fw_wc_social' ) ) {
	function iodtheme_fw_wc_social() {
		$output = '';
		if( ! iodtheme_fw_get_option( 'enable_post_share', true) ) return; // do nothing if not enabled in Themeoptions.

		if( shortcode_exists('iodtheme_pinterest')) $output .= do_shortcode('[iodtheme_pinterest float="fl" class="tt-mr15"]');

		if( shortcode_exists('iodtheme_twitter')) $output .= do_shortcode('[iodtheme_twitter use_post_url="true" float="fl" class="tt-mr15"]');

		if( shortcode_exists('iodtheme_fblike')) $output .= do_shortcode('[iodtheme_fblike style="button_count" float="fl" class="tt-mr15"]');

		return $output;
	}
}




/*Sidebar recent post*/
class trueSidebarPost extends WP_Widget {
	function __construct() {
		parent::__construct(
			'iod_recent_post',
			'IOD: Sidebar recent posts Widget',
			array( 'description' => 'Widget with popular posts (for sidebar)' )
		);
	}


	public function widget( $args, $instance ) {
		$title = apply_filters( 'widget_title', $instance['title'] );
		$posts_per_page = $instance['posts_per_page'];
		print $args['before_widget'];
		if ( ! empty( $title ) )
			print $args['before_title'] . $title . $args['after_title'];
		$q = new WP_Query("posts_per_page=$posts_per_page"); /*add &orderby=comment_count if popular */
		if( $q->have_posts() ):
			?>
			<ul class="papu-post">
			<?php
			while( $q->have_posts() ): $q->the_post();?>
				<li class="media">
				<?php if(has_post_thumbnail()) { ?>
				<div class="media-left">
					<a href="<?php the_permalink(); ?>"> <img alt="" src="<?php echo iodtheme_tt_post_thumb('60', '60', false, true, true); ?>" class="media-object"></a>
					<span><?php the_time('d F') ?></span>
				</div>
				<?php } ?>
					<div class="media-body"> <a href="<?php the_permalink(); ?>" class="media-heading"><?php the_title() ?> </a>
				    <p><?php iodtheme_tt_excerpt_charlength('60') ?> </p>
				  </div>
				</li>
				<?php
			endwhile;
			?>
			</ul>
		<?php
		endif;
		wp_reset_postdata();
		print $args['after_widget'];
	}


	public function form( $instance ) {
        //widgetform in backend
        $title = (isset($instance['title'])) ? strip_tags($instance['title']) : '';
        $posts_per_page = (isset($instance['posts_per_page'])) ? $instance['posts_per_page'] : '';
		?>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'title' )); ?>"><?php  esc_html_e('Title ', 'iodtheme'); ?> </label>
			<input class="widefat" id="<?php echo esc_attr($this->get_field_id( 'title' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'title' )); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id( 'posts_per_page' )); ?>"><?php  esc_html_e('Posts per page:', 'iodtheme'); ?></label>
			<input id="<?php echo esc_attr($this->get_field_id( 'posts_per_page' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'posts_per_page' )); ?>" type="text" value="<?php echo ($posts_per_page) ? esc_attr( $posts_per_page ) : '3'; ?>" size="3" />
		</p>
		<?php
	}


	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['posts_per_page'] = ( is_numeric( $new_instance['posts_per_page'] ) ) ? $new_instance['posts_per_page'] : '3';
		return $instance;
	}
}

function true_sidebar_post_widget_load() {
	register_widget( 'trueSidebarPost' );
}
add_action( 'widgets_init', 'true_sidebar_post_widget_load' );


/*==================================*/
/*       LOAD MORE PORTFOLIO ITEMS      */
/*==================================*/

//add_action('wp_head','iodtheme_products_ajaxurl');
function iodtheme_products_ajaxurl() {
	?>
	<!--test-->
	<script type="text/javascript">
		var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
	</script>
	<?php
}


//add_action( 'wp_ajax_load_portfolio_items', 'iodtheme_load_portfolio_items_function' );
//add_action( 'wp_ajax_nopriv_load_portfolio_items', 'iodtheme_load_portfolio_items_function' );
function iodtheme_iodtheme_load_portfolio_items_function () {
	if (!isset($_POST['num']) || !isset($_POST['next_page']) || !isset($_POST['item_cat'])) die();
	$num = $_POST['num'];
	$next_page = $_POST['next_page'];
	$item_cat = $_POST['item_cat'];

	$catsArr = explode(",", $item_cat);
	$args = array(
		'posts_per_page' => $num,
		'post_type' => 'tt_portfolio',
		'paged'     => $next_page,
		'tax_query' => array(
			array(
				'taxonomy' => 'tt_portfolio_cats',
				'field' => 'term_id',
				'terms' => $catsArr,
			),
		),
	);
	$query = new WP_Query($args);
	while ($query->have_posts()) {
		$query->the_post();

		$cur_post_id = get_the_ID();
		$curent_term_array = wp_get_post_terms($cur_post_id, 'tt_portfolio_cats');
		$curent_term_string = '';
		foreach ($curent_term_array as $curent_term_item) {
			$curent_term_string .= ' ' . $curent_term_item->slug;

		} ?>

		<div class="cbp-item <?php print $curent_term_string; ?>">
			<article class="item"><img class="img-responsive" src="<?php the_post_thumbnail_url(); ?>" alt="" >
				<div class="over-detail">
					<div class="top-detail">
						<?php if( iodtheme_fw_get_option('portfolio_cpt_single', '1')) { ?>
						<a href="<?php the_permalink(); ?>" class="<?php if( iodtheme_fw_get_option('ajax_disable', '1') ) {echo 'cbp-singlePage';}?>"><i class="fa fa-link"></i> </a>
						<?php } ?>
						<a href="<?php the_post_thumbnail_url(); ?>" class="cbp-lightbox" data-title=""><i class="icon-magnifier"></i></a>
					</div>
					<div class="bottom-detail">
						<h3><?php the_title(); ?></h3>
						<span><?php print $curent_term_string; ?></span> </div>
				</div>
			</article>
		</div>

		<?php
	}
	wp_reset_postdata();

	die();
}



/*-----------------------------------------------------------------------------------*/
/* END */
/*-----------------------------------------------------------------------------------*/


<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * The main template file.
 *
 * It is used to show single post.
 *
 */
$postimgg = '';
if(function_exists('iodtheme_tt_post_thumb')) $postimgg = iodtheme_tt_post_thumb('750', '240', false, false, true);
?>
<!-- Post -->
<article <?php post_class( 'single-blog-item blog-post' ); ?>>
<?php if ( has_post_thumbnail()) { ?>
        <a href="<?php the_permalink(); ?>">
          <?php the_post_thumbnail(array('750', '240'), array('class' => 'img-responsive')); ?>
        </a>
<?php } ?>
  <!-- Date -->
  <?php get_template_part( 'inc/templates/tt-post-date' ); ?>
  <!-- Detail -->
  <div class="post-detail">
    <h2 class="posttitle"><a href="<?php the_permalink(); ?>" class="post-title <?php if( empty( $postimgg ) ) echo 'padding-left-89';?>"><?php the_title(); ?></a></h2>
    <div <?php if( empty( $postimgg ) ) echo 'class=padding-left-89';?>>
      <span>
        <?php get_template_part( 'inc/templates/tt-post-meta' ); ?>
      </span>
    <!-- Content -->
      <?php the_excerpt(); ?>
      <?php if (pll_current_language("locale")=="ja"){?>

		<a href="<?php the_permalink(); ?>"><?php esc_html_e( '続きを読む', 'iodtheme' ); ?>	
	      <?php }?>
		 <?php if (pll_current_language("locale")=="en_US"){?>  
		<a href="<?php the_permalink(); ?>"><?php esc_html_e( 'Read more', 'iodtheme' ); }?>	
		 <?php if (pll_current_language("locale")=="vi"){?>  
		<a href="<?php the_permalink(); ?>"><?php esc_html_e( 'Xem chi tiết', 'iodtheme' ); }?>	
       <svg width="20px" height="16px" viewBox="288 467 20 20" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="padding-top: 4px;">
    <!-- Generator: Sketch 40.3 (33839) - http://www.bohemiancoding.com/sketch -->
    <desc>Created with Sketch.</desc>
    <defs></defs>
    <path d="M298,467 C292.486,467 288,471.486 288,477 C288,482.514 292.486,487 298,487 C303.514,487 308,482.514 308,477 C308,471.486 303.514,467 298,467 L298,467 Z M303.187667,477.275333 L295.854333,482.275333 C295.798,482.313667 295.732333,482.333333 295.666667,482.333333 C295.613333,482.333333 295.559667,482.320667 295.511,482.294667 C295.401667,482.237 295.333333,482.123667 295.333333,482 L295.333333,472 C295.333333,471.876333 295.401667,471.763 295.511,471.705333 C295.620333,471.647333 295.752333,471.655333 295.854667,471.724667 L303.188,476.724667 C303.278667,476.786667 303.333333,476.889667 303.333333,477 C303.333333,477.110333 303.278667,477.213333 303.187667,477.275333 L303.187667,477.275333 Z" id="ico_readMore" stroke="none" fill="#8CC63F" fill-rule="evenodd"></path>
</svg></a>
    </div>
  </div>
</article>


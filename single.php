<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * The main template file.
 *
 * It is used to show single post.
 *
 */

 get_header();

if(function_exists('iodtheme_tt_post_thumb')) $postimgg = iodtheme_tt_post_thumb('750', '240', false, false, true);
?>
<div id="content" class="section mainblock">
<div class="blog blog-pages">
    <div class="container">
        <div class="row">
          <div class="col-md-8">
			<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
            <!-- Post -->
            <article>
			    <?php if ( has_post_thumbnail()) { ?>
                  <img src="<?php echo esc_url($postimgg); ?>" alt="<?php echo iodtheme_fw_img_alt('',$post->ID); ?>" />
			    <?php } ?>
              <!-- Date -->
              <?php get_template_part( 'inc/templates/tt-post-date' ); ?>
              <!-- Detail -->
              <div class="post-detail <?php if( empty( $postimgg ) ) echo 'padding-left-89';?>">
                    <h1 class="posttitle"><?php the_title(); ?></h1>
                    <span><?php get_template_part( 'inc/templates/tt-post-meta' ); ?></span>

                  <!-- Content -->
                   <?php the_content(); ?>
                   <?php wp_link_pages(); ?>

					<!-- Tags  -->
					<?php
					the_tags(
					    '<span class="singletags">',
					    ', ', // separator
					    '</span>' // after
					);
					?>

                    <!-- Category  -->
					<?php if( has_category() ) { ?>
					<div class="clearfix"></div>
					<span class="category"><?php esc_html_e( 'Posted in: ', 'iodtheme' ); ?>
						<?php if( function_exists('iodtheme_tt_get_cats') ) echo iodtheme_tt_get_cats( ); ?>
					</span>
					<?php } ?>

                    <!-- Post nav -->
                    <div class="clearfix mbottom30"></div>
                    <?php if( function_exists('iodtheme_tt_prev_post') ) echo iodtheme_tt_prev_post(); ?>
                    <?php if( function_exists('iodtheme_tt_next_post') ) echo iodtheme_tt_next_post(); ?>
                    <div class="clearfix"></div>

                    <?php endwhile; endif; ?>

                    <!-- Post sharing meta -->
                    <?php // get_template_part( 'inc/templates/tt-post-sharing' ); ?>
			  </div>
            </article>

				<!-- commments -->
	             <?php if ( comments_open() ) { comments_template( '', true ); } ?>

          </div>

          <!-- Side Bar -->
          <div class="col-md-4">
	         <?php get_sidebar(); ?>
          </div>
        </div>
    </div>
</div>
</div>

<?php get_footer(); ?>
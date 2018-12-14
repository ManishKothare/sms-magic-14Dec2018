<?php
/**
 * Navy Child Theme: search results page
 * @package WordPress
 * @subpackage Navy Theme
 * @since 1.0
 */

	get_header();

 	$hgr_options = get_option( 'redux_options' );
 ?>


<div class="row blog blogPosts <?php echo (isset($hgr_options['blog_color_scheme']) ? $hgr_options['blog_color_scheme'] : '');?>">
  <div class="container"> 
    <!-- posts -->
    <div class="vc_col-sm-9">
      <h1 class="titleSep" style="margin-bottom:10px;">
        <?php //esc_html_e('You\'ve searched for: ', 'navy'); ?>
        <?php //echo get_search_query(); ?>
        <?php //esc_html( get_search_query() ); ?>
      </h1>
      <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
      <div class="post">
        
        <!-- Display the Title as a link to the Post's permalink. -->
         <?php if($format != 'aside') : ?>
        <h1><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>">
        <?php //the_title(); ?>
          <?php relevanssi_the_title(); ?>
          </a></h1>
        <?php endif;?>
        <?php 
		if ( has_post_thumbnail() ){
			echo('<div class="figure1 crop-bottom nonempty">');
			the_post_thumbnail('medium_large', array('class' => 'img-responsive'));
			echo('</div>');
		}else{
			echo('<div class="figure1 crop-bottom"></div>');
		}
	?>
        
        <!-- Display the Post's content in a div box. -->
        <div class="entry">
          <?php //if(has_excerpt()) : ?>
          <?php the_excerpt(); ?>
          <?php //else : ?>
          <?php //the_content(); ?>
          <?php //endif;?>
        </div>
       <!-- <div class="entry-meta">
          <?php //the_tags(); ?>
        </div>-->
      </div>
      <?php endwhile; ?>
      
      <?php
        $prev_link = get_previous_posts_link( esc_html__('&larr; Previous', 'navy') );
        $next_link = get_next_posts_link( esc_html__('Next &rarr;', 'navy') );
    
        if ($prev_link || $next_link) : ?>
    
      <div class="navigation">
        <div class="alignleft">
          <?php if ($prev_link) { echo $prev_link; } ?>
        </div>
        <div class="alignright">
          <?php if ($next_link) { echo $next_link; } ?>
        </div>
      </div>
      <?php endif;?>
      
      <?php else: ?>
      <p>
        <?php esc_html_e('Sorry, no posts matched your criteria.', 'navy'); ?>
      </p>
      
      <h3 style="margin-bottom:10px;margin-top:30px;">
        <?php esc_html_e('Some recent posts you might be interested in: ', 'navy'); ?>
      </h3>
      
      <?php $args = array(
			'type'            => 'postbypost',
			'limit'           => '10',
			'format'          => 'custom', 
			'before'          => '<p>',
			'after'           => '</p>',
			'show_post_count' => false,
			'echo'            => 1,
			'order'           => 'DESC'
		);
		wp_get_archives( $args ); 		
	?>
      
      <?php endif; ?>
    </div>
    <!-- / posts --> 
    
    <!-- sidebar -->
    <div class="vc_col-sm-3">
      <?php 
		//get_sidebar('nurture');
	 ?>
    </div>
    <!-- / sidebar --> 
    <div class="clearfix"></div>
  </div>
</div>
<?php 
 	get_footer();
 ?>
 <?php
/**
 * Navy Child Theme: Blog, front page
 * @package WordPress
 * @subpackage Navy Theme
 * @since 1.0
 */
	get_header();
 ?>
 
<!-- Header Image -->
<?php if (get_header_image() != '') : ?>

<div class="header_image_container"> <img src="<?php header_image(); ?>" height="<?php echo get_custom_header()->height; ?>" width="<?php echo get_custom_header()->width; ?>" alt="header image" class="header_image" />
  <div class="headerWelcome">
    <h1>
      <?php bloginfo('name'); ?>
    </h1>
    <h2><a href="#" class="readTheBlogBtn"><?php esc_html_e( 'Read the blog', 'navy' );?></a></h2>
  </div>
</div>
<?php endif;?>
<!-- Header Image End --> 

<!-- blog content -->
<div class="row blogPosts <?php echo (isset($hgr_options['blog_color_scheme']) ? $hgr_options['blog_color_scheme'] : '');?>" id="blogPosts">
  <div class="container"> 
    <!-- posts -->
    <div class="vc_col-sm-8">
      <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
      <?php 
	  	$format = get_post_format();
	  ?>
      <div id="post-<?php the_ID(); ?>" <?php post_class('post'); ?>>

        <?php if($format != 'aside') : ?>
        <h1><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>">
          <?php the_title(); ?>
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
        
        <small><span class="highlight"><i class="icon blog-user"></i><?php esc_html_e('Posted by', 'navy');?>
        <?php the_author_posts_link() ?>
        </span> <span class="highlight"><i class="icon blog-date"></i><?php the_time('F j, Y') ?>
        </span><!--<span class="highlight"><i class="icon blog-category"></i>
        <?php //the_category(', '); ?>
        </span> <span class="highlight"><i class="icon blog-comments"></i>
        <?php
			/*$comments_number = get_comments_number();
			if ( 1 === $comments_number ) {
				
				printf( _x( 'One thought on &ldquo;%s&rdquo;', 'comments title', 'navy' ), get_the_title() );
			} else {
				printf(
					
					_nx(
						'%1$s thought on &ldquo;%2$s&rdquo;',
						'%1$s thoughts on &ldquo;%2$s&rdquo;',
						$comments_number,
						'comments title',
						'navy'
					),
					number_format_i18n( $comments_number ),
					get_the_title()
				);
			}*/
		?>
        </span>--></small> 
        <div class="entry">
          <?php //if(has_excerpt()) : ?>
          <?php the_excerpt(); ?>
          <?php //else : ?>
          <?php //the_content(); ?>
          <?php //endif;?>
        </div>
        <!-- <div class="entry-meta">
          <?php //the_tags(); ?>
        </div> -->
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
      <?php endif; ?>
      <?php wp_reset_postdata();?>
    </div>
    <!-- / posts --> 
    
    <!-- sidebar -->
    <div class="vc_col-sm-4">
      <?php 
		get_sidebar();
	 ?>
    </div>
    <!-- / sidebar --> 
  </div>
</div>
<!-- blog content end -->

 <?php 
 	get_footer();
 ?>
<?php
/**
 * Navy Child Theme: Blog page, single post display
 * @package WordPress
 * @subpackage Navy Theme
 * @since 1.0
 */

	get_header();

	$hgr_options = get_option( 'redux_options' );
 ?>
<!-- SINGLE BLOG POST -->
<div class="row blog blogPosts <?php echo (isset($hgr_options['blog_color_scheme']) ? $hgr_options['blog_color_scheme'] : '');?>" id="blogPosts">
  <div class="container"> 
    <!-- posts yyy -->
    <div class="vc_col-sm-8">
      <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
      <div <?php post_class('post'); ?>>
        <h1><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>">
          <?php the_title(); ?>
          </a></h1>

          <?php 
              if ( has_post_thumbnail() ) { // check if the post has a Post Thumbnail assigned to it.
                the_post_thumbnail('full', array('class' => 'img-responsive'));
              } 
          ?>
        
        <small><span class="highlight"><i class="icon blog-date"></i>
        <?php the_time('F j, Y') ?>
        </span> <span class="highlight"><i class="icon blog-user"></i><?php esc_html_e( 'Posted by', 'navy' );?>
        <?php the_author_posts_link() ?>
        </span> <!--<span class="highlight"><i class="icon blog-category"></i>
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
        <!-- Display the Post's content in a div box. -->
        <div class="entry">
          <?php the_content(); ?>
        </div>
        <?php // Paginated post
						$args = array(
							'before'           => '<ul class="pagination">',
							'after'            => '</ul>',
							'link_before'      => '',
							'link_after'       => '',
							'next_or_number'   => 'number',
							'separator'        => ' ',
							'nextpagelink'     => esc_html__( 'Next page','navy' ),
							'previouspagelink' => esc_html__( 'Previous page','navy' ),
							'pagelink'         => '%',
							'echo'             => 1
						);
						wp_link_pages( $args );
					 ?>
        <div class="clear"></div>
        <small>
        <?php the_tags('Tags: <span class="highlight">', ', ', '</span>'); ?>
        </small> 
        <!-- Display a comma separated list of the Post's Categories. --> 
        
      </div>
      <!-- closes the first div box --> 
      
      <!-- comments-->
      <?php 
	  	// If comments are open or we have at least one comment, load up the comment template.
			if ( comments_open() || get_comments_number() ) {
				comments_template();
			}
	  ?>
      
      <?php endwhile; else: ?>
      <p>
        <?php esc_html_e('Sorry, no posts matched your criteria.', 'navy'); ?>
      </p>
      <?php endif; ?>
    </div>
    <!-- / posts --> 
    
    <!-- sidebar -->
    <div class="vc_col-sm-4">
      <?php 
		get_sidebar();
	 ?>
    </div>
    <!-- / sidebar -->
    <div class="clearfix"></div>
  </div>
</div>
<?php 
 	get_footer();
 ?>
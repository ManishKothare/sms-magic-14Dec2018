<?php
/**
 * Template Name: 	Help Docs Public Page
 * Navy Theme:		Help Docs Public Page
 * @package:		WordPress
 * @subpackage:		Navy Theme
 * @version:		1.0
 * @since:			1.0
 */
 
 	// Include framework options
 	$hgr_options = get_option( 'redux_options' );
 
	get_header('help-docs');
 ?>

 <?php
	// Get metaboxes values from database
	$hgr_page_bgcolor			=	get_post_meta( get_the_ID(), '_hgr_page_bgcolor', true );
	$hgr_page_top_padding		=	get_post_meta( get_the_ID(), '_hgr_page_top_padding', true );
	$hgr_page_btm_padding		=	get_post_meta( get_the_ID(), '_hgr_page_btm_padding', true );
	$hgr_page_color_scheme		=	get_post_meta( get_the_ID(), '_hgr_page_color_scheme', true );
	$hgr_page_height			=	get_post_meta( get_the_ID(), '_hgr_page_height', true );
	$hgr_page_title				=	get_post_meta( get_the_ID(), '_hgr_page_title', true );
	$hgr_page_title_color		=	get_post_meta( get_the_ID(), '_hgr_page_title_color', true );
	
	$page_title_color			=	( !empty($hgr_page_title_color) ? ' style="color: '.$hgr_page_title_color.'; "' : ( isset($hgr_options['page_title_h1']['color']) && !empty($hgr_options['page_title_h1']['color']) ? '' : ' style="color: #000; "' ) );
												
												
												
												
	
	// Does this page have a featured image to be used as row background with paralax?!
 	$src = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), array( 5600,1000 ), false, '' );

 	if( !empty($src[0]) ) {
		$parallaxImageUrl 	=	" background-image:url('".$src[0]."'); background-size: cover;";
		$backgroundColor	=	'';
	} elseif( !empty($hgr_page_bgcolor) ) {
		$parallaxImageUrl 	=	'';
		$backgroundColor	=	' background-color:'.$hgr_page_bgcolor.'!important; ';
	} else {
		$parallaxImageUrl 	=	'';
		$backgroundColor	=	' ';
	}
	
	$page_title_top_padding = ( isset($hgr_options['page_title_padding']['padding-top']) ? $hgr_options['page_title_padding']['padding-top'] : '0');
	$page_title_btm_padding = ( isset($hgr_options['page_title_padding']['padding-bottom']) ? $hgr_options['page_title_padding']['padding-bottom'] : '0');
	$page_title_lft_padding = ( isset($hgr_options['page_title_padding']['padding-left']) ? $hgr_options['page_title_padding']['padding-left'] : '0');
	$page_title_rgt_padding = ( isset($hgr_options['page_title_padding']['padding-right']) ? $hgr_options['page_title_padding']['padding-right'] : '0');
	$page_offset			= ( isset($hgr_options['page_top_offset']['height']) ? $hgr_options['page_top_offset']['height'] : '0');
 ?>
 
 <?php if( class_exists("WooCommerce") && is_cart() && WC()->cart->get_cart_contents_count() == 0 ) : ?>
 	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
 		<?php the_content(); ?>
 	<?php endwhile; endif; ?>
 <?php else : ?>
 
 <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
 <div id="<?php echo esc_html($post->post_name);?>" class="row standAlonePage <?php echo esc_attr($hgr_page_color_scheme);?>" style=" <?php echo esc_attr($backgroundColor);?> ">
  <div class="col-md-12" <?php echo ( isset($page_offset) && $page_offset	!= 0 ? 'style="margin-top:'.$page_offset	.';"' : '');?> >
  <?php if( isset($hgr_options['enable_page_title']) && $hgr_options['enable_page_title'] == 1) : ?>
		<?php if( isset($hgr_page_title) && empty($hgr_page_title) ): ?>
        <div class="page_title_container" style=" <?php echo esc_attr($parallaxImageUrl);?> padding: <?php echo esc_attr($page_title_top_padding);?> <?php echo esc_attr($page_title_rgt_padding);?> <?php echo esc_attr($page_title_btm_padding);?> <?php echo esc_attr($page_title_lft_padding);?>; ">
            <div class="container">
                <h1 class="" <?php echo esc_attr($page_title_color);?> ><?php the_title(); ?></h1>
            </div>
        </div>
      <?php endif;?>
  <?php endif;?>
  
    <div class="container" style=" <?php echo ( !empty($hgr_page_top_padding) ? ' padding-top:'.esc_attr($hgr_page_top_padding).'px!important;' : '' ); echo ( !empty($hgr_page_btm_padding) ? ' padding-bottom:'.esc_attr($hgr_page_btm_padding).'px!important;' : '' );?> ">
      <!--<div class="slideContent vc_col-md-9 vc_col-sm-12 vc_col-xs-12" style="float:right;">-->
      <div style="margin-top:50px;">
        <?php the_content(); ?>
       </div>
        
        <?php if(is_paged()) : ?>
      <?php paginate_comments_links(); ?>
      <?php endif;?>
      <?php comments_template(); ?>
      <?php if(is_paged()) : ?>
      <?php paginate_comments_links(); ?>
      <?php endif;?>
      
      <!--</div>-->
      
       <!-- sidebar -->
    <!--<div class="vc_col-md-3 vc_col-sm-12 vc_col-xs-12" style="float:left;">
      <?php 
		//if ( is_active_sidebar( 'page-widgets' ) ) { 
			//dynamic_sidebar('page-widgets');
		//}
	 ?>
    </div>-->
    <!-- / sidebar -->
    <div class="clearfix"></div>
    </div>
  </div>
    
</div>
<?php endwhile; endif; ?>

<?php endif;?>

<?php 
 	get_footer('blank');
 ?>

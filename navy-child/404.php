 <?php
/**
 * Navy Theme: 404 error page
 * @package WordPress
 * @subpackage Navy Theme
 * @since 1.0
 */
 ?>
 
<?php 
	get_header();

 	$hgr_options	=	get_option( 'redux_options' );
 	$custom_error_page = (isset($hgr_options['custom_error_page']) ? $hgr_options['custom_error_page'] : '');
 ?>
 
 
 <?php
	// Get metaboxes values from database
	$hgr_page_bgcolor			=	get_post_meta( $custom_error_page, '_hgr_page_bgcolor', true );
	$hgr_page_top_padding		=	get_post_meta( $custom_error_page, '_hgr_page_top_padding', true );
	$hgr_page_btm_padding		=	get_post_meta( $custom_error_page, '_hgr_page_btm_padding', true );
	$hgr_page_color_scheme		=	get_post_meta( $custom_error_page, '_hgr_page_color_scheme', true );
	$hgr_page_height			=	get_post_meta( $custom_error_page, '_hgr_page_height', true );
	$hgr_page_title				=	get_post_meta( $custom_error_page, '_hgr_page_title', true );
	$hgr_page_title_color		=	get_post_meta( $custom_error_page, '_hgr_page_title_color', true );
	
	$page_title_color			=	( !empty($hgr_page_title_color) ? ' style="color: '.esc_attr( $hgr_page_title_color ).'; "' : ( isset($hgr_options['page_title_h1']['color']) && !empty($hgr_options['page_title_h1']['color']) ? '' : ' style="color: #000; "' ) );
	
	// Does this page have a featured image to be used as row background with paralax?!
 	$src = wp_get_attachment_image_src( get_post_thumbnail_id($custom_error_page), array( 5600,1000 ), false, '' );

 	if( !empty($src[0]) ) {
		$parallaxImageUrl 	=	" background-image:url('".$src[0]."'); ";
		$parallaxClass		=	' parallax ';
		$backgroundColor	=	'';
	} elseif( !empty($hgr_page_bgcolor) ) {
		$parallaxImageUrl 	=	'';
		$parallaxClass		=	' ';
		$backgroundColor	=	' background-color:' . esc_attr( $hgr_page_bgcolor ).'!important; ';
	} else {
		$parallaxImageUrl 	=	'';
		$parallaxClass		=	' ';
		$backgroundColor	=	' ';
	}
	
	
	$page_title_top_padding = ( isset($hgr_options['page_title_padding']['padding-top']) ? esc_attr( $hgr_options['page_title_padding']['padding-top'] ) : '0');
	$page_title_btm_padding = ( isset($hgr_options['page_title_padding']['padding-bottom']) ? esc_attr( $hgr_options['page_title_padding']['padding-bottom'] ) : '0');
	$page_title_lft_padding = ( isset($hgr_options['page_title_padding']['padding-left']) ? esc_attr( $hgr_options['page_title_padding']['padding-left'] ) : '0');
	$page_title_rgt_padding = ( isset($hgr_options['page_title_padding']['padding-right']) ? esc_attr( $hgr_options['page_title_padding']['padding-right'] ) : '0');
	$page_offset			= ( isset($hgr_options['page_top_offset']['height']) ? esc_attr( $hgr_options['page_top_offset']['height'] ) : '0');
 ?> 
 
<div id="<?php echo esc_html($post->post_name);?>" class="row standAlonePage <?php echo esc_attr($hgr_page_color_scheme);?>" style=" <?php echo esc_attr($backgroundColor);?> ">
  <div class="col-md-12" <?php echo ( isset($page_offset) && $page_offset	!= 0 ? 'style="margin-top:'.$page_offset	.';"' : '');?> >
  
  <?php if( isset($hgr_options['enable_page_title']) && esc_attr( $hgr_options['enable_page_title'] ) == 1) : ?>
	  <?php if( isset($hgr_page_title) && empty($hgr_page_title) ): ?>
        <div class="page_title_container" style=" <?php echo esc_url( $parallaxImageUrl );?> padding: <?php echo esc_attr( $page_title_top_padding) ;?> <?php echo esc_attr( $page_title_rgt_padding );?> <?php echo esc_attr( $page_title_btm_padding );?> <?php echo esc_attr($page_title_lft_padding);?>; ">
            <div class="container">
		<div class="wpb_wrapper" style="margin-top:-50px; margin-bottom:-50px;">
			<div class="wpb_single_image wpb_content_element vc_align_center">
				
				<figure class="wpb_wrapper vc_figure">
					<div class="vc_single_image-wrapper   vc_box_border_grey"><img width="789" height="364" src="http://launch.sms-magic.com/wp-content/uploads/2018/06/404-Image.png" class="vc_single_image-img attachment-full" alt="" srcset="http://launch.sms-magic.com/wp-content/uploads/2018/06/404-Image.png 789w, http://launch.sms-magic.com/wp-content/uploads/2018/06/404-Image-300x138.png 300w, http://launch.sms-magic.com/wp-content/uploads/2018/06/404-Image-768x354.png 768w" sizes="(max-width: 789px) 100vw, 789px"></div>
				</figure>
			</div>

			<div class="wpb_text_column wpb_content_element" style="margin-top:-30px;">
				<div class="wpb_wrapper">
					<p style="text-align: center; font-size:20px; color:#2d9cdb;">OOPS! You found a website mistake.</p>
					<p style="text-align: center;">Good thing we're text messaging experts. It’s all we do. Which is why we don’t make mistakes with our customers’ messaging strategies!</p>
					<p style="text-align: center;">Please click below to get back to best practices in text messaging.</p>

				</div>
			</div>
			<div class="vc_btn3-container vc_btn3-center">
				<a class="vc_general vc_btn3 vc_btn3-size-md vc_btn3-shape-rounded vc_btn3-style-classic vc_btn3-color-turquoise" href="http://launch.sms-magic.com/">BACK TO THE MAGIC</a></div>
			</div>
		</div>
            </div>
        </div>
      <?php endif;?>
  <?php endif;?>
  
    <div class="container" style=" <?php echo ( !empty($hgr_page_top_padding) ? ' padding-top:' . esc_attr( $hgr_page_top_padding ) . 'px!important;' : '' ); echo ( !empty($hgr_page_btm_padding) ? ' padding-bottom:' . esc_attr( $hgr_page_btm_padding ) . 'px!important;' : '' );?> ">
      <div class="slideContent gu12">
        <?php
      	if($custom_error_page){	
			$post = get_post($custom_error_page); 
			$content = apply_filters('the_content', $post->post_content); 
			echo $content;  
		} else {
      ?>
      
      <?php
		}
	  ?>
      
      </div>
    </div>
  </div>
</div>

<?php 
 	get_footer();
 ?>
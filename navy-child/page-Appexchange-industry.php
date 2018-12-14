<?php
/**
 * Template Name: 	Appexchange Industry Page
 * Navy Theme:		Appexchange Industry Page
 * @package:		WordPress
 * @subpackage:		Navy Theme
 * @version:		1.0
 * @since:			1.0
 */
 
 	// Include framework options
 	$hgr_options = get_option( 'redux_options' );
 
	get_header('appexchange-bg');
 ?>

 <?php
	// Get metaboxes values from database
	$hgr_page_bgcolor		=	get_post_meta( get_the_ID(), '_hgr_page_bgcolor', true );
	$hgr_page_top_padding		=	get_post_meta( get_the_ID(), '_hgr_page_top_padding', true );
	$hgr_page_btm_padding		=	get_post_meta( get_the_ID(), '_hgr_page_btm_padding', true );
	$hgr_page_color_scheme		=	get_post_meta( get_the_ID(), '_hgr_page_color_scheme', true );
	$hgr_page_height		=	get_post_meta( get_the_ID(), '_hgr_page_height', true );
	$hgr_page_title			=	get_post_meta( get_the_ID(), '_hgr_page_title', true );
	$hgr_page_title_color		=	get_post_meta( get_the_ID(), '_hgr_page_title_color', true );
	
	$page_title_color		=	( !empty($hgr_page_title_color) ? ' style="color: '.$hgr_page_title_color.'; "' : ( isset($hgr_options['page_title_h1']['color']) && !empty($hgr_options['page_title_h1']['color']) ? '' : ' style="color: #000; "' ) );
												
												
												
												
	
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
      <div class="SMS-Magic_Logo"><a href="<?php echo get_site_url()?>"><img src="<?php echo get_site_url()?>/wp-content/themes/navy-child/img/sms-logo-white.svg" alt="logo" /></a></div>
      <div style="margin-top:40px;">
        <?php //the_content(); ?>
        
        <div class="vc_row wpb_row vc_row-fluid Get-started-with-SMS"><div class="wpb_column vc_column_container vc_col-sm-3"><div class="vc_column-inner "><div class="wpb_wrapper"></div></div></div><div class="wpb_column vc_column_container vc_col-sm-6 vc_col-has-fill"><div class="vc_column-inner vc_custom_1535703846312"><div class="wpb_wrapper">
	<div class="wpb_text_column wpb_content_element  vc_custom_1535710956144">
		<div class="wpb_wrapper">
			<p class="Get-started-with-SMS">Select a Trial Org</p>

		</div>
	</div>

	<div class="wpb_text_column wpb_content_element  box-text">
		<div class="wpb_wrapper">
			<p class="Get-started-with-SMS">Below are a select set of Industries for your trial. We have Converse Apps and use cases for a larger set of industries.</p>

<p class="Get-started-with-SMS">If you’d like, you can also schedule a call with a textpert to discuss Converse in your industry. Just go to <a href="https://www.sms-magic.com/">sms-magic.com</a> to schedule a call.</p>

		</div>
	</div>
<div class="vc_empty_space" style="height: 20px"><span class="vc_empty_space_inner"></span></div>
<div class="vc_row wpb_row vc_inner vc_row-fluid vc_column-gap-35">
       
       
       <ul class="industry-blocks-Wrapper">
	      <li class="industry-block" data-formslug="<?php echo get_site_url()?>/appexchange-converse-trial/select-option/use-case/info/"><img width="60" height="60" src="<?php echo get_site_url()?>/wp-content/uploads/2018/08/financial-services.png" class="vc_single_image-img attachment-full" alt="">
	      <p class="industry-title">Financial Services</p></li>
	      <li class="industry-block" data-formslug="<?php echo get_site_url()?>/appexchange-converse-trial/select-option/use-case/info/"><img width="60" height="53" src="<?php echo get_site_url()?>/wp-content/uploads/2018/08/higher-education.png" class="he-icon vc_single_image-img attachment-full" alt="">
	      <p class="industry-title">Higher Education</p></li>
	      <li class="industry-block" data-formslug="<?php echo get_site_url()?>/appexchange-converse-trial/select-option/use-case/info/"><img width="56" height="60" src="<?php echo get_site_url()?>/wp-content/uploads/2018/08/contact-center.png" class="vc_single_image-img attachment-full" alt="">
	      <p class="industry-title">Contact Center</p></li>       
       </ul>

</div><div class="vc_empty_space" style="height: 40px"><span class="vc_empty_space_inner"></span></div>
<div class="vc_row wpb_row vc_inner vc_row-fluid"><div class="wpb_column vc_column_container vc_col-sm-3"><div class="vc_column-inner "><div class="wpb_wrapper"></div></div></div><div class="wpb_column vc_column_container vc_col-sm-3"><div class="vc_column-inner "><div class="wpb_wrapper"><div class="vc_btn3-container  vc_btn3-right">
	<a style="background-color:#ffffff; color:#2d9cdb;" class="go-btn vc_general vc_btn3 vc_btn3-size-lg vc_btn3-shape-round vc_btn3-style-custom" href="<?php echo get_site_url()?>/appexchange-converse-trial/select-option/">Go Back</a></div>
</div></div></div><div class="wpb_column vc_column_container vc_col-sm-3"><div class="vc_column-inner "><div class="wpb_wrapper"><div class="vc_btn3-container vc_btn3-left">
	<a style="background-color:#2d9cdb; color:#ffffff;" class="cont-btn vc_general vc_btn3 vc_btn3-size-lg vc_btn3-shape-round vc_btn3-style-custom" href="#">Continue</a></div>
</div></div></div><div class="wpb_column vc_column_container vc_col-sm-3"><div class="vc_column-inner "><div class="wpb_wrapper"></div></div></div></div><div class="vc_empty_space" style="height: 20px"><span class="vc_empty_space_inner"></span></div>
</div></div></div><div class="wpb_column vc_column_container vc_col-sm-3"><div class="vc_column-inner "><div class="wpb_wrapper"></div></div></div>

</div>
        
<div class="vc_row wpb_row vc_row-fluid"><div class="wpb_column vc_column_container vc_col-sm-12"><div class="vc_column-inner "><div class="wpb_wrapper"><div class="vc_empty_space" style="height: 60px"><span class="vc_empty_space_inner"></span></div>
</div></div></div></div>
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

<script type='text/javascript'>
(function($){
    $(document).ready(function(){

      $(".cont-btn").attr("disabled", "disabled");
      $(".cont-btn").removeAttr("href");
      $(".cont-btn").addClass("dissable-btn");
	
      $("li").click(function(){
	// If this isn't already active
	if (!$(this).hasClass("selected")) {
	  // Remove the class from anything that is active
	  $("li.selected").removeClass("selected");
	  // And make this active
	  $(this).addClass("selected");
	  
	  var formslug=$(this).data('formslug');
	  var usecase=$(this).find('.industry-title').text();
	  /*console.log(formslug);*/
	  $(".cont-btn").removeAttr("disabled");
	  $(".cont-btn").removeClass("dissable-btn");
	  <?php $exptime=time()+7200; ?>
	  document.cookie = "selected_usecase="+usecase+"; expires=<?php echo($exptime); ?>; path=/";
	  $(".cont-btn").attr("href", formslug);
	}
      });
    });
})(jQuery);
</script>


<?php 
 	get_footer('appexchange');
 ?>

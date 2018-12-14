<?php
/**
 * Navy Theme help docs header file
 * @package WordPress
 * @subpackage Navy Theme
 * @since 1.0
 * TO BE INCLUDED IN ALL OTHER PAGES
 */
 $hgr_options = get_option( 'redux_options' );
 $detect = new Mobile_Detect;
?>

<?php
 //$referrer=$_SERVER['HTTP_REFERER']; //echo($referrer);
 $page=$_SERVER['REQUEST_URI'];	//echo($page);
 //var_dump($user_profile_info);
 techdocs_access_check($page);	
?>

<!DOCTYPE html>
<!--[if IE 9 ]>    <html class="ie ie9 ie-lt10 no-js" <?php language_attributes(); ?>> <![endif]-->
<!--[if gt IE 9]><!--><html class="no-js" <?php language_attributes(); ?>><!--<![endif]-->
<!-- the "no-js" class is for Modernizr. --> 
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php if ( ! function_exists( 'has_site_icon' ) || ! has_site_icon() ) : ?>
<?php echo ( !empty($hgr_options['retina_favicon']['url']) ? '<link href="'.$hgr_options['retina_favicon']['url'].'" rel="icon">'."\r\n" : '' ); ?>
<?php echo ( !empty($hgr_options['iphone_icon']['url']) ? '<link href="'.$hgr_options['iphone_icon']['url'].'" rel="apple-touch-icon">'."\r\n" : ''); ?>
<?php echo ( !empty($hgr_options['retina_iphone_icon']['url']) ? '<link href="'.$hgr_options['retina_iphone_icon']['url'].'" rel="apple-touch-icon" sizes="76x76" />'."\r\n" : ''); ?>
<?php echo ( !empty($hgr_options['ipad_icon']['url']) ? '<link href="'.$hgr_options['ipad_icon']['url'].'" rel="apple-touch-icon" sizes="120x120" />'."\r\n" : ''); ?>
<?php echo ( !empty($hgr_options['ipad_retina_icon']['url']) ? '<link href="'.$hgr_options['ipad_retina_icon']['url'].'" rel="apple-touch-icon" sizes="152x152" />'."\r\n" : ''); ?>
<?php endif; ?>

<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

<style>
#hgr_top_navbar_extras{display:none !important;}
#main_navbar_container{display:none !important;}
.top-bar-header{display:none !important;}
.logout-btn{z-index:10; top:25px !important; right:20px; padding-left:35px !important; padding-right:35px !important;}
.vc_btn3.vc_btn3-size-md.themebtncustomization{padding-left:30px !important; padding-right:30px !important; padding-top:5px; padding-bottom:5px; margin:0px auto; font-size:80% !important;}
</style>

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?> >

<?php 
	/*
	*	Custom hook
	*/
	navy_after_body_open(); 
?>

<!--Website Boxed START-->
<div id="website_boxed">
	<?php
		if( (isset($_SESSION['LoggedIn']) || $_SESSION['LoggedIn']==TRUE) ){
			echo ('<div class="vc_btn3-container vc_btn3-right"><a class="logout-btn vc_general vc_btn3 vc_btn3-size-md vc_btn3-shape-rounded vc_btn3-style-classic vc_btn3-color-turquoise themebtncustomization" href="'. get_stylesheet_directory_uri() .'/includes/logout.php" >Logout</a></div>');
			echo ('<div class="clearfix"></div>');
		}
	?>
<?php
	/*if( $detect->isMobile() || $detect->isTablet() ){
		// IS MOBILE
		@require_once( get_template_directory() . '/layouts/headers/mobile_header.php' );
	}
	elseif ( isset($hgr_options['header_floating']) && $hgr_options['header_floating'] == '6'){
		// IS DESKTOP - COMPLEX HEADER
		@require_once( get_template_directory() . '/layouts/headers/complex_header.php' );
	}
	else {
		// IS DESKTOP
			
		// 1 Fixed Header (Left Logo)
		if( isset($hgr_options['header_floating']) && $hgr_options['header_floating'] == '1' ){
			@require_once( get_template_directory() . '/layouts/headers/fixed_header.php' );
		}
		// 2 Appears after scrolling (Left Logo)
		elseif( isset($hgr_options['header_floating']) && $hgr_options['header_floating'] == '2' ){
			@require_once( get_template_directory() . '/layouts/headers/appear_after_scroll.php' );
		}
		// 3 Dissapears after scrolling (Left Logo)
		elseif( isset($hgr_options['header_floating']) && $hgr_options['header_floating'] == '3' ){
			@require_once( get_template_directory() . '/layouts/headers/disappear_after_scroll.php' );
		}
		// 4 Shrinks after scrolling (Left Logo)
		elseif( isset($hgr_options['header_floating']) && $hgr_options['header_floating'] == '4' ){
			@require_once( get_template_directory() . '/layouts/headers/shrink_after_scroll.php' );
		}
		// 5 Transparent before scrolling (Left Logo)
		elseif( isset($hgr_options['header_floating']) && $hgr_options['header_floating'] == '5' ){
			@require_once( get_template_directory() . '/layouts/headers/transparent_before_scroll.php' );
		}
		// 6 Complex header (Left Logo)
		elseif( isset($hgr_options['header_floating']) && $hgr_options['header_floating'] == '6' ){
			@require_once( get_template_directory() . '/layouts/headers/complex_header.php' );
		}
		// 7 Fixed Header (Central Logo)
		elseif( isset($hgr_options['header_floating']) && $hgr_options['header_floating'] == '7' ){
			@require_once( get_template_directory() . '/layouts/headers/fixed_header_central_logo.php' );
		}
		// 8 Appears after scrolling (Central Logo)
		elseif( isset($hgr_options['header_floating']) && $hgr_options['header_floating'] == '8' ){
			@require_once( get_template_directory() . '/layouts/headers/appear_after_scroll_central_logo.php' );
		}
		// 9 Dissapears after scrolling (Central Logo) 
		elseif( isset($hgr_options['header_floating']) && $hgr_options['header_floating'] == '9' ){
			@require_once( get_template_directory() . '/layouts/headers/disappear_after_scroll_central_logo.php' );
		}
		// 10 Shrinks after scrolling (Central Logo)
		elseif( isset($hgr_options['header_floating']) && $hgr_options['header_floating'] == '10' ){
			@require_once( get_template_directory() . '/layouts/headers/shrink_after_scroll_center_logo.php' );
		}
		// 11 Transparent before scrolling (Central Logo)
		elseif( isset($hgr_options['header_floating']) && $hgr_options['header_floating'] == '11' ){
			@require_once( get_template_directory() . '/layouts/headers/transparent_before_scroll_central_logo.php' );
		}
		// DEFAULT and FALLBACK: 1 Fixed Header (Left Logo)
		else {
			@require_once( get_template_directory() . '/layouts/headers/fixed_header.php' );
		}
	}*/
?>
    
  

<!--/ header --> 

<div class="header_spacer"></div>

<?php  if ( isset($hgr_options['back_to_top_button']) && $hgr_options['back_to_top_button'] == '1' ) { ?>
<div class="top">
	<a href="#" class="back-to-top"><i class="icon fa fa-chevron-up"></i></a>
</div>

<?php  }; ?>
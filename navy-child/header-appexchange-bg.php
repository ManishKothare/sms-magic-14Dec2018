<?php
/**
 * Navy Theme blank header file
 * @package WordPress
 * @subpackage Navy Theme
 * @since 1.0
 * TO BE INCLUDED IN ALL OTHER PAGES
 */
 $hgr_options = get_option( 'redux_options' );
 $detect = new Mobile_Detect;
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
<link href="<?php echo get_site_url()?>/wp-content/themes/navy-child/css/appexchange-style.css" rel="stylesheet" type="text/css" />

<style>
body {
   /*background-image: url('<?php //echo get_site_url()?>/wp-content/uploads/2018/08/appexchange-bg.jpg');*/ 
   background: url('<?php echo get_site_url()?>/wp-content/uploads/2018/08/appexchange-bg.jpg');
}

</style>

<?php wp_head(); ?>
</head>

<body style="background: url('https://www.sms-magic.com/wp-content/uploads/2018/08/appexchange-bg.jpg');" <?php body_class(); ?> >

<?php 
	/*
	*	Custom hook
	*/
	navy_after_body_open(); 
?>

<!--Website Boxed START-->
<div id="website_boxed">

<!--/ header --> 

<div class="header_spacer"></div>

<?php  if ( isset($hgr_options['back_to_top_button']) && $hgr_options['back_to_top_button'] == '1' ) { ?>
<div class="top">
	<a href="#" class="back-to-top"><i class="icon fa fa-chevron-up"></i></a>
</div>

<?php  }; ?>
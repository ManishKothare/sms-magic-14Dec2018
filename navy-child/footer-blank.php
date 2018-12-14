<?php
/**
 * Navy Theme blank footer file
 * @package WordPress
 * @subpackage Navy Theme
 * @since 1.0
 * TO BE INCLUDED IN ALL OTHER PAGES
 */

/*$hgr_options = get_option( 'redux_options' );
 $allowed_html_array = array(
    'a' => array(
        'href' => array(),
        'title' => array()
    ),
    'br' => array(),
    'em' => array(),
    'strong' => array(),
);

$hgr_megafooterID		=	esc_attr( get_post_meta( get_the_ID(), '_hgr_megafooterID', true ) );	// hgr_megafooter unique ID for this page

if( class_exists('HGR_MEGAFOOTER') && !empty($hgr_megafooterID) && is_numeric($hgr_megafooterID) ){
	// MEGA FOOTER SET TO AN EXISTING MEGAFOOTER
	$hgr_megafooter				=	get_post( $hgr_megafooterID, ARRAY_A );
	$hgr_page_color_scheme		=	get_post_meta( $hgr_megafooterID, '_hgr_page_color_scheme', true );
	wp_reset_postdata();
	
	echo '<div class="container ' . esc_attr( $hgr_page_color_scheme ) . ' hgr_megafooter">';
	echo do_shortcode( $hgr_megafooter['post_content'] );
	echo '</div>';
}
elseif( class_exists('HGR_MEGAFOOTER') && empty($hgr_megafooterID) && isset($hgr_options['hgr_megafooter_select']) && !empty($hgr_options['hgr_megafooter_select']) ){
	// MEGA FOOTER SET TO DEFAULT MEGAFOOTER
	$hgr_megafooter				=	get_post( $hgr_options['hgr_megafooter_select'], ARRAY_A );
	$hgr_page_color_scheme		=	esc_attr( get_post_meta( $hgr_options['hgr_megafooter_select'], '_hgr_page_color_scheme', true ) );
	wp_reset_postdata();
	echo '<div class="container hgr_megafooter ' . esc_attr($hgr_page_color_scheme) . '">';
	echo do_shortcode($hgr_megafooter['post_content']);
	echo '</div>';
}
elseif( class_exists('HGR_MEGAFOOTER') && $hgr_megafooterID == "minimal_footer" ){ ?>
    <div class="row bka_footer <?php echo esc_attr( $hgr_options['footer_color_scheme'] );?> " style="padding:10px; <?php echo( !empty($hgr_options['footer-bgcolor']) ? ' background-color:' . esc_attr( $hgr_options['footer-bgcolor'] ) . ';' : '');?>">
        <div class="container">
            <div class="col-md-12" style="text-align:center;">
                <?php echo ( !empty($hgr_options['footer-copyright']) ? wp_kses( $hgr_options['footer-copyright'], $allowed_html_array ) : __('Set your Copyright Text into Theme Options', 'navy') );?>
            </div>
        </div>
    </div>
    <?php
}
elseif( class_exists('HGR_MEGAFOOTER') && $hgr_megafooterID == "no_footer" ){
	// NO FOOTER DISPLAYED
}
else{
	?>
    <div class="row bka_footer <?php echo esc_attr( $hgr_options['footer_color_scheme'] );?> " style="padding:10px; <?php echo( !empty($hgr_options['footer-bgcolor']) ? ' background-color:' . esc_attr( $hgr_options['footer-bgcolor'] ) . ';' : '');?>">
        <div class="container">
            <div class="col-md-12" style="text-align:center;">
                <?php echo ( !empty($hgr_options['footer-copyright']) ? wp_kses( $hgr_options['footer-copyright'], $allowed_html_array ) : esc_html__('Set your Copyright Text into Theme Options', 'navy') );?>
            </div>
        </div>
    </div>
    <?php
}*/
?>

<!--<div id="hgr_left"></div>
<div id="hgr_right"></div>
<div id="hgr_top"></div>
<div id="hgr_bottom"></div>-->


<?php 
	/*
	*	Custom hook
	*/
	navy_before_footer_open(); 
?>


</div> <!--Website Boxed END-->

	<?php wp_footer();?>
    
 </body>

</html>
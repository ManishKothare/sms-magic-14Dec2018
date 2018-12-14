 <?php
/**
 * Navy Child Theme: Nurture section, sidebar
 * @package WordPress
 * @subpackage Navy Theme
 * @since 1.0
 * TO BE INCLUDED ON PAGES WITH SIDEBAR: Nurture
 */
 ?>


<?php 
if ( is_active_sidebar( 'sidebar-nurture' ) ) { 
	dynamic_sidebar('sidebar-nurture');
}
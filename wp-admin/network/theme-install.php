<?php
/**
 * Install theme network administration panel.
 *
 * @package App_Package
 * @subpackage Multisite
 * @since 3.1.0
 */

if ( isset( $_GET['tab'] ) && ( 'theme-information' == $_GET['tab'] ) )
	define( 'IFRAME_REQUEST', true );

// Load the website management system.
require_once( dirname( __FILE__ ) . '/admin.php' );

require( ABSPATH . 'wp-admin/theme-install.php' );

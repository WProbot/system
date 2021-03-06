<?php
/**
 * Administration Template Header
 *
 * @package App_Package
 * @subpackage Administration
 */

@header( 'Content-Type: ' . get_option( 'html_type' ) . '; charset=' . get_option( 'blog_charset' ) );
if ( ! defined( 'WP_ADMIN' ) ) {
	require_once( dirname( __FILE__ ) . '/admin.php' );
}

/**
 * In case admin-header.php is included in a function.
 *
 * @global string    $title
 * @global string    $hook_suffix
 * @global WP_Screen $current_screen
 * @global WP_Locale $wp_locale
 * @global string    $pagenow
 * @global string    $update_title
 * @global int       $total_update_count
 * @global string    $parent_file
 */
global $title, $hook_suffix, $current_screen, $wp_locale, $pagenow, $update_title, $total_update_count, $parent_file;

// Catch plugins that include admin-header.php before admin.php completes.
if ( empty( $current_screen ) ) {
	set_current_screen();
}

get_admin_page_title();
$title = esc_html( strip_tags( $title ) );

if ( is_network_admin() ) {
	// Translators: Network admin screen title. 1: Network name.
	$admin_title = sprintf( __( 'Network Admin: %s' ), esc_html( get_network()->site_name ) );
} elseif ( is_user_admin() ) {
	// Translators: User dashboard screen title. 1: Network name.
	$admin_title = sprintf( __( 'User Dashboard: %s' ), esc_html( get_network()->site_name ) );
} else {
	$admin_title = get_bloginfo( 'name' );
}

if ( $admin_title == $title ) {
	// Translators: Admin screen title. 1: Admin screen name.
	$admin_title = sprintf( __( '%1$s' ), $title );
} else {
	// Translators: Admin screen title. 1: Admin screen name, 2: Network or site name.
	$admin_title = sprintf( __( '%1$s &lsaquo; %2$s' ), $title, $admin_title );
}

/**
 * Filters the title tag content for an admin page.
 *
 * @since WP 3.1.0
 * @param string $admin_title The page title, with extra context added.
 * @param string $title The original page title.
 */
$admin_title = apply_filters( 'admin_title', $admin_title, $title );

wp_user_settings();

_wp_admin_html_begin();

?>
<title><?php echo $admin_title; ?></title>

<link rel="preload" href="<?php echo site_url( 'app-assets/fonts/inter/inter-upright-var.woff2' ); ?>" as="font" type="font/woff2" crossorigin>
<link rel="preload" href="<?php echo site_url( 'app-assets/fonts/inter/inter-italic-var.woff2' ); ?>" as="font" type="font/woff2" crossorigin>
<link rel="preload" href="<?php echo site_url( 'app-assets/fonts/crimson/crimson-pro-roman.woff' ); ?>" as="font" type="font/woff" crossorigin>
<link rel="preload" href="<?php echo site_url( 'app-assets/fonts/crimson/crimson-pro-italic.woff' ); ?>" as="font" type="font/woff" crossorigin>
<?php

wp_enqueue_style( 'colors' );
wp_enqueue_style( 'ie' );
wp_enqueue_script( 'utils' );
wp_enqueue_script( 'svg-painter' );

$admin_body_class = preg_replace( '/[^a-z0-9_-]+/i', '-', $hook_suffix );
?>
<script>(function(html){html.className = html.className.replace(/\bno-js\b/,'js')})(document.documentElement);</script>
<script type="text/javascript">
addLoadEvent = function(func){if(typeof jQuery!="undefined")jQuery(document).ready(func);else if(typeof wpOnload!='function'){wpOnload=func;}else{var oldonload=wpOnload;wpOnload=function(){oldonload();func();}}};
var ajaxurl = '<?php echo admin_url( 'admin-ajax.php', 'relative' ); ?>',
	pagenow = '<?php echo $current_screen->id; ?>',
	typenow = '<?php echo $current_screen->post_type; ?>',
	adminpage = '<?php echo $admin_body_class; ?>',
	thousandsSeparator = '<?php echo addslashes( $wp_locale->number_format['thousands_sep'] ); ?>',
	decimalPoint = '<?php echo addslashes( $wp_locale->number_format['decimal_point'] ); ?>',
	isRtl = <?php echo (int) is_rtl(); ?>;
</script>
<meta name="viewport" content="width=device-width,initial-scale=1.0">
<?php

/**
 * Enqueue scripts for all admin pages.
 *
 * Enqueue scripts & styles used on all admin pages,
 * such as layout, admin menu, page header & footer.
 *
 * @since 1.0.0
 */
do_action( 'app_enqueue_scripts' );

/**
 * Enqueue scripts for a specific admin page.
 *
 * @todo Review this when styles page-specific styles ready.
 *
 * @since WP 2.8.0
 * @param string $hook_suffix The current admin page.
 */
do_action( 'admin_enqueue_scripts', $hook_suffix );

/**
 * Fires when styles are printed for a specific admin page based on $hook_suffix.
 *
 * @since WP 2.6.0
 */
do_action( "admin_print_styles-{$hook_suffix}" );

/**
 * Fires when styles are printed for all admin pages.
 *
 * @since WP 2.6.0
 */
do_action( 'admin_print_styles' );

/**
 * Fires when scripts are printed for a specific admin page based on $hook_suffix.
 *
 * @since WP 2.1.0
 */
do_action( "admin_print_scripts-{$hook_suffix}" );

/**
 * Fires when scripts are printed for all admin pages.
 *
 * @since WP 2.1.0
 */
do_action( 'admin_print_scripts' );

/**
 * Fires in head section for a specific admin page.
 *
 * The dynamic portion of the hook, `$hook_suffix`, refers to the hook suffix
 * for the admin page.
 *
 * @since WP 2.1.0
 */
do_action( "admin_head-{$hook_suffix}" );

/**
 * Fires in head section for all admin pages.
 *
 * @since WP 2.1.0
 */
do_action( 'admin_head' );

if ( get_user_setting( 'mfold' ) == 'f' ) {
	$admin_body_class .= ' folded';
}

if ( ! get_user_setting( 'unfold' ) ) {
	$admin_body_class .= ' auto-fold';
}

if ( is_admin_bar_showing() ) {
	$admin_body_class .= ' admin-bar';
}

if ( is_rtl() ) {
	$admin_body_class .= ' rtl';
}

if ( $current_screen->post_type ) {
	$admin_body_class .= ' post-type-' . $current_screen->post_type;
}

if ( $current_screen->taxonomy ) {
	$admin_body_class .= ' taxonomy-' . $current_screen->taxonomy;
}

$admin_body_class .= ' branch-' . str_replace( [ '.', ',' ], '-', floatval( get_bloginfo( 'version' ) ) );
$admin_body_class .= ' version-' . str_replace( '.', '-', preg_replace( '/^([.0-9]+).*/', '$1', get_bloginfo( 'version' ) ) );
$admin_body_class .= ' admin-color-' . sanitize_html_class( get_user_option( 'admin_color' ), 'default' );
$admin_body_class .= ' locale-' . sanitize_html_class( strtolower( str_replace( '_', '-', get_user_locale() ) ) );

if ( wp_is_mobile() ) {
	$admin_body_class .= ' mobile';
}

if ( is_multisite() ) {
	$admin_body_class .= ' multisite';
}

if ( is_network_admin() ) {
	$admin_body_class .= ' network-admin';
}

$admin_body_class .= ' no-customize-support no-svg';

?>
</head>
<?php
/**
 * Filters the CSS classes for the body tag in the admin.
 *
 * This filter differs from the {@see 'post_class'} and {@see 'body_class'} filters
 * in two important ways:
 *
 * 1. `$classes` is a space-separated string of class names instead of an array.
 * 2. Not all core admin classes are filterable, notably: app-admin, app-core-ui,
 *    and no-js cannot be removed.
 *
 * @since WP 2.3.0
 *
 * @param string $classes Space-separated list of CSS classes.
 */
$admin_body_classes = apply_filters( 'admin_body_class', '' );

?>
<body class="app-admin app-core-ui <?php echo $admin_body_classes . ' ' . $admin_body_class; ?>">

	<?php do_action( 'app_toolbar_render' );

	// Make sure the customize body classes are correct as early as possible.
	if ( current_user_can( 'customize' ) ) {
		wp_customize_support_script();
	} ?>

	<?php require( ABSPATH . 'wp-admin/menu-header.php' ); ?>

	<div id="admin-page-wrap" class="page-wrap admin-page-wrap">

		<div id="app-content" class="page-content admin-page-content">
			<?php

			do_action( 'in_admin_header' );

			/**
			 * Screen options & contextual help
			 *
			 * @todo add this via hook.
			 */
			echo $current_screen->render_screen_meta();

			get_template_part( 'backend/header/site', 'identity' ) ?>

			<main id="app-body" class="main admin-main" role="main">
			<?php
			unset( $title_class, $blog_name, $total_update_count, $update_title );

			$current_screen->set_parentage( $parent_file );

			?>

				<div id="app-content" class="app-content" aria-label="<?php esc_attr_e( 'Main content' ); ?>" tabindex="0">
				<?php

				if ( is_network_admin() ) {
					/**
					 * Prints network admin screen notices.
					 *
					 * @since WP 3.1.0
					 */
					do_action( 'network_admin_notices' );

				} elseif ( is_user_admin() ) {
					/**
					 * Prints user admin screen notices.
					 *
					 * @since WP 3.1.0
					 */
					do_action( 'user_admin_notices' );

				} else {
					/**
					 * Prints admin screen notices.
					 *
					 * @since WP 3.1.0
					 */
					do_action( 'admin_notices' );
				}

				/**
				 * Prints generic admin screen notices.
				 *
				 * @since WP 3.1.0
				 */
				do_action( 'all_admin_notices' );

				if ( $parent_file == 'options-general.php' ) {
					require( ABSPATH . 'wp-admin/options-head.php' );
				}
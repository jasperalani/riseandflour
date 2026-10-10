<?php
/**
 * Rise & Flour functions and definitions
 *
 * @package Rise_&_Flour
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! defined( 'RISE_AND_FLOUR_VERSION' ) ) {
	define( 'RISE_AND_FLOUR_VERSION', '1.0.0' );
}

/**
 * Theme setup.
 */
function rise_and_flour_setup() {

	// Let WordPress own the <title>.
	add_theme_support( 'title-tag' );

	// Featured images — used as block/hero imagery.
	add_theme_support( 'post-thumbnails' );

	add_theme_support(
		'html5',
		array( 'search-form', 'gallery', 'caption', 'style', 'script', 'navigation-widgets' )
	);

	add_theme_support( 'responsive-embeds' );

	// Load the site stylesheet into the editor so ACF block previews render
	// as they will on the front end. The web fonts are self-hosted and
	// declared in main.css, so they come along with it.
	add_theme_support( 'editor-styles' );
	add_editor_style( 'css/main.css' );

	// The design has one fixed content width; no user-selectable widths.
	add_theme_support( 'disable-custom-font-sizes' );
	add_theme_support( 'disable-custom-colors' );

	// The wordmark in the stacked header falls back to the bundled
	// asset when no custom logo is set.
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 120,
			'width'       => 440,
			'flex-width'  => true,
			'flex-height' => true,
		)
	);

	register_nav_menus(
		array(
			'primary' => esc_html__( 'Primary', 'rise-and-flour' ),
		)
	);
}
add_action( 'after_setup_theme', 'rise_and_flour_setup' );

/**
 * Version string for a theme asset, based on its modification time.
 *
 * A fixed version means the browser caches CSS and JS across every rebuild,
 * so edits appear to do nothing until a hard refresh. Keying on filemtime
 * changes the URL whenever the file actually changes.
 *
 * @param string $path Theme-relative path, e.g. 'css/main.css'.
 * @return string
 */
function rise_and_flour_asset_version( $path ) {
	$file = get_template_directory() . '/' . ltrim( $path, '/' );
	$time = file_exists( $file ) ? filemtime( $file ) : false;

	return $time ? (string) $time : RISE_AND_FLOUR_VERSION;
}

/**
 * Enqueue styles and scripts.
 */
function rise_and_flour_scripts() {

	// style.css holds only the theme header; this is the real stylesheet.
	wp_enqueue_style(
		'rise-and-flour-style',
		get_template_directory_uri() . '/css/main.css',
		array(),
		rise_and_flour_asset_version( 'css/main.css' )
	);

	wp_enqueue_script(
		'rise-and-flour-main',
		get_template_directory_uri() . '/js/main.js',
		array(),
		rise_and_flour_asset_version( 'js/main.js' ),
		true
	);
}
add_action( 'wp_enqueue_scripts', 'rise_and_flour_scripts' );

/**
 * Preload the two text faces nearly every page paints with.
 *
 * The browser otherwise only finds them after main.css has downloaded and
 * parsed. The Garamond italic and Core Magic are used sparingly and load
 * on demand.
 */
function rise_and_flour_preload_fonts() {
	foreach ( array( 'montserrat-latin.woff2', 'eb-garamond-latin.woff2' ) as $font ) {
		printf(
			'<link rel="preload" href="%s" as="font" type="font/woff2" crossorigin>' . "\n",
			esc_url( get_template_directory_uri() . '/assets/fonts/' . $font )
		);
	}
}
add_action( 'wp_head', 'rise_and_flour_preload_fonts', 1 );

/**
 * Output an image, or a grey placeholder when none is set.
 *
 * Every block calls this rather than echoing <img> directly, so the design
 * stays reviewable before any real photography exists. Accepts an attachment
 * ID, a URL, or an ACF image array — so wiring ACF in later is a one-line
 * change per block.
 *
 * @param array $args {
 *     @type int|string|array $image Attachment ID, URL, or ACF image array.
 *     @type string           $label Placeholder text.
 *     @type string           $class Extra class on the output element.
 *     @type string           $alt   Alt text (ignored for attachment IDs,
 *                                   which use the attachment's own alt).
 * }
 */
function rise_and_flour_media( $args = array() ) {

	$args = wp_parse_args(
		$args,
		array(
			'image' => null,
			'label' => esc_html__( 'Image', 'rise-and-flour' ),
			'class' => '',
			'alt'   => '',
		)
	);

	$image = $args['image'];

	// ACF image fields return an array; normalise to an ID.
	if ( is_array( $image ) && isset( $image['ID'] ) ) {
		$image = (int) $image['ID'];
	}

	if ( is_numeric( $image ) && wp_attachment_is_image( (int) $image ) ) {
		echo wp_get_attachment_image(
			(int) $image,
			'full',
			false,
			array( 'class' => trim( 'media-fill ' . $args['class'] ) )
		);
		return;
	}

	if ( is_string( $image ) && '' !== $image ) {
		printf(
			'<img class="%1$s" src="%2$s" alt="%3$s">',
			esc_attr( trim( 'media-fill ' . $args['class'] ) ),
			esc_url( $image ),
			esc_attr( $args['alt'] )
		);
		return;
	}

	printf(
		'<div class="%1$s">%2$s</div>',
		esc_attr( trim( 'ph ' . $args['class'] ) ),
		esc_html( $args['label'] )
	);
}

/**
 * Editor-only styles.
 *
 * The editor canvas has no site header, so blocks that assume a full-height
 * viewport need reining in to stay usable in preview.
 */
function rise_and_flour_editor_assets() {
	wp_enqueue_style(
		'rise-and-flour-editor',
		get_template_directory_uri() . '/css/editor.css',
		array(),
		rise_and_flour_asset_version( 'css/editor.css' )
	);
}
add_action( 'enqueue_block_editor_assets', 'rise_and_flour_editor_assets' );

/**
 * Nav fallback when no menu is assigned to the Primary location.
 *
 * Lists top-level pages so a fresh install still looks like the design
 * instead of rendering nothing.
 */
function rise_and_flour_nav_fallback() {
	echo '<ul class="site-nav__list">';
	wp_list_pages(
		array(
			'title_li' => '',
			'depth'    => 1,
			'number'   => 5,
		)
	);
	echo '</ul>';
}

/**
 * Render the Primary nav.
 */
function rise_and_flour_nav() {
	wp_nav_menu(
		array(
			'theme_location' => 'primary',
			'container'      => false,
			'menu_class'     => 'site-nav__list',
			'depth'          => 1,
			'fallback_cb'    => 'rise_and_flour_nav_fallback',
		)
	);
}

/**
 * ACF block registration, helpers and field groups.
 */
require get_template_directory() . '/inc/blocks.php';
require get_template_directory() . '/inc/acf-fields.php';
require get_template_directory() . '/inc/site-options.php';
require get_template_directory() . '/inc/hours.php';
require get_template_directory() . '/inc/menu-import.php';
require get_template_directory() . '/inc/events.php';
require get_template_directory() . '/inc/contact.php';

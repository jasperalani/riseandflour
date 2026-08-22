<?php
/**
 * ACF block registration and helpers.
 *
 * Blocks are defined by block.json in blocks/<name>/, so WordPress registers
 * them natively and ACF supplies the fields and render template.
 *
 * @package Rise_&_Flour
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register every block in blocks/ that has a block.json.
 *
 * Dropping a new folder in there is all it takes to add a block — no list to
 * keep in sync.
 */
function rise_and_flour_register_blocks() {

	// ACF supplies the render template and fields; without it these would
	// register as empty blocks.
	if ( ! function_exists( 'acf_register_block_type' ) ) {
		return;
	}

	$rf_blocks = glob( get_template_directory() . '/blocks/*/block.json' );

	if ( ! $rf_blocks ) {
		return;
	}

	foreach ( $rf_blocks as $rf_block ) {
		register_block_type( dirname( $rf_block ) );
	}
}
add_action( 'init', 'rise_and_flour_register_blocks', 5 );

/**
 * Give the theme's blocks their own category in the inserter.
 *
 * @param array $categories Block categories.
 * @return array
 */
function rise_and_flour_block_category( $categories ) {
	array_unshift(
		$categories,
		array(
			'slug'  => 'rise-and-flour',
			'title' => __( 'Rise & Flour', 'rise-and-flour' ),
			'icon'  => null,
		)
	);
	return $categories;
}
add_filter( 'block_categories_all', 'rise_and_flour_block_category' );

/**
 * Build the class string for a block wrapper.
 *
 * Combines the editor's custom class name with the block's own spacing field,
 * which maps to the .section--* rhythm classes.
 *
 * @param array $block ACF block array.
 * @return string
 */
function rise_and_flour_block_classes( $block = array() ) {

	$rf_classes = array();

	if ( ! empty( $block['className'] ) ) {
		$rf_classes[] = $block['className'];
	}

	$rf_spacing = function_exists( 'get_field' ) ? get_field( 'spacing' ) : '';

	if ( $rf_spacing && 'none' !== $rf_spacing ) {
		$rf_classes[] = 'section--' . $rf_spacing;
	}

	return implode( ' ', array_filter( $rf_classes ) );
}

/**
 * Return a block's anchor, if the editor set one.
 *
 * @param array $block ACF block array.
 * @return string
 */
function rise_and_flour_block_anchor( $block = array() ) {
	return ! empty( $block['anchor'] ) ? $block['anchor'] : '';
}

/**
 * Normalise an ACF image value to a URL.
 *
 * Accepts an attachment ID, a URL, or an ACF image array.
 *
 * @param mixed  $image Image value.
 * @param string $size  Image size.
 * @return string
 */
function rise_and_flour_image_url( $image, $size = 'full' ) {

	if ( is_array( $image ) && isset( $image['ID'] ) ) {
		$image = (int) $image['ID'];
	}

	if ( is_numeric( $image ) ) {
		$rf_src = wp_get_attachment_image_url( (int) $image, $size );
		return $rf_src ? $rf_src : '';
	}

	return is_string( $image ) ? $image : '';
}

/**
 * Restrict the editor to the theme's own blocks plus a few core essentials.
 *
 * Keeps pages composable without the full core block library getting in the
 * way of the design.
 *
 * @param bool|array               $allowed Allowed block types.
 * @param WP_Block_Editor_Context $context Editor context.
 * @return bool|array
 */
function rise_and_flour_allowed_blocks( $allowed, $context ) {

	if ( empty( $context->post ) ) {
		return $allowed;
	}

	// An event is a short write-up, not a composed page: core text blocks only.
	if ( 'rf_event' === $context->post->post_type ) {
		return array(
			'core/paragraph',
			'core/heading',
			'core/list',
			'core/list-item',
			'core/image',
			'core/quote',
		);
	}

	return array(
		'acf/rf-hero',
		'acf/rf-text',
		'acf/rf-image-text',
		'acf/rf-image',
		'acf/rf-carousel',
		'acf/rf-menu',
		'acf/rf-location',
		'acf/rf-events',
		// Core escape hatches for one-off content.
		'core/paragraph',
		'core/heading',
		'core/list',
		'core/list-item',
		'core/image',
		'core/spacer',
		'core/html',
	);
}
add_filter( 'allowed_block_types_all', 'rise_and_flour_allowed_blocks', 10, 2 );

<?php
/**
 * ACF block render: Image + Text.
 *
 * @package Rise_&_Flour
 *
 * @var array $block The block settings and attributes.
 */

// The link points at a chosen page (page_link returns its permalink) or a
// typed URL. Blocks saved before the switch existed only have a URL, so
// that stays the fallback when no page is picked.
$rf_link_page = get_field( 'link_page' );
$rf_link_url  = get_field( 'link_url' );
$rf_link      = 'url' === get_field( 'link_type' ) || ! $rf_link_page ? $rf_link_url : $rf_link_page;

get_template_part(
	'template-parts/blocks/image-text',
	null,
	array(
		'image'          => get_field( 'image' ),
		'caption'        => get_field( 'caption' ),
		'frame'          => get_field( 'frame' ) ? get_field( 'frame' ) : 'gapped',
		'media_position' => get_field( 'media_position' ) ? get_field( 'media_position' ) : 'left',
		'eyebrow'        => get_field( 'eyebrow' ),
		'heading'        => get_field( 'heading' ),
		'body'           => get_field( 'body' ),
		'link_text'      => get_field( 'link_text' ),
		'link_url'       => $rf_link,
		'placeholder'    => __( 'Set an image — 4:5', 'rise-and-flour' ),
		'classes'        => rise_and_flour_block_classes( $block ),
		'anchor'         => rise_and_flour_block_anchor( $block ),
	)
);

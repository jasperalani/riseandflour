<?php
/**
 * ACF block render: Image + Text.
 *
 * @package Rise_&_Flour
 *
 * @var array $block The block settings and attributes.
 */

get_template_part(
	'template-parts/blocks/image-text',
	null,
	array(
		'image'          => get_field( 'image' ),
		'media_position' => get_field( 'media_position' ) ? get_field( 'media_position' ) : 'left',
		'eyebrow'        => get_field( 'eyebrow' ),
		'heading'        => get_field( 'heading' ),
		'body'           => get_field( 'body' ),
		'link_text'      => get_field( 'link_text' ),
		'link_url'       => get_field( 'link_url' ),
		'placeholder'    => __( 'Set an image', 'rise-and-flour' ),
		'classes'        => rise_and_flour_block_classes( $block ),
		'anchor'         => rise_and_flour_block_anchor( $block ),
	)
);

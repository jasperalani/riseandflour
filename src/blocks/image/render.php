<?php
/**
 * ACF block render: Image.
 *
 * @package Rise_&_Flour
 *
 * @var array $block The block settings and attributes.
 */

get_template_part(
	'template-parts/blocks/image',
	null,
	array(
		'image'       => get_field( 'image' ),
		'caption'     => get_field( 'caption' ),
		'width'       => get_field( 'width' ) ? get_field( 'width' ) : 'contained',
		'height'      => get_field( 'height' ) ? get_field( 'height' ) : 'small',
		'position'    => get_field( 'position' ) ? get_field( 'position' ) : 'middle',
		'placeholder' => __( 'Set an image', 'rise-and-flour' ),
		'classes'     => rise_and_flour_block_classes( $block ),
		'anchor'      => rise_and_flour_block_anchor( $block ),
	)
);

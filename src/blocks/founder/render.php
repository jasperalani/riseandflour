<?php
/**
 * ACF block render: Founder.
 *
 * @package Rise_&_Flour
 *
 * @var array $block The block settings and attributes.
 */

get_template_part(
	'template-parts/blocks/founder',
	null,
	array(
		'image'   => get_field( 'image' ),
		'name'    => get_field( 'name' ),
		'role'    => get_field( 'role' ),
		'eyebrow' => get_field( 'eyebrow' ),
		'heading' => get_field( 'heading' ),
		'tag'     => get_field( 'tag' ) ? get_field( 'tag' ) : 'h2',
		'body'    => get_field( 'body' ),
		'quote'   => get_field( 'quote' ),
		'classes' => rise_and_flour_block_classes( $block ),
		'anchor'  => rise_and_flour_block_anchor( $block ),
	)
);

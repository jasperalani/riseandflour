<?php
/**
 * ACF block render: Gallery.
 *
 * @package Rise_&_Flour
 *
 * @var array $block The block settings and attributes.
 */

get_template_part(
	'template-parts/blocks/gallery',
	null,
	array(
		'eyebrow' => get_field( 'eyebrow' ),
		'heading' => get_field( 'heading' ),
		'tag'     => get_field( 'tag' ) ? get_field( 'tag' ) : 'h2',
		'intro'   => get_field( 'intro' ),
		'images'  => get_field( 'images' ),
		'columns' => get_field( 'columns' ) ? (int) get_field( 'columns' ) : 3,
		'aspect'  => get_field( 'aspect' ) ? get_field( 'aspect' ) : 'square',
		'classes' => rise_and_flour_block_classes( $block ),
		'anchor'  => rise_and_flour_block_anchor( $block ),
	)
);

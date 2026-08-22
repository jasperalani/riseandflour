<?php
/**
 * ACF block render: Text.
 *
 * @package Rise_&_Flour
 *
 * @var array $block The block settings and attributes.
 */

get_template_part(
	'template-parts/blocks/text',
	null,
	array(
		'eyebrow' => get_field( 'eyebrow' ),
		'heading' => get_field( 'heading' ),
		'body'    => get_field( 'body' ),
		'align'   => get_field( 'align' ) ? get_field( 'align' ) : 'center',
		'variant' => get_field( 'variant' ) ? get_field( 'variant' ) : '',
		'tag'     => get_field( 'tag' ) ? get_field( 'tag' ) : 'h2',
		'classes' => rise_and_flour_block_classes( $block ),
		'anchor'  => rise_and_flour_block_anchor( $block ),
	)
);

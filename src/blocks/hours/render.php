<?php
/**
 * ACF block render: Opening hours.
 *
 * @package Rise_&_Flour
 *
 * @var array $block The block settings and attributes.
 */

get_template_part(
	'template-parts/blocks/hours',
	null,
	array(
		'heading' => get_field( 'heading' ),
		'tag'     => get_field( 'tag' ) ? get_field( 'tag' ) : 'h2',
		'note'    => get_field( 'note' ),
		'style'   => get_field( 'style' ) ? get_field( 'style' ) : 'framed',
		'classes' => rise_and_flour_block_classes( $block ),
		'anchor'  => rise_and_flour_block_anchor( $block ),
	)
);

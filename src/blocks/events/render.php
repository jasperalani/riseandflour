<?php
/**
 * ACF block render: Events.
 *
 * @package Rise_&_Flour
 *
 * @var array $block The block settings and attributes.
 */

get_template_part(
	'template-parts/blocks/events',
	null,
	array(
		'eyebrow'      => get_field( 'eyebrow' ),
		'heading'      => get_field( 'heading' ),
		'tag'          => get_field( 'tag' ) ? get_field( 'tag' ) : 'h1',
		'intro'        => get_field( 'intro' ),
		'show'         => get_field( 'show' ) ? get_field( 'show' ) : 'both',
		'limit'        => (int) get_field( 'limit' ),
		'past_heading' => get_field( 'past_heading' ),
		'empty_text'   => get_field( 'empty_text' ),
		'classes'      => rise_and_flour_block_classes( $block ),
		'anchor'       => rise_and_flour_block_anchor( $block ),
	)
);

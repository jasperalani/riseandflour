<?php
/**
 * ACF block render: Social links.
 *
 * @package Rise_&_Flour
 *
 * @var array $block      The block settings and attributes.
 * @var bool  $is_preview True when rendering inside the editor.
 */

get_template_part(
	'template-parts/blocks/social',
	null,
	array(
		'eyebrow'    => get_field( 'eyebrow' ),
		'style'      => get_field( 'style' ) ? get_field( 'style' ) : 'circles',
		'is_preview' => ! empty( $is_preview ),
		'classes'    => rise_and_flour_block_classes( $block ),
		'anchor'     => rise_and_flour_block_anchor( $block ),
	)
);

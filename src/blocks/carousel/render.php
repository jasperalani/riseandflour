<?php
/**
 * ACF block render: Carousel.
 *
 * @package Rise_&_Flour
 *
 * @var array $block     The block settings and attributes.
 * @var bool  $is_preview True when rendering inside the editor.
 */

$rf_slides = array();

if ( have_rows( 'slides' ) ) {
	while ( have_rows( 'slides' ) ) {
		the_row();
		$rf_slides[] = array(
			'image'       => get_sub_field( 'image' ),
			'caption'     => get_sub_field( 'caption' ),
			'placeholder' => __( 'Slide image', 'rise-and-flour' ),
		);
	}
}

// An empty repeater would render nothing at all, which looks broken in the
// editor. Show placeholder slides there so the block stays visible.
if ( empty( $rf_slides ) && ! empty( $is_preview ) ) {
	$rf_slides = array(
		array(
			'caption'     => __( 'Add slides', 'rise-and-flour' ),
			'placeholder' => __( 'Slide image', 'rise-and-flour' ),
		),
		array(
			'caption'     => __( 'Add slides', 'rise-and-flour' ),
			'placeholder' => __( 'Slide image', 'rise-and-flour' ),
		),
	);
}

get_template_part(
	'template-parts/blocks/carousel',
	null,
	array(
		'heading' => get_field( 'heading' ),
		'slides'  => $rf_slides,
		'size'    => get_field( 'size' ),
		'pager'   => get_field( 'pager' ),
		'classes' => rise_and_flour_block_classes( $block ),
		'anchor'  => rise_and_flour_block_anchor( $block ),
	)
);

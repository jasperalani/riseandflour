<?php
/**
 * ACF block render: Hero.
 *
 * Reads the fields, then hands off to the shared template part so the block
 * and any PHP-composed page emit identical markup.
 *
 * @package Rise_&_Flour
 *
 * @var array $block The block settings and attributes.
 */

$rf_show_logo = get_field( 'show_logo' );
$rf_show_logo = ( null === $rf_show_logo ) ? true : (bool) $rf_show_logo;

$rf_logo = get_field( 'logo' );

get_template_part(
	'template-parts/blocks/hero',
	null,
	array(
		'image'       => get_field( 'image' ),
		'show_logo'   => $rf_show_logo,
		'logo'        => $rf_logo ? rise_and_flour_image_url( $rf_logo ) : get_template_directory_uri() . '/assets/img/logo-rect-stamp.png',
		'caption'     => get_field( 'caption' ),
		'placeholder' => __( 'Full-page hero — set an image', 'rise-and-flour' ),
		'classes'     => rise_and_flour_block_classes( $block ),
		'anchor'      => rise_and_flour_block_anchor( $block ),
	)
);

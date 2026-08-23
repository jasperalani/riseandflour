<?php
/**
 * ACF block render: Text.
 *
 * @package Rise_&_Flour
 *
 * @var array $block The block settings and attributes.
 */

// A chosen page (page_link returns its permalink) or a typed URL.
$rf_link_page = get_field( 'link_page' );
$rf_link      = 'url' === get_field( 'link_type' ) || ! $rf_link_page ? get_field( 'link_url' ) : $rf_link_page;

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
		'link_text' => get_field( 'link_text' ),
		'link_url'  => $rf_link,
		'classes' => rise_and_flour_block_classes( $block ),
		'anchor'  => rise_and_flour_block_anchor( $block ),
	)
);

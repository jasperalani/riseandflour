<?php
/**
 * ACF block render: Contact form.
 *
 * @package Rise_&_Flour
 *
 * @var array $block      The block settings and attributes.
 * @var bool  $is_preview True when rendering inside the editor.
 */

$rf_extras = array();
if ( have_rows( 'extras' ) ) {
	while ( have_rows( 'extras' ) ) {
		the_row();
		$rf_extras[] = array(
			'label' => get_sub_field( 'label' ),
			'text'  => get_sub_field( 'text' ),
		);
	}
}

get_template_part(
	'template-parts/blocks/contact',
	null,
	array(
		'eyebrow'      => get_field( 'eyebrow' ),
		'heading'      => get_field( 'heading' ),
		'tag'          => get_field( 'tag' ) ? get_field( 'tag' ) : 'h1',
		'intro'        => get_field( 'intro' ),
		'show_contact' => null === get_field( 'show_contact' ) ? true : (bool) get_field( 'show_contact' ),
		'phone_note'   => get_field( 'phone_note' ),
		'show_visit'   => null === get_field( 'show_visit' ) ? true : (bool) get_field( 'show_visit' ),
		'extras'       => $rf_extras,
		'subjects'     => get_field( 'subjects' ),
		'button_text'  => get_field( 'button_text' ),
		'reply_note'   => get_field( 'reply_note' ),
		'success_text' => get_field( 'success_text' ),
		'is_preview'   => ! empty( $is_preview ),
		'classes'      => rise_and_flour_block_classes( $block ),
		'anchor'       => rise_and_flour_block_anchor( $block ),
	)
);

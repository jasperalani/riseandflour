<?php
/**
 * ACF field groups for the theme's blocks.
 *
 * Registered in code rather than the database so they're version-controlled
 * and appear on a fresh install with no import step. The trade-off: they can't
 * be edited in the ACF admin UI — change them here.
 *
 * @package Rise_&_Flour
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The spacing control shared by every block.
 *
 * Maps to the .section--* rhythm classes, so the same block can sit tight or
 * loose without baking margin into the block itself.
 *
 * @param string $key Unique field key.
 * @return array
 */
function rise_and_flour_spacing_field( $key ) {
	return array(
		'key'           => $key,
		'label'         => __( 'Space above', 'rise-and-flour' ),
		'name'          => 'spacing',
		'type'          => 'button_group',
		'instructions'  => __( 'Gap between this block and the one before it.', 'rise-and-flour' ),
		'choices'       => array(
			'none'    => __( 'None', 'rise-and-flour' ),
			'tight'   => __( 'Tight', 'rise-and-flour' ),
			'default' => __( 'Default', 'rise-and-flour' ),
			'loose'   => __( 'Loose', 'rise-and-flour' ),
			'xl'      => __( 'Extra', 'rise-and-flour' ),
		),
		'default_value' => 'default',
		'allow_null'    => 0,
	);
}

/**
 * A standard image field.
 *
 * @param string $key   Field key.
 * @param string $label Field label.
 * @param string $name  Field name.
 * @return array
 */
function rise_and_flour_image_field( $key, $label = 'Image', $name = 'image' ) {
	return array(
		'key'           => $key,
		'label'         => $label,
		'name'          => $name,
		'type'          => 'image',
		'return_format' => 'array',
		'preview_size'  => 'medium',
		'library'       => 'all',
		'instructions'  => __( 'Leave empty to show a grey placeholder.', 'rise-and-flour' ),
	);
}

/**
 * Register all block field groups.
 */
function rise_and_flour_register_fields() {

	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	/* ---------------------------------------------------------
	 * Hero
	 * --------------------------------------------------------- */
	acf_add_local_field_group(
		array(
			'key'      => 'group_rf_hero',
			'title'    => __( 'Hero', 'rise-and-flour' ),
			'fields'   => array(
				rise_and_flour_image_field( 'field_rf_hero_image', __( 'Background image', 'rise-and-flour' ) ),
				array(
					'key'           => 'field_rf_hero_show_logo',
					'label'         => __( 'Show logo', 'rise-and-flour' ),
					'name'          => 'show_logo',
					'type'          => 'true_false',
					'instructions'  => __( 'Centres the stamp logo over the image.', 'rise-and-flour' ),
					'default_value' => 1,
					'ui'            => 1,
				),
				array(
					'key'               => 'field_rf_hero_logo',
					'label'             => __( 'Logo override', 'rise-and-flour' ),
					'name'              => 'logo',
					'type'              => 'image',
					'return_format'     => 'array',
					'preview_size'      => 'medium',
					'instructions'      => __( 'Optional. Defaults to the rectangular stamp.', 'rise-and-flour' ),
					'conditional_logic' => array(
						array(
							array(
								'field'    => 'field_rf_hero_show_logo',
								'operator' => '==',
								'value'    => '1',
							),
						),
					),
				),
				array(
					'key'          => 'field_rf_hero_caption',
					'label'        => __( 'Caption', 'rise-and-flour' ),
					'name'         => 'caption',
					'type'         => 'text',
					'instructions' => __( 'Small caps line at the bottom, e.g. "· A café in Cirencester ·".', 'rise-and-flour' ),
				),
			),
			'location' => array(
				array(
					array(
						'param'    => 'block',
						'operator' => '==',
						'value'    => 'acf/rf-hero',
					),
				),
			),
		)
	);

	/* ---------------------------------------------------------
	 * Text
	 * --------------------------------------------------------- */
	acf_add_local_field_group(
		array(
			'key'      => 'group_rf_text',
			'title'    => __( 'Text', 'rise-and-flour' ),
			'fields'   => array(
				array(
					'key'          => 'field_rf_text_eyebrow',
					'label'        => __( 'Eyebrow', 'rise-and-flour' ),
					'name'         => 'eyebrow',
					'type'         => 'text',
					'instructions' => __( 'Terracotta caps line above the heading.', 'rise-and-flour' ),
				),
				array(
					'key'   => 'field_rf_text_heading',
					'label' => __( 'Heading', 'rise-and-flour' ),
					'name'  => 'heading',
					'type'  => 'text',
				),
				array(
					'key'       => 'field_rf_text_body',
					'label'     => __( 'Body', 'rise-and-flour' ),
					'name'      => 'body',
					'type'      => 'textarea',
					'rows'      => 4,
					'new_lines' => '',
				),
				array(
					'key'           => 'field_rf_text_variant',
					'label'         => __( 'Size', 'rise-and-flour' ),
					'name'          => 'variant',
					'type'          => 'button_group',
					'instructions'  => __( 'Intro is slightly larger — use it directly beneath a hero.', 'rise-and-flour' ),
					'choices'       => array(
						''      => __( 'Default', 'rise-and-flour' ),
						'intro' => __( 'Intro', 'rise-and-flour' ),
					),
					'default_value' => '',
					'allow_null'    => 0,
				),
				array(
					'key'           => 'field_rf_text_align',
					'label'         => __( 'Alignment', 'rise-and-flour' ),
					'name'          => 'align',
					'type'          => 'button_group',
					'choices'       => array(
						'center' => __( 'Centre', 'rise-and-flour' ),
						'left'   => __( 'Left', 'rise-and-flour' ),
					),
					'default_value' => 'center',
					'allow_null'    => 0,
				),
				array(
					'key'           => 'field_rf_text_tag',
					'label'         => __( 'Heading level', 'rise-and-flour' ),
					'name'          => 'tag',
					'type'          => 'button_group',
					'instructions'  => __( 'Use H1 once per page, on the opening block.', 'rise-and-flour' ),
					'choices'       => array(
						'h1' => 'H1',
						'h2' => 'H2',
						'h3' => 'H3',
					),
					'default_value' => 'h2',
					'allow_null'    => 0,
				),
				rise_and_flour_spacing_field( 'field_rf_text_spacing' ),
			),
			'location' => array(
				array(
					array(
						'param'    => 'block',
						'operator' => '==',
						'value'    => 'acf/rf-text',
					),
				),
			),
		)
	);

	/* ---------------------------------------------------------
	 * Image + Text
	 * --------------------------------------------------------- */
	acf_add_local_field_group(
		array(
			'key'      => 'group_rf_image_text',
			'title'    => __( 'Image + Text', 'rise-and-flour' ),
			'fields'   => array(
				rise_and_flour_image_field( 'field_rf_it_image' ),
				array(
					'key'           => 'field_rf_it_media_position',
					'label'         => __( 'Image side', 'rise-and-flour' ),
					'name'          => 'media_position',
					'type'          => 'button_group',
					'instructions'  => __( 'On mobile the image always sits above the text.', 'rise-and-flour' ),
					'choices'       => array(
						'left'  => __( 'Left', 'rise-and-flour' ),
						'right' => __( 'Right', 'rise-and-flour' ),
					),
					'default_value' => 'left',
					'allow_null'    => 0,
				),
				array(
					'key'   => 'field_rf_it_eyebrow',
					'label' => __( 'Eyebrow', 'rise-and-flour' ),
					'name'  => 'eyebrow',
					'type'  => 'text',
				),
				array(
					'key'   => 'field_rf_it_heading',
					'label' => __( 'Heading', 'rise-and-flour' ),
					'name'  => 'heading',
					'type'  => 'text',
				),
				array(
					'key'       => 'field_rf_it_body',
					'label'     => __( 'Body', 'rise-and-flour' ),
					'name'      => 'body',
					'type'      => 'textarea',
					'rows'      => 4,
					'new_lines' => '',
				),
				array(
					'key'          => 'field_rf_it_link_text',
					'label'        => __( 'Link text', 'rise-and-flour' ),
					'name'         => 'link_text',
					'type'         => 'text',
					'instructions' => __( 'Both link fields must be filled for the link to show.', 'rise-and-flour' ),
				),
				array(
					'key'   => 'field_rf_it_link_url',
					'label' => __( 'Link URL', 'rise-and-flour' ),
					'name'  => 'link_url',
					'type'  => 'url',
				),
				rise_and_flour_spacing_field( 'field_rf_it_spacing' ),
			),
			'location' => array(
				array(
					array(
						'param'    => 'block',
						'operator' => '==',
						'value'    => 'acf/rf-image-text',
					),
				),
			),
		)
	);

	/* ---------------------------------------------------------
	 * Image
	 * --------------------------------------------------------- */
	acf_add_local_field_group(
		array(
			'key'      => 'group_rf_image',
			'title'    => __( 'Image', 'rise-and-flour' ),
			'fields'   => array(
				rise_and_flour_image_field( 'field_rf_img_image' ),
				array(
					'key'          => 'field_rf_img_caption',
					'label'        => __( 'Caption', 'rise-and-flour' ),
					'name'         => 'caption',
					'type'         => 'text',
					'instructions' => __( 'Optional caps line beneath the image.', 'rise-and-flour' ),
				),
				array(
					'key'           => 'field_rf_img_width',
					'label'         => __( 'Width', 'rise-and-flour' ),
					'name'          => 'width',
					'type'          => 'button_group',
					'choices'       => array(
						'contained' => __( 'Contained', 'rise-and-flour' ),
						'full'      => __( 'Full bleed', 'rise-and-flour' ),
					),
					'default_value' => 'contained',
					'allow_null'    => 0,
				),
				rise_and_flour_spacing_field( 'field_rf_img_spacing' ),
			),
			'location' => array(
				array(
					array(
						'param'    => 'block',
						'operator' => '==',
						'value'    => 'acf/rf-image',
					),
				),
			),
		)
	);

	/* ---------------------------------------------------------
	 * Carousel
	 * --------------------------------------------------------- */
	acf_add_local_field_group(
		array(
			'key'      => 'group_rf_carousel',
			'title'    => __( 'Carousel', 'rise-and-flour' ),
			'fields'   => array(
				array(
					'key'   => 'field_rf_car_eyebrow',
					'label' => __( 'Eyebrow', 'rise-and-flour' ),
					'name'  => 'eyebrow',
					'type'  => 'text',
				),
				array(
					'key'   => 'field_rf_car_heading',
					'label' => __( 'Heading', 'rise-and-flour' ),
					'name'  => 'heading',
					'type'  => 'text',
				),
				array(
					'key'          => 'field_rf_car_slides',
					'label'        => __( 'Slides', 'rise-and-flour' ),
					'name'         => 'slides',
					'type'         => 'repeater',
					'instructions' => __( 'Drag to reorder. The caption floats over the image — keep it short, two lines at most.', 'rise-and-flour' ),
					'layout'       => 'block',
					'button_label' => __( 'Add slide', 'rise-and-flour' ),
					'min'          => 0,
					'sub_fields'   => array(
						rise_and_flour_image_field( 'field_rf_car_slide_image' ),
						array(
							'key'   => 'field_rf_car_slide_caption',
							'label' => __( 'Caption', 'rise-and-flour' ),
							'name'  => 'caption',
							'type'  => 'text',
						),
					),
				),
				rise_and_flour_spacing_field( 'field_rf_car_spacing' ),
			),
			'location' => array(
				array(
					array(
						'param'    => 'block',
						'operator' => '==',
						'value'    => 'acf/rf-carousel',
					),
				),
			),
		)
	);
}
add_action( 'acf/init', 'rise_and_flour_register_fields' );

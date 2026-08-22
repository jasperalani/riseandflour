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
				array(
					'key'           => 'field_rf_car_size',
					'label'         => __( 'Size', 'rise-and-flour' ),
					'name'          => 'size',
					'type'          => 'button_group',
					'instructions'  => __( 'Large fills most of the screen with the next image running off the page edge. Small is a tighter strip with a fixed peek of the next image.', 'rise-and-flour' ),
					'choices'       => array(
						'large' => __( 'Large', 'rise-and-flour' ),
						'small' => __( 'Small', 'rise-and-flour' ),
					),
					'default_value' => 'large',
					'allow_null'    => 0,
				),
				array(
					'key'           => 'field_rf_car_pager',
					'label'         => __( 'Pager style', 'rise-and-flour' ),
					'name'          => 'pager',
					'type'          => 'select',
					'instructions'  => __( 'The row of markers beneath the slides.', 'rise-and-flour' ),
					'choices'       => array(
						'dash'    => __( 'Dashes', 'rise-and-flour' ),
						'dot'     => __( 'Dots', 'rise-and-flour' ),
						'diamond' => __( 'Diamonds', 'rise-and-flour' ),
					),
					'default_value' => 'dash',
					'allow_null'    => 0,
					'ui'            => 0,
					'return_format' => 'value',
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

	/* ---------------------------------------------------------
	 * Menu
	 * --------------------------------------------------------- */
	acf_add_local_field_group(
		array(
			'key'      => 'group_rf_menu',
			'title'    => __( 'Menu', 'rise-and-flour' ),
			'fields'   => array(
				array(
					'key'           => 'field_rf_menu_eyebrow',
					'label'         => __( 'Eyebrow', 'rise-and-flour' ),
					'name'          => 'eyebrow',
					'type'          => 'text',
					'default_value' => 'Rise & Flour Café',
				),
				array(
					'key'           => 'field_rf_menu_heading',
					'label'         => __( 'Heading', 'rise-and-flour' ),
					'name'          => 'heading',
					'type'          => 'text',
					'default_value' => 'Menu',
				),
				array(
					'key'           => 'field_rf_menu_tag',
					'label'         => __( 'Heading level', 'rise-and-flour' ),
					'name'          => 'tag',
					'type'          => 'button_group',
					'instructions'  => __( 'H1 when this is the opening block of the page.', 'rise-and-flour' ),
					'choices'       => array(
						'h1' => 'H1',
						'h2' => 'H2',
					),
					'default_value' => 'h1',
					'allow_null'    => 0,
				),
				array(
					'key'          => 'field_rf_menu_intro',
					'label'        => __( 'Intro', 'rise-and-flour' ),
					'name'         => 'intro',
					'type'         => 'textarea',
					'rows'         => 2,
					'new_lines'    => '',
					'instructions' => __( 'Optional line beneath the heading.', 'rise-and-flour' ),
				),
				array(
					'key'          => 'field_rf_menu_sections',
					'label'        => __( 'Sections', 'rise-and-flour' ),
					'name'         => 'sections',
					'type'         => 'repeater',
					'instructions' => __( 'e.g. "Served all day", "Drinks". Drag to reorder.', 'rise-and-flour' ),
					'layout'       => 'block',
					'button_label' => __( 'Add section', 'rise-and-flour' ),
					'min'          => 0,
					'sub_fields'   => array(
						array(
							'key'   => 'field_rf_menu_section_title',
							'label' => __( 'Section title', 'rise-and-flour' ),
							'name'  => 'title',
							'type'  => 'text',
						),
						array(
							'key'          => 'field_rf_menu_section_note',
							'label'        => __( 'Section note', 'rise-and-flour' ),
							'name'         => 'note',
							'type'         => 'text',
							'instructions' => __( 'Optional, shown under the title.', 'rise-and-flour' ),
						),
						array(
							'key'          => 'field_rf_menu_items',
							'label'        => __( 'Dishes', 'rise-and-flour' ),
							'name'         => 'items',
							'type'         => 'repeater',
							'layout'       => 'block',
							'button_label' => __( 'Add dish', 'rise-and-flour' ),
							'min'          => 0,
							'sub_fields'   => array(
								array(
									'key'          => 'field_rf_menu_item_name',
									'label'        => __( 'Name', 'rise-and-flour' ),
									'name'         => 'name',
									'type'         => 'text',
									'instructions' => __( 'Set in the Core Magic brand face.', 'rise-and-flour' ),
									'wrapper'      => array( 'width' => '55' ),
								),
								array(
									'key'          => 'field_rf_menu_item_price',
									'label'        => __( 'Price', 'rise-and-flour' ),
									'name'         => 'price',
									'type'         => 'text',
									'instructions' => __( 'Just the number — "9.00" shows as £9.00. Leave blank to hide.', 'rise-and-flour' ),
									'wrapper'      => array( 'width' => '25' ),
								),
								array(
									'key'           => 'field_rf_menu_item_veg',
									'label'         => __( 'Vegetarian', 'rise-and-flour' ),
									'name'          => 'vegetarian',
									'type'          => 'true_false',
									'ui'            => 1,
									'default_value' => 0,
									'wrapper'       => array( 'width' => '20' ),
								),
								array(
									'key'          => 'field_rf_menu_item_description',
									'label'        => __( 'Description', 'rise-and-flour' ),
									'name'         => 'description',
									'type'         => 'textarea',
									'rows'         => 2,
									'new_lines'    => '',
									'instructions' => __( 'Ingredients. Wrap in &lt;em&gt; for italics, e.g. &lt;em&gt;with crisps&lt;/em&gt;.', 'rise-and-flour' ),
								),
								array(
									'key'          => 'field_rf_menu_item_extras',
									'label'        => __( 'Add', 'rise-and-flour' ),
									'name'         => 'extras',
									'type'         => 'text',
									'instructions' => __( 'Optional extras, e.g. "Smoked bacon 4 · Halloumi 3.5". Shown after an "Add" label.', 'rise-and-flour' ),
								),
								array(
									'key'          => 'field_rf_menu_item_note',
									'label'        => __( 'Note', 'rise-and-flour' ),
									'name'         => 'note',
									'type'         => 'text',
									'instructions' => __( 'Optional italic line, e.g. a veg option or "Gluten free bread 50p".', 'rise-and-flour' ),
								),
							),
						),
					),
				),
				array(
					'key'           => 'field_rf_menu_hours_heading',
					'label'         => __( 'Hours heading', 'rise-and-flour' ),
					'name'          => 'hours_heading',
					'type'          => 'text',
					'default_value' => 'Hours',
					'wrapper'       => array( 'width' => '30' ),
				),
				array(
					'key'          => 'field_rf_menu_hours',
					'label'        => __( 'Hours', 'rise-and-flour' ),
					'name'         => 'hours',
					'type'         => 'textarea',
					'rows'         => 8,
					'new_lines'    => '',
					'instructions' => __( 'One range per line; a blank line starts a new group and &lt;strong&gt;…&lt;/strong&gt; makes a group label. Leave empty to use the hours from Site Details.', 'rise-and-flour' ),
					'wrapper'      => array( 'width' => '70' ),
				),
				array(
					'key'          => 'field_rf_menu_notes',
					'label'        => __( 'Notes', 'rise-and-flour' ),
					'name'         => 'notes',
					'type'         => 'textarea',
					'rows'         => 4,
					'new_lines'    => '',
					'instructions' => __( 'Closing lines, one per paragraph — "Eat in or takeaway", "Please ask for today\'s specials".', 'rise-and-flour' ),
				),
				array(
					'key'           => 'field_rf_menu_file',
					'label'         => __( 'Menu PDF', 'rise-and-flour' ),
					'name'          => 'file',
					'type'          => 'file',
					'return_format' => 'array',
					'library'       => 'all',
					'mime_types'    => 'pdf',
					'instructions'  => __( 'Optional — adds a "Download the menu" link.', 'rise-and-flour' ),
				),
				rise_and_flour_spacing_field( 'field_rf_menu_spacing' ),
			),
			'location' => array(
				array(
					array(
						'param'    => 'block',
						'operator' => '==',
						'value'    => 'acf/rf-menu',
					),
				),
			),
		)
	);
}
add_action( 'acf/init', 'rise_and_flour_register_fields' );

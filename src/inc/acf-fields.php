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
					'key'          => 'field_rf_it_caption',
					'label'        => __( 'Caption', 'rise-and-flour' ),
					'name'         => 'caption',
					'type'         => 'text',
					'instructions' => __( 'Sits under the photo in italics. Keep it to one line. Photos are cropped to 4:5 (portrait).', 'rise-and-flour' ),
				),
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
					'instructions' => __( 'The link only shows when it has both text and a destination.', 'rise-and-flour' ),
				),
				array(
					'key'           => 'field_rf_it_link_type',
					'label'         => __( 'Link to', 'rise-and-flour' ),
					'name'          => 'link_type',
					'type'          => 'button_group',
					'choices'       => array(
						'page' => __( 'A page', 'rise-and-flour' ),
						'url'  => __( 'A URL', 'rise-and-flour' ),
					),
					'default_value' => 'page',
					'allow_null'    => 0,
				),
				array(
					'key'               => 'field_rf_it_link_page',
					'label'             => __( 'Page', 'rise-and-flour' ),
					'name'              => 'link_page',
					'type'              => 'page_link',
					'post_type'         => array( 'page', 'rf_event' ),
					'allow_null'        => 1,
					'allow_archives'    => 0,
					'multiple'          => 0,
					'conditional_logic' => array(
						array(
							array(
								'field'    => 'field_rf_it_link_type',
								'operator' => '==',
								'value'    => 'page',
							),
						),
					),
				),
				array(
					'key'               => 'field_rf_it_link_url',
					'label'             => __( 'Link URL', 'rise-and-flour' ),
					'name'              => 'link_url',
					'type'              => 'url',
					'conditional_logic' => array(
						array(
							array(
								'field'    => 'field_rf_it_link_type',
								'operator' => '==',
								'value'    => 'url',
							),
						),
					),
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

	/* ---------------------------------------------------------
	 * Location
	 * --------------------------------------------------------- */
	acf_add_local_field_group(
		array(
			'key'      => 'group_rf_location',
			'title'    => __( 'Location', 'rise-and-flour' ),
			'fields'   => array(
				array(
					'key'           => 'field_rf_loc_eyebrow',
					'label'         => __( 'Eyebrow', 'rise-and-flour' ),
					'name'          => 'eyebrow',
					'type'          => 'text',
					'default_value' => 'Find us',
				),
				array(
					'key'           => 'field_rf_loc_heading',
					'label'         => __( 'Heading', 'rise-and-flour' ),
					'name'          => 'heading',
					'type'          => 'text',
					'default_value' => 'Location',
				),
				array(
					'key'           => 'field_rf_loc_tag',
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
					'key'          => 'field_rf_loc_intro',
					'label'        => __( 'Intro', 'rise-and-flour' ),
					'name'         => 'intro',
					'type'         => 'textarea',
					'rows'         => 3,
					'new_lines'    => '',
					'instructions' => __( 'Optional paragraph beneath the heading.', 'rise-and-flour' ),
				),
				array(
					'key'          => 'field_rf_loc_address',
					'label'        => __( 'Address', 'rise-and-flour' ),
					'name'         => 'address',
					'type'         => 'textarea',
					'rows'         => 3,
					'new_lines'    => '',
					'instructions' => __( 'Leave empty to use the address from Site Details.', 'rise-and-flour' ),
					'wrapper'      => array( 'width' => '50' ),
				),
				array(
					'key'           => 'field_rf_loc_show_hours',
					'label'         => __( 'Show hours', 'rise-and-flour' ),
					'name'          => 'show_hours',
					'type'          => 'true_false',
					'ui'            => 1,
					'default_value' => 1,
					'instructions'  => __( 'Opening hours come from Site Details.', 'rise-and-flour' ),
					'wrapper'       => array( 'width' => '50' ),
				),
				array(
					'key'          => 'field_rf_loc_phone',
					'label'        => __( 'Phone', 'rise-and-flour' ),
					'name'         => 'phone',
					'type'         => 'text',
					'instructions' => __( 'Leave empty to use Site Details.', 'rise-and-flour' ),
					'wrapper'      => array( 'width' => '50' ),
				),
				array(
					'key'          => 'field_rf_loc_email',
					'label'        => __( 'Email', 'rise-and-flour' ),
					'name'         => 'email',
					'type'         => 'email',
					'instructions' => __( 'Leave empty to use Site Details.', 'rise-and-flour' ),
					'wrapper'      => array( 'width' => '50' ),
				),
				array(
					'key'           => 'field_rf_loc_note_label',
					'label'         => __( 'Note label', 'rise-and-flour' ),
					'name'          => 'note_label',
					'type'          => 'text',
					'default_value' => 'Getting here',
					'wrapper'       => array( 'width' => '30' ),
				),
				array(
					'key'          => 'field_rf_loc_note',
					'label'        => __( 'Note', 'rise-and-flour' ),
					'name'         => 'note',
					'type'         => 'textarea',
					'rows'         => 3,
					'new_lines'    => '',
					'instructions' => __( 'Parking, access, "dogs welcome" — anything useful for a visit.', 'rise-and-flour' ),
					'wrapper'      => array( 'width' => '70' ),
				),
				array(
					'key'          => 'field_rf_loc_directions',
					'label'        => __( 'Directions link', 'rise-and-flour' ),
					'name'         => 'directions_url',
					'type'         => 'url',
					'instructions' => __( 'A Google Maps link; shows a "Get directions" link when set.', 'rise-and-flour' ),
				),
				array(
					'key'          => 'field_rf_loc_map',
					'label'        => __( 'Map', 'rise-and-flour' ),
					'name'         => 'map',
					'type'         => 'textarea',
					'rows'         => 3,
					'new_lines'    => '',
					'instructions' => __( 'In Google Maps: Share → Embed a map → copy. Paste the whole &lt;iframe&gt; snippet or just its URL. Empty shows a grey placeholder.', 'rise-and-flour' ),
				),
				array(
					'key'           => 'field_rf_loc_map_position',
					'label'         => __( 'Map side', 'rise-and-flour' ),
					'name'          => 'map_position',
					'type'          => 'button_group',
					'instructions'  => __( 'On mobile the details always sit above the map.', 'rise-and-flour' ),
					'choices'       => array(
						'right' => __( 'Right', 'rise-and-flour' ),
						'left'  => __( 'Left', 'rise-and-flour' ),
					),
					'default_value' => 'right',
					'allow_null'    => 0,
				),
				rise_and_flour_spacing_field( 'field_rf_loc_spacing' ),
			),
			'location' => array(
				array(
					array(
						'param'    => 'block',
						'operator' => '==',
						'value'    => 'acf/rf-location',
					),
				),
			),
		)
	);

	/* ---------------------------------------------------------
	 * Contact form
	 * --------------------------------------------------------- */
	acf_add_local_field_group(
		array(
			'key'      => 'group_rf_contact',
			'title'    => __( 'Contact form', 'rise-and-flour' ),
			'fields'   => array(
				array(
					'key'           => 'field_rf_ct_eyebrow',
					'label'         => __( 'Eyebrow', 'rise-and-flour' ),
					'name'          => 'eyebrow',
					'type'          => 'text',
					'default_value' => 'Contact',
					'wrapper'       => array( 'width' => '50' ),
				),
				array(
					'key'           => 'field_rf_ct_heading',
					'label'         => __( 'Heading', 'rise-and-flour' ),
					'name'          => 'heading',
					'type'          => 'text',
					'default_value' => 'Write to us',
					'wrapper'       => array( 'width' => '50' ),
				),
				array(
					'key'           => 'field_rf_ct_tag',
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
					'key'          => 'field_rf_ct_intro',
					'label'        => __( 'Intro', 'rise-and-flour' ),
					'name'         => 'intro',
					'type'         => 'textarea',
					'rows'         => 2,
					'new_lines'    => '',
					'instructions' => __( 'e.g. "Questions, bookings, or something you’d like to see on the menu — we read everything and reply within a day."', 'rise-and-flour' ),
				),
				array(
					'key'          => 'field_rf_ct_recipient',
					'label'        => __( 'Send messages to', 'rise-and-flour' ),
					'name'         => 'recipient',
					'type'         => 'email',
					'instructions' => __( 'Leave empty to use the email in Site Details. Never shown on the page.', 'rise-and-flour' ),
				),
				array(
					'key'           => 'field_rf_ct_show_contact',
					'label'         => __( 'Show email and phone', 'rise-and-flour' ),
					'name'          => 'show_contact',
					'type'          => 'true_false',
					'ui'            => 1,
					'default_value' => 1,
					'instructions'  => __( 'From Site Details.', 'rise-and-flour' ),
					'wrapper'       => array( 'width' => '33' ),
				),
				array(
					'key'          => 'field_rf_ct_phone_note',
					'label'        => __( 'Phone note', 'rise-and-flour' ),
					'name'         => 'phone_note',
					'type'         => 'text',
					'instructions' => __( 'Italic line under the number, e.g. "During opening hours — we’re often at the counter."', 'rise-and-flour' ),
					'wrapper'      => array( 'width' => '34' ),
				),
				array(
					'key'           => 'field_rf_ct_show_visit',
					'label'         => __( 'Show "In person"', 'rise-and-flour' ),
					'name'          => 'show_visit',
					'type'          => 'true_false',
					'ui'            => 1,
					'default_value' => 1,
					'instructions'  => __( 'Address and hours from Site Details.', 'rise-and-flour' ),
					'wrapper'       => array( 'width' => '33' ),
				),
				array(
					'key'          => 'field_rf_ct_extras',
					'label'        => __( 'Extra notes', 'rise-and-flour' ),
					'name'         => 'extras',
					'type'         => 'repeater',
					'instructions' => __( 'More labelled notes beneath the details — "Events & private hire", "Press", and so on.', 'rise-and-flour' ),
					'layout'       => 'block',
					'button_label' => __( 'Add note', 'rise-and-flour' ),
					'min'          => 0,
					'sub_fields'   => array(
						array(
							'key'   => 'field_rf_ct_extra_label',
							'label' => __( 'Label', 'rise-and-flour' ),
							'name'  => 'label',
							'type'  => 'text',
						),
						array(
							'key'       => 'field_rf_ct_extra_text',
							'label'     => __( 'Text', 'rise-and-flour' ),
							'name'      => 'text',
							'type'      => 'textarea',
							'rows'      => 2,
							'new_lines' => '',
						),
					),
				),
				array(
					'key'          => 'field_rf_ct_subjects',
					'label'        => __( 'Subject choices', 'rise-and-flour' ),
					'name'         => 'subjects',
					'type'         => 'textarea',
					'rows'         => 4,
					'new_lines'    => '',
					'instructions' => __( 'One per line. Leave empty for: A general question · A booking · Events & private hire · Something else.', 'rise-and-flour' ),
					'wrapper'      => array( 'width' => '50' ),
				),
				array(
					'key'           => 'field_rf_ct_button',
					'label'         => __( 'Button text', 'rise-and-flour' ),
					'name'          => 'button_text',
					'type'          => 'text',
					'default_value' => 'Send message',
					'wrapper'       => array( 'width' => '50' ),
				),
				array(
					'key'           => 'field_rf_ct_reply_note',
					'label'         => __( 'Line beside the button', 'rise-and-flour' ),
					'name'          => 'reply_note',
					'type'          => 'text',
					'instructions'  => __( 'Optional, e.g. "We reply within a day."', 'rise-and-flour' ),
					'default_value' => '',
					'wrapper'       => array( 'width' => '50' ),
				),
				array(
					'key'           => 'field_rf_ct_success',
					'label'         => __( 'After sending', 'rise-and-flour' ),
					'name'          => 'success_text',
					'type'          => 'text',
					'default_value' => 'Thanks — your message is on its way. We reply within a day.',
					'wrapper'       => array( 'width' => '50' ),
				),
				rise_and_flour_spacing_field( 'field_rf_ct_spacing' ),
			),
			'location' => array(
				array(
					array(
						'param'    => 'block',
						'operator' => '==',
						'value'    => 'acf/rf-contact',
					),
				),
			),
		)
	);

	/* ---------------------------------------------------------
	 * Opening hours
	 * --------------------------------------------------------- */
	acf_add_local_field_group(
		array(
			'key'      => 'group_rf_hours',
			'title'    => __( 'Opening hours', 'rise-and-flour' ),
			'fields'   => array(
				array(
					'key'           => 'field_rf_hrs_style',
					'label'         => __( 'Style', 'rise-and-flour' ),
					'name'          => 'style',
					'type'          => 'button_group',
					'instructions'  => __( 'Panel: cream box, every hours group with its label. Framed: terracotta frame, day and time in rows. Band: ink strip edge to edge, times in columns. Band · page: the same strip, but boxed within the page column.', 'rise-and-flour' ),
					'choices'       => array(
						'panel'     => __( 'Panel', 'rise-and-flour' ),
						'framed'    => __( 'Framed', 'rise-and-flour' ),
						'band'      => __( 'Band · full width', 'rise-and-flour' ),
						'band-wrap' => __( 'Band · page', 'rise-and-flour' ),
					),
					'default_value' => 'framed',
					'allow_null'    => 0,
				),
				array(
					'key'          => 'field_rf_hrs_heading',
					'label'        => __( 'Heading', 'rise-and-flour' ),
					'name'         => 'heading',
					'type'         => 'text',
					'instructions' => __( 'Leave empty for "Hours" (panel, band) or "Opening hours" (framed).', 'rise-and-flour' ),
					'wrapper'      => array( 'width' => '50' ),
				),
				array(
					'key'           => 'field_rf_hrs_tag',
					'label'         => __( 'Heading level', 'rise-and-flour' ),
					'name'          => 'tag',
					'type'          => 'button_group',
					'choices'       => array(
						'h2' => 'H2',
						'h3' => 'H3',
						'h1' => 'H1',
					),
					'default_value' => 'h2',
					'allow_null'    => 0,
					'wrapper'       => array( 'width' => '50' ),
				),
				array(
					'key'          => 'field_rf_hrs_note',
					'label'        => __( 'Note', 'rise-and-flour' ),
					'name'         => 'note',
					'type'         => 'text',
					'instructions' => __( 'Italic line — under the rows in the frame, beside them in the band. Leave empty to describe the further hours groups from Site Details automatically, e.g. "Cooked food served until 2:30pm, Sundays 2pm." The panel lists every group in full instead.', 'rise-and-flour' ),
				),
				rise_and_flour_spacing_field( 'field_rf_hrs_spacing' ),
			),
			'location' => array(
				array(
					array(
						'param'    => 'block',
						'operator' => '==',
						'value'    => 'acf/rf-hours',
					),
				),
			),
		)
	);

	/* ---------------------------------------------------------
	 * Events (list block)
	 * --------------------------------------------------------- */
	acf_add_local_field_group(
		array(
			'key'      => 'group_rf_events',
			'title'    => __( 'Events', 'rise-and-flour' ),
			'fields'   => array(
				array(
					'key'           => 'field_rf_ev_eyebrow',
					'label'         => __( 'Eyebrow', 'rise-and-flour' ),
					'name'          => 'eyebrow',
					'type'          => 'text',
					'default_value' => "What's on",
				),
				array(
					'key'           => 'field_rf_ev_heading',
					'label'         => __( 'Heading', 'rise-and-flour' ),
					'name'          => 'heading',
					'type'          => 'text',
					'default_value' => 'Events',
				),
				array(
					'key'           => 'field_rf_ev_tag',
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
					'key'          => 'field_rf_ev_intro',
					'label'        => __( 'Intro', 'rise-and-flour' ),
					'name'         => 'intro',
					'type'         => 'textarea',
					'rows'         => 3,
					'new_lines'    => '',
					'instructions' => __( 'Optional paragraph beneath the heading.', 'rise-and-flour' ),
				),
				array(
					'key'           => 'field_rf_ev_show',
					'label'         => __( 'Show', 'rise-and-flour' ),
					'name'          => 'show',
					'type'          => 'button_group',
					'instructions'  => __( 'Events are added under Events in the admin menu, not here.', 'rise-and-flour' ),
					'choices'       => array(
						'both'     => __( 'Upcoming + past', 'rise-and-flour' ),
						'upcoming' => __( 'Upcoming only', 'rise-and-flour' ),
						'past'     => __( 'Past only', 'rise-and-flour' ),
					),
					'default_value' => 'both',
					'allow_null'    => 0,
					'wrapper'       => array( 'width' => '60' ),
				),
				array(
					'key'           => 'field_rf_ev_limit',
					'label'         => __( 'Upcoming limit', 'rise-and-flour' ),
					'name'          => 'limit',
					'type'          => 'number',
					'instructions'  => __( '0 shows all upcoming events.', 'rise-and-flour' ),
					'default_value' => 0,
					'min'           => 0,
					'step'          => 1,
					'wrapper'       => array( 'width' => '20' ),
				),
				array(
					'key'           => 'field_rf_ev_past_batch',
					'label'         => __( 'Past events per batch', 'rise-and-flour' ),
					'name'          => 'past_batch',
					'type'          => 'number',
					'instructions'  => __( 'Shown at first, and added per "Show more" click. 0 shows all past events with no button.', 'rise-and-flour' ),
					'default_value' => 3,
					'min'           => 0,
					'step'          => 1,
					'wrapper'       => array( 'width' => '20' ),
				),
				array(
					'key'           => 'field_rf_ev_past_heading',
					'label'         => __( 'Past events heading', 'rise-and-flour' ),
					'name'          => 'past_heading',
					'type'          => 'text',
					'default_value' => 'Past events',
					'wrapper'       => array( 'width' => '50' ),
				),
				array(
					'key'           => 'field_rf_ev_empty',
					'label'         => __( 'Nothing upcoming text', 'rise-and-flour' ),
					'name'          => 'empty_text',
					'type'          => 'text',
					'default_value' => 'Nothing in the diary just yet — check back soon.',
					'wrapper'       => array( 'width' => '50' ),
				),
				rise_and_flour_spacing_field( 'field_rf_ev_spacing' ),
			),
			'location' => array(
				array(
					array(
						'param'    => 'block',
						'operator' => '==',
						'value'    => 'acf/rf-events',
					),
				),
			),
		)
	);

	/* ---------------------------------------------------------
	 * Event (the post type's own fields)
	 * --------------------------------------------------------- */
	acf_add_local_field_group(
		array(
			'key'      => 'group_rf_event',
			'title'    => __( 'Event details', 'rise-and-flour' ),
			'position' => 'acf_after_title',
			'fields'   => array(
				array(
					'key'   => 'field_rf_event_tab_when',
					'label' => __( 'When & where', 'rise-and-flour' ),
					'type'  => 'tab',
				),
				array(
					'key'            => 'field_rf_event_start',
					'label'          => __( 'Starts', 'rise-and-flour' ),
					'name'           => 'start',
					'type'           => 'date_time_picker',
					'required'       => 1,
					'display_format' => 'D j M Y, g:i a',
					'return_format'  => 'Y-m-d H:i:s',
					'first_day'      => 1,
					'wrapper'        => array( 'width' => '50' ),
				),
				array(
					'key'            => 'field_rf_event_end',
					'label'          => __( 'Ends', 'rise-and-flour' ),
					'name'           => 'end',
					'type'           => 'date_time_picker',
					'instructions'   => __( 'Optional. The event counts as past once it has ended — or, with no end, once its day is over.', 'rise-and-flour' ),
					'display_format' => 'D j M Y, g:i a',
					'return_format'  => 'Y-m-d H:i:s',
					'first_day'      => 1,
					'wrapper'        => array( 'width' => '50' ),
				),
				array(
					'key'          => 'field_rf_event_location',
					'label'        => __( 'Where', 'rise-and-flour' ),
					'name'         => 'location',
					'type'         => 'text',
					'instructions' => __( 'Leave empty for "At the café".', 'rise-and-flour' ),
					'wrapper'      => array( 'width' => '50' ),
				),
				array(
					'key'          => 'field_rf_event_price',
					'label'        => __( 'Price', 'rise-and-flour' ),
					'name'         => 'price',
					'type'         => 'text',
					'instructions' => __( 'As you\'d say it: "£45", "Free", "£5 on the door".', 'rise-and-flour' ),
					'wrapper'      => array( 'width' => '25' ),
				),
				array(
					'key'          => 'field_rf_event_capacity',
					'label'        => __( 'Capacity', 'rise-and-flour' ),
					'name'         => 'capacity',
					'type'         => 'text',
					'instructions' => __( 'Optional, e.g. "18 seats". Ends the date line.', 'rise-and-flour' ),
					'wrapper'      => array( 'width' => '25' ),
				),

				array(
					'key'   => 'field_rf_event_tab_titles',
					'label' => __( 'Titles', 'rise-and-flour' ),
					'type'  => 'tab',
				),
				array(
					'key'          => 'field_rf_event_eyebrow',
					'label'        => __( 'Eyebrow', 'rise-and-flour' ),
					'name'         => 'eyebrow',
					'type'         => 'text',
					'instructions' => __( 'Small terracotta line above the title, e.g. "Supper club". Shown as · Supper club ·. Empty shows "Event".', 'rise-and-flour' ),
				),
				array(
					'key'          => 'field_rf_event_subtitle',
					'label'        => __( 'Subtitle', 'rise-and-flour' ),
					'name'         => 'subtitle',
					'type'         => 'text',
					'instructions' => __( 'Italic line under the title, e.g. "An evening of late summer, served family-style".', 'rise-and-flour' ),
				),

				array(
					'key'   => 'field_rf_event_tab_panel',
					'label' => __( 'On the night', 'rise-and-flour' ),
					'type'  => 'tab',
				),
				array(
					'key'           => 'field_rf_event_panel_label',
					'label'         => __( 'Panel heading', 'rise-and-flour' ),
					'name'          => 'panel_label',
					'type'          => 'text',
					'instructions'  => __( 'The ink panel beneath the write-up. Shown as · On the night ·. The panel only appears when it has lines.', 'rise-and-flour' ),
					'default_value' => 'On the night',
					'wrapper'       => array( 'width' => '50' ),
				),
				array(
					'key'          => 'field_rf_event_panel_note',
					'label'        => __( 'Panel footer', 'rise-and-flour' ),
					'name'         => 'panel_note',
					'type'         => 'text',
					'instructions' => __( 'Optional closing line, e.g. "Five courses".', 'rise-and-flour' ),
					'wrapper'      => array( 'width' => '50' ),
				),
				array(
					'key'          => 'field_rf_event_courses',
					'label'        => __( 'Lines', 'rise-and-flour' ),
					'name'         => 'courses',
					'type'         => 'repeater',
					'instructions' => __( 'One per course, act, or item. Drag to reorder.', 'rise-and-flour' ),
					'layout'       => 'table',
					'button_label' => __( 'Add line', 'rise-and-flour' ),
					'min'          => 0,
					'sub_fields'   => array(
						array(
							'key'   => 'field_rf_event_course_text',
							'label' => __( 'Line', 'rise-and-flour' ),
							'name'  => 'text',
							'type'  => 'text',
						),
					),
				),

				array(
					'key'   => 'field_rf_event_tab_booking',
					'label' => __( 'Booking', 'rise-and-flour' ),
					'type'  => 'tab',
				),
				array(
					'key'          => 'field_rf_event_booking_url',
					'label'        => __( 'Booking link', 'rise-and-flour' ),
					'name'         => 'booking_url',
					'type'         => 'url',
					'instructions' => __( 'Eventbrite, a form, or mailto:… — the button is shown while the event is upcoming.', 'rise-and-flour' ),
					'wrapper'      => array( 'width' => '60' ),
				),
				array(
					'key'           => 'field_rf_event_booking_text',
					'label'         => __( 'Button text', 'rise-and-flour' ),
					'name'          => 'booking_text',
					'type'          => 'text',
					'default_value' => 'Book a place',
					'wrapper'       => array( 'width' => '40' ),
				),
				array(
					'key'          => 'field_rf_event_booking_note',
					'label'        => __( 'Line under the button', 'rise-and-flour' ),
					'name'         => 'booking_note',
					'type'         => 'text',
					'instructions' => __( 'e.g. "We reply within a day. No payment until the night."', 'rise-and-flour' ),
				),
			),
			'location' => array(
				array(
					array(
						'param'    => 'post_type',
						'operator' => '==',
						'value'    => 'rf_event',
					),
				),
			),
		)
	);
}
add_action( 'acf/init', 'rise_and_flour_register_fields' );

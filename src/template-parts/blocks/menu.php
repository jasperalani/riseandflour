<?php
/**
 * BLOCK: Menu.
 *
 * The café menu as structured content rather than an uploaded image —
 * readable, searchable, and editable dish by dish. Sections hold dishes;
 * each dish has a name, price, description and optional "add" line, note
 * and vegetarian mark. Hours and closing notes sit in an aside beneath.
 *
 * Dish names are the one place in body content that uses Core Magic —
 * the brand reserves it for the logo and product names.
 *
 * ACF fields: eyebrow · heading · tag · intro · sections (repeater) ·
 *             hours_heading · hours · notes · file
 *
 * @package Rise_&_Flour
 *
 * @var array $args Block arguments.
 */

$rf = wp_parse_args(
	$args ?? array(),
	array(
		'eyebrow'       => '',
		'heading'       => '',
		'tag'           => 'h1',
		'intro'         => '',
		'sections'      => array(),
		'hours_heading' => '',
		'hours'         => '',
		'notes'         => '',
		'file_url'      => '',
		'classes'       => '',
		'anchor'        => '',
	)
);

if ( empty( $rf['sections'] ) ) {
	return;
}

$rf_tag = in_array( $rf['tag'], array( 'h1', 'h2' ), true ) ? $rf['tag'] : 'h1';

// Hours fall back to the Site Details value so they're never typed twice.
$rf_hours = $rf['hours'] ? $rf['hours'] : rise_and_flour_option( 'footer_hours', '' );

// The ampersand is a brand character — terracotta wherever it appears.
$rf_amp = function ( $text ) {
	return str_replace( '&amp;', '<span class="amp">&amp;</span>', esc_html( $text ) );
};

// Prose fields may carry a little inline markup (italic "with crisps").
$rf_inline = array(
	'em'     => array(),
	'strong' => array(),
	'br'     => array(),
);

// Prices are typed as plain numbers on the print menu; add the pound sign
// here so the editor never has to. Anything else ("from 4", "25p") is
// shown as typed.
$rf_price = function ( $price ) {
	$price = trim( (string) $price );
	return is_numeric( $price ) ? '£' . $price : $price;
};
?>

<section
	class="block-menu <?php echo esc_attr( $rf['classes'] ); ?>"
	<?php echo $rf['anchor'] ? 'id="' . esc_attr( $rf['anchor'] ) . '"' : ''; ?>
>

	<?php if ( $rf['eyebrow'] || $rf['heading'] || $rf['intro'] ) : ?>
		<header class="block-menu__head">
			<?php if ( $rf['eyebrow'] ) : ?>
				<p class="eyebrow"><?php echo wp_kses( $rf_amp( $rf['eyebrow'] ), array( 'span' => array( 'class' => array() ) ) ); ?></p>
			<?php endif; ?>

			<?php if ( $rf['heading'] ) : ?>
				<<?php echo esc_attr( $rf_tag ); ?>><?php echo esc_html( $rf['heading'] ); ?></<?php echo esc_attr( $rf_tag ); ?>>
			<?php endif; ?>

			<?php if ( $rf['intro'] ) : ?>
				<div class="block-menu__intro"><?php echo wp_kses_post( wpautop( $rf['intro'] ) ); ?></div>
			<?php endif; ?>
		</header>
	<?php endif; ?>

	<?php foreach ( $rf['sections'] as $rf_section ) : ?>
		<?php
		$rf_section = wp_parse_args(
			$rf_section,
			array(
				'title' => '',
				'note'  => '',
				'items' => array(),
			)
		);
		if ( empty( $rf_section['items'] ) ) {
			continue;
		}
		?>
		<section class="block-menu__section">

			<?php if ( $rf_section['title'] || $rf_section['note'] ) : ?>
				<div class="block-menu__section-head">
					<?php if ( $rf_section['title'] ) : ?>
						<h2 class="block-menu__section-title"><?php echo esc_html( $rf_section['title'] ); ?></h2>
					<?php endif; ?>
					<?php if ( $rf_section['note'] ) : ?>
						<p class="block-menu__section-note"><?php echo wp_kses( $rf_section['note'], $rf_inline ); ?></p>
					<?php endif; ?>
				</div>
			<?php endif; ?>

			<ul class="block-menu__items">
				<?php foreach ( $rf_section['items'] as $rf_item ) : ?>
					<?php
					$rf_item = wp_parse_args(
						$rf_item,
						array(
							'name'        => '',
							'price'       => '',
							'description' => '',
							'vegetarian'  => false,
							'extras'      => '',
							'note'        => '',
						)
					);
					if ( ! $rf_item['name'] ) {
						continue;
					}
					?>
					<li class="block-menu__item">
						<div class="block-menu__item-head">
							<p class="block-menu__name">
								<?php echo wp_kses( $rf_amp( $rf_item['name'] ), array( 'span' => array( 'class' => array() ) ) ); ?>
								<?php if ( $rf_item['vegetarian'] ) : ?>
									<span class="block-menu__tag" title="<?php esc_attr_e( 'Vegetarian', 'rise-and-flour' ); ?>"><abbr><?php esc_html_e( 'V', 'rise-and-flour' ); ?></abbr><span class="screen-reader-text"> <?php esc_html_e( 'Vegetarian', 'rise-and-flour' ); ?></span></span>
								<?php endif; ?>
							</p>
							<?php if ( '' !== trim( (string) $rf_item['price'] ) ) : ?>
								<span class="block-menu__leader" aria-hidden="true"></span>
								<p class="block-menu__price"><?php echo esc_html( $rf_price( $rf_item['price'] ) ); ?></p>
							<?php endif; ?>
						</div>

						<?php if ( $rf_item['description'] ) : ?>
							<p class="block-menu__desc"><?php echo wp_kses( nl2br( $rf_item['description'] ), $rf_inline ); ?></p>
						<?php endif; ?>

						<?php if ( $rf_item['extras'] ) : ?>
							<p class="block-menu__extras"><span class="block-menu__label"><?php esc_html_e( 'Add', 'rise-and-flour' ); ?></span> <?php echo wp_kses( $rf_item['extras'], $rf_inline ); ?></p>
						<?php endif; ?>

						<?php if ( $rf_item['note'] ) : ?>
							<p class="block-menu__note"><?php echo wp_kses( $rf_item['note'], $rf_inline ); ?></p>
						<?php endif; ?>
					</li>
				<?php endforeach; ?>
			</ul>

		</section>
	<?php endforeach; ?>

	<?php if ( $rf_hours || $rf['notes'] || $rf['file_url'] ) : ?>
		<aside class="block-menu__aside">

			<?php if ( $rf_hours ) : ?>
				<div class="block-menu__hours">
					<h2 class="block-menu__aside-title"><?php echo esc_html( $rf['hours_heading'] ? $rf['hours_heading'] : __( 'Hours', 'rise-and-flour' ) ); ?></h2>
					<?php // Either a block textarea (blank lines → paragraphs) or the Site Details value, which already carries <br>s. ?>
					<?php echo wp_kses_post( wpautop( $rf_hours ) ); ?>
				</div>
			<?php endif; ?>

			<?php if ( $rf['notes'] || $rf['file_url'] ) : ?>
				<div class="block-menu__notes">
					<?php if ( $rf['notes'] ) : ?>
						<?php echo wp_kses_post( wpautop( $rf_amp( $rf['notes'] ) ) ); ?>
					<?php endif; ?>
					<?php if ( $rf['file_url'] ) : ?>
						<a class="link-caps" href="<?php echo esc_url( $rf['file_url'] ); ?>"><?php esc_html_e( 'Download the menu', 'rise-and-flour' ); ?></a>
					<?php endif; ?>
				</div>
			<?php endif; ?>

		</aside>
	<?php endif; ?>

</section>

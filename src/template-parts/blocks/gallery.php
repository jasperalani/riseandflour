<?php
/**
 * BLOCK: Gallery.
 *
 * A centred eyebrow, heading and intro over a grid of photographs. Hairline
 * terracotta rules run through the gaps between images — inside the grid
 * only, never round its edge.
 *
 *   columns — 2, 3 or 4.
 *   aspect  — square or 3:2 crops in a regular grid, or masonry: natural
 *             heights flowing down the columns.
 *
 * ACF fields: eyebrow · heading · tag · intro · images (gallery) · columns ·
 *             aspect
 *
 * @package Rise_&_Flour
 *
 * @var array $args Block arguments.
 */

$rf = wp_parse_args(
	$args ?? array(),
	array(
		'eyebrow' => '',
		'heading' => '',
		'tag'     => 'h2',
		'intro'   => '',
		'images'  => array(),
		'columns' => 3,
		'aspect'  => 'square',
		'classes' => '',
		'anchor'  => '',
	)
);

$rf_tag     = in_array( $rf['tag'], array( 'h1', 'h2', 'h3' ), true ) ? $rf['tag'] : 'h2';
$rf_columns = in_array( (int) $rf['columns'], array( 2, 3, 4 ), true ) ? (int) $rf['columns'] : 3;
$rf_aspect  = in_array( $rf['aspect'], array( 'square', 'wide', 'masonry' ), true ) ? $rf['aspect'] : 'square';

// The gallery field returns IDs (or arrays, if the return format changes).
$rf_images = array();
foreach ( (array) $rf['images'] as $rf_image ) {
	$rf_images[] = is_array( $rf_image ) && isset( $rf_image['ID'] ) ? (int) $rf_image['ID'] : (int) $rf_image;
}
$rf_images = array_filter( $rf_images );

// Nothing chosen yet → two rows of placeholders so the layout can be judged.
$rf_placeholders = $rf_images ? 0 : $rf_columns * 2;

$rf_classes = array(
	'block-gallery',
	'block-gallery--cols-' . $rf_columns,
	'block-gallery--' . $rf_aspect,
	'masonry' === $rf_aspect ? 'block-gallery--flow' : 'block-gallery--grid',
);

if ( $rf['classes'] ) {
	$rf_classes[] = $rf['classes'];
}
?>

<section
	class="<?php echo esc_attr( implode( ' ', $rf_classes ) ); ?>"
	<?php echo $rf['anchor'] ? 'id="' . esc_attr( $rf['anchor'] ) . '"' : ''; ?>
>

	<?php if ( $rf['eyebrow'] || $rf['heading'] || $rf['intro'] ) : ?>
		<div class="block-gallery__head">
			<?php if ( $rf['eyebrow'] ) : ?>
				<p class="eyebrow"><?php echo esc_html( $rf['eyebrow'] ); ?></p>
			<?php endif; ?>

			<?php if ( $rf['heading'] ) : ?>
				<<?php echo esc_attr( $rf_tag ); ?>><?php echo esc_html( $rf['heading'] ); ?></<?php echo esc_attr( $rf_tag ); ?>>
			<?php endif; ?>

			<?php if ( $rf['intro'] ) : ?>
				<?php echo wp_kses_post( wpautop( $rf['intro'] ) ); ?>
			<?php endif; ?>
		</div>
	<?php endif; ?>

	<div class="block-gallery__grid">
		<?php foreach ( $rf_images as $rf_id ) : ?>
			<?php
			// The photo's own proportions, so masonry can snap its height to whole pixels.
			$rf_src  = wp_get_attachment_image_src( $rf_id, 'large' );
			$rf_dims = $rf_src && $rf_src[1] && $rf_src[2] ? sprintf( '--w: %d; --h: %d', $rf_src[1], $rf_src[2] ) : '';
			?>
			<figure class="block-gallery__item"<?php echo $rf_dims ? ' style="' . esc_attr( $rf_dims ) . '"' : ''; ?>>
				<div class="block-gallery__media">
					<?php echo wp_get_attachment_image( $rf_id, 'large', false, array( 'class' => 'media-fill', 'loading' => 'lazy' ) ); ?>
				</div>
			</figure>
		<?php endforeach; ?>

		<?php for ( $rf_i = 0; $rf_i < $rf_placeholders; $rf_i++ ) : ?>
			<figure class="block-gallery__item">
				<div class="block-gallery__media">
					<?php
					rise_and_flour_media(
						array(
							'image' => null,
							'label' => __( 'Gallery image', 'rise-and-flour' ),
						)
					);
					?>
				</div>
			</figure>
		<?php endfor; ?>
	</div>

</section>

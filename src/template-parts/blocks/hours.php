<?php
/**
 * BLOCK: Opening hours.
 *
 * Three styles, all fed by the structured hours on Site Details:
 *
 *   panel  — cream box; every hours group with its caps label and one
 *            range per line ("Mon – Fri, 8:30am – 3pm").
 *   framed — terracotta double frame; the main group as day / time rows on
 *            dotted rules, with an italic note beneath.
 *   band   — ink strip; eyebrow and note on the left, the main group's
 *            rows as columns divided by terracotta rules on the right.
 *
 * ACF fields: style · heading · tag · note
 *
 * @package Rise_&_Flour
 *
 * @var array $args Block arguments.
 */

$rf = wp_parse_args(
	$args ?? array(),
	array(
		'style'   => 'framed',
		'heading' => '',
		'tag'     => 'h2',
		'note'    => '',
		'classes' => '',
		'anchor'  => '',
	)
);

$rf_style = in_array( $rf['style'], array( 'panel', 'framed', 'band' ), true ) ? $rf['style'] : 'framed';
$rf_tag   = in_array( $rf['tag'], array( 'h1', 'h2', 'h3' ), true ) ? $rf['tag'] : 'h2';

$rf_heading = $rf['heading'];
if ( '' === $rf_heading ) {
	$rf_heading = 'framed' === $rf_style ? __( 'Opening hours', 'rise-and-flour' ) : __( 'Hours', 'rise-and-flour' );
}

$rf_groups = rise_and_flour_hours_groups();
$rf_pairs  = rise_and_flour_hours_pairs( 0 );

if ( ! $rf_pairs && ! $rf_groups ) {
	return;
}

$rf_classes = array( 'block-hours', 'block-hours--' . $rf_style );
if ( $rf['classes'] ) {
	$rf_classes[] = $rf['classes'];
}
?>

<section
	class="<?php echo esc_attr( implode( ' ', $rf_classes ) ); ?>"
	<?php echo $rf['anchor'] ? 'id="' . esc_attr( $rf['anchor'] ) . '"' : ''; ?>
>
	<div class="block-hours__inner">

	<?php if ( 'panel' === $rf_style ) : ?>

		<<?php echo esc_attr( $rf_tag ); ?> class="block-hours__heading"><?php echo esc_html( $rf_heading ); ?></<?php echo esc_attr( $rf_tag ); ?>>

		<?php if ( $rf_groups ) : ?>
			<?php foreach ( $rf_groups as $rf_i => $rf_group ) : ?>
				<div class="block-hours__group">
					<?php if ( $rf_group['label'] ) : ?>
						<p class="block-hours__label"><?php echo esc_html( $rf_group['label'] ); ?></p>
					<?php endif; ?>
					<ul class="block-hours__list">
						<?php foreach ( rise_and_flour_hours_pairs( $rf_i, true ) as $rf_pair ) : ?>
							<li><?php echo esc_html( $rf_pair['days'] . ( $rf_pair['time'] ? ', ' . $rf_pair['time'] : '' ) ); ?></li>
						<?php endforeach; ?>
						<?php if ( 0 === $rf_i ) : ?>
							<?php foreach ( rise_and_flour_hours_exceptions() as $rf_line ) : ?>
								<li><?php echo esc_html( $rf_line ); ?></li>
							<?php endforeach; ?>
						<?php endif; ?>
					</ul>
				</div>
			<?php endforeach; ?>
		<?php else : ?>
			<ul class="block-hours__list">
				<?php foreach ( $rf_pairs as $rf_pair ) : ?>
					<li><?php echo esc_html( $rf_pair['days'] . ( $rf_pair['time'] ? ', ' . $rf_pair['time'] : '' ) ); ?></li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>

		<?php if ( $rf['note'] ) : ?>
			<p class="block-hours__note"><?php echo esc_html( $rf['note'] ); ?></p>
		<?php endif; ?>
		<?php rise_and_flour_open_status( 'block-hours__status' ); ?>

	<?php elseif ( 'framed' === $rf_style ) : ?>

		<<?php echo esc_attr( $rf_tag ); ?> class="eyebrow block-hours__eyebrow"><?php echo esc_html( $rf_heading ); ?></<?php echo esc_attr( $rf_tag ); ?>>

		<dl class="block-hours__rows">
			<?php foreach ( $rf_pairs as $rf_pair ) : ?>
				<div class="block-hours__row">
					<dt><?php echo esc_html( $rf_pair['days'] ); ?></dt>
					<dd><?php echo esc_html( $rf_pair['time'] ); ?></dd>
				</div>
			<?php endforeach; ?>
		</dl>

		<?php foreach ( rise_and_flour_hours_exceptions() as $rf_line ) : ?>
			<p class="block-hours__note"><?php echo esc_html( $rf_line ); ?></p>
		<?php endforeach; ?>
		<?php if ( $rf['note'] ) : ?>
			<p class="block-hours__note"><?php echo esc_html( $rf['note'] ); ?></p>
		<?php endif; ?>
		<?php rise_and_flour_open_status( 'block-hours__status' ); ?>

	<?php else : ?>

		<div class="block-hours__aside">
			<<?php echo esc_attr( $rf_tag ); ?> class="eyebrow block-hours__eyebrow"><?php echo esc_html( $rf_heading ); ?></<?php echo esc_attr( $rf_tag ); ?>>
			<?php if ( $rf['note'] ) : ?>
				<p class="block-hours__note"><?php echo esc_html( $rf['note'] ); ?></p>
			<?php endif; ?>
			<?php foreach ( rise_and_flour_hours_exceptions() as $rf_line ) : ?>
				<p class="block-hours__note"><?php echo esc_html( $rf_line ); ?></p>
			<?php endforeach; ?>
			<?php rise_and_flour_open_status( 'block-hours__status' ); ?>
		</div>

		<dl class="block-hours__columns">
			<?php foreach ( $rf_pairs as $rf_pair ) : ?>
				<div class="block-hours__col">
					<dt><?php echo esc_html( $rf_pair['days'] ); ?></dt>
					<dd><?php echo esc_html( $rf_pair['time'] ); ?></dd>
				</div>
			<?php endforeach; ?>
		</dl>

	<?php endif; ?>

	</div>
</section>

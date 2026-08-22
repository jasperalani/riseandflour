<?php
/**
 * BLOCK: Contact form.
 *
 * Contact details in a left column (email, phone, where to find us — all
 * from Site Details — plus any extra labelled notes) and the message form in
 * a dotted terracotta panel on the right. The form posts back to this page;
 * inc/contact.php validates and sends it, then redirects here with
 * ?contact=sent. The recipient is the block's field (or Site Details) and is
 * never exposed in the markup.
 *
 * Drop it on any page. One form per page is the normal case; with more, set
 * a different anchor on each so submissions find the right block.
 *
 * ACF fields: eyebrow · heading · tag · intro · recipient · show_contact ·
 *             phone_note · show_visit · extras · subjects · button_text ·
 *             reply_note · success_text
 *
 * @package Rise_&_Flour
 *
 * @var array $args Block arguments.
 */

$rf = wp_parse_args(
	$args ?? array(),
	array(
		'eyebrow'      => '',
		'heading'      => '',
		'tag'          => 'h1',
		'intro'        => '',
		'show_contact' => true,
		'phone_note'   => '',
		'show_visit'   => true,
		'extras'       => array(),
		'subjects'     => '',
		'button_text'  => '',
		'reply_note'   => '',
		'success_text' => '',
		'is_preview'   => false,
		'classes'      => '',
		'anchor'       => '',
	)
);

$rf_tag     = in_array( $rf['tag'], array( 'h1', 'h2' ), true ) ? $rf['tag'] : 'h1';
$rf_form_id = $rf['anchor'] ? $rf['anchor'] : 'contact';

$rf_email   = rise_and_flour_option( 'footer_email', '' );
$rf_phone   = rise_and_flour_option( 'footer_phone', '' );
$rf_address = rise_and_flour_option( 'footer_address', '' );
$rf_hours   = rise_and_flour_option( 'footer_hours', '' );
$rf_tel     = preg_replace( '/[^0-9+]/', '', (string) $rf_phone );

// Site Details values carry <br>s; turn them into " · " joined lines.
$rf_inline = function ( $text ) {
	$parts = preg_split( '/\s*<br\s*\/?>\s*|\r\n|\r|\n/i', (string) $text );
	return implode( ' · ', array_filter( array_map( 'trim', $parts ) ) );
};

$rf_subjects = rise_and_flour_contact_subjects( $rf['subjects'] );

// Submission state for this form, if any.
$rf_state  = function_exists( 'rise_and_flour_contact_state' ) ? rise_and_flour_contact_state() : array();
$rf_mine   = $rf_state && $rf_state['form'] === $rf_form_id;
$rf_errors = $rf_mine ? $rf_state['errors'] : array();
$rf_values = $rf_mine ? $rf_state['values'] : array();
$rf_sent   = ! $rf['is_preview'] && isset( $_GET['contact'] ) && 'sent' === $_GET['contact']; // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- a flag after redirect, nothing is acted on.

$rf_value = function ( $key ) use ( $rf_values ) {
	return isset( $rf_values[ $key ] ) ? $rf_values[ $key ] : '';
};

$rf_success = $rf['success_text'] ? $rf['success_text'] : __( 'Thanks — your message is on its way. We reply within a day.', 'rise-and-flour' );
?>

<section
	class="block-contact <?php echo esc_attr( $rf['classes'] ); ?>"
	id="<?php echo esc_attr( $rf_form_id ); ?>"
>

	<?php if ( $rf['eyebrow'] || $rf['heading'] || $rf['intro'] ) : ?>
		<header class="block-contact__head">
			<?php if ( $rf['eyebrow'] ) : ?>
				<p class="eyebrow block-contact__eyebrow"><?php echo esc_html( $rf['eyebrow'] ); ?></p>
			<?php endif; ?>
			<?php if ( $rf['heading'] ) : ?>
				<<?php echo esc_attr( $rf_tag ); ?>><?php echo esc_html( $rf['heading'] ); ?></<?php echo esc_attr( $rf_tag ); ?>>
			<?php endif; ?>
			<?php if ( $rf['intro'] ) : ?>
				<div class="block-contact__intro"><?php echo wp_kses_post( wpautop( $rf['intro'] ) ); ?></div>
			<?php endif; ?>
		</header>
	<?php endif; ?>

	<div class="block-contact__grid">

		<div class="block-contact__aside">

			<?php if ( $rf['show_contact'] && $rf_email ) : ?>
				<div class="block-contact__item">
					<p class="block-contact__label"><?php esc_html_e( 'Email', 'rise-and-flour' ); ?></p>
					<p class="block-contact__value"><a class="block-contact__accent" href="mailto:<?php echo esc_attr( $rf_email ); ?>"><?php echo esc_html( $rf_email ); ?></a></p>
				</div>
			<?php endif; ?>

			<?php if ( $rf['show_contact'] && $rf_phone ) : ?>
				<div class="block-contact__item">
					<p class="block-contact__label"><?php esc_html_e( 'Telephone', 'rise-and-flour' ); ?></p>
					<p class="block-contact__value"><a href="tel:<?php echo esc_attr( $rf_tel ); ?>"><?php echo esc_html( $rf_phone ); ?></a></p>
					<?php if ( $rf['phone_note'] ) : ?>
						<p class="block-contact__note"><?php echo esc_html( $rf['phone_note'] ); ?></p>
					<?php endif; ?>
				</div>
			<?php endif; ?>

			<?php if ( $rf['show_visit'] && ( $rf_address || $rf_hours ) ) : ?>
				<div class="block-contact__item">
					<p class="block-contact__label"><?php esc_html_e( 'In person', 'rise-and-flour' ); ?></p>
					<?php if ( $rf_address ) : ?>
						<p class="block-contact__value"><?php echo esc_html( $rf_inline( $rf_address ) ); ?></p>
					<?php endif; ?>
					<?php if ( $rf_hours ) : ?>
						<?php // One range per line, as in Site Details. ?>
						<p class="block-contact__value"><?php echo wp_kses( nl2br( trim( preg_replace( '/<br\s*\/?>\s*/i', "\n", (string) $rf_hours ) ) ), array( 'br' => array() ) ); ?></p>
					<?php endif; ?>
				</div>
			<?php endif; ?>

			<?php foreach ( $rf['extras'] as $rf_extra ) : ?>
				<?php
				if ( empty( $rf_extra['label'] ) && empty( $rf_extra['text'] ) ) {
					continue;
				}
				?>
				<div class="block-contact__item">
					<?php if ( ! empty( $rf_extra['label'] ) ) : ?>
						<p class="block-contact__label"><?php echo esc_html( $rf_extra['label'] ); ?></p>
					<?php endif; ?>
					<?php if ( ! empty( $rf_extra['text'] ) ) : ?>
						<div class="block-contact__text"><?php echo wp_kses_post( wpautop( $rf_extra['text'] ) ); ?></div>
					<?php endif; ?>
				</div>
			<?php endforeach; ?>

		</div>

		<div class="block-contact__panel">

			<?php if ( $rf_sent ) : ?>
				<div class="block-contact__sent" role="status">
					<p class="block-contact__sent-mark" aria-hidden="true">&#10003;</p>
					<p><?php echo esc_html( $rf_success ); ?></p>
				</div>
			<?php else : ?>

				<form class="block-contact__form" method="post" action="<?php echo esc_url( get_permalink() . '#' . $rf_form_id ); ?>" novalidate>
					<?php wp_nonce_field( 'rf_contact', '_rf_contact_nonce' ); ?>
					<input type="hidden" name="rf_contact" value="1">
					<input type="hidden" name="rf_contact_form" value="<?php echo esc_attr( $rf_form_id ); ?>">
					<input type="hidden" name="rf_t" value="<?php echo (int) time(); ?>">
					<?php // Honeypot: hidden from people, tempting to bots. ?>
					<div class="block-contact__hp" aria-hidden="true">
						<label for="<?php echo esc_attr( $rf_form_id ); ?>-website"><?php esc_html_e( 'Website', 'rise-and-flour' ); ?></label>
						<input type="text" id="<?php echo esc_attr( $rf_form_id ); ?>-website" name="rf_website" tabindex="-1" autocomplete="off">
					</div>

					<?php if ( ! empty( $rf_errors['form'] ) ) : ?>
						<p class="block-contact__error block-contact__error--form" role="alert"><?php echo esc_html( $rf_errors['form'] ); ?></p>
					<?php endif; ?>

					<div class="block-contact__row block-contact__row--split">
						<div class="block-contact__field<?php echo ! empty( $rf_errors['name'] ) ? ' has-error' : ''; ?>">
							<label for="<?php echo esc_attr( $rf_form_id ); ?>-name"><?php esc_html_e( 'Name', 'rise-and-flour' ); ?></label>
							<input type="text" id="<?php echo esc_attr( $rf_form_id ); ?>-name" name="rf_name" value="<?php echo esc_attr( $rf_value( 'name' ) ); ?>" placeholder="<?php esc_attr_e( 'Your name', 'rise-and-flour' ); ?>" autocomplete="name" required <?php disabled( $rf['is_preview'] ); ?>>
							<?php if ( ! empty( $rf_errors['name'] ) ) : ?>
								<p class="block-contact__error"><?php echo esc_html( $rf_errors['name'] ); ?></p>
							<?php endif; ?>
						</div>
						<div class="block-contact__field<?php echo ! empty( $rf_errors['email'] ) ? ' has-error' : ''; ?>">
							<label for="<?php echo esc_attr( $rf_form_id ); ?>-email"><?php esc_html_e( 'Email', 'rise-and-flour' ); ?></label>
							<input type="email" id="<?php echo esc_attr( $rf_form_id ); ?>-email" name="rf_email" value="<?php echo esc_attr( $rf_value( 'email' ) ); ?>" placeholder="you@example.com" autocomplete="email" required <?php disabled( $rf['is_preview'] ); ?>>
							<?php if ( ! empty( $rf_errors['email'] ) ) : ?>
								<p class="block-contact__error"><?php echo esc_html( $rf_errors['email'] ); ?></p>
							<?php endif; ?>
						</div>
					</div>

					<div class="block-contact__row">
						<div class="block-contact__field">
							<label for="<?php echo esc_attr( $rf_form_id ); ?>-subject"><?php esc_html_e( 'Subject', 'rise-and-flour' ); ?></label>
							<div class="block-contact__select">
								<select id="<?php echo esc_attr( $rf_form_id ); ?>-subject" name="rf_subject" <?php disabled( $rf['is_preview'] ); ?>>
									<?php foreach ( $rf_subjects as $rf_subject ) : ?>
										<option value="<?php echo esc_attr( $rf_subject ); ?>" <?php selected( $rf_value( 'subject' ), $rf_subject ); ?>><?php echo esc_html( $rf_subject ); ?></option>
									<?php endforeach; ?>
								</select>
							</div>
						</div>
					</div>

					<div class="block-contact__row">
						<div class="block-contact__field<?php echo ! empty( $rf_errors['message'] ) ? ' has-error' : ''; ?>">
							<label for="<?php echo esc_attr( $rf_form_id ); ?>-message"><?php esc_html_e( 'Message', 'rise-and-flour' ); ?></label>
							<textarea id="<?php echo esc_attr( $rf_form_id ); ?>-message" name="rf_message" rows="5" placeholder="<?php esc_attr_e( 'Tell us what’s on your mind…', 'rise-and-flour' ); ?>" required <?php disabled( $rf['is_preview'] ); ?>><?php echo esc_textarea( $rf_value( 'message' ) ); ?></textarea>
							<?php if ( ! empty( $rf_errors['message'] ) ) : ?>
								<p class="block-contact__error"><?php echo esc_html( $rf_errors['message'] ); ?></p>
							<?php endif; ?>
						</div>
					</div>

					<div class="block-contact__actions">
						<button class="block-contact__button" type="submit" <?php disabled( $rf['is_preview'] ); ?>><?php echo esc_html( $rf['button_text'] ? $rf['button_text'] : __( 'Send message', 'rise-and-flour' ) ); ?></button>
						<?php if ( $rf['reply_note'] ) : ?>
							<p class="block-contact__reply"><?php echo esc_html( $rf['reply_note'] ); ?></p>
						<?php endif; ?>
					</div>
				</form>

			<?php endif; ?>

		</div>

	</div>

</section>

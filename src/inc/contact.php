<?php
/**
 * Contact form handling.
 *
 * The Contact block (blocks/contact) renders a plain HTML form that posts
 * back to the page it sits on. This file picks that POST up before the page
 * renders, validates it, and sends the message with wp_mail() — which WP
 * Mail SMTP (or any other mail plugin) routes through the configured SMTP
 * account, so there's nothing plugin-specific here. On success the browser
 * is redirected back to the form with ?contact=sent (so a refresh can't
 * resend); on a validation error the page renders with the errors and the
 * typed values filled back in.
 *
 * Spam: a nonce, a honeypot field, and a minimum fill time. Bots that trip
 * the honeypot or timer get the success screen without anything being sent.
 *
 * The recipient is never taken from the POST — it comes from the block's
 * own field on the page (or Site Details), looked up here.
 *
 * @package Rise_&_Flour
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Result of the current request's submission, for the block to render.
 *
 * @param array|null $set Set the state (internal).
 * @return array {form, errors, values} or empty array.
 */
function rise_and_flour_contact_state( $set = null ) {
	static $state = array();
	if ( null !== $set ) {
		$state = $set;
	}
	return $state;
}

/**
 * The subject choices a block offers, one per line in its field.
 *
 * @param string $raw The block's subjects field.
 * @return string[]
 */
function rise_and_flour_contact_subjects( $raw ) {
	$lines = array_filter( array_map( 'trim', preg_split( '/\r\n|\r|\n/', (string) $raw ) ) );
	if ( ! $lines ) {
		$lines = array(
			__( 'A general question', 'rise-and-flour' ),
			__( 'A booking', 'rise-and-flour' ),
			__( 'Events & private hire', 'rise-and-flour' ),
			__( 'Something else', 'rise-and-flour' ),
		);
	}
	return array_values( $lines );
}

/**
 * Find the Contact block on a post, by form id.
 *
 * @param WP_Post $post    The page.
 * @param string  $form_id The form's id (block anchor, or "contact").
 * @return array|null The block's ACF data.
 */
function rise_and_flour_contact_find_block( $post, $form_id ) {
	$fallback = null;
	foreach ( parse_blocks( $post->post_content ) as $block ) {
		if ( 'acf/rf-contact' !== $block['blockName'] ) {
			continue;
		}
		$data   = isset( $block['attrs']['data'] ) && is_array( $block['attrs']['data'] ) ? $block['attrs']['data'] : array();
		$anchor = isset( $block['attrs']['anchor'] ) ? $block['attrs']['anchor'] : '';
		$id     = $anchor ? $anchor : 'contact';
		if ( $id === $form_id ) {
			return $data;
		}
		if ( null === $fallback ) {
			$fallback = $data;
		}
	}
	return $fallback;
}

/**
 * Handle a submission, if there is one.
 */
function rise_and_flour_contact_handle() {
	if ( 'POST' !== ( $_SERVER['REQUEST_METHOD'] ?? '' ) || empty( $_POST['rf_contact'] ) ) {
		return;
	}

	$post = get_queried_object();
	if ( ! $post instanceof WP_Post ) {
		return;
	}

	$form_id = isset( $_POST['rf_contact_form'] ) ? sanitize_key( wp_unslash( $_POST['rf_contact_form'] ) ) : 'contact';
	$back    = get_permalink( $post );
	$done    = add_query_arg( 'contact', 'sent', $back ) . '#' . $form_id;

	$values = array(
		'name'    => isset( $_POST['rf_name'] ) ? sanitize_text_field( wp_unslash( $_POST['rf_name'] ) ) : '',
		'email'   => isset( $_POST['rf_email'] ) ? sanitize_email( wp_unslash( $_POST['rf_email'] ) ) : '',
		'subject' => isset( $_POST['rf_subject'] ) ? sanitize_text_field( wp_unslash( $_POST['rf_subject'] ) ) : '',
		'message' => isset( $_POST['rf_message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['rf_message'] ) ) : '',
	);

	$fail = function ( $errors ) use ( $form_id, $values ) {
		rise_and_flour_contact_state(
			array(
				'form'   => $form_id,
				'errors' => $errors,
				'values' => $values,
			)
		);
	};

	// Nonce failure is almost always an expired page, not an attack.
	if ( ! isset( $_POST['_rf_contact_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['_rf_contact_nonce'] ) ), 'rf_contact' ) ) {
		$fail( array( 'form' => __( 'That took a while — the page had expired. Please send it again.', 'rise-and-flour' ) ) );
		return;
	}

	// Honeypot filled, or submitted faster than a person could type: say
	// "sent" and quietly drop it.
	$started = isset( $_POST['rf_t'] ) ? (int) $_POST['rf_t'] : 0;
	if ( ! empty( $_POST['rf_website'] ) || ( $started && ( time() - $started ) < 3 ) ) {
		wp_safe_redirect( $done );
		exit;
	}

	$block = rise_and_flour_contact_find_block( $post, $form_id );
	if ( null === $block ) {
		$fail( array( 'form' => __( 'This form isn’t set up on this page.', 'rise-and-flour' ) ) );
		return;
	}

	$subjects = rise_and_flour_contact_subjects( $block['subjects'] ?? '' );

	$errors = array();
	if ( mb_strlen( $values['name'] ) < 2 ) {
		$errors['name'] = __( 'Please tell us your name.', 'rise-and-flour' );
	}
	if ( ! is_email( $values['email'] ) ) {
		$errors['email'] = __( 'That email address doesn’t look right.', 'rise-and-flour' );
	}
	if ( ! in_array( $values['subject'], $subjects, true ) ) {
		$values['subject'] = $subjects[0];
	}
	if ( mb_strlen( $values['message'] ) < 10 ) {
		$errors['message'] = __( 'Please write a little more.', 'rise-and-flour' );
	}
	if ( $errors ) {
		$fail( $errors );
		return;
	}

	$to = ! empty( $block['recipient'] ) && is_email( $block['recipient'] ) ? $block['recipient'] : rise_and_flour_option( 'footer_email', get_option( 'admin_email' ) );

	$site    = wp_specialchars_decode( get_bloginfo( 'name' ), ENT_QUOTES );
	$subject = sprintf( '[%s] %s — %s', $site, $values['subject'], $values['name'] );

	$body = implode(
		"\n",
		array(
			sprintf( 'Name: %s', $values['name'] ),
			sprintf( 'Email: %s', $values['email'] ),
			sprintf( 'Subject: %s', $values['subject'] ),
			'',
			$values['message'],
			'',
			'—',
			sprintf( 'Sent from the contact form on %s', $back ),
		)
	);

	// No From header: the mail plugin sets the sender it's authenticated as.
	// Reply-To means "reply" in the inbox goes straight to the person.
	$headers = array(
		sprintf( 'Reply-To: %s <%s>', $values['name'], $values['email'] ),
	);

	$sent = wp_mail( $to, $subject, $body, $headers );

	if ( ! $sent ) {
		$fail( array( 'form' => __( 'Sorry — the message couldn’t be sent just now. Please try again, or email us directly.', 'rise-and-flour' ) ) );
		return;
	}

	wp_safe_redirect( $done );
	exit;
}
add_action( 'template_redirect', 'rise_and_flour_contact_handle' );

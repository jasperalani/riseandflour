<?php
/**
 * Template Name: Reservations
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package riseandflour
 */

get_header();
$fields = get_fields();
$email_sent = form_send_email('Fifield Room Reservation Enquiry', "contact@riseandflour.co.uk")

?>

<div class="container">
	<div class="row">
		<?php if($email_sent): ?>
			<div class="p-5 pl-0 col-12" style="font-size:25px; font-family: 'Roboto', sans-serif">
				Your message has been sent
			</div>
		<?php endif; ?>
	</div>
</div>

<div id="reservations">

	<div class="container">

		<div class="row">

			<div class="col-md-6 col-12">
				<h4 style="padding-left: 5px; padding-top: 10px; padding-bottom: 10px;">Room Hire Enquiry Form</h4>

				<?= do_contact_form() ?>
			</div>

			<div class="col-md-6 col-12">
				<h1><?= $fields['fifield_room_title'] ?></h1>
				<p><?= $fields['fifield_room_description'] ?></p>
				<img src="<?= $fields['fifield_room_image']['url'] ?>"/>
			</div>

		</div> <!-- end row -->

	</div> <!-- end container -->
</div>

<?php
footer_credit();
show_scroll_to_top_button();
wp_footer();

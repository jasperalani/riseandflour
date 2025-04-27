<?php

/**
 * Template Name: Location
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

?>

<div id="location">

	<div class="container">

		<div class="row">



			<div class="col-xl-6 col-12 location-text-box-one">


				<div class="location-text">
					<p>We are located at</p>
					<h3>
						The Elms, Shipton Rd, Milton-under-Wychwood
						Chipping Norton OX7 6JP
					</h3>

					<div class="d-flex" style="margin-top: 20px;"><i class="p-2 fa-solid fa-phone"></i> <a href="tel:<?= $fields['phone_number'] ?? '' ?>"><?= $fields['phone_number'] ?? '' ?></a></div>
					<div class="d-flex"><i class="p-2 fa-solid fa-envelope"></i> <a href="mailto:<?= $fields['email'] ?? '' ?>"><?= $fields['email'] ?? '' ?></a></div>


				</div>

			</div>

			<div class="col-xl-6 col-12">
				<iframe
                        style="width: 100%; border: 0;"
                        width="600"
                        height="450"
                        allowfullscreen=""
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2463.939652100995!2d-1.6159506227091818!3d51.8620582718962!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x48712de6bed7d1d9%3A0xc7113d01b2517f9!2sRise%20and%20Flour%20Artisan%20Bakery%20%26%20Cafe!5e0!3m2!1sen!2suk!4v1689787622934!5m2!1sen!2suk"
                ></iframe>
			</div>

		</div>

	</div> <!-- end container -->
</div>

<?php
footer_credit();
show_scroll_to_top_button();
wp_footer();

<?php
/**
 * Template Name: Home
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

$fields = get_fields() ?? [];
// var_dump($fields);

?>

<div class="container">

	<div class="row">



		<main id="primary" class="site-main">

			<div class="hero-image">
				<img src='<?= $fields['hero_image']['url'] ?>'/>
			</div>

			<div class="location-banner">

				<div class="d-flex justify-content-center">
					<div class="text-center">
						<?= wpautop($fields['location_text']) ?>
					</div>
				</div>

			</div>

		</main><!-- #main -->

        <div class="opening-times col-12">
            <div class="sub">
                <!--                <h1><b>Opening Times</b></h1>-->
                <p>Monday - Friday <b>8:30 am – 3 pm</b></p>
                <p>Saturday <b>9 am – 3 pm</b></p>
                <p>Sunday <b>9 am – 2:30 pm</b></p>
            </div>

        </div>

		<div class="reservation-banner col-12" style="margin-top: 50px;">

			<div class="d-flex justify-content-center">
				<div class="text-center text">
					<h2><?= ($fields['reservation_banner_title_text']) ?></h2>
					<div class="reservation-description">
						<?= wpautop($fields['reservation_banner_description_text']) ?>
					</div>
					<div class="to-reservation-page d-flex justify-content-center">
						<p>
							<a style="text-decoration: none; color: white" href='/fifield-room'>
								Click here to book
							</a>
						</p>
					</div>
				</div>
			</div>

		</div>
	</div> <!-- end row -->

</div> <!-- end container -->





<div class="container">

	<div class="row page-content">


		<div class="feature-one">
			<div class="feature-text col-lg-6 col-md-6 col-sm-6 col-12">
				<h2>
					<?= $fields['feature_one_title'] ?>
				</h2>
				<p>
					<?= $fields['feature_one_description'] ?>
				</p>
			</div>
			<div class="feature-image col-lg-6 col-md-6 col-sm-6 col-12">
				<img src='<?= $fields['feature_one_image']['url'] ?>'/>
			</div>
		</div>

		<div class="feature-two">
			<div class="feature-image">
				<img src='<?= $fields['feature_two_image']['url'] ?>'/>
			</div>
			<div class="feature-text">
				<h2>
					<?= $fields['feature_two_title'] ?>
				</h2>
				<p>
					<?= $fields['feature_two_description'] ?>
				</p>
			</div>
		</div>

		<div class="feature-three">
			<div class="feature-text col-lg-6 col-md-6 col-sm-6 col-12">
				<h2>
					<?= $fields['feature_three_title'] ?>
				</h2>
				<p>
					<?= $fields['feature_three_description'] ?>
				</p>
			</div>
			<div class="feature-image col-lg-6 col-md-6 col-sm-6 col-12">
				<img src='<?= $fields['feature_three_image']['url'] ?>'/>
			</div>
		</div>

	</div>

	<div class="footer-image d-flex justify-content-center" style="background: url(<?= $fields['homepage_footer_banner_image']['url'] ?>); background-position: center; background-repeat: no-repeat; background-size: cover;">
		<div class="footer-banner-image-text d-flex justify-content-center flex-column">
		</div>
	</div>


</div>

<?php
// get_sidebar();
get_footer();

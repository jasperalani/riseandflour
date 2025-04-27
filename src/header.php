<?php

/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package riseandflour
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo('charset'); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="https://gmpg.org/xfn/11">

	<?php wp_head(); ?>

	<!-- Google tag (gtag.js) -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=G-2D646WJ4M6"></script>
	<script>
		window.dataLayer = window.dataLayer || [];
		function gtag(){dataLayer.push(arguments);}
		gtag('js', new Date());

		gtag('config', 'G-2D646WJ4M6');
	</script>

	<!-- Font Awesome -->
	<script src="https://kit.fontawesome.com/21abf60320.js" crossorigin="anonymous"></script>

	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">

	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>

	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400&display=swap" rel="stylesheet">

	<script src="https://code.jquery.com/jquery-3.7.0.slim.min.js" integrity="sha256-tG5mcZUtJsZvyKAxYLVXrmjKBVLd6VpVccqz/r4ypFE=" crossorigin="anonymous"></script>

</head>

<body <?php body_class(); ?>>
	<?php wp_body_open(); ?>
	<div id="page" class="site">
		<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e('Skip to content', 'riseandflour'); ?></a>


		<?php

		$fields = get_fields();

		?>

		<header id="masthead" class="site-header">


			<div class="container">

				<div class="row">

					<div class="col-12">
						<div id="site-name" class="d-flex justify-content-center" >Rise & Flour Cafe</div>
					</div>

					<div class="col-lg-2 col-md-12 site-branding">
						<a href="<?php echo esc_url(home_url('/')); ?>" rel="home">

							<img id="main-logo" src="https://riseandflour.co.uk/wp-content/uploads/2023/07/Rise-and-flour-CAFE-logo-HD.jpg"/>

						</a>

					</div><!-- .site-branding -->

					<div class="col-xl-8 col-lg-9">

						<div class="site-menus">
							<?php
							wp_nav_menu(
								array(
									'theme_location' => 'menu-1',
									'menu_id' => 'primary-menu',
								)
							);
							wp_nav_menu(
								array(
									'theme_location' => 'menu-2',
									'menu_id' => 'secondary-menu',
								)
							);
							?>
						</div>
					</div>

					<div id="social-icons" class="col-xl-2 col-lg-12"> 

						<div class="d-flex">

							<a target="_blank" href="https://www.instagram.com/rise.and.flour/?hl=en">
								<i id='instagram-icon' class="fa-brands fa-instagram"></i>
							</a>

							<a target="_blank" href="https://www.facebook.com/p/Rise-Flour-Artisan-Sourdough-Bakery-Cafe-100082989786663/">
								<i id='facebook-icon' class="fa-brands fa-facebook"></i>
							</a>

						</div>

					</div>





				</div>
			</div>

		</header><!-- #masthead -->
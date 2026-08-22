<?php
/**
 * Site header.
 *
 * Renders one of two nav variants:
 *
 *   --overlay : front page only. Absolutely positioned, so it sits over the
 *               full-page hero that follows it. Transparent, cream text, no
 *               wordmark — the hero's centred stamp is the logo.
 *   --stacked : every other page. Centred wordmark above the nav, ink text,
 *               hairline rule beneath.
 *
 * @package Rise_&_Flour
 */

$rise_and_flour_is_home  = is_front_page();
$rise_and_flour_variant  = $rise_and_flour_is_home ? 'site-header--overlay' : 'site-header--stacked';
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'rise-and-flour' ); ?></a>

<header id="masthead" class="site-header <?php echo esc_attr( $rise_and_flour_variant ); ?>">

	<?php if ( ! $rise_and_flour_is_home ) : ?>
		<a class="site-header__wordmark" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
			<?php
			if ( has_custom_logo() ) {
				$rise_and_flour_logo_id = get_theme_mod( 'custom_logo' );
				echo wp_get_attachment_image( $rise_and_flour_logo_id, 'full', false, array( 'alt' => get_bloginfo( 'name' ) ) );
			} else {
				printf(
					'<img src="%1$s" alt="%2$s">',
					esc_url( get_template_directory_uri() . '/assets/img/wordmark.png' ),
					esc_attr( get_bloginfo( 'name' ) )
				);
			}
			?>
		</a>
	<?php endif; ?>

	<button class="nav-toggle" type="button" aria-label="<?php esc_attr_e( 'Menu', 'rise-and-flour' ); ?>" aria-expanded="false" aria-controls="primary-nav">
		<span class="nav-toggle-bar"></span>
		<span class="nav-toggle-bar"></span>
		<span class="nav-toggle-bar"></span>
	</button>

	<nav class="site-nav" id="primary-nav" aria-label="<?php esc_attr_e( 'Primary', 'rise-and-flour' ); ?>">
		<?php rise_and_flour_nav(); ?>
	</nav>

</header><!-- #masthead -->

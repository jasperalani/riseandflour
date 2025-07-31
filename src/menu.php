<?php
/**
 * Template Name: Menu
 * The template for displaying the image-based cafe menu
 *
 * @package riseandflour
 */

get_header();

$fields = get_fields();

$menu_image = $fields['menu'] ?? null;
?>

    <div id="menu">
        <div class="container">
            <div class="row justify-content-center">
                <?php if ($menu_image): ?>
                    <div class="col-12">
                        <img
                                src="<?php echo esc_url($menu_image['url']); ?>"
                                alt="<?php echo esc_attr($menu_image['alt']); ?>"
                                class="img-fluid w-100"
                                style="max-width: 100%; height: auto;"
                        />
                    </div>
                <?php else: ?>
                    <div class="col-12">
                        <p class="text-center text-muted">Menu image not available.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

<?php
footer_credit();
show_scroll_to_top_button();
wp_footer();

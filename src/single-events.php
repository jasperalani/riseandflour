<?php

// Page for specific event

get_header();

?>

<div id="single-event" class="container">
    <?php
    if (have_posts()):
        while (have_posts()):
            ?>
            <?php
            the_post();

            $title = get_the_title() ?? 'Event title';
            $date_time = get_field('date_time') ?? '';
            $content = get_the_content() ?? '';
            $image = get_the_post_thumbnail_url() ?? '';

            $custom_height_toggle = get_field('image_height');
            $image_height = 'auto';
            if ($custom_height_toggle) {
                $image_height = get_field('image_height_px') . 'px';
            }

            if ($title) {
                echo "<h2>{$title}</h2>";
            }

            if ($date_time) {
                echo "<p class='date-time'>Date: {$date_time}</p>";
            }

            if ($content) {
                echo "<p>{$title}</p>";
            }

            if ($image) {
                ?>
                <img style="height: <?= $image_height ?>" src="<?= $image ?>" alt="Event image"/>
                <?php
            }
        endwhile;
    else:
        echo '<h3 style="text-align: center;">No content found.</h3>';
    endif;
    ?>
</div>

<?php

get_footer();
?>
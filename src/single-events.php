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
            $tz = new DateTimeZone('Europe/London'); // or 'UTC', etc.
            $givenDate = new DateTime($date_time, $tz);
            $now = new DateTime('now', $tz);
            $datePassedText = "";

            if($givenDate < $now){
                $datePassedText = "EVENT PASSED";
            }
            $content = get_the_content() ?? '';
            $thumbnail_image = get_the_post_thumbnail_url() ?? '';
            $gallery = get_field('images') ?? [];

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

            if($datePassedText !== ""){
                echo "<br>";
                echo "<b style='font-size: 2rem'>" . $datePassedText . "</b>";
                echo "<br>";
            }

            if ($content) {
                echo "<p>{$content}</p>";
            }

            if (!empty($gallery)) {
                foreach ($gallery as $img) {
                    ?>
                    <img style="height: <?= $image_height ?>" src="<?= $img ?>" alt="Event image"/>
                    <?php
                }
            } elseif (!empty($thumbnail_image)) {
                ?>
                <img style="height: <?= $image_height ?>" src="<?= $thumbnail_image ?>" alt="Event image"/>
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
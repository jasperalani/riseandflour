<?php

// Page for all events

get_header();

?>

<div id="events-list" class="container">

    <?php

    $args = array(
        'post_type' => 'events',
        'posts_per_page' => -1,  // Get all events
        'post_status' => 'publish',
    );
    $events_query = new WP_Query($args);

    if ($events_query->have_posts()):
        ?>
        <h2>Events</h2>
        <ul> <?php
        while ($events_query->have_posts()):
            $events_query->the_post();
            ?> <li> <?php
            $url = get_permalink();
            $title = get_the_title();
            $date_time = get_field('date_time');
            ?>
            <a href="<?= $url ?>">
                <div class="event-content">
                    <img src="<?= get_the_post_thumbnail_url() ?>" alt="Event image"/>
                    <h4 class="title">
                        <?= $title ?> <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chevron-right" viewBox="0 0 16 16">
  <path fill-rule="evenodd" d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708"/>
</svg>
                    </h4>
                    <p class="content">
                        <?= $date_time ?>
                    </p>
                </div>
            </a>
            <?php
            ?> </li> <?php
        endwhile;
        ?> </ul> <?php
        wp_reset_postdata();
    else:
        echo '<h3 style="text-align: center;">No events found.</h3>';
    endif;

    ?>

</div>

<?php

get_footer();
?>
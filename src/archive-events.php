<?php

// Page for all events

get_header();

?>

<div id="events-list" class="container">


    <?php


    $events = [];
    $past_events = [];

    $args = array(
        'post_type' => 'events',
        'posts_per_page' => -1,  // Get all events
        'post_status' => 'publish',
    );
    $events_query = new WP_Query($args);

    if ($events_query->have_posts()):
        while ($events_query->have_posts()):
            $events_query->the_post();
            $url = get_permalink();
            $title = get_the_title();
            $image = get_the_post_thumbnail_url();
            $date_time = get_field('date_time');

            $tz = new DateTimeZone('Europe/London'); // or 'UTC', etc.
            $givenDate = new DateTime($date_time, $tz);
            $now = new DateTime('now', $tz);
            $datePassedText = "";

            if($givenDate < $now){
                $past_events[] = [
                    'url' => $url,
                    'title' => $title,
                    'image' => $image,
                    'date_time' => $date_time,
                    'date_passed' => "This event has passed",
                ];
            }else{
                $events[] = [
                    'url' => $url,
                    'title' => $title,
                    'image' => $image,
                    'date_time' => $date_time,
                    'date_passed' => "",
                ];
            }
        endwhile;
        wp_reset_postdata();
    else:
        echo '<h3 style="text-align: center;">No events found.</h3>';
    endif;

    ?>

    <section class="events-section">

        <!-- Header -->
        <div class="events-header">
            <h2 class="events-title">Events</h2>
        </div>

        <!-- ── Upcoming events ─────────────────────────────── -->


            <?php

            if(sizeof($events) > 0){
                foreach($events as $event){
                    ?>
        <div class="events-grid">

                    <article class="event-card event-card--upcoming">
                        <!-- Replace src with your actual image path -->
                        <img
                                class="event-card__img"
                                src="<?= $event['image'] ?>"
                                alt="<?= $event['title'] ?>"
                                onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                        />
                        <!-- Fallback placeholder (hidden when image loads) -->
                        <div class="event-card__img-placeholder" style="display:none;">
                            <svg width="40" height="40" viewBox="0 0 40 40" fill="none" aria-hidden="true">
                                <rect x="4" y="4" width="32" height="32" rx="3" fill="#c8b89a"/>
                                <path d="M10 28l8-10 6 7 4-5 6 8H10z" fill="#f5f2e8"/>
                            </svg>
                        </div>
                        <div class="event-card__body">
                            <span class="event-card__date"><?= $event['date_time'] ?></span>
                            <p class="event-card__title"><?= $event['title'] ?></p>
                            <div class="event-card__meta">
                                <span class="badge badge--upcoming">Upcoming</span>
                                <a class="event-card__btn" href="<?= $event['url'] ?>">View event</a>
                            </div>
                        </div>
                    </article>

        </div><!-- /.events-grid (upcoming) -->

            <?php
                }
            }else{
                ?>
                <h2 class="events-no-events-found">There are no events upcoming, check back here soon!</h2>
                    <?php
            }

            ?>



        <!-- ── Past events ─────────────────────────────────── -->
        <div class="past-events">
            <p class="past-events__label">Past events</p>
            <div class="events-grid">

                <?php
                if(sizeof($past_events) > 0){
                    foreach($past_events as $event){
                        ?>

                        <article class="event-card">
                            <img
                                    class="event-card__img"
                                    src="<?= $event['image'] ?>"
                                    alt="<?= $event['title'] ?>"
                                    onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                            />
                            <div class="event-card__img-placeholder" style="display:none; background:#8b1a1a;">
                                <svg width="48" height="48" viewBox="0 0 48 48" aria-hidden="true">
                                    <circle cx="24" cy="24" r="18" fill="#c0392b" opacity="0.8"/>
                                    <text x="24" y="28" text-anchor="middle" font-size="10" fill="#f5e6e6" font-family="Georgia, serif">Yard</text>
                                </svg>
                            </div>
                            <div class="event-card__body">
                                <span class="event-card__date"><?= $event['date_time'] ?></span>
                                <p class="event-card__title"><?= $event['title'] ?></p>
                                <div class="event-card__meta">
                                    <span class="badge badge--passed">Passed</span>
                                    <a class="event-card__btn" href="<?= $event['url'] ?>">View event</a>
                                </div>
                            </div>
                        </article>

                <?php
                    }
                }
                ?>

            </div><!-- /.events-grid (past) -->
        </div><!-- /.past-events -->

    </section>

</div>

<?php

get_footer();
?>
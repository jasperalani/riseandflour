<?php
/*
 * Template Name: Careers
 */

get_header();
$email_sent = form_send_email("Message from contact form: careers", "contact@riseandflour.co.uk")

?>


    <div class="container">

        <div class="row">

            <?php if($email_sent): ?>
                <div class="p-5 pl-0 col-12" style="font-size:25px; font-family: 'Roboto', sans-serif">
                    Your message has been sent
                </div>
            <?php endif; ?>

            <div class="col-md-2 col-1"></div>
            <div class="col-md-8 col-10">

                <div id="careers-text-content">

                    <p>Can’t get enough of Rise and Flour?</p>

                    <p>Come join the team!</p>

                    <p>Are you passionate about serving delicious food and drinks? Are you a hard-working team player who's dedicated, ever-evolving, and devoted to staying ahead? Oui, oui? So are we!</p>

                    <p>If you have a love for all things culinary and want to help us create more Rise and Flour magic, we may have the perfect position for you. On one condition… you must love cookies!</p>

                    <p>We're always looking for great people to join our growing team—apply today by emailing us at contact@riseandflour.co.uk</p>

                    <p class="pt-20">Positions available:</p>

                    <ul>
                        <li><p>FOH Staff, weekend daytime</p></li>
                        <li><p>Barista, part-time</p></li>
                    </ul>

<!--                    --><?php //= do_contact_form() ?>
                </div>

            </div>
            <div class="col-md-2 col-1"></div>

        </div>

    </div>

<?php
footer_credit();
show_scroll_to_top_button();
wp_footer();
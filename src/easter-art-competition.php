<?php

/**
 * Template Name: Easter Art Competition
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

?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <style>



    </style>

<div class="eac_landing container">
    <div class="row">
        <div class="col-lg-6 col-md-12">
            <h1>Calling all young artists!</h1>

            <p>
                🖌️ Get ready to celebrate the Easter holidays with our exciting Art Competition! Whether you love drawing, painting, photography, or sculpting, this is your chance to shine!
            </p>

            <p>
                For ages 5-11, the theme is Easter! Let your imagination run wild and create your masterpiece. Max size A4. The winners will be rewarded with delicious Easter treats!
            </p>

            <p>
                For ages 12-20, the theme is Food! Show off your culinary creativity on an A4 canvas. Prizes for the top three winners include £50, £30, and £20 Amazon vouchers respectively. Winners will have their artwork proudly displayed in the cafe!
            </p>

            <p class="last">
                <b>Submit your artwork to the cafe by Sunday, April 7th, to be considered.</b> Don’t miss out on this fantastic opportunity to showcase your talent and win amazing prizes! Let’s make this Easter holiday an artistic extravaganza! 🐰🎨
            </p>
        </div>
        <div class="col-lg-6 col-md-12">
            <img class="eac-image" alt="A drawing of bread that resembles deer and butterflies" src="https://riseandflour.co.uk/wp-content/uploads/2024/03/eac-image.jpg"/>
        </div>
        <div class="col-lg-6 col-md-12">
            <figure>
                <figcaption>Thanks to Millie Joerin for providing this awesome art!</figcaption>
                <img class="eac-image" alt="A drawing of a mars bar" src="https://riseandflour.co.uk/wp-content/uploads/2024/03/easter-mars-bar.jpg"/>
            </figure>
        </div>
    </div>
</div>

<?php

footer_credit();
wp_footer();
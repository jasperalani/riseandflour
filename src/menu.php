<?php
/**
 * Template Name: Menu
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

$fields = get_fields();

?>

    <div id="menu">

        <div class="container">

            <div class="row">

<!--                <h1 class="col-12 text-center">Menu</h1>-->

                <h4>SAMPLE MENU. <i>served all day</i></h4>

                <div class="menu-item col-sm-6 col-12">
                    <div class="d-flex justify-content-between">
                        <p class="item-title">SOURDOUGH TOAST (V) *</p>
                        <p class="item-price">£4.50</p>
                    </div>

                    <p class="item-desc">2 slices of rise & flour sourdough toast served w/ butter & preserves</p>
                </div>

                <div class="menu-item col-sm-6 col-12">
                    <div class="d-flex justify-content-between">
                        <p class="item-title">THE BAP</p>
                        <p class="item-price">£7.00</p>
                    </div>

                    <p class="item-desc">Cumberland sausage or smoked bacon in a toasted brioche w/ house made tomato
                        sauce - add an egg 1.50</p>
                </div>

                <div class="menu-item col-sm-6 col-12">
                    <div class="d-flex justify-content-between">
                        <p class="item-title">The Grande bap</p>
                        <p class="item-price">£10.50</p>
                    </div>

                    <p class="item-desc">Cumberland sausage, smoked bacon, crispy hashbrown & a Cacklebean fried egg in
                        a toasted brioche bun w/ house made tomato sauce</p>
                </div>

                <div class="menu-item col-sm-6 col-12">
                    <div class="d-flex justify-content-between">
                        <p class="item-title">Eggs & Soldiers (V) *</p>
                        <p class="item-price">£7.50</p>
                    </div>

                    <p class="item-desc">Cacklebean boiled eggs w/ sourdough soldiers - add marmite 25p</p>
                </div>

                <div class="menu-item col-sm-6 col-12">
                    <div class="d-flex justify-content-between">
                        <p class="item-title">Granola Bowl (V, N) *</p>
                        <p class="item-price">£8.00</p>
                    </div>

                    <p class="item-desc">Greek yoghurt topped with house made granola, berry compote, fresh berries &
                        local honey</p>
                </div>

                <div class="menu-item col-sm-6 col-12">
                    <div class="d-flex justify-content-between">
                        <p class="item-title">The French Stack (V) *</p>
                        <p class="item-price">£12.50</p>
                    </div>

                    <p class="item-desc">Brioche French toast stack topped with berry compote, fresh fruit, ginger
                        crunch, maple syrup, whipped cream</p>
                </div>

                <div class="menu-item col-sm-6 col-12">
                    <div class="d-flex justify-content-between">
                        <p class="item-title">Smashed Avocado (V) *</p>
                        <p class="item-price">£13.00</p>
                    </div>

                    <p class="item-desc">Toasted sourdough, avocado, feta, 2 Cacklebean poached eggs, basil, pea &
                        cashew pesto - add bacon 2.50</p>
                </div>

                <div class="menu-item col-sm-6 col-12">
                    <div class="d-flex justify-content-between">
                        <p class="item-title">All day breakfast *</p>
                        <p class="item-price">£12.50</p>
                    </div>

                    <p class="item-desc">2 fried eggs, 1 Cumberland sausage, 2 rashers smoked bacon, roasted mushrooms, crispy hash brown, buttered toasted sourdough</p>
                </div>
                <div class="menu-item col-sm-6 col-12">
                    <div class="d-flex justify-content-between">
                        <p class="item-title">Veg All day breakfast (V) *</p>
                        <p class="item-price">£12.50</p>
                    </div>

                    <p class="item-desc">2 fried eggs, roasted tomato & mushrooms, crispy hash brown, buttered toasted sourdough</p>
                </div>

                <div class="menu-item col-sm-6 col-12">
                    <div class="d-flex justify-content-between">
                        <p class="item-title">The Bagel (V)</p>
                        <p class="item-price">£10.00</p>
                    </div>

                    <p class="item-desc">Toasted bagel with smashed avocado, grilled Halloumi, chilli jam served with crisps - add crispy bacon 2.50</p>
                </div>

                <div class="menu-item col-sm-6 col-12">
                    <div class="d-flex justify-content-between">
                        <p class="item-title">Sourdough sandwiches</p>
                        <p class="item-price">£10.00</p>
                    </div>

                    <p class="item-desc">- Mortadella, sundried tomato mayo, fresh mozzarella, rocket leaves served with crisps</p>
                    <p class="item-desc">- Avocado, sundried tomato mayo, fresh mozzarella, rocket leaves served with crisps (V)</p>
                </div>

                <div class="menu-item col-sm-6 col-12">
                    <div class="d-flex justify-content-between">
                        <p class="item-title">Quiche of the day (V)</p>
                        <p class="item-price">£9.00</p>
                    </div>

                    <p class="item-desc">served warm with house salad</p>
                </div>

                <div class="menu-item col-sm-6 col-12">
                    <div class="d-flex justify-content-between">
                        <p class="item-title">Home-made pork sausage Roll</p>
                        <p class="item-price">£7.00</p>
                    </div>

                    <p class="item-desc">served with house salad & tomato sauce</p>
                </div>

                <div class="menu-item col-sm-6 col-12">
                    <div class="d-flex justify-content-between">
                        <p class="item-title">* Kids Portions Available</p>
                    </div>
                </div>


            </div> <!-- end row -->

            <div class="row">

                <div class="menu-item menu-item-addons col-sm-6 col-12">
                    <p class="item-desc"><b>Allergies & Intolerance's</b></p>
                    <p class="item-desc">We prepare all our food in the Bakery kitchen, using shared equipment for
                        different dishes. We are therefore unable to guarantee any of the dishes are 100% allergen free.
                        If you have a food allergy or intolerance, please speak to a member of the team before placing
                        your order.</p>
                    <br>
                    <p class="item-desc">Gluten free bread available on request</p>
                    <p class="item-desc"><b>(V) - Vegetarian</b></p>
                    <p class="item-desc"><b>(VG) - Vegan (Dairy free butter available upon request)</b></p>
                    <p class="item-desc"><b>(N) - Contains nuts</b></p>
                </div>

                <div class="menu-item col-sm-6 col-12">
                    <figure>
<!--                        <figcaption>Granola Bowl</figcaption>-->
                        <img class="menu-feature-image" alt="A delicious looking yogurt and granola bowl" src="https://riseandflour.co.uk/wp-content/uploads/2024/03/menu-granola.jpg"/>
                    </figure>
                </div>

            </div> <!-- end row -->

<!--            <div class="row">-->
<!---->
<!--                <h4>For Kids. <i>served all day</i></h4>-->
<!---->
<!--                <div class="menu-item col-sm-6 col-12">-->
<!--                    <div class="d-flex justify-content-between">-->
<!--                        <p class="item-title">Sourdough Toast (V)</p>-->
<!--                        <p class="item-price">£3.00</p>-->
<!--                    </div>-->
<!---->
<!--                    <p class="item-desc">One slice of sourdough toast w/ preserves</p>-->
<!--                </div>-->
<!---->
<!--                <div class="menu-item col-sm-6 col-12">-->
<!--                    <div class="d-flex justify-content-between">-->
<!--                        <p class="item-title">Eggs & Soldiers (V)</p>-->
<!--                        <p class="item-price">£4.50</p>-->
<!--                    </div>-->
<!---->
<!--                    <p class="item-desc">One Cacklebean boiled egg w/ sourdough soldiers - add marmite £4.75</p>-->
<!--                </div>-->
<!---->
<!--                <div class="menu-item col-sm-6 col-12">-->
<!--                    <div class="d-flex justify-content-between">-->
<!--                        <p class="item-title">Smashed Avocado (V)</p>-->
<!--                        <p class="item-price">£5.50</p>-->
<!--                    </div>-->
<!---->
<!--                    <p class="item-desc">Sourdough toast w/ avocado - add poached egg £7.00</p>-->
<!--                </div>-->
<!---->
<!--                <div class="menu-item col-sm-6 col-12">-->
<!--                    <div class="d-flex justify-content-between">-->
<!--                        <p class="item-title">PB & J Bagel (N)</p>-->
<!--                        <p class="item-price">£4.00</p>-->
<!--                    </div>-->
<!---->
<!--                    <p class="item-desc">Peanut Butter & Jam on a toasted sesame bagel</p>-->
<!--                </div>-->
<!---->
<!--            </div>

            <!-- TODO: add drinks -->

        </div> <!-- end container -->
    </div>

<?php
footer_credit();
show_scroll_to_top_button();
wp_footer();

<?php
/**
 * Template Name: Our Food
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

<div id="ourfood">

	<div class="container">

		<div class="row">

			<?php 
			global $post;
			$content_array = explode('/>', get_post_field('post_content', $post->ID));

			$built_array = [];
			if(!empty($content_array)){
				foreach($content_array as $image){
					if(empty($image)){
						break;
					}

                    $image = str_replace("&nbsp;", '', $image);

                    $build = '<div class="img-element col-lg-4 col-md-6 col-12">';
                    $build .= $image . '/>';
                    $build .= '</div>';

                    $string = htmlentities($build, null, 'utf-8');
                    $content = str_replace("&nbsp;", "", $string);
                    $build = html_entity_decode($content);

                    $built_array[] = $build;
				}
			}

			shuffle($built_array);



			if(!empty($built_array)){
			    foreach($built_array as $image){
			        echo $image;
                }
            }

			?>

		</div> <!-- end row -->

	</div> <!-- end container -->
</div>

<?php
footer_credit();
show_scroll_to_top_button();
wp_footer();

<?php

namespace CoursesPlanner;

/* 
*   index_shortcode() function is used to create a new shortcode.
*
*   More info: https://codex.wordpress.org/Function_Reference/add_shortcode
*/

if (!defined('ABSPATH')){
    die;
}

function index_shortcode($atts)
{
    $atts = array_change_key_case((array) $atts, CASE_LOWER);

    $index_atts = shortcode_atts([
        'category' => '',
        'h2'       => false,
        'link'     => false
    ], $atts);

    $term = term_exists($index_atts['category'], 'course');

    if ($index_atts['category'] === '') {
        return 'Category was not chosen';
    } else if (0 !== $term && null !== $term) {

        $args = array(
            'post_type' => 'lessons',
            'tax_query' => array(
                array(
                    'taxonomy' => 'course',
                    'field' => 'Courses',
                    'terms' => $index_atts['category']
                )
            )
        );


        $search_query = new \WP_Query($args);

        if ($search_query->have_posts()) {
            $index_code = '
            <div id="toc_container">
            <p class="toc_title">Table Of Contents</p>
            <ul class="toc_list">
            ';
            while ($search_query->have_posts()) {
                $search_query->the_post();
                $index_code .= '<li class="index_title"> &#8226; ';
                if ($index_atts['link'] == false) {
                    $index_code .= get_the_title();
                } else if ($index_atts['link'] == true) {
                    $index_code .= '<a Target= "_blank" class="index_link" href="' . get_the_permalink(get_the_ID()) . '">' . get_the_title() . '</a>';
                }
                $index_code .= '</li>';
                if ($index_atts['h2'] == true) {
                    $dom = new domDocument;
                    $dom->loadHTML(get_the_content());

                    $headings = $dom->getElementsByTagName('h2');

                    foreach ($headings as $h) {
                        $index_code .= '<li class="index_subtitle"> &#8226; ' . $h->textContent . '</li>';
                    }
                }
            }
            $index_code .= '
            </ul>
            </div>
            ';
        } else {
            return 'No post found';
        }

        wp_reset_postdata();

        return $index_code;
    } else {
        return 'Category was not chosen';
    }
}
add_shortcode('indexShortcode', __NAMESPACE__ . '\\index_shortcode');

/* 
*   courses_shortcode() function is used to create a new shortcode.
*
*   More info: https://codex.wordpress.org/Function_Reference/add_shortcode
*/

function courses_shortcode($atts)
{

    $courses_atts = shortcode_atts([
        'image' => false
    ], $atts);

    $terms = get_terms([
        'taxonomy' => 'course',
        'hide_empty' => true,
    ]);


    $courses_style = ' 
    <div id="course-container">
    ';
    foreach ($terms as $term) {
        $courses_style .= '
        <div class="card-container">
        ';
        if ($courses_atts['image'] == true) {
            $courses_style .= '
            <img class="thumbnail-image" src="
            ';

            $image_id = get_term_meta($term->term_id, 'featured-image-id', true);
            $image_src = wp_get_attachment_image_src($image_id);

            $courses_style .= $image_src[0] . ' " alt="thumbnail"/>';
        }

        $courses_style .= '
        <div class="contents-container">
        <span class="secondary-text" >Lessons: ' . $term->count . '</span>
        <h2 class="card-title">' . $term->name . '</h2>
        <span class="secondary-text">
        <a class="link-course" target="_blank" href="' . network_site_url('/') . '?course=' . $term->slug . '">Read More</a>
        </span>
        </div>
        <div class="color-hover"></div>
        </div>
        ';
    }
    $courses_style .= '
            </div>';
    return $courses_style;
}
add_shortcode('CoursesShortcode', __NAMESPACE__ . '\\courses_shortcode');

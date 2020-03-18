<?php

namespace CoursesPlanner;

if (!defined('ABSPATH')){
    die;
}

/*
*   register_lessons_courses() function is used to register Custom Post Type(lessons) and Custom Taxonomy(course).
*/

function register_lessons_courses()
{
    /*
    *   Labels for Custom Post Type(lessons). 
    */

    $labels = array(
        'name'               => _x('Lessons', 'post type general name', 'courses-planner'),
        'singular_name'      => _x('Lesson', 'post type singular name', 'courses-planner'),
        'menu_name'          => _x('Courses Settings', 'admin menu', 'courses-planner'),
        'name_admin_bar'     => _x('Lesson', 'add new on admin bar', 'courses-planner'),
        'add_new'            => _x('Add New', 'book', 'courses-planner'),
        'add_new_item'       => __('Add New Lesson', 'courses-planner'),
        'new_item'           => __('New Lesson', 'courses-planner'),
        'edit_item'          => __('Edit Lesson', 'courses-planner'),
        'view_item'          => __('View Lesson', 'courses-planner'),
        'all_items'          => __('All Lessons', 'courses-planner'),
        'search_items'       => __('Search Lessons', 'courses-planner'),
        'parent_item_colon'  => __('Parent Lessons:', 'courses-planner'),
        'not_found'          => __('No Lessons found.', 'courses-planner'),
        'not_found_in_trash' => __('No Lessons found in Trash.', 'courses-planner')
    );

    /* 
    *   Arguments for Custom Post Type(lessons).
    */

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'menu_icon'          => 'dashicons-welcome-learn-more',
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'supports'           => array('title', 'editor', 'author', 'thumbnail', 'comments', 'page-attributes'),
        'rewrite'            => false,
        'hierarchical'       => true,
        'taxonomies'         => array('course')
    );

    /* 
    *   register_post_type( string $post_type, array|string $args = array() )
    *   Post types can support any number of built-in core features such as meta boxes, 
    *   custom fields, post thumbnails, post statuses, comments, and more. See the 
    *   $supports argument for a complete list of supported features.
    *
    *   More info : https://codex.wordpress.org/Function_Reference/register_post_type
    */

    register_post_type('lessons', $args);

    /*
    *   Labels for Custom Taxonomy(course). 
    */

    $labels = array(
        'name'              => _x('Courses', 'taxonomy general name'),
        'singular_name'     => _x('Course', 'taxonomy singular name'),
        'search_items'      => __('Search Courses'),
        'all_items'         => __('All Courses'),
        'parent_item'       => __('Parent Course'),
        'parent_item_colon' => __('Parent Course:'),
        'edit_item'         => __('Edit Course'),
        'update_item'       => __('Update Course'),
        'add_new_item'      => __('Add New Course'),
        'new_item_name'     => __('New Course Name'),
        'menu_name'         => __('Courses'),
    );

    /* 
    *   Arguments for Custom Taxonomy(course).
    */

    $args = array(
        'label'             => 'course',
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_in_menu'      => true,
        'rewrite'           => false,
    );

    /* 
    *   register_taxonomy( string $taxonomy, array|string $object_type, array|string $args = array() )
    *   A simple function for creating or modifying a taxonomy object based on the parameters given. 
    *   If modifying an existing taxonomy object, note that the $object_type value from the 
    *   original registration will be overwritten.
    *
    *   More info : https://codex.wordpress.org/Function_Reference/register_taxonomy
    */

    register_taxonomy('course', 'lessons', $args);
}
add_action('init', __NAMESPACE__ . '\register_lessons_courses');

/*
*   course_menu() function is used to add a submenu(Settings) to the Custom Post menu.
*/

function course_menu()
{
    add_submenu_page(
        'edit.php?post_type=lessons',
        'Settings',
        'Settings',
        'manage_options',
        'Courses-Settings',
        __NAMESPACE__ . '\\setting_tabs'
    );
}
add_action('admin_menu', __NAMESPACE__ . '\\course_menu');

/*
*   setting_tabs() function is used to add customizations to the Setting page.
*/

function setting_tabs()
{
    ?>

    <div id="settings-container">
        <div id="general-settings">
            <h2 class="title">Courses Page Setting</h2>
            <input type="text" value="text" id="text-shortcodepage" disabled />
            <a id="clickcopycode">Click to copy!</a>
            <label class="container">Show Thumbnails
                <input type="checkbox" id="imageCheck"> <span class="checkmark"></span> </label>
            <input type="button" value="CREATE" id="btnCreatePage" class="btn-style" />
        </div>
        <div id="index-settings">
            <h2 class="title">Table Of Contents Setting</h2>
            <input type="text" value="text" id="text-shortcode" disabled />
            <a id="clickcopy">Click to copy!</a>
            <label class="container">Show Subtitles(h2)
                <input type="checkbox" id="subtitleCheck"> <span class="checkmark"></span> </label>
            <label class="container">Enable Clickable Index
                <input type="checkbox" id="linkCheck"> <span class="checkmark"></span> </label>
            <?php
                $args = array(
                    'show_option_all'    => __('Select courses'),
                    'orderby'            => 'ID',
                    'order'              => 'ASC',
                    'hide_empty'         => 0,
                    'hierarchical'       => 1,
                    'taxonomy'           => 'course',
                    'id'                 => 'selector',
                    'hide_if_empty'      => false,
                    'value_field'         => 'term_id',
                );
                wp_dropdown_categories($args);
                ?>
            <input type="button" value="CREATE" id="btnCreate" class="btn-style" />
        </div>
        <div id="copyleft">
            <p id="copyleft-text" class="title">Copyleft &#x1f12f; Emilio Bruno</p>
        </div>
    </div>

<?php
}

/*
*   add_course_image() function is used to add a new field(Featured Image) when you create a Taxonomy term.
*/

function add_course_image($taxonomy)
{ ?>
    <div>
        <label for="featured-image-id">Image</label>
        <input type="hidden" id="featured-image-id" name="featured-image-id" value="">
        <div id="featured-image-wrapper">
            <img id="output" width="150" />
        </div>
        <p>
            <input style="background-color: #4CAF50; color: white; padding: 5px;border: 2px solid #4CAF50;" type="button" id="add_media" name="add_media" value="Add Image" />
            <input style="cursor; background-color: #f44336; color: white; padding: 5px;border: 2px solid #f44336;" type="button" id="rm_media" name="rm_media" value="Remove Image" />
        </p>
    </div>
<?php
}
add_action('course_add_form_fields', __NAMESPACE__ . '\\add_course_image', 10, 2);

/*
*   add_script() function is used to manage the new Featured Image field.
*/

function add_script()
{ ?>
    <script>
        jQuery(document).ready(function($) {

            var mediaUploader;

            /*
             *   When you click on the #add_media button, the script creates and opens a frame to choose an image.
             *   After you choose the image, a hidden field is going to store the 
             *   attachment id of the pic and you will be able to see it.
             */

            jQuery("#add_media").click(function() {

                if (mediaUploader) {
                    mediaUploader.open();
                    return;
                }

                mediaUploader = wp.media({
                    frame: 'select',
                    title: 'Choose Image',
                    button: {
                        text: 'Choose Image'
                    },
                    library: {
                        type: ['image']
                    },
                    multiple: false
                });

                mediaUploader.on('select', function() {
                    var attachment = mediaUploader.state().get('selection').first().toJSON();
                    $('#output').attr('src', attachment.url);
                    $('#featured-image-id').val(attachment.id);
                });
                mediaUploader.open();

            });

            /*
             *   When you click on the #rm_media button, the script removes the pic and
             *   the attachment id from the hidden field.
             */

            jQuery("#rm_media").click(function() {
                $('#output').attr('src', '');
                $('#featured-image-id').val('');
            });

            /*
             *   This ajax script removes the pic and the attachment id from the hidden field
             *   when the taxonomy term creation is terminated.
             */

            $(document).ajaxComplete(function(event, xhr, settings) {
                var queryStringArr = settings.data.split('&');
                if ($.inArray('action=add-tag', queryStringArr) !== -1) {
                    var xml = xhr.responseXML;
                    $response = $(xml).find('term_id').text();
                    if ($response != "") {
                        $('#output').attr('src', '');
                        $('#featured-image-id').val('');
                    }
                }
            });

        });
    </script>
<?php }
add_action('admin_footer', __NAMESPACE__ . '\\add_script');

/*
*   save_course_image() function is used to add new meta to the term.
*/

function save_course_image($term_id)
{
    /*
    *   if the featured-image-id value exists
    *
    *   add_term_meta( int $term_id, string $meta_key, mixed $meta_value, bool $unique = false )
    *   
    *   more info: https://developer.wordpress.org/reference/functions/add_term_meta/
    */

    if (isset($_POST['featured-image-id']) && '' !== $_POST['featured-image-id']) {
        add_term_meta($term_id, 'featured-image-id', $_POST['featured-image-id'], true);
    }
}
add_action('created_course', __NAMESPACE__ . '\\save_course_image', 10, 2);

/*
*   update_course_image() function is used to add a new field(Featured Image) when you edit a Taxonomy term.
*/

function update_course_image($term)
{ ?>
    <tr>
        <th scope="row">
            <label for="featured-image-id">Image</label>
        </th>
        <td>
            <?php
                $image_id = get_term_meta($term->term_id, 'featured-image-id', true);
                $image_attributes = wp_get_attachment_image_src($image_id);
                ?>
            <input type="hidden" id="featured-image-id" name="featured-image-id" value="<?php echo $image_id; ?>"">
        <div id=" featured-image-wrapper">
            <img id="output" width="150" src="<?php echo $image_attributes[0]; ?>" />
            </div>
            <p>
                <input style="pointer: cursor; background-color: #4CAF50; color: white; padding: 5px;border: 2px solid #4CAF50;" type="button" id="add_media" name="add_media" value="Update Image" />
                <input style="pointer: cursor; background-color: #f44336; color: white; padding: 5px;border: 2px solid #f44336;" type="button" id="rm_media" name="rm_media" value="Remove Image" />
            </p>
        </td>
    </tr>
<?php
}
add_action('course_edit_form_fields', __NAMESPACE__ . '\\update_course_image', 10, 2);

/*
*   updated_course_image() function is used to update the meta of the edited term.
*/

function updated_course_image($term_id)
{
    /*
    *   update_term_meta( int $term_id, string $meta_key, mixed $meta_value, mixed $prev_value = '' )
    *
    *   More info: https://developer.wordpress.org/reference/functions/update_term_meta/
    */

    if (isset($_POST['featured-image-id']) && '' !== $_POST['featured-image-id']) {
        update_term_meta($term_id, 'featured-image-id', $_POST['featured-image-id']);
    } else {
        update_term_meta($term_id, 'featured-image-id', '');
    }
}
add_action('edited_course', __NAMESPACE__ . '\\updated_course_image', 10, 2);

/*
*   load_media() function is used to call the wp_enqueue_media() method.
*/

function load_media()
{
    /*
    *   Enqueues all scripts, styles, settings, and templates necessary to use all media JavaScript APIs. 
    *
    *   More info: https://codex.wordpress.org/Function_Reference/wp_enqueue_media
    */

    wp_enqueue_media();
}
add_action('admin_enqueue_scripts', __NAMESPACE__ . '\\load_media');

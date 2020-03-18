<?php

namespace CoursesPlanner;

/**
 * Plugin Name:       Courses Planner
 * Plugin URI:        https://scriptisle.com/courses-planner-plugin/
 * Description:       This plugin will help you to manage your courses easily. For now, you can only publish free courses for guest users, but there will be a new update in the future. 
 * Version:           1.0.0
 * Author:            Emilio Bruno
 * Author URI:        https://scriptisle.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       courses-planner
 */
/*
    Courses Planner is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 2 of the License, or
    any later version.
     
    Courses Planner is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
    GNU General Public License for more details.
     
    You should have received a copy of the GNU General Public License
    along with Courses Planner. If not, see https://www.gnu.org/licenses/gpl-2.0.html.
*/

/*
*   The require_once statement is identical to require except PHP will check if the file has 
*   already been included, and if so, not include (require) it again. 
*/

if (!defined('ABSPATH')){
    die;
}

require_once(dirname(__FILE__) . '/inc/php/shortcode.php');
require_once(dirname(__FILE__) . '/inc/php/Custom-courses-parts.php');

/* 
*   The register_activation_hook function registers a plugin function to be run when the plugin is activated. 
*   activate() function calls the flusher() function.
*/

function activate()
{
    flusher();
}
register_activation_hook(__FILE__, __NAMESPACE__ . '\\activate');

/* 
*   The function register_deactivation_hook (introduced in WordPress 2.0) registers a plugin function to be run when the plugin is deactivated. 
*   deactivate() function calls the flusher() function.
*/

function deactivate()
{
    flusher();
}
register_deactivation_hook(__FILE__, __NAMESPACE__ . '\\deactivate');

/* 
*   flusher() function uses an external method called flush_rewrite_rules().
*   flush_rewrite_rules() is useful when used with custom post types as it allows for automatic flushing 
*   of the WordPress rewrite rules (usually needs to be done manually for new custom post types). 
*   However, this is an expensive operation so it should only be used when absolutely necessary.
*/

function flusher()
{
    flush_rewrite_rules();
}

/* 
*   admin_course_setting_script() function is used to enqueue external script and style for the
*   setting page of the Courses Planner plugin. The if condition is used to check if you are on the
*   right page to enqueue files to avoid useless enqueue.
*/

function admin_course_setting_script($hook)
{
    if ('lessons_page_Courses-Settings' != $hook) {
        return;
    }
    wp_enqueue_style('admin-course-setting-style', plugins_url('inc/css/admin-style.css', __FILE__));
    wp_enqueue_script('course-setting-script', plugins_url('inc/js/script.js', __FILE__), array('jquery'));
}
add_action('admin_enqueue_scripts', __NAMESPACE__ . '\\admin_course_setting_script');

/* 
*   user_course_style() function is used to enqueue external style for the
*   front-end pages.
*/

function user_course_style()
{
    wp_enqueue_style('user-course-style', plugins_url('inc/css/style.css', __FILE__));
}
add_action('wp_enqueue_scripts', __NAMESPACE__ . '\\user_course_style');

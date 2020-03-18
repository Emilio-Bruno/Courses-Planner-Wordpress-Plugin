<?php

if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}

/* 
*   delete_CPT() function is used to delete all posts of CPT.
*/

function delete_CPT() {
    global $wpdb;

    $posts = get_posts( array(
        'numberposts' => -1,
        'post_type' => 'lessons',
        'post_status' => 'any' ) );

    foreach ( $posts as $post ){
        wp_delete_post( $post->ID, true );
    }
}

delete_CPT();

/* 
*   This query is used to delete all terms of CT
*/

global $wpdb;

$wpdb->query( "
    DELETE FROM
    {$wpdb->terms}
    WHERE term_id IN
    ( SELECT * FROM (
        SELECT {$wpdb->terms}.term_id
        FROM {$wpdb->terms}
        JOIN {$wpdb->term_taxonomy}
        ON {$wpdb->term_taxonomy}.term_id = {$wpdb->terms}.term_id
        WHERE taxonomy = 'course'
    ) as T
    );
");

/* 
*   This query is used to delete the CT
*/

$wpdb->query( "DELETE FROM {$wpdb->term_taxonomy} WHERE taxonomy = 'course'" );
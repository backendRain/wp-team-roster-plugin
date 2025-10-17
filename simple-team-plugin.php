<?php
/*
Plugin Name: Simple Team Members
Description: A custom plugin to add and display team members with a REST API and JS filtering.
Version: 2.0
Author: Ty-Querria Searcy
*/

// Security check to prevent direct access to the file.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * 1. Create the Custom Post Type for "Team Members".
 */
function sdm_create_team_member_cpt() {
    $labels = array(
        'name'          => 'Team Members',
        'singular_name' => 'Team Member',
        'add_new_item'  => 'Add New Team Member',
        'edit_item'     => 'Edit Team Member',
    );
    $args = array(
        'labels'      => $labels,
        'public'      => true,
        'has_archive' => true,
        'menu_icon'   => 'dashicons-groups',
        'supports'    => array( 'title', 'editor', 'thumbnail', 'custom-fields' ),
        'show_in_rest' => true, // Make it available to the REST API
    );
    register_post_type( 'team_member', $args );
}
add_action( 'init', 'sdm_create_team_member_cpt' );


/**
 * 2. Create the Shortcode HTML container.
 * The shortcode now only outputs the containers for the JS to populate.
 */
function sdm_display_team_members_shortcode() {
    // This HTML provides the skeleton for the JavaScript application.
    return '
        <div id="team-app-wrapper">
            <div class="team-filter-buttons"></div>
            <div class="team-members-wrapper">
                <p class="team-loading-message">Loading team members...</p>
            </div>
        </div>
    ';
}
add_shortcode( 'team_members', 'sdm_display_team_members_shortcode' );


/**
 * 3. Create the Custom REST API Endpoint.
 */
function sdm_register_rest_route() {
    register_rest_route( 'team/v1', '/members', array(
        'methods'  => 'GET',
        'callback' => 'sdm_get_team_members_rest',
        'permission_callback' => '__return_true', // Publicly accessible
    ) );
}
add_action( 'rest_api_init', 'sdm_register_rest_route' );


/**
 * 4. The callback function for the REST API endpoint.
 * This function queries and returns the team member data as JSON.
 */
function sdm_get_team_members_rest() {
    $args = array(
        'post_type'      => 'team_member',
        'posts_per_page' => -1,
        'orderby'        => 'title',
        'order'          => 'ASC',
    );
    $query = new WP_Query( $args );

    $team_members_data = array();

    if ( $query->have_posts() ) {
        while ( $query->have_posts() ) {
            $query->the_post();
            $id = get_the_ID();
            $team_members_data[] = array(
                'name'     => get_the_title(),
                'bio'      => get_the_content(),
                'photo'    => get_the_post_thumbnail_url( $id, 'medium' ),
                'category' => get_post_meta( $id, 'category', true ),
            );
        }
    }
    wp_reset_postdata();

    return new WP_REST_Response( $team_members_data, 200 );
}


/**
 * 5. Enqueue scripts and styles and pass data to JavaScript.
 */
function sdm_enqueue_assets() {
    // Enqueue the stylesheet.
    wp_enqueue_style(
        'sdm-team-styles',
        plugin_dir_url( __FILE__ ) . 'style.css',
        array(),
        '2.0'
    );

    // Enqueue the JavaScript file.
    wp_enqueue_script(
        'sdm-team-scripts',
        plugin_dir_url( __FILE__ ) . 'plugin.js',
        array(),
        '2.0',
        true
    );

    // Pass the REST API URL to the JavaScript file.
    wp_localize_script(
        'sdm-team-scripts',
        'sdm_data',
        array(
            'rest_url' => rest_url( 'team/v1/members' ),
        )
    );
}
add_action( 'wp_enqueue_scripts', 'sdm_enqueue_assets' );
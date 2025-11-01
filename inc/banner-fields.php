<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) exit;

add_action( 'cmb2_admin_init', 'razuTheme_home_slider_metabox' );

function razuTheme_home_slider_metabox() {

    $prefix = '_razu_home_slider_';

    // Get front page ID
    $front_page_id = (int) get_option( 'page_on_front' );

    $cmb = new_cmb2_box( array(
        'id'            => $prefix . 'metabox',
        'title'         => __( 'Homepage Slider', 'razuTheme' ),
        'object_types'  => array( 'page' ),
        'show_on'       => array(
            'key'   => 'id',
            'value' => array( $front_page_id ), // Only show on front page
        ),
    ) );

    // Add a group for multiple slides
    $group_field_id = $cmb->add_field( array(
        'id'          => $prefix . 'slides',
        'type'        => 'group',
        'description' => __( 'Add slides for homepage slider', 'razuTheme' ),
        'options'     => array(
            'group_title'   => __( 'Slide {#}', 'razuTheme' ),
            'add_button'    => __( 'Add Slide', 'razuTheme' ),
            'remove_button' => __( 'Remove Slide', 'razuTheme' ),
            'sortable'      => true,
        ),
    ) );

    // Slide image
    $cmb->add_group_field( $group_field_id, array(
        'name' => 'Slide Image',
        'id'   => 'image',
        'type' => 'file',
        'options' => array(
            'url' => false, // Hide the URL input
        ),
    ) );

    // Slide title
    $cmb->add_group_field( $group_field_id, array(
        'name' => 'Slide Title',
        'id'   => 'title',
        'type' => 'text',
    ) );

    // Slide button URL
    $cmb->add_group_field( $group_field_id, array(
        'name' => 'Slide Button URL',
        'id'   => 'link',
        'type' => 'text_url',
    ) );
}

<?php

use Kirki\Compatibility\Kirki;

if ( class_exists( 'Kirki' ) ) {

    /**
     * Add Panel
     */
    Kirki::add_panel( 'vapedreamhub', [
        'priority'    => 160,
        'title'       => esc_html__( 'VapeDreamHub', 'razuTheme' ),
        'description' => esc_html__( 'Theme settings for VapeDreamHub', 'razuTheme' ),
    ] );

    /**
     * Add Section
     */
    Kirki::add_section( 'homepage_settings', [
    'title'           => esc_html__( 'Homepage Settings', 'razuTheme' ),
    'panel'           => 'vapedreamhub',
    'priority'        => 10,
    'description'     => esc_html__( 'Customize your homepage', 'razuTheme' ),
    'active_callback' => function() {
        // Check if this is the front page
        return is_front_page();
    },
] );


    /**
     * Add a Title Field
     */
    Kirki::add_field( 'razuTheme_config', [
        'type'        => 'text',
        'settings'    => 'homepage_title',
        'label'       => esc_html__( 'Homepage Title', 'razuTheme' ),
        'section'     => 'homepage_settings',
        'default'     => esc_html__( 'Welcome to VapeDreamHub', 'razuTheme' ),
        'priority'    => 10,
    ] );
}

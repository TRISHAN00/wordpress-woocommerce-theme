function mytheme_customize_register( $wp_customize ) {

  // --- Top Header Section ---
  $wp_customize->add_section( 'top_header_section', array(
    'title'       => __( 'Top Header', 'mytheme' ),
    'priority'    => 30,
    'description' => __( 'Manage the top header bar content and social links', 'mytheme' ),
  ) );

  // Header Text
  $wp_customize->add_setting( 'top_header_text', array(
    'default'           => '🚚 Free Delivery Above 350 Dhs | 💳 Card Payment Available (Dubai/Sharjah/Ajman)',
    'sanitize_callback' => 'wp_kses_post',
  ) );

  $wp_customize->add_control( 'top_header_text', array(
    'label'   => __( 'Header Text', 'mytheme' ),
    'section' => 'top_header_section',
    'type'    => 'textarea',
  ) );

  // Social Links
  $socials = array('facebook', 'instagram', 'twitter', 'whatsapp');

  foreach ( $socials as $social ) {
    $wp_customize->add_setting( "top_header_{$social}_url", array(
      'default'           => '#',
      'sanitize_callback' => 'esc_url_raw',
    ) );

    $wp_customize->add_control( "top_header_{$social}_url", array(
      'label'   => ucfirst($social) . ' URL',
      'section' => 'top_header_section',
      'type'    => 'url',
    ) );
  }
}
add_action( 'customize_register', 'mytheme_customize_register' );

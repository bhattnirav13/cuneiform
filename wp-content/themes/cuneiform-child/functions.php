<?php

add_action('wp_enqueue_scripts', 'enqueue_parent_styles');
function enqueue_parent_styles()
{
  wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');
  wp_enqueue_style('child-one-style', get_stylesheet_directory_uri() . '/style.css');
  wp_enqueue_style( 'font-custom', get_stylesheet_directory_uri() . '/assets/css/font-icon.css' ); 
  wp_enqueue_style( 'owl-style', get_stylesheet_directory_uri() . '/assets/css/owl_carousel_min.css' );		
  wp_enqueue_style( 'custom-style', get_stylesheet_directory_uri() . '/assets/css/custom.css' );

  wp_enqueue_style( 'my_custom-style', get_stylesheet_directory_uri() . '/assets/css/custom.css' );

  wp_enqueue_script( 'owl-script', get_stylesheet_directory_uri() . '/assets/js/owl_carousel_min.js', ['jquery'], '1.0.0', true );
  wp_enqueue_script( 'custom-js', get_stylesheet_directory_uri() . '/assets/js/custom.js' );    
}

add_filter('use_widgets_block_editor', '__return_false');
add_filter('use_block_editor_for_post', '__return_false', 10);


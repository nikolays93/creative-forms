<?php

function shortcode_form_callback( $atts = array(), $content = '' ) {
    $atts = shortcode_atts( array(
        'id' => false,
    ), $atts, 'cform' );

    if( ! $form_id = absint($atts['id']) ) {
        return false;
    }

    $form = '';
    $form .= '<form action="#">';

    $_post = get_post($form_id);
    $content = $_post->post_content;
    $content = apply_filters('the_content', $content);
    $content = str_replace(']]>', ']]&gt;', $content);
    $form .= $content;

    $form .= '</form>';
}
add_shortcode( 'cform', 'shortcode_form_callback' );
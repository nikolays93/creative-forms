<?php

function shortcode_form_callback( $atts = array(), $content = '' ) {
    $atts = shortcode_atts( array(
        'id' => false,
    ), $atts, 'cform' );

    if( ! $form_id = absint($atts['id']) ) {
        return false;
    }

    $form = '';
    $form .= '<form action="#" id="cform_'.$form_id.'">';

    $_post = get_post($form_id);
    $content = $_post->post_content;
    $content = apply_filters('the_content', $content);
    $content = str_replace(']]>', ']]&gt;', $content);
    $form .= $content;

    $form .= '</form>';

    wp_enqueue_script( 'submit-cform', PLUGINNAME_URL . '/scripts/front-submit.js', array( 'jquery' ), PLUGINNAME::VERSION, true );

    wp_localize_script( $handle, 'cform', array(
        'cform_'
        ) );

    return $form;
}
add_shortcode( 'cform', 'shortcode_form_callback' );
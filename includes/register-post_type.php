<?php

add_action('init', 'register_form_type');
function register_form_type() {
    register_post_type(PLUGINNAME::SLUG, array(
        'label'  => 'form',
        'labels' => array(
            'name'               => __('Forms'),
            'singular_name'      => __('Form'),
            'add_new'            => __('Add Form'),
            'add_new_item'       => __('Add new Form'),
            'edit_item'          => __('Edit Form'),
            'new_item'           => __('New Form'),
            'view_item'          => __('View Form'),
            'search_items'       => __('Search Form'),
            // 'menu_name'          => 'Записи', // название меню
        ),
        'description'         => '',
        'public'              => true,
        'publicly_queryable'  => true,
        'exclude_from_search' => true,
        'show_ui'             => true,
        'show_in_menu'        => false,
        'show_in_admin_bar'   => false,
        'show_in_nav_menus'   => true,
        'menu_position'       => null,
        'menu_icon'           => null,
        //'capability_type'   => 'post',
        //'capabilities'      => 'post', // массив дополнительных прав для этого типа записи
        //'map_meta_cap'      => null, // Ставим true чтобы включить дефолтный обработчик специальных прав
        'hierarchical'        => false,
        'supports'            => array('title','editor'), // 'title','editor','author','thumbnail','excerpt','trackbacks','comments','revisions','page-attributes','post-formats', 'custom-fields'
        'taxonomies'          => array(),
        'has_archive'         => false,
        'rewrite'             => true,
        'query_var'           => true,
    ) );
}

add_action( 'add_meta_boxes', 'add_form_metaboxes' );
function add_form_metaboxes() {
    $inputs = array(
        'text'     => __('Text field'),
        'email'    => __('Email field'),
        'textarea' => __('Text Area field'),
        'checkbox' => __('Checkbox'),
        'submit'   => __('Submit button'),
    );

    foreach ($inputs as $input_type => $input_title) {
        add_meta_box( $input_type, $input_title, 'form_input_content', PLUGINNAME::SLUG, 'side', 'default' );

        add_filter( "postbox_classes_" .PLUGINNAME::SLUG. "_" . $input_type, 'push_closed_in_array' );
    }
}

add_action( 'admin_enqueue_scripts', 'set_to_enqueue_form_type_scripts' );
function set_to_enqueue_form_type_scripts() {
    $screen = get_current_screen();
    if( $screen instanceof WP_Screen && $screen->id === PLUGINNAME::SLUG ) {
        wp_enqueue_script( 'jquery-ui-droppable' );
        $s_url = PLUGINNAME_URL . '/scripts/';
        wp_enqueue_script( 'form_script', $s_url . 'drag_n_drop.js', array('jquery'), PLUGINNAME::VERSION, true );
    }
}


add_action('edit_form_after_title', 'form_reorder_boxes');
function form_reorder_boxes(){
    ?>
    <style>
        #visibility.misc-pub-section {
            display: none;
        }
        #wp-content-editor-container .mce-edit-area {
            position: relative;
        }
        #wp-content-editor-container.overed .mce-edit-area:before {
            content: '';
            position: absolute;
            left: 0;
            right: 0;
            height: 100%;
            bottom: 0;
            border: 4px dashed #83b4d8;
            z-index: 1050;
        }
        [name="form_shortcode"] {
            padding: 6px;
        }
    </style>
    <?php

    global $post, $wp_meta_boxes;

    if( PLUGINNAME::SLUG === ($post_type = get_post_type($post)) ) {
        // $attrs = array('id' => $post->ID);
        echo sprintf('<p><input type="text" class="widefat" name="form_shortcode" value=\'[cform id="%d"]\'></p>',
            $post->ID);

        do_meta_boxes( get_current_screen(), 'advanced', $post );
        unset( $wp_meta_boxes[ $post_type ]['advanced'] );

        wp_nonce_field( 'security', $name = 'create_creative_form' );
    }
}

function form_input_content() {
    echo "Field description";
}

function push_closed_in_array( $classes ) {
    array_push( $classes, 'closed' );

    return $classes;
}

add_action( 'save_post', 'save_form_shortcode' );
function save_form_shortcode( $post_id ) {
    if( ! isset( $_POST['create_creative_form'] ) ) {
        return $post_id;
    }

    if ( ! wp_verify_nonce( $_POST['create_creative_form'], 'security' ) ) {
        return $post_id;
    }

    if( ! empty($_POST[ 'form_shortcode' ]) ) {
        update_post_meta( $post_id, 'form_shortcode',
            sanitize_text_field( $_POST[ 'form_shortcode' ] ) );
    }

    return $post_id;
}

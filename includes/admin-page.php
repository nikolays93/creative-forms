<?php

class PLUGINNAME_Page
{
    function __construct()
    {
        $page = new WP_Admin_Page( PLUGINNAME::SETTINGS );
        $page->set_args( array(
            'parent'      => false,
            'title'       => '',
            'menu'        => 'Creative Forms',
            'callback'    => array($this, 'page_render'),
            // 'validate'    => array($this, 'validate_options'),
            'permissions' => 'manage_options',
            'tab_sections'=> null,
            'columns'     => 2,
            ) );


        $page->add_metabox( 'metabox1', __('Creative Forms'),
            array($this, 'metabox1_callback'), 'side' );

        // $page->add_metabox( 'metabox2', 'metabox2', array($this, 'metabox2_callback'), $position = 'side');
        $page->set_metaboxes();
    }

    /**
     * Основное содержимое страницы
     *
     * @access
     *     must be public for the WordPress
     */
    function page_render() {
        $table = new Example_List_Table();
        $table->set_fields( array('post_type' => PLUGINNAME::SLUG) );
        $table->prepare_items();
        $table->display();
    }

    /**
     * Тело метабокса вызваное функций $this->add_metabox
     *
     * @access
     *     must be public for the WordPress
     */
    function metabox1_callback() {
        echo sprintf('<p><a href="%s">%s</a></p>', '#', __('Documentation') );

        $cr_link = add_query_arg( array( 'post_type' => PLUGINNAME::SLUG ), admin_url('post-new.php') );
        echo sprintf( '<a href="%s" class="button button-primary right">%s</a>', $cr_link, __( 'Create new' ) );
        echo '<div class="clear"></div>';
    }

    function metabox2_callback() {
        $data = array(
            // id or name - required
            array(
                'id'    => 'example_0',
                'type'  => 'text',
                'label' => 'TextField',
                'desc'  => 'This is example text field',
                ),
             array(
                'id'    => 'example_1',
                'type'  => 'select',
                'label' => 'Select',
                'options' => array(
                    // simples first (not else)
                    'key_option5' => 'option5',
                    'option1' => array(
                        'key_option2' => 'option2',
                        'key_option3' => 'option3',
                        'key_option4' => 'option4'),
                    ),
                ),
            array(
                'id'    => 'example_2',
                'type'  => 'checkbox',
                'label' => 'Checkbox',
                ),
            );

        $form = new WP_Admin_Forms( $data, $is_table = true, $args = array(
            // Defaults:
            // 'admin_page'  => true,
            // 'item_wrap'   => array('<p>', '</p>'),
            // 'form_wrap'   => array('', ''),
            // 'label_tag'   => 'th',
            // 'hide_desc'   => false,
            ) );
        echo $form->render();

        submit_button( 'Сохранить', 'primary right', 'save_changes' );
        echo '<div class="clear"></div>';
    }
}
new PLUGINNAME_Page();

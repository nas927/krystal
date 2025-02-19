<?php

class Custom
{

    public array $tab;

    public function add_new_section(WP_Customize_Manager $wp_customize, string $section_name, string $title)
    {
        // Do stuff with $wp_customize, the WP_Customize_Manager object.
        $wp_customize->add_section($section_name, [
            'title' => $title
        ]);
    }

    public function add_new_text(WP_Customize_Manager $wp_customize, string $name, string $section, string $showMsg, string $type = 'text', string $default = 'Un text', string $post = 'refresh', string $callback = '')
    {
        $this->add_new_setting($wp_customize, $name, $default, $post, $callback);
        $wp_customize->add_control($name, array(
            'type' => $type,
            'section' => $section,
            'settings' => $name,
            'label' => $showMsg
        ));
    }
    public function add_new_setting(WP_Customize_Manager $wp_customize, string $name, string $default, string  $post = 'refresh', string $callback = '')
    {
        $wp_customize->add_setting($name, [
            'default' => $default,
            'capability' => 'edit_theme_options',
            'transport' => $post,
            'sanitize_callback' => $callback
        ]);
    }

    // Add color
    public function add_custom_color_sett(WP_Customize_Manager $wp_customize, string $name, string $default, string $section, string $showMsg, array $style = [], array $id = [], string $callback = 'sanitize_hex_color', string $post = 'postMessag'): void
    {
        $this->add_new_setting($wp_customize, $name, $default, $post, $callback);
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, $name, [
            'section' => $section,
            'setting' => $name,
            'label' => $showMsg
        ]));
        $this->tab[$name] = [$style ,implode(' ', $id)];
    }


    // Add picture
    public function add_custom_media_sett(WP_Customize_Manager $wp_customize, string $name, string $default, string $post, string $section, string $showMsg): void
    {
        $this->add_new_setting($wp_customize, $name, $default, $post);
        $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, $name, [
            'section' => $section,
            'setting' => $name,
            'label' => $showMsg
        ]));
    }

    // Add Range input
    public function add_custom_range(WP_Customize_Manager $wp_customize, string $name, int $default, string $transport, string $section, string $label, string $description, int $min, int $max, int $step): void
    {
        $this->add_new_setting($wp_customize, $name, $default);
        $wp_customize->add_control($name, array(
            'type' => 'range',
            'section' => $section,
            'label' => $label,
            'description' => $description,
            'input_attrs' => array(
                'min' => $min,
                'max' => $max,
                'step' => $step,
            )
        ));
    }

    //Add select
    public function add_custom_font_sett(WP_Customize_Manager $wp_customize, string $name, string $default, string $section, string $label)
    {
        $this->add_new_setting($wp_customize, $name, $default);
        $wp_customize->add_control($name, array(
            'label' => $label,
            'section' => $section,
            'settings' => $name,
            'type' => 'select',
            'choices' => array(
                '"Lora", cursive' => 'Lora',
                '"Dancing Script", cursive' => 'Dancing',
                '"Jost", sans-serif' => 'Jost',
                '"Cormorant Upright", serif' => 'Cormorant',
                'Roboto' => 'Roboto',
                'Open Sans' => 'Open Sans',
                'Lato' => 'Lato',
                'Montserrat' => 'Montserrat',
                'Oswald' => 'Oswald',
                'Source Sans Pro' => 'Source Sans Pro',
                'Poppins' => 'Poppins',
                'Merriweather' => 'Merriweather',
                'Nunito' => 'Nunito',
                'Playfair Display' => 'Playfair Display',
                'Raleway' => 'Raleway',
                'Ubuntu' => 'Ubuntu',
                'PT Sans' => 'PT Sans',
                'Noto Sans' => 'Noto Sans',
                'Rubik' => 'Rubik',
                'Dosis' => 'Dosis',
                'Quicksand' => 'Quicksand',
                'Cabin' => 'Cabin',
                'Josefin Sans' => 'Josefin Sans',
                'Libre Baskerville' => 'Libre Baskerville',
                'Muli' => 'Muli',
                'Zilla Slab' => 'Zilla Slab',
                'Bitter' => 'Bitter',
                'Work Sans' => 'Work Sans',
                'Fira Sans' => 'Fira Sans',
                'Arvo' => 'Arvo',
                'Karla' => 'Karla',
                'Inconsolata' => 'Inconsolata',
                'Lobster' => 'Lobster',
                'Pacifico' => 'Pacifico',
                'Raleway' => 'Raleway',
                'Crimson Text' => 'Crimson Text',
                'Space Mono' => 'Space Mono',
            )
        ));
    }

    public function my_enqueue_customizer_scripts ()
    {
            wp_register_script('alterate', get_template_directory_uri() . '/assets/admin/perso.js', ['jquery', 'customize-preview', 'customize-controls'], time(), true);
            $main_js_data = $this->tab;
            wp_localize_script('alterate', 'tab', $main_js_data);
            wp_enqueue_script('alterate');
    }

    public function after_create_control()
    {
        add_action('customize_controls_enqueue_scripts', array($this, 'my_enqueue_customizer_scripts'));
    }
}

?>
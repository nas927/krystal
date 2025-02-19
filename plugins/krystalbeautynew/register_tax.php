<?php

require_once "register/create.php";
require_once "register/edit.php";

class Taxo {
    use CreateAll;
    use EditAll;
	public string $name;
    public array $label;
	public string $description;

    public string $media;
	
	public function __construct(string $name, array $label, string $media = '', string $description = '')
	{
		$this->name = str_replace(" ", "_", $name);
		$this->label = $label;
        $this->description = $description;
        $this->media = $media;
	}

    public function showAll() {

        // Ajouter les colonnes personnalisées
        add_filter('manage_edit-' . $this->name . '_columns', function($columns) {
            foreach ($this->label as $label) {
                $columns[$this->name . '_' . $label] = ucfirst($label);
            }
            if (!empty($this->media)) {
                $columns[$this->name . '_' . $this->media] = ucfirst($this->media);
            }
            return $columns;
        });

        // Remplir le contenu des colonnes
        add_filter('manage_' . $this->name . '_custom_column', function($content, $column_name, $term_id) {
            foreach ($this->label as $label) {
                if ($column_name === $this->name . '_' . $label) {
                    $value = esc_html(get_term_meta($term_id, $this->name . '_' . $label, true));
                    $value .= " €";
                    return $value ? $value : '-';
                }
            }
            if (!empty($this->media) && $column_name === $this->name . '_' . $this->media) {
                $image_id = esc_html(get_term_meta($term_id, $this->name . '_' . $this->media, true));
                if ($image_id) {
                    $image_url = wp_get_attachment_image_url($image_id, 'thumbnail');
                    return $image_url ? '<img src="' . esc_url($image_url) . '" style="max-width: 50px;">' : '-';
                }
            }
            return $content;
        }, 10, 3);
    }

    public function mount()
    {
        add_action('init', function () {
            register_taxonomy($this->name, 'post', [
                'labels' => [
                    'name' => ucfirst($this->name)
                ],
                'show_in_rest' => false
            ]);
        });
        add_action('admin_enqueue_scripts', function (){
            $screen = get_current_screen();
            preg_match('/' . preg_quote($this->name, '/') . '/', $screen->id, $matches, PREG_OFFSET_CAPTURE);
            if ($matches)
                wp_enqueue_media();
                wp_enqueue_script('preview-before', get_template_directory_uri() . '/assets/admin/preview_before.js', ['jquery'], ['1.0.1'], true);
        });
    }

    public function run(): void
    {
        foreach ($this->label as $l):
            add_action($this->name . '_add_form_fields', function () use ($l){
                $this->create_input_field($l);
            });

            add_action('created_' . $this->name, function ($term_id) use ($l){
                if (isset($_POST[$this->name . '_' . $l])) {
                    $var = sanitize_text_field($_POST[$this->name . '_' . $l]);
                    add_term_meta($term_id, $this->name . '_' . $l, $var, true);
                }
            });

            add_action($this->name . '_edit_form_fields', function ($term) use ($l){
                $this->edit_input_field($term, $l);
            });

            add_action('edited_' . $this->name, function ($term_id) use ($l){
                if (isset($_POST[$this->name . '_' . $l])) {
                    $var = sanitize_text_field($_POST[$this->name . '_' . $l]);
                    update_term_meta($term_id, $this->name . '_' . $l, $var);
                }
            });
        endforeach;
        $this->quickEdit();
        $this->showAll();
        if (!empty($this->media))
            $this->create_media($this->media);
        $this->mount();
    }
}


(new Taxo('manucure', ['prix']))->run();
(new Taxo('pedicure', ['prix']))->run();
(new Taxo('nail art', ['prix']))->run();
(new Taxo('pose faux ongles', ['prix']))->run();
(new Taxo('epilation', ['prix']))->run();


?>
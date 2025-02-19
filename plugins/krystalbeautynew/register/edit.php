<?php

trait EditAll {
    public function edit_input_field($term, string $label): void
    {
        $myvar = esc_html(get_term_meta($term->term_id, $this->name . '_' . $label, true));
        echo '<div class="form-field">
			<label for="' . $this->name . '">' .$label . '</label>
			<input type="text" name="'.$this->name . '_' . $label . '" id="' . $this->name . '" value="'.esc_attr($myvar).'" />
			<p>' . $this->description . '</p>
			</div>';
    }

    public function edit_media_field($term): void
    {
        $image_id = esc_html(get_term_meta($term->term_id, $this->name . '_' . $this->media, true));
        $image_id = filter_var($image_id, FILTER_VALIDATE_INT) ? $image_id : 1;
        $image_url = $image_id ? wp_get_attachment_url($image_id) : '';
        echo '<div class="form-field">
			<label for="' . $this->name . '">' .$this->media . '</label>
			<input type="button" id="media" class="button" value="'.$image_id.'" />
            <input type="hidden" name="'.$this->name . '_' . $this->media . '" id="'.$this->name . '_' . $this->media . '" value="' . $image_id . '" required/>
            <br>
            <img src="'.esc_url($image_url).'" style="max-width: 150px;" id="image_preview"/>
			<p>' . $this->description . '</p>
			</div>';
    }

    public function quickEdit() {
        // Ajouter les champs dans Quick Edit
        add_action('quick_edit_custom_box', function($column_name, $screen, $name) {
            if ($name !== $this->name) return;
            var_dump($name, $column_name);
            foreach ($this->label as $label) {
                if ($column_name === $this->name . '_' . $label) {
                    echo '<fieldset>
                        <div class="inline-edit-col">
                            <label>
                                <span class="title">'.ucfirst($label).'</span>
                                <span class="input-text-wrap">
                                    <input type="text" name="'.$this->name.'_'.$label.'" class="ptitle" placeholder="10 €" value="'.esc_html(get_term_meta($name, $this->name . '_' . $label, true)).'">
                                </span>
                            </label>
                        </div>
                    </fieldset>';
                }
            }
        }, 10, 3);
    
        // Sauvegarder les données de Quick Edit
        add_action('edited_'.$this->name, function($term_id) {
            foreach ($this->label as $label) {
                if (isset($_POST[$this->name.'_'.$label])) {
                    $value = sanitize_text_field($_POST[$this->name.'_'.$label]);
                    update_term_meta($term_id, $this->name.'_'.$label, $value);
                }
            }
        });
    }
}

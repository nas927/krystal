<?php

trait CreateAll {
	public function create_input_field(string $label): void
	{
		echo '<div class="form-field">
			<label for="'.$this->name.'">'.$label.'</label>
			<input type="text" name="'.$this->name .'_' .$label.'" id="'.$this->name.'" />
			<p>'.$this->description.'</p>
			</div>';
	}

    public function create_media_field($term): void
	{
		echo '<div class="form-field">
			<label for="'.$this->name.'">'.$this->media.'</label>
			<input type="button" id="media" class="button" value="Votre image" />
            <input type="hidden" name="'.$this->name . '_' . $this->media . '" id="'.$this->name . '_' . $this->media . '" required/>
            <br>
            <img src="" style="max-width: 150px;" id="image_preview"/>
			<p>'.$this->description.'</p>
			</div>';
	}

    public function create_media($media)
    {
        add_action($this->name . '_add_form_fields', function ($term) {
            $this->create_media_field($term);
        });

        add_action('created_' . $this->name, function ($term_id) {
            if (isset($_POST[$this->name . '_' . $this->media])) {
                $var = sanitize_text_field($_POST[$this->name . '_' . $this->media]);
                add_term_meta($term_id, $this->name . '_' . $this->media, $var, true);
            }
        });

        add_action($this->name . '_edit_form_fields', function ($term) {
            $this->edit_media_field($term);
        });

        add_action('edited_' . $this->name, function ($term_id) {
            if (isset($_POST[$this->name . '_' . $this->media])) {
                $var = sanitize_text_field($_POST[$this->name . '_' . $this->media]);
                update_term_meta($term_id, $this->name . '_' . $this->media, $var);
            }
        });
    }
}

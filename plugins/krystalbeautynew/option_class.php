<?php

namespace option_class;

class option
{
    public string $group;
    public array $option_name_setting;
    public string $option_name_section;
    public array $title;
    public array $typeInput;
    private int $count;
    
    public function __construct(string $group = '', array $option_name_setting = [], string $option_name_section = '', array $title = [], array $typeInput = ['text'])
    {
        $this->group = $group;
        $this->option_name_setting = $option_name_setting;
        $this->option_name_section = $option_name_section;
        $this->title = $title;
        $this->typeInput = $typeInput;
        $this->count = count($this->title);
    }
    
    public function register() 
    {
        for ($i = 0; $i < $this->count; $i++) {
            register_setting($this->group, $this->option_name_setting[$i]);
        }
    }
    
    public function add_setting_sec(string $section, string $title, string $msg, string $group): void
    {
        add_settings_section($section, $title, function () use($msg) {
            echo $msg;
        }, $group);
    }
    
    public function add_setting_fie(): void
    {
        for ($i = 0; $i < $this->count; $i++) {
            add_settings_field($this->option_name_setting[$i], $this->title[$i], function () use ($i) {
                $type_input = $this->typeInput[$i] == "checkbox" ? true : false;
                $value = esc_html(get_option($this->option_name_setting[$i]));
                ?>
                <input type="<?= $this->typeInput[$i] ?>" name="<?= $this->option_name_setting[$i] ?>" style="width: <?= $type_input ? "12px": "100%"; ?>" <?= ($type_input && intval($value)) ? "checked='true'": null; ?> placeholder="<?= $value ?>" value="<?= $value ?>">
                <?php
            }, $this->group, $this->option_name_section);
        }
    }

    public function enqueue_scripts()
    {
        global $pagenow;
        if (isset($_GET['page']))
            $page = $_GET["page"];
        else
            $page = "";

        if ($pagenow == "options-general.php" && $page == "site_option")
            wp_enqueue_script('option-script', get_template_directory_uri() . '/assets/admin/option_script.js', ['jquery'], time(), true);
    }
    
    public function do_all()
    {
        $this->enqueue_scripts();
        $this->register();
        $this->add_setting_fie();
    }
}

?>
<?php

require_once 'customize_class.php';

function themeslug_customize_register($wp_customize)
{
    $custom = new Custom();

    // general
    $custom->add_new_section($wp_customize, 'general', 'Général');
    $custom->add_new_text($wp_customize, 'margin_space', 'general', 'Espacement', 'number', '10');
    $custom->add_custom_font_sett($wp_customize, 'font_site', 'Cormorant', 'general', 'Customiser le font du site');

    // color
    $custom->add_new_section($wp_customize, 'color', 'Couleur');
    $custom->add_custom_color_sett($wp_customize, 'primary', '#fff', 'color', 'Couleur primaire du site', ['--primary-color'], ['.btn, a'], 'sanitize_hex_color', 'refresh');
    $custom->add_custom_color_sett($wp_customize, 'secondary', '#fff', 'color', 'Couleur secondaire du site', ['--secondary_color'], ['.btn'], 'sanitize_hex_color', 'refresh');
    $custom->add_custom_color_sett($wp_customize, 'title_color', '#fff', 'color', 'Couleur des titres du site', ['--title-color'], ['h1']);
    $custom->add_custom_color_sett($wp_customize, 'icons_color_footer', '#fff', 'color', 'Couleur des icônes en bas du site', ['--icon-color'], ['.social i']);
    // header
    $custom->add_new_section($wp_customize, 'header', 'Header');
    
    $custom->add_new_text($wp_customize, 'header_text_1', 'header', 'Titre du header');
    $custom->add_new_text($wp_customize, 'header_text_span', 'header', 'Titre en couleur');
    $custom->add_new_text($wp_customize, 'header_text_after', 'header', 'Après le titre en jaune');
    // second section
    $custom->add_new_section($wp_customize, 'header_images', 'Header images');
    $custom->add_custom_media_sett($wp_customize, 'media_header_image_1', '', 'refresh', 'header_images', 'Change l\'image n°1 des du header');
    $custom->add_new_text($wp_customize, 'header_image_text_1', 'header_images', 'Premier titre de la section header_image');
    $custom->add_custom_media_sett($wp_customize, 'media_header_image_2', '', 'refresh', 'header_images', 'Change l\'image n°2 des du header');
    $custom->add_new_text($wp_customize, 'header_image_text_2', 'header_images', 'Deuxième titre de la section header_image');
    $custom->add_custom_media_sett($wp_customize, 'media_header_image_3', '', 'refresh', 'header_images', 'Change l\'image n°3 des du header');
    $custom->add_new_text($wp_customize, 'header_image_text_3', 'header_images', 'Troisième titre de la section header_image');
    $custom->add_custom_media_sett($wp_customize, 'media_header_image_4', '', 'refresh', 'header_images', 'Change l\'image n°4 des du header');
    $custom->add_new_text($wp_customize, 'header_image_text_4', 'header_images', 'Quatrième titre de la section header_image');

    // section carte
    $custom->add_new_section($wp_customize, 'card', 'Notre carte titre et image');
    $custom->add_custom_media_sett($wp_customize, 'media_card_image_1', '', 'refresh', 'card', 'Change l\'image n°1 des du carte');
    $custom->add_new_text($wp_customize, 'card_image_text_1', 'card', 'Premier titre de la section carte');
    $custom->add_custom_media_sett($wp_customize, 'media_card_image_2', '', 'refresh', 'card', 'Change l\'image n°2 des du carte');
    $custom->add_new_text($wp_customize, 'card_image_text_2', 'card', 'Deuxième titre de la section carte');
    $custom->add_custom_media_sett($wp_customize, 'media_card_image_3', '', 'refresh', 'card', 'Change l\'image n°3 des du carte');
    $custom->add_new_text($wp_customize, 'card_image_text_3', 'card', 'Troisième titre de la section carte');
    $custom->add_custom_media_sett($wp_customize, 'media_card_image_4', '', 'refresh', 'card', 'Change l\'image n°4 des du carte');
    $custom->add_new_text($wp_customize, 'card_image_text_4', 'card', 'Quatrième titre de la section carte');

    // Title
    $custom->add_new_section($wp_customize, 'title', 'Section Titres');
    $custom->add_new_text($wp_customize, 'title_text_card', 'title', 'Titre de la section carte');
    $custom->add_new_text($wp_customize, 'title_text_gallery', 'title', 'Titre de la section Galerie');
    $custom->add_new_text($wp_customize, 'title_text_testimonial', 'title', 'Titre de la section avis');
    $custom->add_new_text($wp_customize, 'title_text_contact', 'title', 'Titre de la section contact');
    
    // Button
    $custom->add_new_section($wp_customize, 'button', 'Les boutons');
    $custom->add_new_text($wp_customize, 'button_text_header_1', 'button', 'Text du bouton de la section header');
    $custom->add_new_text($wp_customize, 'button_text_stripe', 'button', 'Text du bouton de la section rendez-vous');

    // URL
    // button
    $custom->add_new_section($wp_customize, 'url', 'Les url');
    $custom->add_new_text($wp_customize, 'url_button_text_header', 'url', 'Url du bouton de la réserver');

    // video
    $custom->add_new_section($wp_customize, 'video_head', 'Vidéo darrière plan');
    $custom->add_custom_media_sett($wp_customize, 'video_header', '', 'refresh', 'video_head', 'La vidéo d\'arrière plan');

    //testimonial and service
    $custom->add_new_section($wp_customize, 'testimonial_service', 'Background testimonial et service');
    $custom->add_custom_media_sett($wp_customize, 'media_service_1', '', 'refresh', 'testimonial_service', 'Change l\'image d\'arrière plan de la section service');
    $custom->add_custom_media_sett($wp_customize, 'media_testimonial_1', '', 'refresh', 'testimonial_service', 'Change l\'image d\'arrière plan de la section avis');

    // Gallery
    $custom->add_new_section($wp_customize, 'gallery', 'Section Gallery');
    $custom->add_custom_media_sett($wp_customize, 'media_gallery_1', '', 'refresh', 'gallery', 'Change l\'image n°1 de la gallery');
    $custom->add_custom_media_sett($wp_customize, 'media_gallery_2', '', 'refresh', 'gallery', 'Change l\'image n°2 de la gallery');
    $custom->add_custom_media_sett($wp_customize, 'media_gallery_3', '', 'refresh', 'gallery', 'Change l\'image n°3 de la gallery');
    $custom->add_custom_media_sett($wp_customize, 'media_gallery_4', '', 'refresh', 'gallery', 'Change l\'image n°4 de la gallery');
    $custom->add_custom_media_sett($wp_customize, 'media_gallery_5', '', 'refresh', 'gallery', 'Change l\'image n°5 de la gallery');
    $custom->add_custom_media_sett($wp_customize, 'media_gallery_6', '', 'refresh', 'gallery', 'Change l\'image n°6 de la gallery');

    $custom->after_create_control();
}

// Personnalisation page
add_action('customize_register', 'themeslug_customize_register');
?>
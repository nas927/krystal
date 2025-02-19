<?php

function show_redirection() 
{
    $url = get_template_directory_uri().'/php_post/redirection.php';
    echo "<div>
        <span id='waiting' data-url='{$url}'>Veuillez patienter nous vérifions le lien</span>
    </div>";
}

function add_submenu_redirection() {
    // Ajouter un sous-menu sous "Réglages" (par exemple)
    add_submenu_page(
        'options-general.php', // Parent (ici, "Réglages")
        'redirection',  // Titre de la page du sous-menu
        'redirection',  // Nom du sous-menu affiché
        'manage_options', // Capacité nécessaire pour accéder à ce menu
        'redirection_connect', // Identifiant unique pour ce sous-menu
        'show_redirection' // Fonction qui génère le contenu de la page du sous-menu
    );
}
add_action('admin_menu', 'add_submenu_redirection');
<?php

function show_treatwell() {
    if (!isset($_GET['search']))
        $data = bdd_connect_wpmail("wp_rdv", "SELECT * FROM wp_rdv ORDER BY id DESC");
    else
        $data = search('wp_rdv', 'email');
    toast_html();
    search_bar_html('treatwell_connect');
    if (!empty($data['data']) && $data["count"]):
        echo '<div class="container text-center">
        <div class="row">';
        foreach ($data["data"] as $d)
        {
            echo "
            <div class='col'>
                <div class='card' style='width: 18rem;'>
                    <div class='card-body'>
                        <h5 class='card-title'>{$d->name}</h5>
                        <a href='mailto:{$d->email}'><p class='card-text'>{$d->email}</p></a>
                    </div>
                    <ul class='list-group list-group-flush'>
                        <li class='list-group-item'>Création : {$d->created_at}</li>
                        <li class='list-group-item'>Confirmation le : {$d->created_at}</li>
                        <li class='list-group-item'>{$d->ip}</li>
                    </ul>
                    <div class='card-body'>
                        <button type='submit' data-url='".get_template_directory_uri().'/php_post/treatwell.php'."' data-rdv='{$d->rdv}' data-id='$d->id' data-email='{$d->email}' data-event='{$d->eventid}' name='cancel' class='btn btn-danger cancel' title='Annuler {$d->rdv}'>Annuler</button>
                    </div>
                </div>
            </div>";
        }
        echo '</div>
        '. wp_nonce_field('treatwell_form_action', 'treatwell_form_nonce') .'
        </div>
        <br>';
        paginate($data["page"], $data["count"], "treatwell_connect");
    else:
        echo "Rien à afficher...";
    endif;
}

function add_submenu_treatwell() {
    global $wpdb;
    
    // Compte les treatwell non lus
    $count_unread_treatwell = $wpdb->get_var("
        SELECT COUNT(*) AS counter
        FROM wp_rdv 
        WHERE confirmed = 0
    ");

    $news = $count_unread_treatwell ? "<span style='color: white; background-color: red; border-radius: 50%; padding: 5px; margin-right: 5px; height: 15px; width: 15pw;'>$count_unread_treatwell</span>": '';

    // Ajouter un sous-menu sous "Réglages" (par exemple)
    add_submenu_page(
        'options-general.php', // Parent (ici, "Réglages")
        'treatwell',  // Titre de la page du sous-menu
        "treatwell $news",  // Nom du sous-menu affiché
        'manage_options', // Capacité nécessaire pour accéder à ce menu
        'treatwell_connect', // Identifiant unique pour ce sous-menu
        'show_treatwell' // Fonction qui génère le contenu de la page du sous-menu
    );
}
add_action('admin_menu', 'add_submenu_treatwell');
<?php

function show_emails() {

    if (!isset($_GET['search']))
        $data = bdd_connect_wpmail("wp_contact", "SELECT * FROM wp_contact ORDER BY 'id' DESC, 'read' ASC LIMIT %d, %d");
    else
        $data = search('wp_contact', 'email');
    toast_html();
    search_bar_html('message_received');
    if (!empty($data['data']) && $data['count']):
        echo '<div class="container text-center">
        <div class="row">';
        foreach ($data["data"] as $d)
        {
            $read = $d->read ? 'Lu' : 'Non Lu';
            $notread = !$d->read;
            echo "
            <div class='col'>
                <div class='card' style='width: 18rem;'>
                    <div class='card-body'>
                        <h5 class='card-title'>{$d->name}</h5>
                        <p class='card-text'>{$d->message}</p>
                    </div>
                    <ul class='list-group list-group-flush'>
                        <a href='tel:{$d->number}'><li class='list-group-item'>{$d->number}</li></a>
                        <li class='list-group-item'>{$d->created_at}</li>
                        <li class='list-group-item'>{$d->ip}</li>
                        <li class='list-group-item readenornot-{$d->id}'>{$read}</li>
                    </ul>
                    <div class='card-body'>
                        <a href='mailto:{$d->email}' class='readen' data-id='{$d->id}' data-read='{$notread}' data-url='". get_template_directory_uri().'/php_post/readen.php' ."' title='Permet également de marquer comme lu ou non'>
                        <button type='button' class='btn btn-primary'>Répondre</button></a>
                    </div>
                </div>
            </div>";
        }
        echo '</div>
        '.wp_nonce_field('sent_form_action', 'sent_form_nonce') .'
        </div>
        <br>';
        paginate($data["page"], $data["count"], "message_received");
    else:
        echo "Rien à afficher...";
    endif;
}

function add_submenu_emails() {
    global $wpdb;
    
    // Compte les emails non lus
    $count_unread_email = $wpdb->get_var("
        SELECT COUNT(*) AS counter_mail
        FROM wp_contact 
        WHERE 'read' = 0
    ");

    $news = $count_unread_email ? "<span style='color: white; background-color: red; border-radius: 50%; padding: 5px; margin-right: 5px; height: 15px; width: 15pw;'>$count_unread_email</span>": '';

    // Ajouter un sous-menu sous "Réglages" (par exemple)
    add_submenu_page(
        'options-general.php', // Parent (ici, "Réglages")
        'Mails',  // Titre de la page du sous-menu
        "Les emails reçu $news",  // Nom du sous-menu affiché
        'manage_options', // Capacité nécessaire pour accéder à ce menu
        'message_received', // Identifiant unique pour ce sous-menu
        'show_emails' // Fonction qui génère le contenu de la page du sous-menu
    );
}
add_action('admin_menu', 'add_submenu_emails');
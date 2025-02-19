<?php

session_start();

require_once __DIR__."/../../../themes/krystalbeautynew/vendor/autoload.php";
require_once __DIR__."/../../../themes/krystalbeautynew/krystal/class_php/StripeConnectClass.php";

function paginate_stripe($data, string $first, bool $has_more, string $url): void
{
    $end = end($data->data);
    if (!empty($end))
    {
        $to_begin_or_back = !$has_more ? "Revenir au début" : "Précédent";
        if (!$has_more)
            $first = "";
        echo "<nav aria-label='Page navigation example'>
            <ul class='pagination'>
                <li class='page-item'><a class='page-link' href='?page={$url}&limit={$first}'>{$to_begin_or_back}</a></li>";
        if ($has_more)
            echo "<li class='page-item'><a class='page-link' href='?page={$url}&limit={$end->id}'>Suivant</a></li>
                </ul>
            </nav>";
    }
}

function load_data()
{
    $data = new Payment();
    if (isset($_GET['search']))
    {
        $search = htmlspecialchars(trim($_GET['search']));
        return $data->retrieveBalance([
            'source' => $search
        ]);
    }
    if (isset($_GET['limit']) && !empty($_GET['limit']))
    {
        $limit = htmlspecialchars(trim($_GET['limit']));
        $data = $data->retrieveBalance([
            'limit' => 20,
            'starting_after' => $limit
        ]);
        return $data;
    }
    return $data->retrieveBalance([
        'limit' => 20
    ]);
}

function check_status(string $status): array
{
    $tab = [];
    if ($status == 'pending')
    {
        $tab['status'] = 'En attente';
        $tab['class'] = 'warning';
    }
    else if ($status == "available") 
    {
        $tab['status'] = 'Confirmé';
        $tab['class'] = 'success';
    }
    else
    {
        $tab['status'] = 'Pas passé';
        $tab['class'] = 'danger';
    }
    return $tab;
}

function show_stripe() {
    $data = load_data();
    search_bar_html('stripe_connect');
    if (!empty($data->data)):
        $_GET['has_more'] = $data->has_more;
        echo '<div class="container text-center">
        <div class="row">';
        foreach ($data->data as $d)
        {
            $dt = new DateTime();
            $dt->setTimestamp($d['created']);
            $d['amount'] = intval($d['amount']) / 100;
            $status = check_status($d->status);
            echo "
            <div class='col'>
                <div class='card' style='width: 18rem;'>
                    <div class='card-body'>
                        <h5 class='card-title'>{$d['source']}</h5>
                        <p>{$d['description']}</p>
                    </div>
                    <ul class='list-group list-group-flush'>
                        <li class='list-group-item'>{$d['amount']} €</li>
                        <li class='list-group-item'>{$dt->format('d/m/Y H:i:s')}</li>
                    </ul>
                    <div class='card-body'>
                        <button type='button' class='btn btn-{$status['class']}' title='{$d['id']} cancel' title='STATUS'>{$status['status']}</button>
                    </div>
                </div>
            </div>";
        }
        echo '</div>
        </div>
        <br>';
        paginate_stripe($data, $data->data[0]->id, $data->has_more, "stripe_connect");
    else:
        echo "Rien à afficher...";
    endif;
}

function add_submenu_stripe() {
    // Ajouter un sous-menu sous "Réglages" (par exemple)
    add_submenu_page(
        'options-general.php', // Parent (ici, "Réglages")
        'stripe',  // Titre de la page du sous-menu
        'stripe',  // Nom du sous-menu affiché
        'manage_options', // Capacité nécessaire pour accéder à ce menu
        'stripe_connect', // Identifiant unique pour ce sous-menu
        'show_stripe' // Fonction qui génère le contenu de la page du sous-menu
    );
}
add_action('admin_menu', 'add_submenu_stripe');
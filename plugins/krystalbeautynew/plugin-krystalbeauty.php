<?php 

/*
    * Plugin Name: krystalbeautynew
    * Description:       Ton propre plugin.
    * Version:           1.0.0
    * Author: Nassim Amrane
    * Author URI:        https://author.example.com/
*/


require_once 'option_class.php';
require_once 'wp_customize.php';
require_once 'utils.php';
require_once 'register_tax.php';
require_once 'submenu/email.php';
require_once 'submenu/treatwell.php';
require_once 'submenu/stripe.php';
require_once 'submenu/redirection.php';
use option_class\option;

function registerSettings()
{
    // réseau
    $network = new option('index_options', [
            'network_option_fb', 
            'network_option_snap', 
            'network_option_insta', 
            'network_option_amazon', 
            'network_option_twitter',
            'network_option_youtube',
            'network_option_pinterest',
            'address',
            'network_option_number',
            'network_option_mail'
        ], 'network_option_section', 
       [
            'facebook',
            'snapchat',
            'instagram',
            'amazon',
            'twitter',
            'youtube',
            'pinterest',
            'Adresse de l\'entreprise',
            'Numéro',
            'Email'
        ],
        [
            "text",
            "text",
            "text",
            "text",
            "text",
            "text",
            "text",
            "text",
            "text",
        ]);
    $network->add_setting_sec('network_option_section', 'Paramètre des réseau', 'Personnalisation des réseau en footer', 'index_options');
    $network->do_all();

    // seo 
    $seo = new option(
        'index_options',
        [
            'keywords',
        ],
        'seo_option_section',
        [
            'Mot clé (obsolète sur chorme)',
        ]
    );
    $seo->add_setting_sec('seo_option_section', 'Paramètre du seo', 'Personnalisation du seo', 'index_options');
    $seo->do_all();

    // banner
    $banner = new option(
        'index_options',
        [
            'banner',
            'promo'
        ],
        'banner_option_section',
        [
            'Date de promotion',
            'Message promotionnel'
        ],
        ['date', 'text']
    );
    $banner->add_setting_sec('banner_option_section', 'Paramètre de la bannière', 'Personnalisation général', 'index_options');
    $banner->do_all();

    // Stripe
    $stripe = new option(
        'index_options',
        [
            'stripe',
            'stripe_id',
            'stripe_private'
        ],
        'stripe_option_section',
        [
            'Activer le paiement rapide',
            'Clé api publique stripe',
            'Clé api secrète stripe'
        ],
        ['checkbox', 'text', 'text']
    );
    $stripe->add_setting_sec('stripe_option_section', 'Paramètre de paiement', 'Personnalisation du paiment par avance', 'index_options');
    $stripe->do_all();

    $treatwell = new option(
        'index_options',
        [
            'treatwell_name',
            'treatwell_json_name',
            'treatwell_code',
            'id_calendar'
        ],
        'treatwell_option_section',
        [
            'Nom de l\'application',
            'Nom du fichier json (technique)',
            'Nom du code généré suite au fichier json (Technique)',
            'Id de l\'email à utilser'
        ],
        ['text', 'text', 'text', 'text']
    );
    $treatwell->add_setting_sec('treatwell_option_section', 'Paramètre de treatwell', 'Personnalisation des emails treatwell liés', 'index_options');
    $treatwell->do_all();
}

function render()
{
    ?>
        <h1>Gestion global du site</h1>

        <form action="options.php" method="post">
            <?php
            settings_fields('index_options');
            do_settings_sections('index_options');
            submit_button();
            ?>
        </form>
    <?php
}

function site_menu()
{
    add_options_page('Gestion du site', 'Gestion du site', 'manage_options', 'site_option', 'render', 0);
}

add_action('admin_menu', 'site_menu');
add_action('admin_init', 'registerSettings');
function enqueue_custom_admin_script_alternative()
{
    global $pagenow;
    if (isset($_GET['page']))
        $current = $_GET["page"];
    else
        $current = false;

    // Vérifie si c'est la page options.php et l'option correspondante
    if ($pagenow === 'options-general.php' && empty($current)) {
        // Enqueue le script
        wp_enqueue_script(
            'option_script',
            get_template_directory_uri() . 'assets/admin/option_script.js',
            array('jquery'),
            time(),
            true
        );
    }
    if (!empty($current))
    {
        if ($pagenow === 'options-general.php' && ($current == 'message_received' || $current == "treatwell_connect" || $current == "stripe_connect" || $current == "redirection_connect"))
        {
            wp_enqueue_style('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css', [], []);
            wp_enqueue_script('bootstrapjs', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js', ['jquery'], ['1.0.0'], true);
            wp_enqueue_script('utilitaire', get_template_directory_uri().'/assets/admin/utils-admin.js', ['jquery', 'bootstrapjs'], time(), true);
            if ($current == "message_received")
                wp_enqueue_script('message', get_template_directory_uri().'/assets/admin/msg.js', ['jquery', 'utilitaire'], time(), true);
            if ($current == "treatwell_connect")
                wp_enqueue_script('treatwell-admin', get_template_directory_uri().'/assets/admin/treatwell-admin.js', ['jquery', 'utilitaire'], time(), true);
            if ($current == "redirection_connect")
                wp_enqueue_script('redirection-admin', get_template_directory_uri().'/assets/admin/redirection-admin.js', ['jquery'], time(), true);
        }
    }
}
add_action('admin_enqueue_scripts', 'enqueue_custom_admin_script_alternative');



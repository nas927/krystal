<?php

function toast_html(): void
{
    echo "<div id='toast' class='toast text-center fw-bold align-items-center text-bg-success mt-4 me-2 border-0 position-fixed top-0 end-0' style='z-index: 1056;' role='alert' aria-live='assertive' aria-atomic='true' data-bs-delay='2000'>
        <div class='d-flex'>
            <div class='toast-body'></div>
            <button type='button' class='btn-close btn-close-white me-2 m-auto' data-bs-dismiss='toast' aria-label='Close'></button>
        </div>
    </div>";
}

function search_bar_html(string $page): void
{
    echo "
    <form method='get' action='./options-general.php'>
        <div class='input-group flex-nowrap'>
            <span class='input-group-text' id='addon-wrapping'>🔎</span>
            <input type='text' class='form-control' placeholder='Rechercher...' name='search' id='search' aria-describedby='addon-wrapping'>
            <input type='hidden' name='page' value='{$page}'>
            <button id='search_button' class='btn btn-outline-secondary' type='submit'>Rechercher</button>
            ".wp_nonce_field('utils_form_action', 'utils_form_nonce') ."
        </div>
    </form>";
}

function search(string $table, string $what)
{
    if (isset($_GET['search']) && wp_verify_nonce($_GET['utils_form_nonce'], 'utils_form_action'))
    {
        $search = htmlspecialchars(trim($_GET['search']));

        if (!empty($search))
        {
            try
            {
                global $wpdb;

                $query = $wpdb->get_results("SELECT * FROM {$table} WHERE {$what} LIKE '%{$search}%' ORDER BY id DESC LIMIT 5");
                $query['data'] = $query;
                $query['count'] = count($query);
                $query['page'] = 0;

                if (!empty($query))
                    return $query;
            }
            catch (Exception $e) {}
        }
    }
    return false;
}

function bdd_connect_wpmail(string $table, string $query): bool|array
{
    global $wpdb;

    $post_par_page = 10;
    $count_data = $wpdb->query("SELECT * FROM {$table}");
    $count_data = ceil($count_data / $post_par_page);
    if (empty($_GET["limit"]))
    {
        // if first page
        $data = $wpdb->get_results("SELECT * FROM {$table} LIMIT {$post_par_page}");
        $page = 0;
    }
    else
    {
        $page = intval(htmlspecialchars(trim($_GET["limit"])));
        $page*=10;
        if (intval($page))
        {
            try
            {
                $query = $wpdb->prepare($query, array($page+1, $page+10));
                $data = $wpdb->get_results($query);
            }
            catch (exception $e)
            {
                echo "Une erreur est survenue dans la base de donnée veuillez réessayer !";
                return false;
            }
        }
        else
        {
            echo "Veuillez surveiller l'url soyez sûr que limit est une valeur numérique !";
            return false;
        }
    }
    return [
        "page" => $page, 
        "count" => $count_data, 
        "data" => $data
    ];
}

function paginate(int $current_page, int $count_data, string $url): void
{
    $prev = $current_page > 0 ? intval($current_page / 10) - 1 : 0;
    echo "<nav aria-label='Page navigation example'>
        <ul class='pagination'>
            <li class='page-item'><a class='page-link' href='?page={$url}&limit={$prev}'>Précédent</a></li>";
    for ($i = 0 ; $i < $count_data ; $i++)
    {
        $add = $i+1;
        if (intval($current_page/10) == $i)
            echo  "<li class='page-item'><a class='page-link active' aria-current='page' href='?page={$url}&limit={$i}'>{$add}</a></li>";
        else
            echo  "<li class='page-item'><a class='page-link' href='?page={$url}&limit={$i}'>{$add}</a></li>";

    }
    $next = $current_page < $count_data ? $current_page + 1 : $count_data - 1;
    echo "<li class='page-item'><a class='page-link' href='?page={$url}&limit={$next}'>Suivant</a></li>
        </ul>
    </nav>";
}
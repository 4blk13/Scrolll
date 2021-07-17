<?php
require '../vendor/autoload.php';
require '../Views/auth.php';
require '../App/Database.php';

$db = new Database();

function requireView ($viewName) {
    global $db;
    require '../Views/header.php';
    require "../Views/{$viewName}.php";
    require '../Views/footer.php';
}

function requireGet ($viewName, $keyName, $value) {
    global $db;
    $_GET[$keyName] = $value;
    require "../Views/{$viewName}.php";
}

function requireViewGet ($viewName, $keyName, $value) {
    $_GET[$keyName] = $value;
    requireView($viewName);
}

$router = new AltoRouter();

$router->map('GET', '/', fn() => requireView('home'));
$router->map('GET|POST', '/login', fn() => requireView('login'));
$router->map('GET', '/logout', fn() => requireView('logout'));
$router->map('GET', '/dashboard.php', fn() => requireView('dashboard'));
$router->map('GET|POST', '/edit-file.php', fn() => requireView('edit-file'));
$router->map('GET', '/delete-file.php', fn() => requireView('delete-file'));
$router->map('GET|POST', '/add-categories.php', fn() => requireView('add-categories'));
$router->map('GET|POST', '/thumbnailCat.php', fn() => requireView('thumbnailCat'));

$router->map('POST', '/scriptHome.php/page/[i:idPage]', fn($idPage) => requireGet('scriptHome', 'page', $idPage));
$router->map('GET', '/thumbnailCat.php/url/[*:url]', fn($url) => requireGet('thumbnailCat', 'url', $url));
$router->map('POST', '/search-category.php/q/[*:query]', fn($query) => requireGet('search-category', 'q', $query));
$router->map('POST', '/search-categoryHome.php/q/[*:query]', fn($query) => requireGet('search-categoryHome', 'q', $query));
$router->map('GET|POST', '/add-categories.php/add/[i:resp]', fn($resp) => requireViewGet('add-categories', 'add', $resp));


$match = $router->match();
if (is_array($match)) {
    call_user_func_array($match['target'], $match['params']);
}
else {
    requireView('error404');
}

?>
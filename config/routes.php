<?php

use Core\Router;
use Controller\AppController;

return function (Router $router, AppController $controller) {

$router->get('/', [$controller, 'home']);
$router->get('/games', [$controller, 'games']);
$router->get('/random-game', [$controller, 'randomGame']);
$router->get('/games/add-new-game', [$controller, 'createNewGame']);
$router->post('/games/add-new-game', [$controller, 'createNewGame']);
$router->getRegex('#^/games/(\d+)$#', [$controller, 'gameById']);

$router->get('/not-found', [$controller, 'notFound']);

};
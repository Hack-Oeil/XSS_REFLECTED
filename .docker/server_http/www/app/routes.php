<?php

return function(\FastRoute\RouteCollector $router) {
    $router->get('/', 'App\Controller\HomeController::index');
    $router->post('/', 'App\Controller\HomeController::index');
    $router->get('/contact', 'App\Controller\HomeController::contact');
    $router->post('/contact', 'App\Controller\HomeController::contact');
    $router->get('/cookies_admin_6acd1465bf472', 'App\Controller\HomeController::cookiesAdmin');
};

<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'LandingController::index');
$routes->get('/login', 'LoginController::index');

$routes->get('/jenis-fasilitas', 'JenisFasilitasController::index');
$routes->get('/fasilitas/add','FasilitasController::add');

/* APIs */
// $routes->group('api', ['filter' => 'auth'], function ($routes) { //use auth filter later
$routes->group('api', function ($routes) {
    /* Jenis Fasilitas */
    $routes->group('jenis_fasilitas', function ($routes) {
        $routes->get('/', 'JenisFasilitasController::getAll');
        $routes->post('/', 'JenisFasilitasController::addData');
        $routes->get('(:num)', 'JenisFasilitasController::detail/$1');
        $routes->put('/', 'JenisFasilitasController::updateData');
        $routes->delete('(:num)', 'JenisFasilitasController::deleteData/$1');
    });
});
<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'LandingController::index');

$routes->get('/jenis-fasilitas', 'JenisFasilitasController::index', ['filter' => 'auth']);
$routes->get('/fasilitas/add','FasilitasController::add', ['filter' => 'auth']);

/* routes Login */
$routes->get('/login', 'LoginController::index');
$routes->post('/login', 'LoginController::authenticate');
$routes->get('/logout', 'LoginController::logout');

/* Users */
$routes->get('/users', 'UsersController::index', ['filter' => 'auth']);

/* Profile */
$routes->get('/profile', 'UsersController::profile', ['filter' => 'auth']);

/* APIs */
$routes->group('api', ['filter' => 'auth'], function ($routes) {
// $routes->group('api', function ($routes) {
    /* Jenis Fasilitas */
    $routes->group('jenis_fasilitas', function ($routes) {
        $routes->get('/', 'JenisFasilitasController::getAll');
        $routes->post('/', 'JenisFasilitasController::addData');
        $routes->get('(:num)', 'JenisFasilitasController::detail/$1');
        $routes->put('/', 'JenisFasilitasController::updateData');
        $routes->delete('(:num)', 'JenisFasilitasController::deleteData/$1');
    });

    /* Fasilitas */
    $routes->group('fasilitas', function ($routes) {
       $routes->get('/', 'FasilitasController::getAll');
       $routes->post('/', 'FasilitasController::addData'); 
    });

    /* Users */
    $routes->group('users', function ($routes) {
        $routes->get('/', 'UsersController::getAll');
        $routes->post('/', 'UsersController::addData');
        $routes->get('(:num)', 'UsersController::detail/$1');
        $routes->put('(:num)', 'UsersController::updateData/$1');
        $routes->delete('(:num)', 'UsersController::deleteData/$1');
        $routes->post('reset-password', 'UsersController::resetPassword');
    });
});
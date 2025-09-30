<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'LandingController::index');

/* Jenis Fasilitas */
$routes->get('/jenis-fasilitas', 'JenisFasilitasController::index', ['filter' => 'auth']);

/* Fasilitas */
$routes->get('/fasilitas','FasilitasController::index', ['filter' => 'auth']);
$routes->get('/fasilitas/add','FasilitasController::add', ['filter' => 'auth']);
$routes->get('/fasilitas/detail/(:num)','FasilitasController::detail/$1', ['filter' => 'auth']);

/* routes Login */
$routes->get('/login', 'LoginController::index');
$routes->post('/login', 'LoginController::authenticate');
$routes->get('/logout', 'LoginController::logout');

/* Dashboard */
$routes->get('/dashboard', 'DashboardController::index', ['filter' => 'auth']);

/* Users */
$routes->get('/users', 'UsersController::index', ['filter' => 'auth']);

/* Profile */
$routes->get('/profile', 'UsersController::profile', ['filter' => 'auth']);

/* APIs */
// grup api publik
$routes->group('api', function ($routes) {
    /* Jenis Fasilitas */
    $routes->group('jenis_fasilitas', function ($routes) {
        $routes->get('/', 'JenisFasilitasController::getAll');
    });

    $routes->get('kondisi-fasilitas', 'LandingController::kondisiFasilitas');
});

// grup api private
$routes->group('api', ['filter' => 'auth'], function ($routes) {
// $routes->group('api', function ($routes) {
    /* Jenis Fasilitas */
    $routes->group('jenis_fasilitas', function ($routes) {
        // $routes->get('/', 'JenisFasilitasController::getAll'); /* pindah ke API publik */
        $routes->post('/', 'JenisFasilitasController::addData');
        $routes->get('(:num)', 'JenisFasilitasController::detail/$1');
        $routes->post('(:num)', 'JenisFasilitasController::updateData/$1');  // update
        $routes->delete('(:num)', 'JenisFasilitasController::deleteData/$1');
    });

    /* Fasilitas */
    $routes->group('fasilitas', function ($routes) {
       $routes->get('/', 'FasilitasController::getAll');
       $routes->post('/', 'FasilitasController::addData');
       $routes->get('(:num)', 'FasilitasController::getDetail/$1');
       $routes->delete('(:num)', 'FasilitasController::deleteData/$1');
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

    /* Dashboard */
    $routes->group('dashboard', function ($routes) {
        $routes->get('markers', 'DashboardController::getDataMarkers');
    });
});
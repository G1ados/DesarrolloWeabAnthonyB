<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
// $routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Home::index');
$routes->get('/prueba', 'Home::prueba');
$routes->get('/api', 'Home::api');
$routes->get('/login', 'Home::login');
$routes->get('/testbd/(:any)', 'Home::testbd/$1');
$routes->get('/buscar/(:any)', 'Home::buscar/$1');
$routes->get('/verTodo', 'Home::verTodo');
$routes->post('/nuevoArtista', 'Home::nuevoArtista');
$routes->delete('/eliminarArtista', 'Home::eliminarArtista');
$routes->put('/actualizarArtista', 'Home::actualizarArtista');
$routes->get('datosartista', 'App\Controllers\DatosArtista::index');
$routes->post('datosartista', 'App\Controllers\DatosArtista::create');
$routes->get('datosartista/(:segment)', 'App\Controllers\DatosArtista::show/$1');
$routes->put('datosartista/(:segment)', 'App\Controllers\DatosArtista::update/$1');
$routes->delete('datosartista/(:segment)', 'App\Controllers\DatosArtista::delete/$1');
/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}

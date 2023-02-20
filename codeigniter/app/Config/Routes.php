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
$routes->get('home', 'Home::home');

$routes->group('project', static function ($routes) {
    $routes->get('/', 'Project::projects');
    $routes->get('add', 'Project::add');
    $routes->post('save', 'Project::save');
    $routes->get('(:segment)/edit/(:num)', 'Project::edit/$1/$2');
    $routes->post('(:segment)/update/(:num)', 'Project::update/$1/$2');
    $routes->get('(:segment)/dashboard', 'Project::dashboard/$1');
    $routes->post('searchProjects', 'Project::searchProjects');
});

$routes->group('collaborator', static function ($routes) {
    $routes->get('/', 'Collaborator::collaborators');
    $routes->get('add', 'Collaborator::add');
    $routes->get('(:segment)', 'Collaborator::collaborators/$1');
    $routes->post('save', 'Collaborator::save');
    $routes->get('edit/(:num)', 'Collaborator::edit/$1');
    $routes->post('update/(:num)', 'Collaborator::update/$1');
    $routes->get('view/(:num)', 'Collaborator::view/$1');
    $routes->post('searchCollaborators', 'Collaborator::searchCollaborators');
    $routes->post('assignProjects', 'Collaborator::assignProjects');
});

$routes->group('issue', static function ($routes) {
    $routes->get('(:segment)', 'Issue::issues/$1');
    $routes->get('(:segment)/issues', 'Issue::issues/$1');
    $routes->get('(:segment)/issue/(:num)', 'Issue::issues/$1/$2');
    $routes->get('(:segment)/add', 'Issue::add/$1');
    $routes->post('save', 'Issue::save');
    $routes->get('(:segment)/edit/(:num)', 'Issue::edit/$1/$2');
    $routes->post('update/(:num)', 'Issue::update/$1');
});

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

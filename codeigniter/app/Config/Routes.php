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
    $routes->get('(:segment)/dashboard', 'Project::dashboard/$1', ['filter' => 'projectaccess']);
    $routes->post('searchProjects', 'Project::searchProjects');
});

$routes->group('collaborator', static function ($routes) {
    $routes->get('view/(:num)', 'Collaborator::view/$1');
    $routes->get('(:segment)', 'Collaborator::collaborators/$1', ['filter' => 'projectaccess']);
    $routes->get('edit/(:num)', 'Collaborator::edit/$1', ['filter' => 'checkownership']);
    $routes->post('update/(:num)', 'Collaborator::update/$1', ['filter' => 'checkownership']);
    $routes->post('searchCollaborators', 'Collaborator::searchCollaborators');
    $routes->post('assignProjects', 'Collaborator::assignProjects');
});

$routes->group('issue', static function ($routes) {
    $routes->get('(:segment)', 'Issue::issues/$1');
    $routes->get('(:segment)/issues', 'Issue::issues/$1');
    $routes->get('(:segment)/issue/(:num)', 'Issue::issue/$1/$2');
    $routes->get('(:segment)/add', 'Issue::add/$1');
    $routes->post('(:segment)/save', 'Issue::save/$1');
    $routes->get('(:segment)/edit/(:num)', 'Issue::edit/$1/$2', ['filter' => 'issueownership']);
    $routes->post('(:segment)/update/(:num)', 'Issue::update/$1/$2', ['filter' => 'issueownership']);
    $routes->post('uploadIssueImage', 'Issue::uploadIssueImage');
    $routes->post('deleteIssueImage', 'Issue::deleteIssueImage');
    $routes->post('deleteIssueFile', 'Issue::deleteIssueFile');
    $routes->post('deleteIssue', 'Issue::deleteIssue');
});

$routes->group('auth', static function ($routes) {
    $routes->get('/', 'Auth::login');
    $routes->get('login', 'Auth::login');
    $routes->post('authenticate', 'Auth::authenticate');
    $routes->get('logout', 'Auth::logout');
});

$routes->group('manage', ['filter' => 'checkpermissions'], static function ($routes) {
    $routes->group('project', static function ($routes) {
        $routes->get('add', 'Project::add');
        $routes->post('save', 'Project::save');
        $routes->get('(:segment)/edit/(:num)', 'Project::edit/$1/$2');
        $routes->post('(:segment)/update/(:num)', 'Project::update/$1/$2');
    });

    $routes->group('collaborator', static function ($routes) {
        $routes->get('/', 'Collaborator::collaborators');
        $routes->get('add', 'Collaborator::add');
        $routes->get('(:segment)', 'Collaborator::collaborators/$1');
        $routes->post('save', 'Collaborator::save');
        $routes->get('edit/(:num)', 'Collaborator::edit/$1');
        $routes->post('update/(:num)', 'Collaborator::update/$1');
    });

    $routes->group('role', static function ($routes) {
        $routes->get('/', 'Role::roles');
        $routes->get('add', 'Role::add');
        $routes->post('save', 'Role::save');
        $routes->get('edit/(:num)', 'Role::edit/$1');
        $routes->post('update/(:num)', 'Role::update/$1');
    });
});

$routes->post('category/searchCategories', 'Category::searchCategories');


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

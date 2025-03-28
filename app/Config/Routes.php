<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'Auth::login');
$routes->get('home', 'Home::home', ['filter' => 'isloggedin']);

$routes->group('project', ['filter' => 'isloggedin'], static function ($routes) {
    $routes->get('/', 'Project::projects');
    $routes->get('(:segment)/dashboard', 'Project::dashboard/$1', ['filter' => ['isloggedin', 'projectaccess']]);
});

$routes->group('collaborator', ['filter' => 'isloggedin'], static function ($routes) {
    $routes->get('view/(:num)', 'Collaborator::view/$1');
    $routes->get('(:segment)', 'Collaborator::collaborators/$1', ['filter' => ['isloggedin', 'projectaccess']]);
    $routes->get('edit/(:num)', 'Collaborator::edit/$1', ['filter' => ['isloggedin', 'checkownership']]);
    $routes->post('update/(:num)', 'Collaborator::update/$1', ['filter' => 'checkownership']);
});

$routes->group('issue', ['filter' => 'isloggedin'], static function ($routes) {
    $routes->get('(:segment)', 'Issue::issues/$1');
    $routes->get('(:segment)/issues', 'Issue::issues/$1');
    $routes->get('(:segment)/issue/(:num)', 'Issue::issue/$1/$2');
    $routes->get('(:segment)/add', 'Issue::add/$1');
    $routes->post('(:segment)/save', 'Issue::save/$1');
    $routes->get('(:segment)/edit/(:num)', 'Issue::edit/$1/$2', ['filter' => ['isloggedin', 'issueownership']]);
    $routes->post('(:segment)/update/(:num)', 'Issue::update/$1/$2', ['filter' => ['isloggedin', 'issueownership']]);
});

$routes->group('auth', static function ($routes) {
    $routes->get('/', 'Auth::login');
    $routes->get('login', 'Auth::login');
    $routes->post('authenticate', 'Auth::authenticate');
    $routes->get('logout', 'Auth::logout');
});

$routes->group('file', ['filter' => 'isloggedin'], static function ($routes) {
    $routes->get('(:segment)', 'File::files/$1');
    $routes->get('(:segment)/files', 'File::files/$1');
});

$routes->group('report', ['filter' => 'isloggedin'], static function ($routes) {
    $routes->get('/', 'Report::report');
    $routes->get('(:segment)/report', 'Report::report/$1');
    $routes->get('(:segment)', 'Report::report/$1');
    $routes->post('getReport', 'Report::getReport');
});


$routes->group('calendar', ['filter' => 'isloggedin'], static function ($routes) {
    $routes->get('/', 'Calendar::calendar');
    $routes->get('(:segment)/calendar', 'Calendar::calendar/$1');
    $routes->get('(:segment)', 'Calendar::calendar/$1');
    $routes->post('getIssues', 'Calendar::getIssues');
});

$routes->group('manage', ['filter' => ['isloggedin', 'checkpermissions']], static function ($routes) {
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
    });

    $routes->group('role', static function ($routes) {
        $routes->get('/', 'Role::roles');
        $routes->get('add', 'Role::add');
        $routes->post('save', 'Role::save');
        $routes->get('edit/(:num)', 'Role::edit/$1');
        $routes->post('update/(:num)', 'Role::update/$1');
    });
});

$routes->group('v1', ['filter' => 'isloggedin'], static function($routes) {
    $routes->group('project', static function($routes) {
        $routes->post('searchProjects', 'Project::searchProjects');
    });

    $routes->group('collaborator', static function($routes) {
        $routes->post('searchCollaborators', 'Collaborator::searchCollaborators');
        $routes->post('assignProjects', 'Collaborator::assignProjects');
        $routes->delete('removeCollaboratorFromProject', 'Collaborator::removeCollaboratorFromProject');
        $routes->delete('deleteCollaborator', 'Collaborator::deleteCollaborator');
    });

    $routes->group('issue', static function($routes) {
        $routes->post('uploadIssueImage', 'Issue::uploadIssueImage');
        $routes->post('assignIssue', 'Issue::assignIssue');
        $routes->delete('deleteIssueImage', 'Issue::deleteIssueImage');
        $routes->delete('deleteIssueFile', 'Issue::deleteIssueFile');
        $routes->delete('deleteIssue', 'Issue::deleteIssue');
    });

    $routes->group('report', ['filter' => 'isloggedin'], static function ($routes) {
        $routes->get('getReport', 'Report::getReport');
    });
    
    $routes->group('calendar', ['filter' => 'isloggedin'], static function ($routes) {
        $routes->get('getIssues', 'Calendar::getIssues');
    });
    
    $routes->group('role', ['isloggedin', 'checkpermissions'], static function ($routes) {
        $routes->delete('deleteRole', 'Role::deleteRole');
    });
    
    $routes->post('category/searchCategories', 'Category::searchCategories');
});

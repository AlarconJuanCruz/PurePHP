<?php

/**
 * Pure PHP — Application Routes
 */

// ── Installer (always available, gate is in index.php) ─────────────────────
$router->get('/install',          'InstallController@index');
$router->get('/install/database', 'InstallController@database');
$router->post('/install/test-db', 'InstallController@testDb');
$router->get('/install/account',  'InstallController@account');
$router->post('/install/account', 'InstallController@saveDb');   // DB fields → session → show account form
$router->post('/install/run',     'InstallController@run');
$router->get('/install/complete', 'InstallController@complete');

// ── Dashboard ──────────────────────────────────────────────────────────────
$router->get('/',              'HomeController@index')->name('home');
$router->get('/api/stats',     'HomeController@stats')->name('api.stats');

// ── Users ──────────────────────────────────────────────────────────────────
$router->get('/users',                  'UsersController@index')->name('users.index');
$router->post('/users',                 'UsersController@store')->name('users.store');
$router->post('/users/{id}',            'UsersController@update')->name('users.update');
$router->post('/users/{id}/delete',     'UsersController@destroy')->name('users.destroy');

// ── Roles ──────────────────────────────────────────────────────────────────
$router->get('/roles',                  'RolesController@index')->name('roles.index');
$router->post('/roles',                 'RolesController@store')->name('roles.store');
$router->post('/roles/{id}',            'RolesController@update')->name('roles.update');
$router->post('/roles/{id}/delete',     'RolesController@destroy')->name('roles.destroy');

// ── UI Components ──────────────────────────────────────────────────────────
$router->get('/components',    'ComponentsController@index')->name('components');

// ── Documentation ──────────────────────────────────────────────────────────
$router->get('/docs',          'DocsController@index')->name('docs');

// ── Auth ───────────────────────────────────────────────────────────────────
$router->get('/login',         'AuthController@showLogin')->name('login');
$router->post('/login',        'AuthController@login')->name('login.post');
$router->get('/logout',        'AuthController@logout')->name('logout');

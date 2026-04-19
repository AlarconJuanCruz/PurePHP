<?php
/**
 * Pure PHP — Application Routes
 */

// ── Installer ──────────────────────────────────────────────────────────────
$router->get('/install',          'InstallController@index');
$router->get('/install/database', 'InstallController@database');
$router->post('/install/test-db', 'InstallController@testDb');
$router->get('/install/account',  'InstallController@account');
$router->post('/install/account', 'InstallController@saveDb');
$router->post('/install/run',     'InstallController@run');
$router->get('/install/complete', 'InstallController@complete');

// ── Language switcher ──────────────────────────────────────────────────────
$router->get('/lang/{locale}',    'LangController@switch');

// ── PUBLIC site (news frontend) ────────────────────────────────────────────
$router->get('/portal',                    'PublicController@home');
$router->get('/portal/nota/{slug}',        'PublicController@nota');
$router->get('/portal/categoria/{slug}',   'PublicController@categoria');

// ── Admin: Dashboard ───────────────────────────────────────────────────────
$router->get('/',                          'HomeController@index')->name('home');
$router->get('/api/stats',                 'HomeController@stats')->name('api.stats');

// ── Admin: Users ───────────────────────────────────────────────────────────
$router->get('/users',                     'UsersController@index')->name('users.index');
$router->post('/users',                    'UsersController@store')->name('users.store');
$router->post('/users/{id}',               'UsersController@update')->name('users.update');
$router->post('/users/{id}/delete',        'UsersController@destroy')->name('users.destroy');

// ── Admin: Roles ───────────────────────────────────────────────────────────
$router->get('/roles',                     'RolesController@index')->name('roles.index');
$router->post('/roles',                    'RolesController@store')->name('roles.store');
$router->post('/roles/{id}',               'RolesController@update')->name('roles.update');
$router->post('/roles/{id}/delete',        'RolesController@destroy')->name('roles.destroy');

// ── Admin: Notas (Articles) ────────────────────────────────────────────────
$router->get('/notas',                     'NotasController@index')->name('notas.index');
$router->get('/notas/crear',               'NotasController@create')->name('notas.create');
$router->post('/notas',                    'NotasController@store')->name('notas.store');
$router->get('/notas/{id}/edit',           'NotasController@edit')->name('notas.edit');
$router->post('/notas/{id}',               'NotasController@update')->name('notas.update');
$router->post('/notas/{id}/delete',        'NotasController@destroy')->name('notas.destroy');
$router->post('/notas/{id}/upload-image',  'NotasController@uploadImage');
$router->post('/notas/{id}/delete-image',  'NotasController@deleteImage');

// ── Admin: Categorias ──────────────────────────────────────────────────────
$router->get('/categorias',                'CategoriasController@index')->name('categorias.index');
$router->post('/categorias',               'CategoriasController@store')->name('categorias.store');
$router->post('/categorias/{id}',          'CategoriasController@update')->name('categorias.update');
$router->post('/categorias/{id}/delete',   'CategoriasController@destroy')->name('categorias.destroy');

// ── Admin: UI Components ───────────────────────────────────────────────────
$router->get('/components',                'ComponentsController@index')->name('components');

// ── Admin: Documentation ───────────────────────────────────────────────────
$router->get('/docs',                      'DocsController@index')->name('docs');

// ── Auth ───────────────────────────────────────────────────────────────────
$router->get('/login',                     'AuthController@showLogin')->name('login');
$router->post('/login',                    'AuthController@login')->name('login.post');
$router->get('/logout',                    'AuthController@logout')->name('logout');

// ── Settings ───────────────────────────────────────────────────────────────
$router->get('/settings',  'SettingsController@index')->name('settings');
$router->post('/settings', 'SettingsController@update')->name('settings.update');

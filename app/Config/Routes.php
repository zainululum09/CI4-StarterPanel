<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Auth::index');
$routes->get('login', 'Auth::index');
$routes->post('login', 'Auth::index');
$routes->get('logout', 'Auth::logout');
$routes->get('blocked', 'Auth::forbiddenPage');
$routes->get('register', 'Auth::register');
$routes->post('register', 'Auth::registration');

$routes->get('dashboard', 'Home::index');

// Setting Routes
$routes->group('users', static function ($routes) {
    $routes->get('/', 'Settings::users');
    $routes->post('create-role', 'Settings::createRole');
    $routes->post('update-role', 'Settings::updateRole');
    $routes->delete('delete-role/(:num)', 'Settings::deleteRole/$1');

    $routes->get('role-access', 'Settings::roleAccess');
    $routes->post('create-user', 'Settings::createUser');
    $routes->post('update-user', 'Settings::updateUser');
    $routes->delete('delete-user/(:num)', 'Settings::deleteUser/$1');

    $routes->post('change-menu-permission', 'Settings::changeMenuPermission');
    $routes->post('change-menu-category-permission', 'Settings::changeMenuCategoryPermission');
    $routes->post('change-submenu-permission', 'Settings::changeSubMenuPermission');
});

$routes->group('menu-management', static function ($routes) {
    $routes->get('/', 'Settings::menuManagement');
    $routes->post('create-menu-category', 'Settings::createMenuCategory');
    $routes->post('create-menu', 'Settings::createMenu');
    $routes->post('create-submenu', 'Settings::createSubMenu');
});
$routes->get('menu','Menu::index');

// Halaman utama
$routes->get('tarik-dapo', 'Tarik_dapo::index');

// Test koneksi
$routes->get('tarik-dapo/dapodik/(:segment)', 'Tarik_dapo::callDapodik/$1');
// Test koneksi
$routes->get('tarik-dapo/test-connection', 'Tarik_dapo::testConnection');

// Cek data dari API
$routes->get('tarik-dapo/checkData/(:segment)', 'Tarik_dapo::checkData/$1');

// Simpan data ke database
$routes->post('tarik-dapo/saveData/(:segment)', 'Tarik_dapo::saveData/$1');

// Hapus data dari database
$routes->delete('tarik-dapo/delete/(:segment)', 'Tarik_dapo::deleteData/$1');

// Konfigurasi
$routes->post('tarik-dapo/config/save', 'Tarik_dapo::saveConfig');
$routes->get('tarik-dapo/config', 'Tarik_dapo::getConfig');

// Statistik
$routes->get('tarik-dapo/statistics', 'Tarik_dapo::getStatistics');
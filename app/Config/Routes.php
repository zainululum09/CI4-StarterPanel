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
$routes->get('dapodik/siswa','Dapodik::siswa');

// Detail Siswa
$routes->get('dapodik/detail_siswa/(:segment)', 'Dapodik::detailSiswa/$1');
$routes->get('dapodik/get_form/(:alpha)/(:num)', 'Dapodik::getForm/$1/$2');
$routes->get('dapodik/getProvinces', 'Dapodik::getProvinces');
$routes->get('dapodik/getRegencies/(:segment)', 'Dapodik::getRegencies/$1');
$routes->get('dapodik/getDistricts/(:segment)', 'Dapodik::getDistricts/$1');
$routes->get('dapodik/getVillages/(:segment)', 'Dapodik::getVillages/$1');
$routes->post('dapodik/update_data', 'Dapodik::update_data');
$routes->get('dapodik/ptk','Dapodik::ptk');
$routes->get('dapodik/sekolah','Dapodik::sekolah');
$routes->post('dapodik/updateSekolah','Dapodik::updateSekolah');
$routes->get('dapodik/rombel','Dapodik::rombel');

//get File
$routes->get('writable/uploads/(:segment)/(:any)', 'Dapodik::showImage/$1/$2');

//hapus data
$routes->post('dapodik/deleteSiswa/(:segment)', 'Dapodik::deleteSiswa/$1');
$routes->post('dapodik/cariSekolah', 'Dapodik::cariSekolah');
$routes->get('dapodik/cariSekolah', 'Dapodik::cariSekolah');

//nilai siswa
$routes->get('nilai_siswa','Nilai_siswa::index');
$routes->get('nilai_siswa/daftar_nilai/(:segment)','Nilai_siswa::daftar_nilai/$1');
$routes->post('nilai_siswa/upload', 'Nilai_siswa::upload');
$routes->get('spmb','Spmb::index');

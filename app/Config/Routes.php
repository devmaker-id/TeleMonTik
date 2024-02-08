<?php

use CodeIgniter\Router\RouteCollection;
//api roite resource
//$routes->resource('hotspot');
//$routes->resource('hotspotprofile');
$routes->get('/hotspot', 'Hotspot::index');
$routes->get('/hotspot/(:any)', 'Hotspot::show/$1');
$routes->post('/hotspot', 'Hotspot::create');

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::about');
$routes->post('/simpan_data', 'Home::save_mikrotik');
$routes->get('/(:num)', 'Home::index/$1');
$routes->post('/requestdata', 'Home::chek_mikrotik');
$routes->post('/webhooktelegram', 'Telegram::index');

//debug route
$routes->get('/webhooktelegram', 'Telegram::debug');

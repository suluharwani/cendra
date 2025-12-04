<?php

// use CodeIgniter\Router\RouteCollection;


use App\Controllers\Home;

$routes->get('/', [Home::class, 'index']);
$routes->get('layanan', [Home::class, 'layanan']);
$routes->get('layanan/(:segment)', [Home::class, 'layanan/$1']);
$routes->get('produk', [Home::class, 'produk']);
$routes->get('produk/(:segment)', [Home::class, 'produk/$1']);
$routes->get('tentang', [Home::class, 'tentang']);
$routes->get('kontak', [Home::class, 'kontak']);
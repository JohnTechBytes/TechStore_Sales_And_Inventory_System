<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and environment
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Auth');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// ==================== AUTHENTICATION (Public) ====================
// Auth
// Auth
$routes->get('/', 'Auth::index');
$routes->get('/login', 'Auth::index');
$routes->post('/login', 'Auth::auth');
$routes->post('/auth', 'Auth::auth');      // if your form uses /auth
$routes->get('/register', 'Auth::register');
$routes->post('/register', 'Auth::doRegister');
$routes->get('/logout', 'Auth::logout');

// ==================== PROTECTED ROUTES (Logged in users) ====================
$routes->group('', ['filter' => 'auth'], function($routes) {
    
    // Dashboard
$routes->get('/dashboard', 'Dashboard::index');
    
    // Sales (POS, Cart, History, Receipt)
$routes->get('/sales/pos', 'Sales::pos');
$routes->post('/sales/addToCart', 'Sales::addToCart');
$routes->get('/sales/getCart', 'Sales::getCart');
$routes->post('/sales/updateCart', 'Sales::updateCart');
$routes->get('/sales/removeFromCart/(:num)', 'Sales::removeFromCart/$1');
$routes->post('/sales/checkout', 'Sales::checkout');
$routes->get('/sales/receipt/(:num)', 'Sales::receipt/$1');
$routes->get('/sales/history', 'Sales::history');
$routes->get('/pos', 'Sales::pos');   // optional, but keep if you use /pos
$routes->post('/sales/syncCart', 'Sales::syncCart');
$routes->get('sales/searchProducts', 'Sales::searchProducts');

    
    // Reports (accessible by both admin and staff)
 $routes->get('/reports', 'Reports::index');
    
    // ========== ADMIN ONLY ROUTES ==========
 $routes->group('', ['filter' => 'auth:admin'], function($routes) {
        
 
// Products
$routes->get('/products', 'Products::index');
$routes->get('/products/create', 'Products::create');
$routes->post('/products/store', 'Products::store');
$routes->get('/products/edit/(:num)', 'Products::edit/$1');
$routes->put('/products/update/(:num)', 'Products::update/$1');   // <-- ADD THIS
$routes->delete('/products/delete/(:num)', 'Products::delete/$1');
$routes->post('/sales/processCheckout', 'Sales::processCheckout');
        
        // Category Management
        $routes->get('/categories', 'Categories::index');
        $routes->post('/categories/store', 'Categories::store');
        $routes->post('/categories/update/(:num)', 'Categories::update/$1');
        $routes->get('/categories/delete/(:num)', 'Categories::delete/$1');
        
        // Stock Management
        $routes->get('/stock', 'Stock::index');
        $routes->post('/stock/add', 'Stock::addStock');
        
        // User Management
        $routes->get('/users', 'Users::index');
        $routes->post('/users/store', 'Users::store');
       $routes->post('/users/update/(:num)', 'Users::update/$1');
        $routes->put('/users/update/(:num)', 'Users::update/$1');
        $routes->get('/users/delete/(:num)', 'Users::delete/$1');

        // Activity Logs (Audit Trail)
$routes->get('/audit', 'AuditController::index');
$routes->get('/audit/getLogs', 'AuditController::getLogs');
    });
});

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 */


<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'AuthController::login');
$routes->get('/test-db', 'Home::testDb');

// Auth
$routes->get('auth/login', 'AuthController::login');
$routes->get('auth/login/admin', 'AuthController::loginAdmin');
$routes->get('auth/login/client', 'AuthController::loginClient');
$routes->post('auth/login', 'AuthController::loginPost');
$routes->post('auth/login/admin', 'AuthController::loginAdminPost');
$routes->post('auth/login/client', 'AuthController::loginClientPost');
$routes->get('auth/logout', 'AuthController::logout');
$routes->get('auth/mot-de-passe-oublie', 'AuthController::motDePasseOublie');
$routes->post('auth/mot-de-passe-oublie', 'AuthController::motDePasseOubliePost');

// Mobile money
$routes->get('mobile-money', 'MobileMoneyController::index');
$routes->get('mobile-money/login', 'MobileMoneyController::login');
$routes->post('mobile-money/login', 'MobileMoneyController::loginPost');
$routes->get('mobile-money/logout', 'MobileMoneyController::logout');
$routes->get('mobile-money/client', 'MobileMoneyController::clientDashboard');
$routes->get('mobile-money/operator', 'MobileMoneyController::operatorDashboard');
$routes->post('mobile-money/deposit', 'MobileMoneyController::deposit');
$routes->post('mobile-money/withdraw', 'MobileMoneyController::withdraw');
$routes->post('mobile-money/transfer', 'MobileMoneyController::transfer');
$routes->post('mobile-money/operator/prefixes', 'MobileMoneyController::savePrefix');
$routes->get('mobile-money/operator/prefixes/delete/(:num)', 'MobileMoneyController::deletePrefix/$1');
$routes->post('mobile-money/operator/operations', 'MobileMoneyController::saveOperation');
$routes->get('mobile-money/operator/operations/delete/(:num)', 'MobileMoneyController::deleteOperation/$1');
$routes->post('mobile-money/operator/fees', 'MobileMoneyController::saveFee');
$routes->get('mobile-money/operator/fees/delete/(:num)', 'MobileMoneyController::deleteFee/$1');

$routes->post('mobile-money/operator/prefixes', 'MobileMoneyController::savePrefix');
$routes->post('mobile-money/operator/commissions', 'MobileMoneyController::saveCommission');
$routes->post('mobile-money/transfer-multiple', 'MobileMoneyController::transferMultiple');
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
$routes->get('mobile-money/client', 'ClientMobileMoneyController::clientDashboard');
$routes->get('mobile-money/client/history', 'ClientMobileMoneyController::clientHistory');
$routes->get('mobile-money/client/transfers', 'ClientMobileMoneyController::clientTransfers');
$routes->get('mobile-money/operator', 'OperatorMobileMoneyController::operatorDashboard');
$routes->get('mobile-money/operator/prefixes', 'OperatorMobileMoneyController::operatorPrefixes');
$routes->get('mobile-money/operator/operations', 'OperatorMobileMoneyController::operatorOperations');
$routes->get('mobile-money/operator/fees', 'OperatorMobileMoneyController::operatorFees');
$routes->get('mobile-money/operator/clients', 'OperatorMobileMoneyController::operatorClients');
$routes->get('mobile-money/operator/transactions', 'OperatorMobileMoneyController::operatorTransactions');
$routes->post('mobile-money/deposit', 'ClientMobileMoneyController::deposit');
$routes->post('mobile-money/withdraw', 'ClientMobileMoneyController::withdraw');
$routes->post('mobile-money/transfer', 'ClientMobileMoneyController::transfer');
$routes->post('mobile-money/operator/prefixes', 'OperatorMobileMoneyController::savePrefix');
$routes->get('mobile-money/operator/prefixes/delete/(:num)', 'OperatorMobileMoneyController::deletePrefix/$1');
$routes->post('mobile-money/operator/operations', 'OperatorMobileMoneyController::saveOperation');
$routes->get('mobile-money/operator/operations/delete/(:num)', 'OperatorMobileMoneyController::deleteOperation/$1');
$routes->post('mobile-money/operator/fees', 'OperatorMobileMoneyController::saveFee');
$routes->get('mobile-money/operator/fees/delete/(:num)', 'OperatorMobileMoneyController::deleteFee/$1');
$routes->post('mobile-money/operator/commissions', 'OperatorMobileMoneyController::saveCommission');
$routes->post('mobile-money/transfer-multiple', 'ClientMobileMoneyController::transferMultiple');
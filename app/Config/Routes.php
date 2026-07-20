<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// ─── Page d'accueil → login ────────────────────────────────────────────────
$routes->get('/', function () {
    return redirect()->to(base_url('login'));
});

// ─── Auth (accessible à tous) ─────────────────────────────────────────────
$routes->get('login', 'AuthController::login');
$routes->post('login', 'AuthController::loginPost');
$routes->get('logout', 'AuthController::logout');

$routes->get('auth/login', 'AuthController::login');
$routes->post('auth/login', 'AuthController::loginPost');
$routes->get('auth/logout', 'AuthController::logout');
$routes->get('auth/mot-de-passe-oublie', 'AuthController::motDePasseOublie');
$routes->post('auth/mot-de-passe-oublie', 'AuthController::motDePasseOubliePost');

$routes->get('auth/changer-mot-de-passe/nouveau', 'AuthController::changerMotDePasseNouveau');
$routes->post('auth/changer-mot-de-passe/nouveau', 'AuthController::changerMotDePasseNouveauPost');

$routes->get('auth/register-admin-secret-gate', 'AuthController::registerAdmin');
$routes->post('auth/register-admin-secret-gate', 'AuthController::registerAdminPost');

// ─── Opérateur ────────────────────────────────────────────────────
$routes->group('operator', ['filter' => 'auth:admin,agent_commercial,magasinier,livreur'], function ($routes) {
    $routes->get('prefixes', 'OperatorController::prefixes');
    $routes->post('ajouter-prefixe', 'OperatorController::ajouterPrefixe');
    $routes->get('supprimer-prefixe/(:num)', 'OperatorController::supprimerPrefixe/$1');

    $routes->get('types', 'OperatorController::types');
    $routes->post('ajouter-type', 'OperatorController::ajouterType');
    $routes->get('modifier-type/(:num)', 'OperatorController::modifierType/$1');
    $routes->post('update-type/(:num)', 'OperatorController::updateType/$1');
    $routes->get('supprimer-type/(:num)', 'OperatorController::supprimerType/$1');

    $routes->get('baremes', 'OperatorController::baremes');
    $routes->post('ajouter-bareme', 'OperatorController::ajouterBareme');
    $routes->get('supprimer-bareme/(:num)', 'OperatorController::supprimerBareme/$1');

    $routes->get('gains', 'OperatorController::gains');
    $routes->get('comptes', 'OperatorController::comptesClients');
});
<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// ─── Page d'accueil → login ────────────────────────────────────────────────
$routes->get('/', function () {
    return redirect()->to(base_url('auth/login'));
});

// ─── Auth (accessible à tous) ─────────────────────────────────────────────
$routes->get('auth/login',                   'AuthController::login');
$routes->post('auth/login',                  'AuthController::loginPost');
$routes->get('auth/logout',                  'AuthController::logout');
$routes->get('auth/mot-de-passe-oublie',     'AuthController::motDePasseOublie');
$routes->post('auth/mot-de-passe-oublie',    'AuthController::motDePasseOubliePost');

$routes->get('auth/changer-mot-de-passe/nouveau',  'AuthController::changerMotDePasseNouveau');
$routes->post('auth/changer-mot-de-passe/nouveau', 'AuthController::changerMotDePasseNouveauPost');

$routes->get('auth/register-admin-secret-gate',  'AuthController::registerAdmin');
$routes->post('auth/register-admin-secret-gate', 'AuthController::registerAdminPost');

// ─── Opérateur ────────────────────────────────────────────────────
$routes->get('operator/prefixes', 'OperatorController::prefixes');
$routes->post('operator/ajouter-prefixe', 'OperatorController::ajouterPrefixe');
$routes->get('operator/supprimer-prefixe/(:num)', 'OperatorController::supprimerPrefixe/$1');

$routes->get('operator/types', 'OperatorController::types');
$routes->post('operator/ajouter-type', 'OperatorController::ajouterType');
$routes->get('operator/modifier-type/(:num)', 'OperatorController::modifierType/$1');
$routes->post('operator/update-type/(:num)', 'OperatorController::updateType/$1');
$routes->get('operator/supprimer-type/(:num)', 'OperatorController::supprimerType/$1');

$routes->get('operator/baremes', 'OperatorController::baremes');
$routes->post('operator/ajouter-bareme', 'OperatorController::ajouterBareme');
$routes->get('operator/supprimer-bareme/(:num)', 'OperatorController::supprimerBareme/$1');

$routes->get('operator/gains', 'OperatorController::gains');
$routes->get('operator/comptes', 'OperatorController::comptesClients');
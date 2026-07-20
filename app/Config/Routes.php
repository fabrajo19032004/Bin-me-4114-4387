<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/test-db', 'Home::testDb');
// ─── Page d'accueil → login ────────────────────────────────────────────────
// $routes->get('/', function () {
//     return redirect()->to(base_url('auth/login'));
// });

// ─── Auth (accessible à tous) ─────────────────────────────────────────────
$routes->get('auth/login',                   'AuthController::login');
$routes->post('auth/login',                  'AuthController::loginPost');
$routes->get('auth/logout',                  'AuthController::logout');
$routes->get('auth/mot-de-passe-oublie',     'AuthController::motDePasseOublie');
$routes->post('auth/mot-de-passe-oublie',    'AuthController::motDePasseOubliePost');

// $routes->get('auth/changer-mot-de-passe/nouveau',  'AuthController::changerMotDePasseNouveau');
// $routes->post('auth/changer-mot-de-passe/nouveau', 'AuthController::changerMotDePasseNouveauPost');

// $routes->get('auth/register-admin-secret-gate',  'AuthController::registerAdmin');
// $routes->post('auth/register-admin-secret-gate', 'AuthController::registerAdminPost');

// // ─── Admin (filtre role = admin) ───────────────────────────────────────────
// $routes->group('admin', ['filter' => 'auth:admin'], function ($routes) {
//     $routes->get('dashboard',        'AdminController::dashboard');    
//     $routes->get('categories', 'AdminController::categories');
//     $routes->get('produits', 'AdminController::produits');
//     $routes->get('entrees_stock', 'AdminController::entreesStock');
//     $routes->get('sorties_stock', 'AdminController::sortiesStock');
//     $routes->get('sorties_stock/export', 'AdminController::exportSorties');
//     $routes->get('historique', 'AdminController::historique');
//     $routes->get('utilisateurs',     'AdminController::utilisateurs');
//     $routes->post('utilisateurs/ajouter', 'AdminController::ajouterUtilisateur');
//     $routes->get('utilisateurs/supprimer/(:num)', 'AdminController::supprimerUtilisateur/$1');
//     $routes->get('utilisateurs/reinitialiser-mdp/(:num)', 'AdminController::reinitialiserMotDePasse/$1');
//     $routes->get('testClient', 'AdminController::testClient');
//     $routes->get('stocks', 'AdminController::stocks');
//     $routes->get('mouvements',       'AdminController::mouvements');
//     $routes->get('entrees-sorties',  'AdminController::entreesSorties');

//     $routes->get('depenses',         'AdminController::depenses');
//     $routes->get('depenses/transactions-filtrees', 'AdminController::getTransactionsFiltrees');
//     $routes->get('depenses/historique-depense', 'AdminController::historiqueDepenses');
//     $routes->get('depenses/validation-depense', 'AdminController::validationDepense');
//     $routes->post('depenses/modifier-transaction', 'AdminController::modifierTransaction');
//     $routes->post('depenses/approuver-transaction', 'AdminController::approuverTransaction');
//     $routes->post('depenses/rejeter-transaction', 'AdminController::rejeterTransaction');

//     // ── Packs ──
//     $routes->get('packs',                'AdminController::packs');
//     $routes->get('packs/creer',          'AdminController::packsCreer');
//     $routes->post('packs/enregistrer',   'AdminController::packsStore');
//     $routes->get('packs/annuler',        'AdminController::packsAnnuler');

//     // ── Clients ──
//     $routes->get('clients', 'AdminController::clients');

//     // Reservations
//     $routes->get('reservations', 'ReservationController::reservations/true');
//     $routes->get('reservations/confirmerRes', 'ReservationController::confirmerRes/true');
//     $routes->get('reservations/annulerRes', 'ReservationController::annulerRes/true');
//     $routes->get('ajout_reservation', 'ReservationController::ajout_reservation/true');
//     $routes->get('ajout_reservation/ajout', 'ReservationController::ajout/true');
//     $routes->get('reservations/filtrerRes', 'ReservationController::filtrerRes/true');

//     // Paiements
//     $routes->get('paiements', 'PaiementController::index/true');
//     $routes->get('paiements/validation', 'PaiementController::index/true');
//     $routes->post('paiements/valider', 'PaiementController::valider/true');
//     $routes->get('paiements/historique', 'PaiementController::historique/true');
//     $routes->get('paiements/modifier/(:num)', 'PaiementController::modifier/$1/true');
//     $routes->post('paiements/update/(:num)', 'PaiementController::update/$1/true');
//     $routes->post('paiements/supprimer/(:num)', 'PaiementController::supprimer/$1/true');
//     $routes->get('paiements/ticket/(:num)', 'PaiementController::ticket/$1/true');
//     $routes->get('paiements/qrcode/(:num)', 'PaiementController::qrcode/$1/true');
// });

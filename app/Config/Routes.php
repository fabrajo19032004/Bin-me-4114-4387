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

// ─── Mobile Money ───────────────────────────────────────────────────────
$routes->get('mobile-money/login', 'MobileMoneyController::login');
$routes->post('mobile-money/login', 'MobileMoneyController::loginPost');
$routes->get('mobile-money/logout', 'MobileMoneyController::logout');
$routes->get('mobile-money', 'MobileMoneyController::index');
$routes->get('mobile-money/client', 'MobileMoneyController::clientDashboard');
$routes->get('mobile-money/operator', 'MobileMoneyController::operatorDashboard');
$routes->post('mobile-money/deposit', 'MobileMoneyController::deposit');
$routes->post('mobile-money/withdraw', 'MobileMoneyController::withdraw');
$routes->post('mobile-money/transfer', 'MobileMoneyController::transfer');

$routes->get('auth/changer-mot-de-passe/nouveau',  'AuthController::changerMotDePasseNouveau');
$routes->post('auth/changer-mot-de-passe/nouveau', 'AuthController::changerMotDePasseNouveauPost');

$routes->get('auth/register-admin-secret-gate',  'AuthController::registerAdmin');
$routes->post('auth/register-admin-secret-gate', 'AuthController::registerAdminPost');

// ─── Admin (filtre role = admin) ───────────────────────────────────────────
$routes->group('admin', ['filter' => 'auth:admin'], function ($routes) {
    $routes->get('dashboard',        'AdminController::dashboard');    
    $routes->get('categories', 'AdminController::categories');
    $routes->get('produits', 'AdminController::produits');
    $routes->get('entrees_stock', 'AdminController::entreesStock');
    $routes->get('sorties_stock', 'AdminController::sortiesStock');
    $routes->get('sorties_stock/export', 'AdminController::exportSorties');
    $routes->get('historique', 'AdminController::historique');
    $routes->get('utilisateurs',     'AdminController::utilisateurs');
    $routes->post('utilisateurs/ajouter', 'AdminController::ajouterUtilisateur');
    $routes->get('utilisateurs/supprimer/(:num)', 'AdminController::supprimerUtilisateur/$1');
    $routes->get('utilisateurs/reinitialiser-mdp/(:num)', 'AdminController::reinitialiserMotDePasse/$1');
    $routes->get('testClient', 'AdminController::testClient');
    $routes->get('stocks', 'AdminController::stocks');
    $routes->get('mouvements',       'AdminController::mouvements');
    $routes->get('entrees-sorties',  'AdminController::entreesSorties');

    $routes->get('depenses',         'AdminController::depenses');
    $routes->get('depenses/transactions-filtrees', 'AdminController::getTransactionsFiltrees');
    $routes->get('depenses/historique-depense', 'AdminController::historiqueDepenses');
    $routes->get('depenses/validation-depense', 'AdminController::validationDepense');
    $routes->post('depenses/modifier-transaction', 'AdminController::modifierTransaction');
    $routes->post('depenses/approuver-transaction', 'AdminController::approuverTransaction');
    $routes->post('depenses/rejeter-transaction', 'AdminController::rejeterTransaction');

    // ── Packs ──
    $routes->get('packs',                'AdminController::packs');
    $routes->get('packs/creer',          'AdminController::packsCreer');
    $routes->post('packs/enregistrer',   'AdminController::packsStore');
    $routes->get('packs/annuler',        'AdminController::packsAnnuler');

    // ── Clients ──
    $routes->get('clients', 'AdminController::clients');

    // Reservations
    $routes->get('reservations', 'ReservationController::reservations/true');
    $routes->get('reservations/confirmerRes', 'ReservationController::confirmerRes/true');
    $routes->get('reservations/annulerRes', 'ReservationController::annulerRes/true');
    $routes->get('ajout_reservation', 'ReservationController::ajout_reservation/true');
    $routes->get('ajout_reservation/ajout', 'ReservationController::ajout/true');
    $routes->get('reservations/filtrerRes', 'ReservationController::filtrerRes/true');

    // Paiements
    $routes->get('paiements', 'PaiementController::index/true');
    $routes->get('paiements/validation', 'PaiementController::index/true');
    $routes->post('paiements/valider', 'PaiementController::valider/true');
    $routes->get('paiements/historique', 'PaiementController::historique/true');
    $routes->get('paiements/modifier/(:num)', 'PaiementController::modifier/$1/true');
    $routes->post('paiements/update/(:num)', 'PaiementController::update/$1/true');
    $routes->post('paiements/supprimer/(:num)', 'PaiementController::supprimer/$1/true');
    $routes->get('paiements/ticket/(:num)', 'PaiementController::ticket/$1/true');
    $routes->get('paiements/qrcode/(:num)', 'PaiementController::qrcode/$1/true');
});

// ─── Agent commercial + Admin ─────────────────────────────────────────────
$routes->group('agent', ['filter' => 'auth:agent_commercial,admin'], function ($routes) {
    $routes->get('dashboard', 'AgentController::dashboard');
    $routes->get('clients', 'AgentController::clients');
    
    // Reservations
    $routes->get('reservations', 'ReservationController::reservations/false');
    $routes->get('reservations/confirmerRes', 'ReservationController::confirmerRes/false');
    $routes->get('reservations/annulerRes', 'ReservationController::annulerRes/false');
    $routes->get('ajout_reservation', 'ReservationController::ajout_reservation/false');
    $routes->get('ajout_reservation/ajout', 'ReservationController::ajout/false');
    $routes->get('reservations/filtrerRes', 'ReservationController::filtrerRes/false');

    // Paiements
    $routes->get('paiements', 'PaiementController::index/false');
    $routes->get('paiements/validation', 'PaiementController::index/false');
    $routes->post('paiements/valider', 'PaiementController::valider/false');
    $routes->get('paiements/historique', 'PaiementController::historique/false');
    $routes->get('paiements/modifier/(:num)', 'PaiementController::modifier/$1/false');
    $routes->post('paiements/update/(:num)', 'PaiementController::update/$1/false');
    $routes->post('paiements/supprimer/(:num)', 'PaiementController::supprimer/$1/false');
    $routes->get('paiements/ticket/(:num)', 'PaiementController::ticket/$1/false');
    $routes->get('paiements/qrcode/(:num)', 'PaiementController::qrcode/$1/false');

    $routes->get('packs', 'PackController::index');
    $routes->get('packs/detail/(:num)', 'PackController::detail/$1');
    $routes->get('packs/creer', 'PackController::create');
    $routes->post('packs/store', 'PackController::store');
    $routes->get('packs/annuler', 'PackController::cancel');
    $routes->get('packs/modifier/(:num)', 'PackController::modifier/$1');
    $routes->post('packs/modifier/(:num)', 'PackController::modifierPost/$1');
    $routes->get('packs/delete/(:num)', 'PackController::delete/$1');
    $routes->get('packs/archives', 'PackController::archives');
    $routes->get('packs/restore/(:num)', 'PackController::restore/$1');
});

// ─── Magasinier + Admin ───────────────────────────────────────────────────
$routes->group('magasinier', ['filter' => 'auth:magasinier,admin'], function ($routes) {
    $routes->get('dashboard',       'MagasinierController::dashboard');
    $routes->get('stocks',          'MagasinierController::stocks');
    $routes->get('alertes',  'StockController::alertesStock');
    $routes->get('mouvements',      'MagasinierController::mouvements');
    $routes->get('entrees-sorties', 'MagasinierController::entreesSorties');
    $routes->get('depenses',        'MagasinierController::depenses');
    $routes->get('depenses/ajout-depense',        'MagasinierController::ajoutDepenses');
    $routes->get('depenses/historique-depense', 'MagasinierController::historiqueDepenses');
    $routes->get('depenses/validation-depense',        'MagasinierController::validationDepense');
    $routes->post('depenses/ajouter-categorie',   'MagasinierController::ajouterCategorie');
    $routes->post('depenses/enregistrer',         'MagasinierController::enregistrerDepense');
    $routes->get('depenses/transactions-filtrees', 'MagasinierController::getTransactionsFiltrees');
    $routes->get('depenses/transaction/(:num)', 'MagasinierController::getTransaction/$1');
    $routes->post('depenses/modifier-transaction', 'MagasinierController::modifierTransaction');
    $routes->post('depenses/approuver-transaction', 'MagasinierController::approuverTransaction');
    $routes->post('depenses/rejeter-transaction', 'MagasinierController::rejeterTransaction');

    $routes->get('produits',        'MagasinierController::produits');
    $routes->get('produits/ajouter', 'MagasinierController::afficherProduits');
    $routes->post('produits/ajouter', 'MagasinierController::ajoutProduits');
    $routes->get('produits/formulaire/(:num)', 'MagasinierController::afficherFormulaireModificationProduit/$1');
    $routes->post('produits/formulaire/(:num)', 'MagasinierController::modifierProduit/$1');
    $routes->get('produits/Supprimer/(:num)', 'MagasinierController::supprimerProduits/$1');

    $routes->get('categoriesProduits', 'MagasinierController::listerCategories');
    $routes->get('categoriesProduits/ajouter', 'MagasinierController::afficherCategories');
    $routes->post('categoriesProduits/ajouter', 'MagasinierController::ajoutCategories');
    $routes->get('categoriesProduits/formulaire/(:num)', 'MagasinierController::afficherFormulaireModifications/$1');
    $routes->post('categoriesProduits/formulaire/(:num)', 'MagasinierController::modifierFormulaire/$1');
    $routes->get('categoriesProduits/Supprimer/(:num)', 'MagasinierController::supprimerModifications/$1');

    $routes->get('entrees-stock', 'AchatStockController::entrees');
    $routes->post('entrees-stock/ajouter', 'AchatStockController::ajouterEntree');
    $routes->post('entrees-stock/update-quantite', 'AchatStockController::updateQuantiteRestante');
    $routes->get('sorties-stock', 'AchatStockController::sorties');
    $routes->get('sorties-stock/export', 'AchatStockController::exportSorties');
    $routes->get('historique', 'AchatStockController::historique');
});

// ─── Livreur + Admin ─────────────────────────────────────────────────────
$routes->group('livreur', ['filter' => 'auth:livreur,admin'], function ($routes) {
    $routes->get('dashboard',    'LivreurController::dashboard');
    $routes->get('reservations', 'LivreurController::reservations');
    $routes->get('scan',            'LivreurController::scan');
    $routes->post('scan/confirmer', 'LivreurController::confirmerScan');
});


// ─── Module CRM (accessible par Agent Commercial et Admin) ─────────────────
$routes->group('crm', ['filter' => 'auth:agent_commercial,admin'], function ($routes) {
    $routes->get('clients', 'ClientController::index');
    $routes->get('clients/create', 'ClientController::create');
    $routes->post('clients/store', 'ClientController::store');
    $routes->get('clients/edit/(:num)', 'ClientController::edit/$1');
    $routes->post('clients/update/(:num)', 'ClientController::update/$1');
    $routes->get('clients/delete/(:num)', 'ClientController::delete/$1');
    $routes->get('clients/profile/(:num)', 'ClientController::profile/$1');
    $routes->post('clients/bulk', 'ClientController::bulkAction');
    $routes->post('clients/interaction/(:num)', 'ClientController::addInteraction/$1');
    $routes->get('clients/autocomplete', 'ClientController::autocomplete');
    $routes->get('clients/districts', 'ClientController::getDistricts');
});

//'namespace' => 'App\Controllers\CRM',
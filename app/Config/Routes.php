<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Login::index');
$routes->get('/login', 'Login::index');
$routes->get('/operateur/creation-operation', 'Creation_operation::index');
$routes->get('/operateur/depot', 'Creation_operation::depot');
$routes->get('/operateur/retrait', 'Creation_operation::retrait');
$routes->get('/operateur/transfert', 'Creation_operation::transfert');
$routes->get('/operateur/modifier-frais', 'Creation_operation::modifier_frais');
$routes->get('/operateur/gain', 'Creation_operation::gain');
$routes->post('/operateur/depot', 'Creation_operation::store_depot');

$routes->post('/operateur/retrait', 'Creation_operation::store_retrait');

$routes->post('/operateur/transfert', 'Creation_operation::store_transfert');

$routes->post('/operateur/modifier-frais/retrait', 'Creation_operation::store_frais_retrait');
$routes->post('/operateur/modifier-frais/retrait/(:num)', 'Creation_operation::update_frais_retrait/$1');
$routes->post('/operateur/modifier-frais/retrait/(:num)/supprimer', 'Creation_operation::delete_frais_retrait/$1');

$routes->post('/operateur/modifier-frais/transfert', 'Creation_operation::store_frais_transfert');
$routes->post('/operateur/modifier-frais/transfert/(:num)', 'Creation_operation::update_frais_transfert/$1');
$routes->post('/operateur/modifier-frais/transfert/(:num)/supprimer', 'Creation_operation::delete_frais_transfert/$1');

$routes->get('/operateur/prefixes', 'Prefixe::index');
$routes->post('/operateur/prefixes', 'Prefixe::store');
$routes->post('/operateur/prefixes/(:num)', 'Prefixe::update/$1');
$routes->post('/operateur/prefixes/(:num)/supprimer', 'Prefixe::delete/$1');

$routes->get('/operateur/organisations', 'Organisation::index');
$routes->post('/operateur/organisations', 'Organisation::store');
$routes->post('/operateur/organisations/(:num)', 'Organisation::update/$1');
$routes->post('/operateur/organisations/(:num)/supprimer', 'Organisation::delete/$1');

$routes->get('/operateur/commission-supp', 'Creation_operation::commission_supp');
$routes->post('/operateur/commission-supp', 'Creation_operation::store_commission_supp');
$routes->post('/operateur/commission-supp/(:num)', 'Creation_operation::update_commission_supp/$1');
$routes->post('/operateur/commission-supp/(:num)/supprimer', 'Creation_operation::delete_commission_supp/$1');

$routes->get('/operateur/comptes', 'Creation_operation::comptes');
$routes->get('/operateur/comptes/(:num)', 'Creation_operation::compte_detail/$1');

$routes->post('/login', 'Login::authentifier');
$routes->get('/logout', 'Login::deconnexion');

$routes->group('client', ['filter' => 'clientAuth'], static function ($routes) {
    $routes->get('/', 'Client::index');
    $routes->get('depot', 'Client::depot');
    $routes->post('depot', 'Client::store_depot');
    $routes->get('retrait', 'Client::retrait');
    $routes->post('retrait', 'Client::store_retrait');
    $routes->get('transfert', 'Client::transfert');
    $routes->post('transfert', 'Client::store_transfert');
    $routes->get('transfert-multiple', 'Client::transfert_multiple');
    $routes->post('transfert-multiple', 'Client::store_transfert_multiple');
    $routes->get('historique', 'Client::historique');
});

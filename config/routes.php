<?php

$routes->get('/', function() {
    UserController::login();
});

$routes->get('/hiekkalaatikko', function() {
    HelloWorldController::sandbox();
});

$routes->get('/etusivu', function() {
    HelloWorldController::etusivu();
});

$routes->get('/omasivu', function() {
    HelloWorldController::omasivu();
});

$routes->get('/ehdotus', function() {
    HelloWorldController::ehdotus();
});

$routes->get('/passwdreset', function() {
    HelloWorldController::passwdreset();
});

$routes->post('/logout', function() {
    UserController::logout();
});

// VIIIKKKO 3 ETEENPÄIN
// kaikki vedonlyöntikohteet

$routes->get('/vedonlyonti', function() {
    BettingController::index();
});

$routes->get('/vedonlyonti/', function() {
    BettingController::index();
});

$routes->get('/login', function() {
    UserController::login();
});

$routes->post('/login', function() {
    UserController::handle_login();
});

$routes->get('/vedonlyonti/lisaa', function() {
    BettingController::add();
});

$routes->post('/vedonlyonti/lisaa', function() {
    BettingController::createMatchup();
});

$routes->post('/vedonlyonti/:id/poista', function($id) {
    BettingController::deleteMatchup($id);
});

$routes->post('/vedonlyonti/:id/editodds', function($id) {
    BettingController::updateOdds($id);
});

$routes->post('/vedonlyonti/:id/editscore', function($id) {
    BettingController::updateScore($id);
});

$routes->get('/vedonlyonti/:id', function($id) {
    BettingController::show($id);
});


$routes->get('/controlpanel', function() {
    UserController::controlpanel();
});

// post wager

$routes->post('/vedonlyonti/:id/wager', function($id) {
    WagerController::create($id);
});



$routes->get('/users', function() {
    UserController::index();
});

$routes->get('/users/', function() {
    UserController::index();
});


$routes->post('/users', function() {
    UserController::create();
});

$routes->get('/users/new', function() {
    UserController::add();
});

// yhden käyttäjän listaus

$routes->get('/users/:id', function($id) {
    UserController::show($id);
});

// käyttäjä päivittää tietojaan

$routes->post('/updateinfo', function() {
    UserController::update();
});

// käyttäjän poisto

$routes->post('/users/:id/delete', function($id) {
    UserController::deleteUser($id);
});

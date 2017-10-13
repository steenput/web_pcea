<?php

// Index page
$app->get('/', "Pcea\Controller\IndexController::indexAction")->bind('index');

// Register user
$app->match('/register', "Pcea\Controller\IndexController::registerAction")->bind('register');

// Login form
$app->get('/login', "Pcea\Controller\IndexController::loginAction")->bind('login');

// Event page
$app->get('/event/{eventId}', "Pcea\Controller\EventController::eventAction")->bind('event');

// New event page
$app->match('/newevent', "Pcea\Controller\EventController::newEventAction")->bind('new_event');

// New spent page
$app->match('/event/{eventId}/newspent', "Pcea\Controller\EventController::newSpentAction")->bind('new_spent');

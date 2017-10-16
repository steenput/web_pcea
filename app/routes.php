<?php

// Index page
$app->match('/', "Pcea\Controller\IndexController::indexAction")->bind('index');

// Event page
$app->get('/event/{eventId}', "Pcea\Controller\EventController::eventAction")->bind('event');

// New event page
$app->match('/newevent', "Pcea\Controller\EventController::newEventAction")->bind('new_event');

// New spent page
$app->match('/event/{eventId}/newspent', "Pcea\Controller\EventController::newSpentAction")->bind('new_spent');

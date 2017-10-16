<?php

// Index
$app->match('/', "Pcea\Controller\IndexController::indexAction")->bind('index');

// Event
$app->get('/event/{eventId}', "Pcea\Controller\EventController::eventAction")->bind('event');

// New event
$app->match('/newevent', "Pcea\Controller\EventController::newEventAction")->bind('new_event');

// Delete event
$app->match('/event/{eventId}/delete', "Pcea\Controller\EventController::deleteEventAction")->bind('delete_event');

// New spent
$app->match('/event/{eventId}/spent/new', "Pcea\Controller\EventController::newSpentAction")->bind('new_spent');

// Delete spent
$app->match('/event/{eventId}/spent/delete/{spentId}', "Pcea\Controller\EventController::deleteSpentAction")->bind('delete_spent');

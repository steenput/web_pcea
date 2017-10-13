<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CurrencyType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Pcea\Entity\User;
use Pcea\Entity\Event;
use Pcea\Entity\Spent;

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


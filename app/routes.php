<?php

use Symfony\Component\HttpFoundation\Request;
use Pcea\Entity\User;
use Pcea\Entity\Event;
use Pcea\Form\Type\RegisterType;
use Pcea\Form\Type\EventType;

const saltLength = 23;

// Index page
$app->get('/', function() use ($app) {
	if ($app['security.authorization_checker']->isGranted('IS_AUTHENTICATED_FULLY')) {
		$user = $app['user'];
		$events = $app['dao.event']->readByUser($user->getId());
		return $app['twig']->render('index.html.twig', array('events' => $events));
	}
	else {
		return $app['twig']->render('index.html.twig');
	}
})->bind('index');

// Event page
$app->match('/newevent', function(Request $request) use ($app) {
	if ($app['security.authorization_checker']->isGranted('IS_AUTHENTICATED_FULLY')) {
		$user = $app['user'];
		$event = new Event();
		$eventForm = $app['form.factory']->create(EventType::class, $event);
		$eventForm->handleRequest($request);
		if ($eventForm->isSubmitted() && $eventForm->isValid()) {
			$app['dao.event']->create($event);
			$app['session']->getFlashBag()->add('success', 'The event ' . $event->getName() . ' was successfully created.');
		}
		return $app['twig']->render('new_event.html.twig', array(
			'title' => 'New event',
			'eventForm' => $eventForm->createView()));
	}
	else {
		return $app->redirect('/pcea/web');
	}
})->bind('new_event');

// Event page
$app->get('/event/{id}', function($id) use ($app) {
	if ($app['security.authorization_checker']->isGranted('IS_AUTHENTICATED_FULLY')) {
		$user = $app['user'];
		if ($app['dao.event']->isAccessibleBy($id, $user->getId())) {
			$event = $app['dao.event']->read($id);
			$spents = $app['dao.spent']->readByEvent($id);
			return $app['twig']->render('event.html.twig', array('spents' => $spents, 'event' => $event));
		}
		else {
			return $app->redirect('/pcea/web');
		}
	}
	else {
		return $app->redirect('/pcea/web');
	}
})->bind('event');

// Login form
$app->get('/login', function(Request $request) use ($app) {
	$app['monolog']->debug(sprintf("'%s'", $request));
	return $app['twig']->render('login.html.twig', array(
		'error'         => $app['security.last_error']($request),
		'last_username' => $app['session']->get('_security.last_username'),
	));
})->bind('login');

// Register user
$app->match('/register', function(Request $request) use ($app) {
	$user = new User();
	$userForm = $app['form.factory']->create(RegisterType::class, $user);
	$userForm->handleRequest($request);
	if ($userForm->isSubmitted() && $userForm->isValid()) {
		// generate a random salt value
		$salt = substr(md5(time()), 0, saltLength);
		$user->setSalt($salt);
		// compute the encoded password
		$password = $app['security.encoder.bcrypt']->encodePassword($user->getPassword(), $salt);
		$user->setPassword($password); 
		$app['dao.user']->create($user);
		$app['session']->getFlashBag()->add('success', 'The user ' . $user->getUsername() . ' was successfully created.');
	}
	return $app['twig']->render('register_form.html.twig', array(
		'title' => 'New user',
		'userForm' => $userForm->createView()));
})->bind('register');

<?php

use Symfony\Component\HttpFoundation\Request;
use Pcea\Entity\User;
use Pcea\Form\Type\RegisterType;

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


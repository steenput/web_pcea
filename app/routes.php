<?php

use Symfony\Component\HttpFoundation\Request;

const saltLength = 23;

// For developpement only, generate and show hash and salt
$app->get('/hashpwd/{pass}', function($pass) use ($app) {
	$salt = substr(md5(time()), 0, saltLength);
	$hash = $app['security.encoder.bcrypt']->encodePassword($pass, $salt);
	return $hash . "<br><br>" . $salt;
});

// Index page
$app->get('/', function() use ($app) {
	return $app['twig']->render('index.html.twig');
})->bind('index');

// Login form
$app->get('/login', function(Request $request) use ($app) {
	return $app['twig']->render('login.html.twig', array(
		'error'         => $app['security.last_error']($request),
		'last_username' => $app['session']->get('_security.last_username'),
	));
})->bind('login');
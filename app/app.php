<?php

use Symfony\Component\Debug\ErrorHandler;
use Symfony\Component\Debug\ExceptionHandler;
use Symfony\Component\HttpFoundation\Request;

// Register global error and exception handlers
ErrorHandler::register();
ExceptionHandler::register();

// Register service providers.
$app->register(new Silex\Provider\DoctrineServiceProvider());
$app->register(new Silex\Provider\TwigServiceProvider(), array(
	'twig.path' => __DIR__.'/../views',
));
$app['twig'] = $app->extend('twig', function(Twig_Environment $twig, $app) {
	$twig->addExtension(new Twig_Extensions_Extension_Text());
	return $twig;
});
$app->register(new Silex\Provider\ValidatorServiceProvider());
$app->register(new Silex\Provider\AssetServiceProvider(), array(
	'assets.version' => 'v1'
));
$app->register(new Silex\Provider\SessionServiceProvider());
$app->register(new Silex\Provider\SecurityServiceProvider(), array(
	'security.firewalls' => array(
		'secured' => array(
			'pattern' => '^/',
			'anonymous' => true,
			'logout' => true,
			'form' => array('login_path' => '/', 'check_path' => '/login_check'),
			'users' => function () use ($app) {
				return new Pcea\DAO\UserDAO($app['db']);
			},
		),
	),
));
$app->register(new Silex\Provider\FormServiceProvider());
$app->register(new Silex\Provider\LocaleServiceProvider());
$app->register(new Silex\Provider\TranslationServiceProvider());
$app->register(new Silex\Provider\MonologServiceProvider(), array(
	'monolog.logfile' => __DIR__.'/../var/logs/pcea.log',
	'monolog.name' => 'Pcea',
	'monolog.level' => $app['monolog.level']
));

// Register services.
$app['dao.user'] = function ($app) {
	return new Pcea\DAO\UserDAO($app['db']);
};
$app['dao.event'] = function ($app) {
	return new Pcea\DAO\EventDAO($app['db']);
};
$app['dao.spent'] = function ($app) {
	$spentDAO = new Pcea\DAO\SpentDAO($app['db']);
	$spentDAO->setEventDAO($app['dao.event']);
	$spentDAO->setUserDAO($app['dao.user']);
	return $spentDAO;
};

$app->error(function (\Exception $e, Request $request, $code) use ($app) {
	switch ($code) {
		case 403:
			$message = 'Access denied.';
			break;
		case 404:
			$message = 'The requested resource could not be found.';
			break;
		default:
			$message = "Something went wrong.";
	}
	return $app['twig']->render('error.html.twig', array('message' => $message));
});
<?php

namespace Pcea\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Pcea\Entity\User;

class IndexController {
	public function indexAction(Application $app) {
		if ($app['security.authorization_checker']->isGranted('IS_AUTHENTICATED_FULLY')) {
			$user = $app['user'];
			$events = $app['dao.event']->readByUser($user->getId());
			return $app['twig']->render('index.html.twig', array('events' => $events));
		}
		else {
			return $app['twig']->render('index.html.twig');
		}
	}

	public function registerAction(Request $request, Application $app) {
		if ($app['security.authorization_checker']->isGranted('IS_AUTHENTICATED_FULLY')) {
			return $app->redirect('/pcea/web');
		}
		else {
			$saltLength = 23;
			$user = new User();
			
			$userForm = $app['form.factory']->createBuilder(FormType::class, $user)
					->add('username', TextType::class)
					->add('password', RepeatedType::class, array(
						'type'            => PasswordType::class,
						'invalid_message' => 'The password fields must match.',
						'options'         => array('required' => true),
						'first_options'   => array('label' => 'Password'),
						'second_options'  => array('label' => 'Repeat password'),
					))
					->getForm();

			$userForm->handleRequest($request);
			if ($userForm->isSubmitted() && $userForm->isValid()) {
				// generate a random salt value
				$salt = substr(md5(time()), 0, $saltLength);
				$user->setSalt($salt);
				// compute the encoded password
				$password = $app['security.encoder.bcrypt']->encodePassword($user->getPassword(), $salt);
				$user->setPassword($password); 
				$app['dao.user']->create($user);
				return $app->redirect('/pcea/web');
			}
			return $app['twig']->render('register_form.html.twig', array(
				'title' => 'New user',
				'userForm' => $userForm->createView())
			);
		}
	}

	public function loginAction(Request $request, Application $app) {
		$app['monolog']->debug(sprintf("'%s'", $request));
		return $app['twig']->render('login.html.twig', array(
			'error'         => $app['security.last_error']($request),
			'last_username' => $app['session']->get('_security.last_username'),
		));
	}
}

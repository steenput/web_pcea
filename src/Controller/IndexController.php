<?php

namespace Pcea\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Pcea\Entity\User;

class IndexController {
	public function indexAction(Request $request, Application $app) {
		if ($app['security.authorization_checker']->isGranted('IS_AUTHENTICATED_FULLY')) {
			$user = $app['user'];
			$events = $app['dao.event']->readByUser($user->getId());
			return $app['twig']->render('index.html.twig', array('events' => $events));
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
				
				try {
					$app['dao.user']->create($user);
				} catch (UniqueConstraintViolationException $e) {
					$app['session']->getFlashBag()->add('error', 'Username already taken.');
					return $app['twig']->render('index.html.twig', array(
						'userForm' => $userForm->createView())
					);
				}

				$app['session']->getFlashBag()->add('success', 'User successfully created.');
			}

			return $app['twig']->render('index.html.twig', array(
				'error'         => $app['security.last_error']($request),
				'userForm' => $userForm->createView())
			);
		}
	}
}

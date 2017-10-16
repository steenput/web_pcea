<?php

namespace Pcea\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CurrencyType;
use Pcea\Entity\Event;
use Pcea\Entity\Spent;
use Symfony\Component\Validator\Constraints as Assert;

class EventController {
	public function eventAction($eventId, Application $app) {
		if ($app['security.authorization_checker']->isGranted('IS_AUTHENTICATED_FULLY')) {
			$currentUser = $app['user'];
			if ($app['dao.event']->isAccessibleBy($eventId, $currentUser->getId())) {
				$event = $app['dao.event']->read($eventId);
				// The parts per users are computed at select in database
				$spents = $app['dao.spent']->readByEvent($eventId);
				$parts = array();
				$reallyPayed = array();
				$situations = array();
				$debts = array();
				$total = 0;

				// Init arrays at zero
				foreach ($event->getUsers() as $user) {
					$parts[$user->getId()] = 0;
					$reallyPayed[$user->getId()] = 0;
					$situations[$user->getId()] = 0;
				}
				
				// If there is not spents, no need to calculate situations and debts
				if ($spents != null) {
					foreach ($spents as $spent) {
						$amount = floatval($spent->getAmount());
						$total += $amount;

						foreach ($spent->getUsers() as $user) {
							if ($spent->getBuyer()->getId() === $user->getId()) {
								$reallyPayed[$user->getId()] += $amount;
							}
							$parts[$user->getId()] += $user->getPart();
						}
					}

					foreach ($situations as $key => $value) {
						$situations[$key] = $reallyPayed[$key] - $parts[$key];
					}

					// At this time, we have in situations the 
					// positive or negative amount per user.
					// The next while compute debts array
					// to show who must pay who.
					$gaps = $situations;
					$isBalanced = false;
					$posCursor = -1;
					$negCursor = -1;

					while (!$isBalanced) {
						// find positive and negative values
						foreach ($gaps as $key => $value) {
							if ($posCursor < 0 && $value > 0) {
								$posCursor = $key;
							}
							if ($negCursor < 0 && $value < 0) {
								$negCursor = $key;
							}
						}
						
						if ($posCursor >= 0 && $negCursor >= 0) {
							$balance = $gaps[$posCursor] + $gaps[$negCursor];
							if ($balance < 0) {
								$debts[] = array(
									"from" => $event->getUsers()[$negCursor]->getUsername(),
									"howMuch" => $gaps[$posCursor],
									"to" => $event->getUsers()[$posCursor]->getUsername()
								);
								$gaps[$negCursor] = $balance;
								$gaps[$posCursor] = 0;
								$posCursor = -1;
							}
							elseif ($balance > 0) {
								$debts[] = array(
									"from" => $event->getUsers()[$negCursor]->getUsername(),
									"howMuch" => abs($gaps[$negCursor]),
									"to" => $event->getUsers()[$posCursor]->getUsername()
								);
								$gaps[$posCursor] = $balance;
								$gaps[$negCursor] = 0;
								$negCursor = -1;
							}
							else {
								$debts[] = array(
									"from" => $event->getUsers()[$negCursor]->getUsername(),
									"howMuch" => $gaps[$posCursor],
									"to" => $event->getUsers()[$posCursor]->getUsername()
								);
								$isBalanced = true;
							}
						}
						else {
							$isBalanced = true;
						}
					}
				}

				return $app['twig']->render('event.html.twig', array(
					'spents' => $spents,
					'event' => $event,
					'parts' => $parts,
					'reallyPayed' => $reallyPayed,
					'situations' => $situations,
					'total' => $total,
					'debts' => $debts
				));
			}
			else {
				return $app->redirect('/pcea/web');
			}
		}
		else {
			return $app->redirect('/pcea/web');
		}
	}

	public function newEventAction(Request $request, Application $app) {
		if ($app['security.authorization_checker']->isGranted('IS_AUTHENTICATED_FULLY')) {
			$event = new Event();
			$users = $app['dao.user']->readAll();

			$eventForm = $app['form.factory']->createBuilder(FormType::class, $event)
				->add('name', TextType::class, array(
					'required' => true,
					'constraints' => array(
						new Assert\NotBlank(), 
						new Assert\Length(array(
						'min' => 5,'max' => 45,
						)))
					)
				)
				->add('description', TextareaType::class)
				->add('currency', CurrencyType::class, array(
					'required' => true,
					'preferred_choices' => array('CHF', 'EUR', 'USD')

				))
				->add('users', ChoiceType::class, array(
					'choices'  => array_column($users, 'id', 'username'),
					'required' => true,
					'multiple' => true
				))
				->getForm();

			$eventForm->handleRequest($request);
			if ($eventForm->isSubmitted() && $eventForm->isValid()) {
				$weight = $_POST['weight'];
				$app['dao.event']->create($event, $weight);
				return $app->redirect('/pcea/web/event/' . $event->getId());
			}
			return $app['twig']->render('new_event.html.twig', array(
				'title' => 'New event',
				'eventForm' => $eventForm->createView()));
		}
		else {
			return $app->redirect('/pcea/web');
		}
	}

	public function newSpentAction($eventId, Request $request, Application $app) {
		if ($app['security.authorization_checker']->isGranted('IS_AUTHENTICATED_FULLY')) {
			$user = $app['user'];
			if ($app['dao.event']->isAccessibleBy($eventId, $user->getId())) {
				$spent = new Spent();
				$users = $app['dao.user']->readAllFromEvent($eventId);

				$spentForm = $app['form.factory']->createBuilder(FormType::class, $spent)
					->add('name', TextType::class, array(
					'required' => true,
					'constraints' => array(
						new Assert\NotBlank(), 
						new Assert\Length(array(
						'min' => 5,'max' => 45,
						))
					)))
					->add('amount', NumberType::class, array(
					'required' => true,
					'constraints' => array(
						new Assert\NotBlank(),
						new Assert\GreaterThan(0)
					)))
					->add('buyDate', DateType::class, array(
						'input' => 'string',
						'required' => true,
						'constraints' => array(
							new Assert\NotBlank()
						)
					))
					->add('buyer', ChoiceType::class, array(
						'choices'  => array_column($users, 'id', 'username'),
						'required' => true,
						'constraints' => array(
							new Assert\NotBlank()
						)
					))
					->add('users', ChoiceType::class, array(
						'choices'  => array_column($users, 'id', 'username'),
						'expanded' => true,
						'multiple' => true,
						'data' => array_column($users, 'id', 'username'),
						'required' => true,
						'constraints' => array(
							new Assert\NotBlank()
						)
					))
					->getForm();

					$spentForm->handleRequest($request);
					if ($spentForm->isSubmitted() && $spentForm->isValid()) {
						$spent->setEvent($eventId);
						$app['dao.spent']->create($spent);
						return $app->redirect('/pcea/web/event/' . $eventId);
					}
					return $app['twig']->render('new_spent.html.twig', array(
						'title' => 'New spent',
						'spentForm' => $spentForm->createView(),
						'eventId' => $eventId
					));
			}
			else {
				return $app->redirect('/pcea/web');
			}
		}
		else {
			return $app->redirect('/pcea/web');
		}
	}

	public function deleteEventAction($eventId, Application $app) {
		if ($app['security.authorization_checker']->isGranted('IS_AUTHENTICATED_FULLY')) {
			$currentUser = $app['user'];
			if ($app['dao.event']->isAccessibleBy($eventId, $currentUser->getId())) {
				$app['dao.event']->delete($eventId);
				return $app->redirect('/pcea/web');
			}
		}
	}

	public function deleteSpentAction($eventId, $spentId, Application $app) {
		if ($app['security.authorization_checker']->isGranted('IS_AUTHENTICATED_FULLY')) {
			$currentUser = $app['user'];
			if ($app['dao.event']->isAccessibleBy($eventId, $currentUser->getId())) {
				$app['dao.spent']->delete($spentId);
				return $app->redirect('/pcea/web/event/' . $eventId);
			}
		}
	}
}

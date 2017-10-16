<?php

namespace Pcea\DAO;

use Pcea\Entity\Event;
use Pcea\Entity\Spent;
use Pcea\Entity\User;

class SpentDAO extends DAO {
	/**
	 * @var \Pcea\DAO\EventDAO
	 */
	private $eventDAO;

	/**
	 * The buyer
	 *
	 * @var \Pcea\DAO\UserDAO
	 */
	private $userDAO;

	public function setEventDAO(EventDAO $eventDAO) {
		$this->eventDAO = $eventDAO;
	}
	
	public function setUserDAO($userDAO) {
		$this->userDAO = $userDAO;
	}

	public function nbConcerned($spentId, $eventId) {
		$sql = "SELECT SUM(user_weight) FROM users_has_events JOIN users_has_spents ON 
		users_has_events.users_id = users_has_spents.users_id WHERE spents_id = ? AND events_id = ?";
		$result = $this->getDb()->fetchAll($sql, array($spentId, $eventId));

		$nb = $result[0]['SUM(user_weight)'];

		if ($nb)
			return $nb;
		else
			throw new Exception(sprintf('Zero division'));
	}

	public function readByEvent($eventId) {
		$sql = "SELECT * FROM spents WHERE events_id = ?";
		$dbSpents = $this->getDb()->fetchAll($sql, array($eventId));

		$spents = array();
		foreach ($dbSpents as $spent) {
			$spents[$spent['id']] = $this->buildEntityObject($spent);
			$sql = "SELECT id, username, user_weight FROM users 
					JOIN users_has_spents ON id = users_has_spents.users_id 
					JOIN users_has_events ON id = users_has_events.users_id 
					WHERE spents_id = ? AND events_id = ?";
			$dbUsers = $this->getDb()->fetchAll($sql, array($spent['id'], $eventId));
			$users = array();

			$nbConcerned = floatval($this->nbConcerned($spent['id'], $eventId));
			
			foreach ($dbUsers as $u) {
				$user = new User();
				$user->setId($u['id']);
				$user->setUsername($u['username']);
				$user->setWeight($u['user_weight']);
				$user->setPart(round(($spents[$spent['id']]->getAmount() / $nbConcerned) * floatval($user->getWeight()), 2, PHP_ROUND_HALF_UP));
				$users[$user->getId()] = $user;
			}
			$spents[$spent['id']]->setUsers($users);
		}
		return $spents;
	}

	/**
	 * Create a spent into the database.
	 *
	 * @param \Pcea\Entity\Spent $spent The spent to create
	 */
	public function create(Spent $spent) {
		$spentData = array(
			'name' => $spent->getName(),
			'amount' => $spent->getAmount(),
			'buy_date' => $spent->getBuyDate(),
			'buyer' => $spent->getBuyer(),
			'events_id' => $spent->getEvent()
			);

		$this->getDb()->insert('spents', $spentData);
		$id = $this->getDb()->lastInsertId();
		$spent->setId($id);

		foreach ($spent->getUsers() as $userId) {
			$usersSpentsData = array(
				'users_id'  => $userId,
				'spents_id' => $spent->getId()
			);

			$this->getDb()->insert('users_has_spents', $usersSpentsData);
		}

		return $spent;
	}

	public function delete($id) {
		$this->getDb()->delete('spents', array('id' => $id));
	}

	/**
	 * Create an Spent object based on a DB row.
	 *
	 * @param array $row The DB row containing Spent data.
	 * @return \Pcea\Entity\Spent
	 */
	protected function buildEntityObject(array $row) {
		$spent = new Spent();
		$spent->setId($row['id']);
		$spent->setName($row['name']);
		$spent->setAmount($row['amount']);
		$spent->setBuyDate($row['buy_date']);

		if (array_key_exists('buyer', $row)) {
			$buyer = $this->userDAO->read($row['buyer']);
			$spent->setBuyer($buyer);
		}

		if (array_key_exists('events_id', $row)) {
			$event = $this->eventDAO->read($row['events_id']);
			$spent->setEvent($event);
		}

		return $spent;
	}
}

<?php

namespace Pcea\DAO;

use Pcea\Entity\Event;
use Pcea\Entity\Spent;

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

	public function readByEvent($id) {
		$sql = "SELECT * FROM spents WHERE events_id = ?";
		$result = $this->getDb()->fetchAll($sql, array($id));

		$events = array();
		foreach ($result as $row) {
			$events[$row['id']] = $this->buildEntityObject($row);
		}
		return $events;
	}

	public function concernUsers($id) {
		$sql = "SELECT username, user_weight FROM users JOIN users_has_spents ON id = users_id WHERE spents_id = ?";
		$result = $this->getDb()->fetchAll($sql, array($id));

		$users = array();
		foreach ($result as $row) {
			$users[$row['id']] = $row;
		}
		return $users;
	}

	/**
	 * Create a spent into the database.
	 *
	 * @param \Pcea\Entity\Spent $spent The spent to create
	 */
	public function create(Spent $spent) {
		$spentData = array(
			'name' => $spent->getName(),
			'amount' => $spent->getCurrency(),
			'buyDate' => $spent->getBuyDate(),
			'buyer' => $spent->getBuyer(),
			'event' => $spent->getEvent()
			);

		$this->getDb()->insert('spents', $spentData);
		$id = $this->getDb()->lastInsertId();
		$spent->setId($id);

		return $spent;
	}

	public function read($id) {
		$sql = "select * from spents where id=?";
		$row = $this->getDb()->fetchAssoc($sql, array($id));

		if ($row)
			return $this->buildEntityObject($row);
		else
			throw new Exception(sprintf('Spent "%s" not found.', $id));
	}

	/**
	 * Update an spent into the database.
	 *
	 * @param \Pcea\Entity\Spent $spent The spent to update
	 */
	public function update(Spent $spent) {
		$spentData = array(
			'name' => $spent->getName(),
			'amount' => $spent->getCurrency(),
			'buyDate' => $spent->getBuyDate(),
			'buyer' => $spent->getBuyer(),
			'event' => $spent->getEvent()
			);

		$this->getDb()->update('spents', $spentData, array('id' => $spent->getId()));

		return $spent;
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
		$spent->setBuyDate($row['buyDate']);

		if (array_key_exists('buyer', $row)) {
			$buyer = $this->userDAO->read($row['buyer']);
			$spent->setBuyer($buyer);
		}

		if (array_key_exists('event', $row)) {
			$event = $this->eventDAO->read($row['event']);
			$spent->setEvent($event);
		}

		return $spent;
	}
}
<?php

namespace Pcea\DAO;

use Pcea\Entity\Event;
use Pcea\Entity\User;

class EventDAO extends DAO {
	public function getWeight($eventId, $userId) {
		$sql = "SELECT user_weight FROM users_has_events WHERE events_id = ? AND users_id = ?";
		$row = $this->getDb()->fetchAssoc($sql, array($eventId, $userId));

		$w = $row['user_weight'];

		if ($w)
			return $w;
		else
			throw new Exception(sprintf('Event "%s" or user "%s" not found.', $eventId, $userId));
	}

	public function isAccessibleBy($eventId, $userId) {
		$sql = "SELECT username FROM users JOIN users_has_events ON id = users_id WHERE events_id = ? AND id = ?";

		if ($this->getDb()->fetchAssoc($sql, array($eventId, $userId))) {
			return true;
		}
		else {
			return false;
		}
	}

	public function readByUser($id) {
		$sql = "SELECT * FROM events JOIN users_has_events ON id = events_id WHERE users_id = ?";
		$result = $this->getDb()->fetchAll($sql, array($id));

		$events = array();
		foreach ($result as $row) {
			$events[$row['id']] = $this->buildEntityObject($row);
		}
		return $events;
	}

	/**
	 * Create an event into the database.
	 *
	 * @param \Pcea\Entity\Event $event The event to create
	 */
	public function create(Event $event, $weight) {
		$eventData = array(
			'name' => $event->getName(),
			'currency' => $event->getCurrency()
			);

		$this->getDb()->insert('events', $eventData);
		$id = $this->getDb()->lastInsertId();
		$event->setId($id);

		foreach ($event->getUsers() as $userId) {
			$usersEventsData = array(
				'users_id'  => $userId,
				'events_id' => $event->getId(),
				'user_weight' => $weight[$userId]
			);

			$this->getDb()->insert('users_has_events', $usersEventsData);
		}

		return $event;
	}

	public function read($id) {
		$sql = "SELECT * FROM events WHERE id=?";
		$row = $this->getDb()->fetchAssoc($sql, array($id));

		if ($row) {
			$event = $this->buildEntityObject($row);
			$sql = "SELECT id, username, user_weight FROM users JOIN users_has_events ON id = users_id WHERE events_id = ?";
			$dbUsers = $this->getDb()->fetchAll($sql, array($id));
			$users = array();

			foreach ($dbUsers as $u) {
				$user = new User();
				$user->setId($u['id']);
				$user->setUsername($u['username']);
				$user->setWeight($u['user_weight']);
				$users[] = $user;
			}
			$event->setUsers($users);

			return $event;
		}
		else {
			throw new Exception(sprintf('Event "%s" not found.', $id));
		}
	}

	/**
	 * Update an event into the database.
	 *
	 * @param \Pcea\Entity\Event $event The event to update
	 */
	public function update(Event $event) {
		$eventData = array(
			'name' => $event->getName(),
			'currency' => $event->getCurrency()
			);

		$this->getDb()->update('events', $eventData, array('id' => $event->getId()));

		foreach ($event->getUsers() as $userId) {
			$usersEventsData = array(
				'users_id'  => $userId,
				'events_id' => $event->getId()
			);

			$this->getDb()->update('users_has_events', $usersEventsData);
		}

		return $event;
	}

	/**
	 * Create an Event object based on a DB row.
	 *
	 * @param array $row The DB row containing Event data.
	 * @return \Pcea\Entity\Event
	 */
	protected function buildEntityObject(array $row) {
		$event = new Event();
		$event->setId($row['id']);
		$event->setName($row['name']);
		$event->setCurrency($row['currency']);
		return $event;
	}
}

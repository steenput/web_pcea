<?php

namespace Pcea\DAO;

use Pcea\Entity\Event;

class EventDAO extends DAO {
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
	public function create(Event $event) {
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
				'events_id' => $event->getId()
			);

			$this->getDb()->insert('users_has_events', $usersEventsData);
		}

		return $event;
	}

	public function read($id) {
		$sql = "select * from events where id=?";
		$row = $this->getDb()->fetchAssoc($sql, array($id));

		if ($row)
			return $this->buildEntityObject($row);
		else
			throw new Exception(sprintf('Event "%s" not found.', $id));
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

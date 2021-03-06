<?php

namespace Pcea\Entity;

class Event {
	private $id;
	private $name;
	private $description;
	private $currency;
	private $users;

	/**
	 * Gets the value of id
	 * @return mixed
	 */
	public function getId() {
		return $this->id;
	}
	
	/**
	 * Sets the value of id
	 *
	 * @param mixed $id
	 * @return self
	 */
	public function setId($id) {
		$this->id = $id;
		return $this;
	}
	
	/**
	 * Gets the value of name
	 * @return mixed
	 */
	public function getName() {
		return $this->name;
	}
	
	/**
	 * Sets the value of name
	 *
	 * @param mixed $name
	 * @return self
	 */
	public function setName($name) {
		$this->name = $name;
		return $this;
	}
	
	/**
	 * Gets the value of currency
	 * @return mixed
	 */
	public function getCurrency() {
		return $this->currency;
	}
	
	/**
	 * Sets the value of currency
	 *
	 * @param mixed $currency
	 * @return self
	 */
	public function setCurrency($currency) {
		$this->currency = $currency;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getUsers() {
		return $this->users;
	}

	/**
	 * @param mixed $users
	 *
	 * @return self
	 */
	public function setUsers($users) {
		$this->users = $users;
		return $this;
	}

	/**
	 * Gets the value of description

	 * @return mixed
	 */
	public function getDescription() {
		return $this->description;
	}
	
	/**
	 * Sets the value of description
	 *
	 * @param mixed $description
	 * @return self
	 */
	public function setDescription($description) {
		$this->description = $description;
		return $this;
	}
}

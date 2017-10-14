<?php

namespace Pcea\Entity;

use Symfony\Component\Security\Core\User\UserInterface;

class User implements UserInterface {
	private $id;
	private $username;
	private $password;
	private $salt;
	private $role;
	private $weight;

	public function getId() {
		return $this->id;
	}

	public function setId($id) {
		$this->id = $id;
		return $this;
	}

	public function getUsername() {
		return $this->username;
	}

	public function setUsername($username) {
		$this->username = $username;
		return $this;
	}

	public function getPassword() {
		return $this->password;
	}

	public function setPassword($password) {
		$this->password = $password;
		return $this;
	}

	public function getSalt() {
		return $this->password;
	}

	public function setSalt($salt) {
		$this->salt = $salt;
		return $this;
	}

	public function getRole() {
		return $this->role;
	}

	public function setRole($role) {
		$this->role = $role;
		return $this;
	}

	/**
	 * @inheritDoc
	 */
	public function getRoles() {
		return array($this->getRole());
	}

	/**
	 * @inheritDoc
	 */
	public function eraseCredentials() {
		// Nothing to do here
	}

	/**
	 * Gets the value of weight

	 * @return mixed
	 */
	public function getWeight() {
		return $this->weight;
	}
	
	/**
	 * Sets the value of weight
	 *
	 * @param mixed $weight
	 * @return self
	 */
	public function setWeight($weight) {
		$this->weight = $weight;
		return $this;
	}
}

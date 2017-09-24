<?php

namespace Pcea\Entity;

class User {
	private $id;
	private $username;
	private $password;

	public function __construct($id, $username, $password) {
		$this->id = $id;
		$this->username = $username;
		$this->password = $password;
	}
}
<?php

namespace Pcea\DAO;

use Pcea\Entity\User;

class UserDAO extends DAO {
	/**
	 * Create an User object based on a DB row.
	 *
	 * @param array $row The DB row containing User data.
	 * @return \Pcea\Entity\User
	 */
	protected function buildEntityObject(array $row) {
		$user = new User($row['id'], $row['username'], $row['password']);
		return $user;
	}
}
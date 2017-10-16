<?php

namespace Pcea\DAO;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Pcea\Entity\User;

class UserDAO extends DAO implements UserProviderInterface {
	public function readAllFromEvent($id) {
		$sql = "SELECT * FROM users JOIN users_has_events ON id = users_id WHERE events_id = " . $id;
		return $this->getDb()->fetchAll($sql);
	}

	public function readAll() {
		$sql = "SELECT * FROM users";
		return $this->getDb()->fetchAll($sql);
	}

	/**
	 * Create an user into the database.
	 *
	 * @param \Pcea\Entity\User $user The user to create
	 */
	public function create(User $user) {
		$userData = array(
			'username' => $user->getUsername(),
			'password' => $user->getPassword(),
			'salt' => $user->getSalt(),
			'role' => 'ROLE_USER'
			);

		$this->getDb()->insert('users', $userData);
		$id = $this->getDb()->lastInsertId();
		$user->setId($id);

		return $user;
	}

	public function read($id) {
		$sql = "select * from users where id=?";
		$row = $this->getDb()->fetchAssoc($sql, array($id));

		if ($row)
			return $this->buildEntityObject($row);
		else
			throw new UsernameNotFoundException(sprintf('User with id "%s" not found.', $id));
	}

	/**
	 * {@inheritDoc}
	 */
	public function loadUserByUsername($username) {
		$sql = "select * from users where username=?";
		$row = $this->getDb()->fetchAssoc($sql, array($username));

		if ($row)
			return $this->buildEntityObject($row);
		else
			throw new UsernameNotFoundException(sprintf('User "%s" not found.', $username));
	}

	/**
	 * {@inheritDoc}
	 */
	public function refreshUser(UserInterface $user) {
		$class = get_class($user);
		if (!$this->supportsClass($class)) {
			throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $class));
		}
		return $this->loadUserByUsername($user->getUsername());
	}

	/**
	 * {@inheritDoc}
	 */
	public function supportsClass($class) {
		return 'Pcea\Entity\User' === $class;
	}

	/**
	 * Create an User object based on a DB row.
	 *
	 * @param array $row The DB row containing User data.
	 * @return \Pcea\Entity\User
	 */
	protected function buildEntityObject(array $row) {
		$user = new User();
		$user->setId($row['id']);
		$user->setUsername($row['username']);
		$user->setPassword($row['password']);
		$user->setSalt($row['salt']);
		$user->setRole($row['role']);
		return $user;
	}
}

<?php

namespace Pcea\DAO;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Pcea\Entity\User;

class UserDAO extends DAO implements UserProviderInterface {
	/**
	 * Saves a user into the database.
	 *
	 * @param \MicroCMS\Domain\User $user The user to save
	 */
	public function save(User $user) {
		$userData = array(
			'username' => $user->getUsername(),
			'password' => $user->getPassword(),
			'salt' => $user->getSalt(),
			'role' => 'ROLE_USER'
			);

		if ($user->getId()) {
			// The user has already been saved : update it
			$this->getDb()->update('users', $userData, array('id' => $user->getId()));
		} else {
			// The user has never been saved : insert it
			$this->getDb()->insert('users', $userData);
			// Get the id of the newly created user and set it on the entity.
			$id = $this->getDb()->lastInsertId();
			$user->setId($id);
		}
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
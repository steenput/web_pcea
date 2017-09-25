<?php

namespace Pcea\DAO;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Pcea\Entity\User;

class UserDAO extends DAO implements UserProviderInterface {
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
		return new User($row['id'], $row['username'], $row['password'], $row['salt'], $row['role']);
	}
}
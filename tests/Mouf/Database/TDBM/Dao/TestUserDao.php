<?php

namespace Mouf\Database\TDBM\Dao;

use Mouf\Database\TDBM\TDBMService;
use Mouf\Database\TDBM\Test\Dao\UserBaseDao;

/**
 * The UserDao class will maintain the persistence of UserBean class into the users table.
 */
class TestUserDao extends UserBaseDao
{
    /**
     * Returns the list of users by alphabetical order
     *
     * @return UserBean[]
     */
    public function getUsersByAlphabeticalOrder() {
        // The third parameter will be used in the "ORDER BY" clause of the SQL query.
        return $this->find(null, [], 'login ASC');
    }

    /**
     * Returns the list of users whose login starts with $login
     *
     * @param string $login
     * @param string $mode
     * @return \Mouf\Database\TDBM\ResultIterator|\Mouf\Database\TDBM\Test\Dao\Bean\UserBean[]|\Mouf\Database\TDBM\Test\Dao\ResultArray
     */
    public function getUsersByLoginStartingWith($login, $mode = null) {
        return $this->find("login LIKE :login", [ "login" => $login.'%' ], null, [], $mode);
    }

    /**
     * Returns the user whose login is $login
     *
     * @param string $login
     * @return \Mouf\Database\TDBM\Test\Dao\Bean\UserBean
     */
    public function getUserByLogin($login) {
        return $this->findOne("login = :login", [ "login" => $login ]);
    }
}

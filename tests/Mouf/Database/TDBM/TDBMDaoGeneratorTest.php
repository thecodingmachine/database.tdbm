<?php
/*
 Copyright (C) 2006-2014 David Négrier - THE CODING MACHINE

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

namespace Mouf\Database\TDBM;

// Require needed if we run this class directly
if (file_exists(__DIR__.'/../../../../../../autoload.php')) {
	require_once __DIR__.'/../../../../../../autoload.php';
} else {
	require_once __DIR__.'/../../../../vendor/autoload.php';
}

/**
 */
class TDBMDaoGeneratorTest extends TDBMAbsctractServiceTest {

	public function testDaoGeneration() {
        $daoFactoryClassName = "DaoFactory";
        // Put your right directory from your home
        // Todo, find a good way to do it
        $sourcedirectory = "projects/mouf/database.tdbm/tests/Mouf/Database/TDBM";
        // Don't forget to change it in require_once also
        $daonamespace = "Tests\\Dao";
        $beannamespace = "Tests\\Dao\\Bean";
        $support = false;
        $storeInUtc = false;

        $tables = $this->tdbmDaoGenerator->generateAllDaosAndBeans($daoFactoryClassName, $sourcedirectory, $daonamespace, $beannamespace, $support, $storeInUtc);

        // Test the daoFactory
        require_once(__DIR__.'/../../../Mouf/Database/TDBM/Tests/Dao/DaoFactory.php');

        // Test the others
        foreach ($tables as $table) {
            $daoName = $this->tdbmDaoGenerator->getDaoNameFromTableName($table);
            $daoBaseName = $daoName."Base";
            $beanName = $this->tdbmDaoGenerator->getBeanNameFromTableName($table);
            $baseBeanName = $this->tdbmDaoGenerator->getBaseBeanNameFromTableName($table);

            // DaoDirectory and BeanDirectory are private
            require_once(__DIR__.'/../../../Mouf/Database/TDBM/Tests/Dao/Bean/'.$baseBeanName.".php");
            require_once(__DIR__.'/../../../Mouf/Database/TDBM/Tests/Dao/Bean/'.$beanName.".php");
            require_once(__DIR__.'/../../../Mouf/Database/TDBM/Tests/Dao/'.$daoBaseName.".php");
            require_once(__DIR__.'/../../../Mouf/Database/TDBM/Tests/Dao/'.$daoName.".php");
        }
    }

}

?>
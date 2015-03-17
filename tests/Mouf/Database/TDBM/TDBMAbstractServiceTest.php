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

use Mouf\Database\DBConnection\MySqlConnection;
use Mouf\Database\TDBM\Utils\TDBMDaoGenerator;
use Mouf\Utils\Cache\NoCache;

// Require needed if we run this class directly
if (file_exists(__DIR__.'/../../../../../../autoload.php')) {
    require_once __DIR__.'/../../../../../../autoload.php';
} else {
    require_once __DIR__.'/../../../../vendor/autoload.php';
}

/**
 */
class TDBMAbsctractServiceTest extends \PHPUnit_Framework_TestCase {

    /** @var MySqlConnection $dbConnection */
    protected $dbConnection;
    /** @var TDBMService $tdbmService */
    protected $tdbmService;
    /** @var TDBMDaoGenerator $tdbmDaoGenerator */
    protected $tdbmDaoGenerator;

    protected function setUp() {

        $dbConnectionAdmin = new MySqlConnection();
        $dbConnectionAdmin->host = 'localhost';
        $dbConnectionAdmin->user = 'root';

        try {
            $dbConnectionAdmin->dropDatabase('tdbm_testcase');
        } catch (\Exception $e) {
            // We don't care if the database does not exist.
        }
        $dbConnectionAdmin->createDatabase('tdbm_testcase');

        $this->dbConnection = new MySqlConnection();
        $this->dbConnection->host = 'localhost';
        $this->dbConnection->user = 'root';
        $this->dbConnection->dbname = 'tdbm_testcase';

        $this->dbConnection->executeSqlFile(__DIR__.'/../../../sql/tdbmunittest2.sql');

        $this->tdbmService = new TDBMService();
        $this->tdbmService->dbConnection = $this->dbConnection;
        $this->tdbmService->cacheService = new NoCache();

        $this->tdbmDaoGenerator = new TDBMDaoGenerator($this->dbConnection);
    }

    static function main() {
        $suite = new \PHPUnit_Framework_TestSuite( __CLASS__);
        \PHPUnit_TextUI_TestRunner::run( $suite);
    }

    /**
     * Without this only test for the abscract class, PhP Unit will raise a warning:
     * No tests found in class "Mouf\Database\TDBM\TDBMAbsctractServiceTest"
     */
    public function testFake() {
        // do nothing
    }
}

if (!defined('PHPUnit_MAIN_METHOD')) {
	require_once __DIR__.'/../../../../vendor/autoload.php';
    TDBMAbsctractServiceTest::main();
}
?>
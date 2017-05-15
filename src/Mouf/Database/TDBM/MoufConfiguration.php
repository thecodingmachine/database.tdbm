<?php


namespace Mouf\Database\TDBM;

use TheCodingMachine\TDBM\Configuration;

/**
 * Class containing configuration used only for Mouf specific tasks.
 */
class MoufConfiguration extends Configuration
{
    private $daoFactoryInstanceName = 'daoFactory';

    /**
     * @return string
     */
    public function getDaoFactoryInstanceName() : string
    {
        return $this->daoFactoryInstanceName;
    }

    /**
     * @param string $daoFactoryInstanceName
     */
    public function setDaoFactoryInstanceName(string $daoFactoryInstanceName)
    {
        $this->daoFactoryInstanceName = $daoFactoryInstanceName;
    }
}

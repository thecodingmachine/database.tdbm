<?php

namespace Mouf\Database\TDBM\Dao;

use Mouf\Database\TDBM\Test\Dao\Bean\CountryBean;
use Mouf\Database\TDBM\Test\Dao\Generated\CountryBaseDao;
use Porpaginas\Result;

/**
 * The CountryDao class will maintain the persistence of CountryBean class into the country table.
 */
class TestCountryDao extends CountryBaseDao
{
    /**
     * @return CountryBean[]|Result
     */
    public function getCountriesByUserCount()
    {
        $sql = <<<SQL
SELECT country.*
FROM country
LEFT JOIN users ON users.country_id = country.id
GROUP BY country.id
ORDER BY COUNT(users.id) DESC
SQL;

        return $this->findFromRawSql($sql);
    }
}

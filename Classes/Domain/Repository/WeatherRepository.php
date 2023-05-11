<?php

namespace Homeinfo\openweathermap\Domain\Repository;

use DateTime;

use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;

class WeatherRepository
{
    public function __construct(
        private readonly ConnectionPool $connectionPool
    )
    {}

    public function findByCity(
        string $name,
        string $country = 'DE',
        ?DateTime $since = null,
        ?DateTime $until = null,
    ): array
    {
        $query = ($queryBuilder = $this->select())
            ->where(
                $queryBuilder->expr()->eq(
                    'city_name',
                    $queryBuilder->createNamedParameter($name)
                )
            )
            ->andWhere(
                $queryBuilder->expr()->eq(
                    'city_country',
                    $queryBuilder->createNamedParameter($country)
                )
            );

        if ($since !== null)
            $query = $query->andWhere(
                $queryBuilder->expr()->ge(
                    'dt',
                    $queryBuilder->createNamedParameter($since)
                )
            );


        if ($until !== null)
            $query = $query->andWhere(
                $queryBuilder->expr()->lt(
                    'dt',
                    $queryBuilder->createNamedParameter($until)
                )
            );

        return $query->executeQuery()->fetch();
    }

    private function select(): QueryBuilder
    {
        return ($queryBuilder = $this->connectionPool->getQueryBuilderForTable('forecast'))
            ->select(
                'forecast.*',
                'city.name as city_name',
                'city.country as city_country',
                'city.longitude as city_longitude',
                'city.latitude as city_latitude',
                'city.last_update as city_last_update',
            )
            ->from('forecast')
            ->join(
                'forecast',
                'city',
                'city',
                $queryBuilder->expr()->eq('city.id', $queryBuilder->quoteIdentifier('forecast.city'))
            );
    }
}

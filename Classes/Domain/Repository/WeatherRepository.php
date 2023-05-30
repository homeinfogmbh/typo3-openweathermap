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
                    'city.name',
                    $queryBuilder->createNamedParameter($name)
                )
            )
            ->andWhere(
                $queryBuilder->expr()->eq(
                    'city.country',
                    $queryBuilder->createNamedParameter($country)
                )
            );

        if ($since !== null)
            $query = $query->andWhere(
                $queryBuilder->expr()->gte(
                    'dt',
                    $queryBuilder->createNamedParameter($since->format(DateTime::ISO8601))
                )
            );


        if ($until !== null)
            $query = $query->andWhere(
                $queryBuilder->expr()->lt(
                    'dt',
                    $queryBuilder->createNamedParameter($until->format(DateTime::ISO8601))
                )
            );

        return $query->executeQuery()->fetchAll();
    }

    private function select(): QueryBuilder
    {
        return ($queryBuilder = $this->connectionPool->getQueryBuilderForTable('forecast'))
            ->select('forecast.*', 'weather.*')
            ->from('forecast')
            ->join(
                'forecast',
                'city',
                'city',
                $queryBuilder->expr()->eq('city.id', $queryBuilder->quoteIdentifier('forecast.city'))
            )
            ->join(
                'forecast',
                'weather',
                'weather',
                $queryBuilder->expr()->eq('weather.forecast', $queryBuilder->quoteIdentifier('forecast.id'))
            );
    }
}

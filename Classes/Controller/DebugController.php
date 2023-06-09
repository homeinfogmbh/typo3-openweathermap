<?php

namespace Homeinfo\openweathermap\Controller;

use DateTime;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Object\ObjectManager;

use Homeinfo\openweathermap\Domain\Repository\WeatherRepository;


class DebugController extends ActionController
{
    public function debugWeatherAction()
    {
        $repository = GeneralUtility::makeInstance(ObjectManager::class)
            ->get(WeatherRepository::class);
        $weather = $repository->findByCity('Hannover', since: new DateTime());
        \TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($weather, "Weather: ");
    }
}

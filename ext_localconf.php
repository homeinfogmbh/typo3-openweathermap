<?php
defined('TYPO3_MODE') || die();

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'openweathermap',
    'openweathermap',
    [
        \Homeinfo\openweathermap\Controller\DebugController::class => 'listWeather',
    ],
);
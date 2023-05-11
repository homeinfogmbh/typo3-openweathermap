<?php
defined('TYPO3_MODE') || die();

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'openweathermap',
    'debug',
    [
        \Homeinfo\openweathermap\Controller\DebugController::class => 'debugWeather',
    ],
);
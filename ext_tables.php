<?php
defined('TYPO3_MODE') or die();

// Register frontend plugin
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'Kronovanet.PrTeamspeak',
    'teamspeak',
    'TeamSpeak 3',
    'EXT:pr_teamspeak/Resources/Libraries/planetteamspeak/ts3-php-framework/images/icons/ts3client.ico'
);

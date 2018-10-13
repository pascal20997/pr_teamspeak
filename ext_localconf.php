<?php
// Load autoloader for external libraries
require_once \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('pr_teamspeak') . 'Resources/Libraries/autoload.php';

// Configure frontend plugin
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
    'CryntonCom.PrTeamspeak',
    'teamspeak',
    [
        'TeamSpeak' => 'list,ajaxList',
    ],
    [
        'TeamSpeak' => 'ajaxList'
    ]
);

// Cache for channel list
if (!is_array($GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['prteamspeak_teamspeak'])) {
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['prteamspeak_teamspeak'] = [
        'frontend' => \TYPO3\CMS\Core\Cache\Frontend\StringFrontend::class,
        'backend' => \TYPO3\CMS\Core\Cache\Backend\Typo3DatabaseBackend::class,
        'options' => [],
        'groups' => []
    ];
}

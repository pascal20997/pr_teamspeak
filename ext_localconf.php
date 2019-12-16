<?php
// Load autoloader for external libraries
require_once \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath('pr_teamspeak') . 'Resources/Libraries/autoload.php';

// Configure frontend plugin
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addTypoScript(
    'pr_teamspeak',
    'setup',
    'tt_content.list.20.prteamspeak_teamspeak = USER
tt_content.list.20.prteamspeak_teamspeak {
    userFunc = KronovaNet\\PrTeamspeak\\TeamSpeakPlugin->render
}',
    'defaultContentRendering'
);

// ajax eID
$GLOBALS['TYPO3_CONF_VARS']['FE']['eID_include']['pr_teamspeak_list'] = \KronovaNet\PrTeamspeak\Eid\TeamSpeakAjaxEid::class . '::process';

// Cache for channel list
if (!is_array($GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['prteamspeak_teamspeak'])) {
    $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations']['prteamspeak_teamspeak'] = [
        'frontend' => \TYPO3\CMS\Core\Cache\Frontend\VariableFrontend::class,
        'backend' => \TYPO3\CMS\Core\Cache\Backend\Typo3DatabaseBackend::class,
        'options' => [],
        'groups' => ['all', 'pages']
    ];
}

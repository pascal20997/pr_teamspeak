<?php
// Register frontend plugin
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPlugin(
    ['TeamSpeak 3', 'prteamspeak_teamspeak', 'EXT:pr_teamspeak/Resources/Public/Icons/Extension.png'],
    'list_type',
    'pr_teamspeak'
);

// Add flexform for TeamSpeak plugin
$pluginSignature = 'prteamspeak_teamspeak';
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist'][$pluginSignature] = 'layout,select_key';
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist'][$pluginSignature] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
    $pluginSignature,
    'FILE:EXT:pr_teamspeak/Configuration/FlexForms/teamspeak.xml'
);
// Exclude some fields we don´t need for this plugin
$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist'][$pluginSignature] = 'pages,recursive';

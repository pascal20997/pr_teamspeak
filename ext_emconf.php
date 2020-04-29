<?php
$EM_CONF[$_EXTKEY] = [
    'title' => 'TeamSpeak 3 monitor',
    'description' => 'Frontend extension to display the channels and clients of a TeamSpeak 3 server.',
    'category' => 'plugin',
    'state' => 'stable',
    'uploadfolder' => 0,
    'createDirs' => '',
    'clearCacheOnLoad' => 1,
    'author' => 'Pascal Rinker',
    'author_email' => 'info@kronova.net',
    'author_company' => 'kronova.net',
    'version' => '3.0.1',
    'constraints' => [
        'depends' => [
            'typo3' => '8.7.0-10.9.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];

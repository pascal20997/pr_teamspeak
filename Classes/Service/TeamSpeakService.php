<?php declare(strict_types=1);
namespace CryntonCom\PrTeamspeak\Service;

/*
* This file is part of the TYPO3 CMS project.
*
* It is free software; you can redistribute it and/or modify it under
* the terms of the GNU General Public License, either version 2
* of the License, or any later version.
*
* For the full copyright and license information, please read the
* LICENSE.txt file that was distributed with this source code.
*
* The TYPO3 project - inspiring people to share!
*/

use TeamSpeak3_Node_Server;
use TeamSpeak3_Viewer_Html;
use TYPO3\CMS\Core\Cache\CacheManager;
use TYPO3\CMS\Core\Cache\Frontend\StringFrontend;
use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\PathUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManager;
use TYPO3\CMS\Extbase\Object\ObjectManager;

/**
 * Class TeamSpeakService
 *
 * @package CryntonCom\PrTeamspeak\Service;
 */
class TeamSpeakService implements SingletonInterface
{
    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * @var TeamSpeak3_Node_Server
     */
    protected $teamSpeak3NodeServer;

    /**
     * @var StringFrontend
     */
    protected $cache;

    /**
     * Plugin settings
     *
     * @var array
     */
    protected $settings = [];

    /**
     * inject objectManager
     *
     * @param ObjectManager $objectManager
     * @return void
     */
    public function injectObjectManager(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    /**
     * @return void
     */
    public function initializeObject()
    {
        $configurationManager = $this->objectManager->get(ConfigurationManager::class);
        $this->settings = $configurationManager->getConfiguration(ConfigurationManager::CONFIGURATION_TYPE_SETTINGS);
        $this->cache = GeneralUtility::makeInstance(CacheManager::class)->getCache('prteamspeak_teamspeak');
    }

    /**
     * Get TeamSpeak 3 channel list using
     * plugin settings
     *
     * @return string HTML
     */
    public function getChannelListHTML()
    {
        $cacheIdentifier = $this->getCacheIdentifier();
        if ($this->cache->has($cacheIdentifier)) {
            $html = $this->cache->get($cacheIdentifier);
        } else {
            // First get an instance of the node server because of autoload
            $teamSpeak3NodeServer = $this->getTeamSpeak3NodeServer();
            $htmlViewer = new TeamSpeak3_Viewer_Html(
                PathUtility::getAbsoluteWebPath(
                    GeneralUtility::getFileAbsFileName(
                        $this->settings['advanced']['icon-path']
                    )
                ),
                null,
                null,
                $this->settings['advanced']['pattern']
            );
            $html = $teamSpeak3NodeServer->getViewer($htmlViewer);
            $this->cache->set($cacheIdentifier, $html, [], $this->settings['advanced']['cache-lifetime']);
        }
        return $html;
    }

    /**
     * Get cache identifier for current TeamSpeak plugin
     *
     * @return string
     */
    protected function getCacheIdentifier(): string
    {
        return md5(json_encode($this->settings));
    }

    /**
     * Returns TeamSpeak3NodeServer
     *
     * @return TeamSpeak3_Node_Server
     */
    public function getTeamSpeak3NodeServer(): TeamSpeak3_Node_Server
    {
        if ($this->teamSpeak3NodeServer === null) {
            $this->teamSpeak3NodeServer = \TeamSpeak3::factory($this->getUri());
        }
        return $this->teamSpeak3NodeServer;
    }

    /**
     * Get URI for TeamSpeak3 query
     *
     * @return string
     */
    protected function getUri(): string
    {
        $uri = 'serverquery://';
        if (!empty($this->settings['general']['username']) && !empty($this->settings['general']['password'])) {
            $uri .= $this->settings['general']['username'] . ':' . $this->settings['general']['password'] . '@';
        }
        $uri .= $this->settings['general']['server-ip'] . ':' . $this->settings['general']['port'];
        $uri .= '/?server_port=' . $this->settings['general']['server-port'];
        return $uri;
    }

    /**
     * Sets TeamSpeak3NodeServer
     *
     * @param TeamSpeak3_Node_Server $teamSpeak3NodeServer
     */
    public function setTeamSpeak3NodeServer(TeamSpeak3_Node_Server $teamSpeak3NodeServer)
    {
        $this->teamSpeak3NodeServer = $teamSpeak3NodeServer;
    }
}

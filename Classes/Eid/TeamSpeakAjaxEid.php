<?php
namespace KronovaNet\PrTeamspeak\Eid;

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

use TYPO3\CMS\Core\Cache\CacheManager;
use TYPO3\CMS\Core\Cache\Frontend\VariableFrontend;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Http\JsonResponse;
use TYPO3\CMS\Core\Http\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Log\LogManager;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Utility\PathUtility;

/**
 * eID script to process TeamSpeak ajax requests
 */
class TeamSpeakAjaxEid
{
    /**
     * @var int
     */
    protected $uid = 0;

    /**
     * @var array
     */
    protected $settings = [];

    /**
     * @var VariableFrontend
     */
    protected $cache;

    public function __construct()
    {
        $this->cache = GeneralUtility::makeInstance(CacheManager::class)->getCache('prteamspeak_teamspeak');
    }

    public function process(ServerRequestInterface $request, Response $response): ResponseInterface
    {
        // CE contains the value of TSFE->currentRecord, which MUST contain tt_content: to be valid
        if (!array_key_exists('ce', $request->getQueryParams()) || strpos($request->getQueryParams()['ce'], 'tt_content:') !== 0) {
            return $response;
        }
        preg_match('@tt_content:(?<uid>[\d]+){1}@', $request->getQueryParams()['ce'], $matches);
        if (!array_key_exists('uid', $matches) || (int)$matches <= 0) {
            return $response;
        }
        $this->uid = (int)$matches['uid'];
        $responseCode = 200;
        if (!$this->cache->has($this->uid)) {
            if (!$this->fetchFlexformSettings()) {
                return $response;
            }
            $data = ['html' => $this->getChannelListHtml()];
            if ($data['html'] === '') {
                // will render a general localized error message in frontend
                $responseCode = 500;
            } else {
                $this->cache->set($this->uid, $data, [], $this->settings['data']['sDEF']['lDEF']['settings.advanced.cache-lifetime']['vDEF']);
            }
        } else {
            $data = $this->cache->get($this->uid);
        }
        return new JsonResponse($data, $responseCode);
    }

    protected function fetchFlexformSettings(): bool
    {
        $success = false;
        $connection = GeneralUtility::makeInstance(ConnectionPool::class)->getConnectionForTable('tt_content');
        $statement = $connection->select(['pi_flexform'], 'tt_content', ['uid' => $this->uid, 'CType' => 'list', 'list_type' => 'prteamspeak_teamspeak']);
        $result = $statement->fetch();
        if ($result !== false) {
            $this->settings = GeneralUtility::xml2array($result['pi_flexform']);
            $success = true;
        }
        return $success;
    }

    protected function getChannelListHtml(): string
    {
        try {
            /** @var \TeamSpeak3_Node_Server $teamSpeak3NodeServer */
            $teamSpeak3NodeServer = \TeamSpeak3::factory($this->getUri());
            $htmlViewer = new \TeamSpeak3_Viewer_Html(
                PathUtility::getAbsoluteWebPath(GeneralUtility::getFileAbsFileName($this->getIconPath())),
                null,
                null,
                $this->settings['data']['sDEF']['lDEF']['settings.advanced.pattern']['vDEF']
            );
            $result = $teamSpeak3NodeServer->getViewer($htmlViewer);
        } catch (\Exception $exception) {
            // write exception into the log file and return an empty result
            // render an localized general error message in frontend using inline language labels
            $result = '';
            $settingsForException = $this->settings;
            $settingsForException['data']['sDEF']['lDEF']['settings.general.password']['vDEF'] = '****';
            GeneralUtility::makeInstance(LogManager::class)->getLogger(__CLASS__)->error(
                'Exception while fetching channel list',
                ['exception' => $exception, 'settings' => $settingsForException]
            );
        }
        return $result;
    }

    protected function getUri(): string
    {
        $uri = 'serverquery://';
        if (!empty($this->settings['data']['sDEF']['lDEF']['settings.general.username']['vDEF']) && !empty($this->settings['data']['sDEF']['lDEF']['settings.general.password']['vDEF'])) {
            $uri .= $this->settings['data']['sDEF']['lDEF']['settings.general.username']['vDEF'] . ':' . $this->settings['data']['sDEF']['lDEF']['settings.general.password']['vDEF'] . '@';
        }
        $uri .= $this->settings['data']['sDEF']['lDEF']['settings.general.server-ip']['vDEF'] . ':' . $this->settings['data']['sDEF']['lDEF']['settings.general.port']['vDEF'];
        $uri .= '/?server_port=' . $this->settings['data']['sDEF']['lDEF']['settings.general.server-port']['vDEF'];
        return $uri;
    }

    protected function getIconPath(): string
    {
        $iconPath = 'EXT:pr_teamspeak/Resources/Public/Images/ts3-php-framework/viewer/';
        if ($this->settings['data']['sDEF']['lDEF']['settings.advanced.icon-path']['vDEF']) {
            $iconPath = $this->settings['data']['sDEF']['lDEF']['settings.advanced.icon-path']['vDEF'];
        }
        return $iconPath;
    }
}

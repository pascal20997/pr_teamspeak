<?php declare(strict_types=1);
namespace CryntonCom\PrTeamspeak\Controller;

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

use CryntonCom\PrTeamspeak\Service\TeamSpeakService;
use TYPO3\CMS\Core\Messaging\AbstractMessage;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

/**
 * Class TeamSpeakController
 *
 * @package CryntonCom\PrTeamspeak\Controller;
 */
class TeamSpeakController extends ActionController
{
    /**
     * @var TeamSpeakService
     */
    protected $teamSpeakService;

    /**
     * @var PageRenderer
     */
    protected $pageRenderer;

    /**
     * inject teamSpeakService
     *
     * @param TeamSpeakService $teamSpeakService
     * @return void
     */
    public function injectTeamSpeakService(TeamSpeakService $teamSpeakService)
    {
        $this->teamSpeakService = $teamSpeakService;
    }

    /**
     * inject pageRenderer
     *
     * @param PageRenderer $pageRenderer
     * @return void
     */
    public function injectPageRenderer(PageRenderer $pageRenderer)
    {
        $this->pageRenderer = $pageRenderer;
    }

    /**
     * TeamSpeak 3 client list
     *
     * @return void
     */
    public function listAction()
    {
        $this->pageRenderer->addInlineSetting(
            'PrTeamspeak',
            'ajaxUrl',
            $this->getControllerContext()->getUriBuilder()
                ->reset()
                ->setTargetPageType(20172512)
                ->uriFor('ajaxList')
        );
        $this->pageRenderer->addInlineSetting(
            'PrTeamspeak',
            'ajaxRefreshTime',
            $this->settings['advanced']['cache-lifetime']
        );
        $this->assignChannelListHTML();
    }

    /**
     * TeamSpeak 3 AJAX list
     *
     * @return void
     */
    public function ajaxListAction()
    {
        $this->assignChannelListHTML();
    }

    /**
     * Assign channel list HTML to the view
     * On error: Log exception into system log and show an error message
     * to the user
     *
     * @return void
     */
    protected function assignChannelListHTML()
    {
        try {
            $this->view->assign('list', $this->teamSpeakService->getChannelListHTML());
        } catch (\Exception $exception) {
            $this->addFlashMessage(
                LocalizationUtility::translate('frontend-exception', 'pr_teamspeak'),
                '',
                AbstractMessage::ERROR
            );
            GeneralUtility::sysLog(
                'Exception while getChannelListHTML: ' . $exception->getMessage()
                . ' (Code: ' . $exception->getCode() . ')',
                'pr_teamspeak',
                GeneralUtility::SYSLOG_SEVERITY_ERROR
            );
        }
    }
}

<?php declare(strict_types=1);
namespace Kronovanet\PrTeamspeak\Controller;

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

use Kronovanet\PrTeamspeak\Service\TeamSpeakService;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

/**
 * Class TeamSpeakController
 *
 * @package Kronovanet\PrTeamspeak\Controller;
 */
class TeamSpeakController extends ActionController
{
    /**
     * @var TeamSpeakService
     */
    protected $teamSpeakService;

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
     * TeamSpeak 3 client list
     *
     * @return void
     */
    public function listAction()
    {
        $this->view->assign('list', $this->teamSpeakService->getChannelListHTML());
    }

    /**
     * TeamSpeak 3 AJAX list
     *
     * @return void
     */
    public function ajaxListAction()
    {
        $this->view->assign('list', $this->teamSpeakService->getChannelListHTML());
    }
}

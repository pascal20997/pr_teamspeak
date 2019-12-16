<?php
namespace KronovaNet\PrTeamspeak;

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

use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

/**
 * TeamSpeak frontend plugin
 */
class TeamSpeakPlugin
{
    public function render(): string
    {
        $this->addLanguageLabelsToFrontend();
        return sprintf(
            '<div class="tx-pr-teamspeak"><div class="teamspeak-channel-list" data-ce="%s"><p>%s</p></div></div>',
            $GLOBALS['TSFE']->currentRecord,
            LocalizationUtility::translate('LLL:EXT:pr_teamspeak/Resources/Private/Language/locallang_frontend.xlf:loading')
        );
    }

    protected function addLanguageLabelsToFrontend(): void
    {
        GeneralUtility::makeInstance(PageRenderer::class)
            ->addInlineLanguageLabelFile('EXT:pr_teamspeak/Resources/Private/Language/locallang_frontend.xlf');
    }
}

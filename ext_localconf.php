<?php

defined('TYPO3_MODE') || defined('TYPO3') || die('Access denied.');

call_user_func(static function ($extKey) {

    // Loading the page.tsconfig is only required for TYPO3 v10 and v11 at this point.
    // From v12 the page.tsconfig is loaded directly by TYPO3.
    if (TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(TYPO3\CMS\Core\Information\Typo3Version::class)->getMajorVersion() < 12) {
        TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
            '@import "EXT:fnn_powermail_altcha/Configuration/page.tsconfig"'
        );
    }

}, 'fnn_powermail_altcha');

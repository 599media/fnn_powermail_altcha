<?php
namespace Fnn\FnnPowermailAltcha\ViewHelpers;

use AltchaOrg\Altcha\ChallengeOptions;
use Fnn\FnnPowermailAltcha\Service\AltchaService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;

class AltchaSpamProtectionViewHelper extends \TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper
{
    protected string $hmacKey = '';

    protected int $maxNumber = 0;

    protected int $expires = 0;

    protected int $pid = 0;

    /**
     * Initializes the arguments for the function.
     *
     * @return void
     */
    public function initializeArguments()
    {
        parent::initializeArguments();
        $this->registerArgument('settings', 'array', 'Powermail settings');
    }

    /**
     * Generates and returns a configuration array used for rendering
     * a spam shield challenge along with corresponding language labels.
     *
     * The method extracts and validates certain configurations,
     * such as hmacKey, maxNumber, expiration time, and pid from the given
     * settings, initializing default values if necessary. Language labels
     * for the challenge may also be configured and returned.
     *
     * @return array An array containing the generated challenge (`altchaChallenge`)
     *               and optional translated language labels (`langLabels`).
     */
    public function render(): array
    {

        $this->hmacKey = $this->getHmacKey($this->arguments['settings']);

        $altchaService = GeneralUtility::makeInstance(AltchaService::class,$this->hmacKey, $this->maxNumber, $this->expires, $this->pid);

        if (isset($this->arguments['settings']['spamshield']['methods'][2025]['configuration']['maxNumber']) &&
            (int)$this->arguments['settings']['spamshield']['methods'][2025]['configuration']['maxNumber'] > 0) {
            $this->maxNumber = (int)$this->arguments['settings']['spamshield']['methods'][2025]['configuration']['maxNumber'];
        } else {
            $this->maxNumber = 1000000;
        }

        if (isset($this->arguments['settings']['spamshield']['methods'][2025]['configuration']['expires']) &&
            (int)$this->arguments['settings']['spamshield']['methods'][2025]['configuration']['expires'] > 0) {
            $this->expires = (int)$this->arguments['settings']['spamshield']['methods'][2025]['configuration']['expires'];
        } else {
            $this->expires = 86400;
        }

        if (isset($this->arguments['settings']['main']['pid']) &&
            (int)$this->arguments['settings']['main']['pid'] > 0) {
            $this->pid = (int)$this->arguments['settings']['main']['pid'];
        }

        if (isset($this->arguments['settings']['spamshield']['methods'][2025]['configuration']['labels'])) {
            $labelKeys = $this->arguments['settings']['spamshield']['methods'][2025]['configuration']['labels'];

            $langLabels = [
                'error' => LocalizationUtility::translate($labelKeys['error']),
                'expired' => LocalizationUtility::translate($labelKeys['expired']),
                'label' => LocalizationUtility::translate($labelKeys['label']),
                'verified' => LocalizationUtility::translate($labelKeys['verified']),
                'verifying' => LocalizationUtility::translate($labelKeys['verifying']),
                'waitAlert' => LocalizationUtility::translate($labelKeys['waitAlert']),
            ];
        }
        DebuggerUtility::var_dump($altchaService);
        return [
            'altchaChallenge' => $altchaService->createChallenge(),
            'langLabels' => $langLabels ?? [],
            'lifetime' => $this->expires,
            'configuration' => $this->arguments['settings']['spamshield']['methods'][2025]['configuration'],
            'hmacKey' => $this->hmacKey,
        ];
    }

    // TODO: Duplicate code here and in Spamshield Validator. Refactoring to later time
    private function getHmacKey($settings): string
    {
        if($settings['spamshield']['methods'][2025]['configuration']['hmacKey'] !== '[YOUR_UNIQUE_KEY_HERE]' && $settings['spamshield']['methods'][2025]['configuration']['hmacKey'] !== '') {
            $this->hmacKey = $settings['spamshield']['methods'][2025]['configuration']['hmacKey'];
            return $settings['spamshield']['methods'][2025]['configuration']['hmacKey'];
        } else if ($GLOBALS['TYPO3_CONF_VARS']['SYS']['encryptionKey'] !== '') {
            return $GLOBALS['TYPO3_CONF_VARS']['SYS']['encryptionKey'];
        } else {
            return 'error';
        }
    }
}

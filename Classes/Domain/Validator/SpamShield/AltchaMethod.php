<?php
namespace Fnn\FnnPowermailAltcha\Domain\Validator\SpamShield;

use Fnn\FnnPowermailAltcha\Service\AltchaService;
use In2code\Powermail\Domain\Model\Answer;
use In2code\Powermail\Domain\Validator\SpamShield\AbstractMethod;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Class AltchaMethod
 *
 * Extends the AbstractMethod class to implement Altcha-based spam protection.
 * This class handles checking the provided spam protection field, validating its solution,
 * and processing configurations such as HMAC key, maximum number, and expiration time.
 */
class AltchaMethod extends AbstractMethod
{
    protected string $hmacKey = '';

    protected int $maxNumber = 0;

    protected int $expires = 0;

    protected int $pid = 0;

    /**
     * Checks if the mail contains a spam protection field and validates it.
     *
     * @return bool Returns true if the spam check passes, otherwise false.
     */
    public function spamCheck(): bool
    {
        // If the action is not Create, no check is carried out.
        if ($this->arguments['action'] !== 'create' && $this->arguments['action'] !== 'checkCreate') {
            return false;
        }

        $require = false;
        foreach ($this->mail->getForm()->getPages() as $page) {
            foreach ($page->getFields() as $field) {
                if ($field->getType() === 'altchaSpamProtection')
                    $require = true;
            }
        }

        if (!$require) {
            return false;
        }

        /** @var Answer $answer */
        foreach ($this->mail->getAnswers() as $answer) {
            if ($answer->getField()->getType() == 'altchaSpamProtection') {
                return !$this->altchaCheck($answer->getValue(), $answer);
            }
        }

        return true;
    }

    /**
     * Validates the Altcha spam protection solution based on the provided value and configuration.
     *
     * @param string $value The encoded solution value to be validated.
     * @param Answer $answer The Answer object to store the validation result.
     * @return bool Returns false if the validation fails, otherwise true.
     */
    protected function altchaCheck(string $value, Answer $answer): bool
    {
        if (isset( $this->settings['settings']['spamshield']['methods'][2025]['configuration']['hmacKey'])) {
            $this->hmacKey =  $this->settings['settings']['spamshield']['methods'][2025]['configuration']['hmacKey'];
        } else {
            $this->hmacKey = 'xjLN96F6Q4lTv9Q0wOyC9aJ9IJFcLF';
        }

        if (isset( $this->settings['settings']['spamshield']['methods'][2025]['configuration']['maxNumber']) &&
            (int) $this->settings['settings']['spamshield']['methods'][2025]['configuration']['maxNumber'] > 0) {
            $this->maxNumber = (int) $this->settings['settings']['spamshield']['methods'][2025]['configuration']['maxNumber'];
        } else {
            $this->maxNumber = 1000000;
        }

        if (isset( $this->settings['settings']['spamshield']['methods'][2025]['configuration']['expires']) &&
            (int) $this->settings['settings']['spamshield']['methods'][2025]['configuration']['expires'] > 0) {
            $this->expires = (int) $this->settings['settings']['spamshield']['methods'][2025]['configuration']['expires'];
        } else {
            $this->expires = 86400;
        }

        $decodedPayload = base64_decode($value);
        $payload = json_decode($decodedPayload, true);

        $altchaService = GeneralUtility::makeInstance(
            AltchaService::class,
            $this->settings['spamshield']['methods'][2025]['configuration']['hmacKey'],
            (int)$this->settings['spamshield']['methods'][2025]['configuration']['maxNumber'],
            (int)$this->settings['spamshield']['methods'][2025]['configuration']['expires']);

        $checkResult = $altchaService->verifySolution($payload);
        if ($checkResult) {
            $answer->setValue('valid');
        } else {
            $answer->setValue('invalid');
        }

        return $checkResult;
    }
}

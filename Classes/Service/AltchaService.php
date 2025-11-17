<?php
namespace Fnn\FnnPowermailAltcha\Service;

use Fnn\FnnPowermailAltcha\Domain\Model\Challenge;
use AltchaOrg\Altcha\ChallengeOptions;
use AltchaOrg\Altcha\Altcha;
use Fnn\FnnPowermailAltcha\Domain\Repository\ChallengeRepository;
use TYPO3\CMS\Core\Utility\GeneralUtility;


/**
 * The AltchaService class provides methods to create and verify challenges using
 * the Altcha library. It integrates with a repository to persist and manage challenges.
 */
class AltchaService
{
    protected string $hmacKey = '';

    protected int $maxNumber = 0;

    protected int $expires = 0;

    protected int $pid = 0;

    protected ?ChallengeRepository $challengeRepository = null;

    public function __construct(string $hmacKey, int $maxNumber, int $expires, int $pid = 0)
    {
        $this->hmacKey = $hmacKey;
        $this->maxNumber = $maxNumber;
        $this->expires = $expires;
        $this->pid = $pid;
        $this->challengeRepository = GeneralUtility::makeInstance(ChallengeRepository::class);
    }

    /**
     * Creates a new challenge based on the configured options and repository.
     *
     * @return \AltchaOrg\Altcha\Challenge The created challenge instance from the Altcha library.
     */
    public function createChallenge(): \AltchaOrg\Altcha\Challenge {
        $options = new ChallengeOptions([
            'hmacKey'   => $this->hmacKey,
            'maxNumber' => $this->maxNumber,
            'expires'   => (new \DateTimeImmutable())->add(new \DateInterval('PT' . $this->expires . 'S')),
        ]);

        /** @var \AltchaOrg\Altcha\Challenge $altchaChallenge */
        $altchaChallenge = Altcha::createChallenge($options);
        $challenge = new Challenge(
            $altchaChallenge->challenge,
            $options->expires->getTimestamp()
        );
        $challenge->setPid($this->pid);

        $this->challengeRepository->add($challenge);
        $this->challengeRepository->persistAll();

        return $altchaChallenge;
    }

    /**
     * Verifies the solution to a given challenge from the provided payload.
     *
     * @param array $payload An associative array containing the challenge data to verify.
     * @return bool Returns true if the solution is valid, false otherwise.
     */
    public function verifySolution(array $payload): bool
    {
        $challenge = $this->challengeRepository->findByChallenge($payload['challenge']);

        if (is_null($challenge)) {
            return false;
        }

        $checkResult = Altcha::verifySolution($payload, $this->hmacKey, true);
        if ($checkResult) {
            $challenge->setSolved(true);
            $this->challengeRepository->update($challenge);
            $this->challengeRepository->persistAll();
        }

        $this->challengeRepository->removeObsolete();

        return $checkResult;
    }
}

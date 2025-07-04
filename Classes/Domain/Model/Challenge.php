<?php
namespace Fnn\FnnPowermailAltcha\Domain\Model;

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;

class Challenge extends AbstractEntity
{
    /**
     * Represents a challenge or task, initialized as an empty string.
     */
    protected string $challenge = '';

    /**
     * The challenge expires at this point
     */
    protected int $expires = 0;

    /**
     * Indicates whether the challenge has been solved
     */
    protected bool $solved = false;

    public function __construct(
        string $challenge,
        int $expires)
    {
        $this->challenge = $challenge;
        $this->expires = $expires;
    }

    /**
     * Retrieves the challenge string.
     *
     * @return string The challenge string.
     */
    public function getChallenge(): string
    {
        return $this->challenge;
    }

    /**
     * Sets the challenge string.
     *
     * @param string $challenge The challenge string to set.
     * @return void
     */
    public function setChallenge(string $challenge): void
    {
        $this->challenge = $challenge;
    }

    /**
     * Retrieves the expiration time.
     *
     * @return int The expiration timestamp.
     */
    public function getExpires(): int
    {
        return $this->expires;
    }

    /**
     * Sets the expiration time.
     *
     * @param int $expires The expiration time as a timestamp.
     * @return void
     */
    public function setExpires(int $expires): void
    {
        $this->expires = $expires;
    }

    /**
     * Determines whether the current state is solved.
     *
     * @return bool True if solved, false otherwise.
     */
    public function isSolved(): bool
    {
        return $this->solved;
    }

    /**
     * Sets the solved status.
     *
     * @param bool $solved Indicates whether the item is solved.
     * @return void
     */
    public function setSolved(bool $solved): void
    {
        $this->solved = $solved;
    }
}

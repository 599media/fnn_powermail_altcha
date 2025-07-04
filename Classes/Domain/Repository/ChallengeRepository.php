<?php

namespace Fnn\FnnPowermailAltcha\Domain\Repository;

use Fnn\FnnPowermailAltcha\Domain\Model\Challenge;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Information\Typo3Version;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * A repository handling operations related to Challenge entities. Provides
 * methods for persisting data and retrieving specific challenges based on
 * given criteria.
 */
class ChallengeRepository extends \TYPO3\CMS\Extbase\Persistence\Repository
{
    /**
     * Represents the name of the database table used for storing challenge data.
     */
    protected string $tableName = 'tx_fnnpowermailaltcha_domain_model_challenge';

    /**
     * Initializes the object by configuring default query settings.
     *
     * @return void
     */
    public function initializeObject(): void
    {
        $querySettings = $this->createQuery()->getQuerySettings();
        $querySettings->setRespectStoragePage(false);
        $this->setDefaultQuerySettings($querySettings);
    }

    /**
     * Persists all changes to the database by committing any pending modifications
     * managed by the persistence layer.
     *
     * @return void No return value.
     */
    public function persistAll() : void
    {
        $this->persistenceManager->persistAll();
    }

    /**
     * Finds and retrieves a Challenge entity based on the provided challenge identifier
     * and ensures the challenge is not marked as solved.
     *
     * @param string $challenge The identifier of the challenge to be fetched.
     * @return Challenge|null Returns a Challenge object if found, or null if no matching entity exists.
     */
    public function findByChallenge(string $challenge): ?Challenge
    {
        $query = $this->createQuery();
        $query->matching($query->logicalAnd(
            $query->equals('challenge', $challenge),
            $query->equals('solved', 0)
        ));
        return $query->execute()->getFirst();
    }

    /**
     * Removes obsolete entries from the database table based on specific conditions.
     * Entries are deleted if the 'solved' field is set to 1 or if the 'expires' field has a value less than the current time.
     *
     * @return void
     */
    public function removeObsolete(): void
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable($this->tableName);
        $queryBuilder
            ->delete($this->tableName)
            ->where($queryBuilder->expr()->eq('solved', 1))
            ->orWhere($queryBuilder->expr()->lt('expires', time()));

        // $queryBuilder->execute() has been marked as deprecated since v12 and was removed in v13
        if (GeneralUtility::makeInstance(Typo3Version::class)->getMajorVersion() < 12) {
            $queryBuilder->execute();
        } else {
            $queryBuilder->executeStatement();
        }
    }
}

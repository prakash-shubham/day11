<?php

namespace Drupal\day11;

use Drupal\Core\Entity\ContentEntityStorageInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Language\LanguageInterface;
use Drupal\day11\Entity\day11EntityInterface;

/**
 * Defines the storage handler class for Day11entity entities.
 *
 * This extends the base storage class, adding required special handling for
 * Day11entity entities.
 *
 * @ingroup day11
 */
interface day11EntityStorageInterface extends ContentEntityStorageInterface {

  /**
   * Gets a list of Day11entity revision IDs for a specific Day11entity.
   *
   * @param \Drupal\day11\Entity\day11EntityInterface $entity
   *   The Day11entity entity.
   *
   * @return int[]
   *   Day11entity revision IDs (in ascending order).
   */
  public function revisionIds(day11EntityInterface $entity);

  /**
   * Gets a list of revision IDs having a given user as Day11entity author.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The user entity.
   *
   * @return int[]
   *   Day11entity revision IDs (in ascending order).
   */
  public function userRevisionIds(AccountInterface $account);

  /**
   * Counts the number of revisions in the default language.
   *
   * @param \Drupal\day11\Entity\day11EntityInterface $entity
   *   The Day11entity entity.
   *
   * @return int
   *   The number of revisions in the default language.
   */
  public function countDefaultLanguageRevisions(day11EntityInterface $entity);

  /**
   * Unsets the language for all Day11entity with the given language.
   *
   * @param \Drupal\Core\Language\LanguageInterface $language
   *   The language object.
   */
  public function clearRevisionsLanguage(LanguageInterface $language);

}

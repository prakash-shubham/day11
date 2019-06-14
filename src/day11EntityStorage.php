<?php

namespace Drupal\day11;

use Drupal\Core\Entity\Sql\SqlContentEntityStorage;
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
class day11EntityStorage extends SqlContentEntityStorage implements day11EntityStorageInterface {

  /**
   * {@inheritdoc}
   */
  public function revisionIds(day11EntityInterface $entity) {
    return $this->database->query(
      'SELECT vid FROM {day11_entity_revision} WHERE id=:id ORDER BY vid',
      [':id' => $entity->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function userRevisionIds(AccountInterface $account) {
    return $this->database->query(
      'SELECT vid FROM {day11_entity_field_revision} WHERE uid = :uid ORDER BY vid',
      [':uid' => $account->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function countDefaultLanguageRevisions(day11EntityInterface $entity) {
    return $this->database->query('SELECT COUNT(*) FROM {day11_entity_field_revision} WHERE id = :id AND default_langcode = 1', [':id' => $entity->id()])
      ->fetchField();
  }

  /**
   * {@inheritdoc}
   */
  public function clearRevisionsLanguage(LanguageInterface $language) {
    return $this->database->update('day11_entity_revision')
      ->fields(['langcode' => LanguageInterface::LANGCODE_NOT_SPECIFIED])
      ->condition('langcode', $language->getId())
      ->execute();
  }

}

<?php

namespace Drupal\day11;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Day11entity entity.
 *
 * @see \Drupal\day11\Entity\day11Entity.
 */
class day11EntityAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\day11\Entity\day11EntityInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished day11entity entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published day11entity entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit day11entity entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete day11entity entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add day11entity entities');
  }

}

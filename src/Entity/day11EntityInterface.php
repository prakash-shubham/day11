<?php

namespace Drupal\day11\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\RevisionLogInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Day11entity entities.
 *
 * @ingroup day11
 */
interface day11EntityInterface extends ContentEntityInterface, RevisionLogInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Day11entity name.
   *
   * @return string
   *   Name of the Day11entity.
   */
  public function getName();

  /**
   * Sets the Day11entity name.
   *
   * @param string $name
   *   The Day11entity name.
   *
   * @return \Drupal\day11\Entity\day11EntityInterface
   *   The called Day11entity entity.
   */
  public function setName($name);

  /**
   * Gets the Day11entity creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Day11entity.
   */
  public function getCreatedTime();

  /**
   * Sets the Day11entity creation timestamp.
   *
   * @param int $timestamp
   *   The Day11entity creation timestamp.
   *
   * @return \Drupal\day11\Entity\day11EntityInterface
   *   The called Day11entity entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Day11entity published status indicator.
   *
   * Unpublished Day11entity are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Day11entity is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Day11entity.
   *
   * @param bool $published
   *   TRUE to set this Day11entity to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\day11\Entity\day11EntityInterface
   *   The called Day11entity entity.
   */
  public function setPublished($published);

  /**
   * Gets the Day11entity revision creation timestamp.
   *
   * @return int
   *   The UNIX timestamp of when this revision was created.
   */
  public function getRevisionCreationTime();

  /**
   * Sets the Day11entity revision creation timestamp.
   *
   * @param int $timestamp
   *   The UNIX timestamp of when this revision was created.
   *
   * @return \Drupal\day11\Entity\day11EntityInterface
   *   The called Day11entity entity.
   */
  public function setRevisionCreationTime($timestamp);

  /**
   * Gets the Day11entity revision author.
   *
   * @return \Drupal\user\UserInterface
   *   The user entity for the revision author.
   */
  public function getRevisionUser();

  /**
   * Sets the Day11entity revision author.
   *
   * @param int $uid
   *   The user ID of the revision author.
   *
   * @return \Drupal\day11\Entity\day11EntityInterface
   *   The called Day11entity entity.
   */
  public function setRevisionUserId($uid);

}

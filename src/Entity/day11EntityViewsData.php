<?php

namespace Drupal\day11\Entity;

use Drupal\views\EntityViewsData;

/**
 * Provides Views data for Day11entity entities.
 */
class day11EntityViewsData extends EntityViewsData {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    // Additional information for Views integration, such as table joins, can be
    // put here.

    return $data;
  }

}

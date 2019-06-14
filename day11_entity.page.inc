<?php

/**
 * @file
 * Contains day11_entity.page.inc.
 *
 * Page callback for Day11entity entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Day11entity templates.
 *
 * Default template: day11_entity.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_day11_entity(array &$variables) {
  // Fetch day11Entity Entity Object.
  $day11_entity = $variables['elements']['#day11_entity'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}

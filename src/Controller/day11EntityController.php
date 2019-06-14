<?php

namespace Drupal\day11\Controller;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Url;
use Drupal\day11\Entity\day11EntityInterface;

/**
 * Class day11EntityController.
 *
 *  Returns responses for Day11entity routes.
 */
class day11EntityController extends ControllerBase implements ContainerInjectionInterface {

  /**
   * Displays a Day11entity  revision.
   *
   * @param int $day11_entity_revision
   *   The Day11entity  revision ID.
   *
   * @return array
   *   An array suitable for drupal_render().
   */
  public function revisionShow($day11_entity_revision) {
    $day11_entity = $this->entityManager()->getStorage('day11_entity')->loadRevision($day11_entity_revision);
    $view_builder = $this->entityManager()->getViewBuilder('day11_entity');

    return $view_builder->view($day11_entity);
  }

  /**
   * Page title callback for a Day11entity  revision.
   *
   * @param int $day11_entity_revision
   *   The Day11entity  revision ID.
   *
   * @return string
   *   The page title.
   */
  public function revisionPageTitle($day11_entity_revision) {
    $day11_entity = $this->entityManager()->getStorage('day11_entity')->loadRevision($day11_entity_revision);
    return $this->t('Revision of %title from %date', ['%title' => $day11_entity->label(), '%date' => format_date($day11_entity->getRevisionCreationTime())]);
  }

  /**
   * Generates an overview table of older revisions of a Day11entity .
   *
   * @param \Drupal\day11\Entity\day11EntityInterface $day11_entity
   *   A Day11entity  object.
   *
   * @return array
   *   An array as expected by drupal_render().
   */
  public function revisionOverview(day11EntityInterface $day11_entity) {
    $account = $this->currentUser();
    $langcode = $day11_entity->language()->getId();
    $langname = $day11_entity->language()->getName();
    $languages = $day11_entity->getTranslationLanguages();
    $has_translations = (count($languages) > 1);
    $day11_entity_storage = $this->entityManager()->getStorage('day11_entity');

    $build['#title'] = $has_translations ? $this->t('@langname revisions for %title', ['@langname' => $langname, '%title' => $day11_entity->label()]) : $this->t('Revisions for %title', ['%title' => $day11_entity->label()]);
    $header = [$this->t('Revision'), $this->t('Operations')];

    $revert_permission = (($account->hasPermission("revert all day11entity revisions") || $account->hasPermission('administer day11entity entities')));
    $delete_permission = (($account->hasPermission("delete all day11entity revisions") || $account->hasPermission('administer day11entity entities')));

    $rows = [];

    $vids = $day11_entity_storage->revisionIds($day11_entity);

    $latest_revision = TRUE;

    foreach (array_reverse($vids) as $vid) {
      /** @var \Drupal\day11\day11EntityInterface $revision */
      $revision = $day11_entity_storage->loadRevision($vid);
      // Only show revisions that are affected by the language that is being
      // displayed.
      if ($revision->hasTranslation($langcode) && $revision->getTranslation($langcode)->isRevisionTranslationAffected()) {
        $username = [
          '#theme' => 'username',
          '#account' => $revision->getRevisionUser(),
        ];

        // Use revision link to link to revisions that are not active.
        $date = \Drupal::service('date.formatter')->format($revision->getRevisionCreationTime(), 'short');
        if ($vid != $day11_entity->getRevisionId()) {
          $link = $this->l($date, new Url('entity.day11_entity.revision', ['day11_entity' => $day11_entity->id(), 'day11_entity_revision' => $vid]));
        }
        else {
          $link = $day11_entity->link($date);
        }

        $row = [];
        $column = [
          'data' => [
            '#type' => 'inline_template',
            '#template' => '{% trans %}{{ date }} by {{ username }}{% endtrans %}{% if message %}<p class="revision-log">{{ message }}</p>{% endif %}',
            '#context' => [
              'date' => $link,
              'username' => \Drupal::service('renderer')->renderPlain($username),
              'message' => ['#markup' => $revision->getRevisionLogMessage(), '#allowed_tags' => Xss::getHtmlTagList()],
            ],
          ],
        ];
        $row[] = $column;

        if ($latest_revision) {
          $row[] = [
            'data' => [
              '#prefix' => '<em>',
              '#markup' => $this->t('Current revision'),
              '#suffix' => '</em>',
            ],
          ];
          foreach ($row as &$current) {
            $current['class'] = ['revision-current'];
          }
          $latest_revision = FALSE;
        }
        else {
          $links = [];
          if ($revert_permission) {
            $links['revert'] = [
              'title' => $this->t('Revert'),
              'url' => $has_translations ?
              Url::fromRoute('entity.day11_entity.translation_revert', ['day11_entity' => $day11_entity->id(), 'day11_entity_revision' => $vid, 'langcode' => $langcode]) :
              Url::fromRoute('entity.day11_entity.revision_revert', ['day11_entity' => $day11_entity->id(), 'day11_entity_revision' => $vid]),
            ];
          }

          if ($delete_permission) {
            $links['delete'] = [
              'title' => $this->t('Delete'),
              'url' => Url::fromRoute('entity.day11_entity.revision_delete', ['day11_entity' => $day11_entity->id(), 'day11_entity_revision' => $vid]),
            ];
          }

          $row[] = [
            'data' => [
              '#type' => 'operations',
              '#links' => $links,
            ],
          ];
        }

        $rows[] = $row;
      }
    }

    $build['day11_entity_revisions_table'] = [
      '#theme' => 'table',
      '#rows' => $rows,
      '#header' => $header,
    ];

    return $build;
  }

}

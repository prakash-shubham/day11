<?php

namespace Drupal\day11\Form;

use Drupal\Core\Datetime\DateFormatterInterface;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Language\LanguageManagerInterface;
use Drupal\day11\Entity\day11EntityInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a form for reverting a Day11entity revision for a single translation.
 *
 * @ingroup day11
 */
class day11EntityRevisionRevertTranslationForm extends day11EntityRevisionRevertForm {


  /**
   * The language to be reverted.
   *
   * @var string
   */
  protected $langcode;

  /**
   * The language manager.
   *
   * @var \Drupal\Core\Language\LanguageManagerInterface
   */
  protected $languageManager;

  /**
   * Constructs a new day11EntityRevisionRevertTranslationForm.
   *
   * @param \Drupal\Core\Entity\EntityStorageInterface $entity_storage
   *   The Day11entity storage.
   * @param \Drupal\Core\Datetime\DateFormatterInterface $date_formatter
   *   The date formatter service.
   * @param \Drupal\Core\Language\LanguageManagerInterface $language_manager
   *   The language manager.
   */
  public function __construct(EntityStorageInterface $entity_storage, DateFormatterInterface $date_formatter, LanguageManagerInterface $language_manager) {
    parent::__construct($entity_storage, $date_formatter);
    $this->languageManager = $language_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity.manager')->getStorage('day11_entity'),
      $container->get('date.formatter'),
      $container->get('language_manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'day11_entity_revision_revert_translation_confirm';
  }

  /**
   * {@inheritdoc}
   */
  public function getQuestion() {
    return t('Are you sure you want to revert @language translation to the revision from %revision-date?', ['@language' => $this->languageManager->getLanguageName($this->langcode), '%revision-date' => $this->dateFormatter->format($this->revision->getRevisionCreationTime())]);
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $day11_entity_revision = NULL, $langcode = NULL) {
    $this->langcode = $langcode;
    $form = parent::buildForm($form, $form_state, $day11_entity_revision);

    $form['revert_untranslated_fields'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Revert content shared among translations'),
      '#default_value' => FALSE,
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  protected function prepareRevertedRevision(day11EntityInterface $revision, FormStateInterface $form_state) {
    $revert_untranslated_fields = $form_state->getValue('revert_untranslated_fields');

    /** @var \Drupal\day11\Entity\day11EntityInterface $default_revision */
    $latest_revision = $this->day11EntityStorage->load($revision->id());
    $latest_revision_translation = $latest_revision->getTranslation($this->langcode);

    $revision_translation = $revision->getTranslation($this->langcode);

    foreach ($latest_revision_translation->getFieldDefinitions() as $field_name => $definition) {
      if ($definition->isTranslatable() || $revert_untranslated_fields) {
        $latest_revision_translation->set($field_name, $revision_translation->get($field_name)->getValue());
      }
    }

    $latest_revision_translation->setNewRevision();
    $latest_revision_translation->isDefaultRevision(TRUE);
    $revision->setRevisionCreationTime(REQUEST_TIME);

    return $latest_revision_translation;
  }

}

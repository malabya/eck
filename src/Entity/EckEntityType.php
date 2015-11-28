<?php

/**
 * @file
 * Contains Drupal\eck\Entity\EckEntityType.
 */

namespace Drupal\eck\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Routing\LinkGeneratorTrait;
use Drupal\eck\EckEntityTypeInterface;

/**
 * Defines the ECK Entity Type config entities.
 *
 * @ConfigEntityType(
 *   id = "eck_entity_type",
 *   label = @Translation("ECK Entity Type"),
 *   handlers = {
 *     "list_builder" = "Drupal\eck\Controller\EckEntityTypeListBuilder",
 *     "form" = {
 *       "add" = "Drupal\eck\Form\EntityType\EckEntityTypeAddForm",
 *       "edit" = "Drupal\eck\Form\EntityType\EckEntityTypeEditForm",
 *       "delete" = "Drupal\eck\Form\EntityType\EckEntityTypeDeleteForm"
 *     }
 *   },
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label"
 *   },
 *   links = {
 *     "edit-form" = "/admin/structure/eck/entity_type/manage/{eck_entity_type}",
 *     "delete-form" = "/admin/structure/eck/entity_type/manage/{eck_entity_type}/delete"
 *   }
 * )
 *
 * @ingroup eck
 */
class EckEntityType extends ConfigEntityBase implements EckEntityTypeInterface {

  use LinkGeneratorTrait;

  /**
   * The ECK entity type ID.
   *
   * @var string
   */
  public $id;

  /**
   * The ECK entity type UUID.
   *
   * @var string
   */
  public $uuid;

  /**
   * The ECK entity type label.
   *
   * @var string
   */
  public $label;

  /**
   * {@inheritdoc}
   */
  public function postSave(EntityStorageInterface $storage, $update = TRUE) {
    parent::postSave($storage, $update);

    // Create an edit link.
    $edit_link = $this->l(t('Edit'), $this->urlInfo());

    if ($update) {
      $this->logger($this->id())->notice(
        'Entity type %label has been updated.',
        ['%label' => $this->label(), 'link' => $edit_link]
      );
    }
    else {
      $entity_manager = $this->entityManager();

      // Clear caches first.
      $entity_manager->clearCachedDefinitions();
      \Drupal::service('router.builder')->rebuild();

      // Notify storage to create the database schema.
      $entity_type = $entity_manager->getDefinition($this->id());
      $entity_manager->onEntityTypeCreate($entity_type);

      $this->logger($this->id())->notice(
        'Entity type %label has been added.',
        ['%label' => $this->label(), 'link' => $edit_link]
      );
    }
  }

  /**
   * Gets the logger for a specific channel.
   *
   * @param string $channel
   *   The name of the channel.
   *
   * @return \Psr\Log\LoggerInterface
   *   The logger for this channel.
   */
  protected function logger($channel) {
    return \Drupal::getContainer()->get('logger.factory')->get($channel);
  }

}

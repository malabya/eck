<?php

/**
 * @file
 * Contains Drupal\eck\Entity\EckEntityType.
 */

namespace Drupal\eck\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBase;
use Drupal\eck\EckEntityTypeInterface;

/**
 * Defines the ECK Entity Type config entities.
 *
 * @ConfigEntityType(
 *   id = "eck_entity_type",
 *   label = @Translation("ECK Entity Type"),
 *   admin_permission = "administer ECK entity types",
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
 *     "edit-form" = "eck.entity_type.edit",
 *     "delete-form" = "eck.entity_type.delete"
 *   }
 * )
 *
 * @ingroup eck
 */
class EckEntityType extends ConfigEntityBase implements EckEntityTypeInterface {

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
   * The ECK entity type author flag.
   *
   * @var string
   */
  public $author;

  /**
   * The ECK entity type created flag.
   *
   * @var string
   */
  public $created;

  /**
   * The ECK entity type changed flag.
   *
   * @var string
   */
  public $changed;

  /**
   * The ECK entity type language flag.
   *
   * @var string
   */
  public $language;

}

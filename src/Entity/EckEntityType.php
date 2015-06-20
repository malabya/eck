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

}

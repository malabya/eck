<?php

/**
 * @file
 * Contains \Drupal\eck\Entity\EckEntityBundle.
 */

namespace Drupal\eck\Entity;


use Drupal\Core\Config\Entity\ConfigEntityBundleBase;
use Drupal\Core\Config\Entity\ConfigEntityStorage;
use Drupal\Core\Config\Entity\ThirdPartySettingsTrait;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\eck\EckEntityBundleInterface;

/**
 * Defines the Node type configuration entity.
 *
 * @ingroup eck
 */
class EckEntityBundle extends ConfigEntityBundleBase implements EckEntityBundleInterface {

  /**
   * The machine name of this ECK entity bundle.
   *
   * @var string
   */
  public $type;

  /**
   * The human-readable name of the ECK entity type.
   *
   * @var string
   */
  public $name;

  /**
   * A brief description of this ECK bundle.
   *
   * @var string
   */
  public $description;

  /**
   * Help information shown to the user when creating an Entity of this bundle.
   *
   * @var string
   */
  public $help;

  /**
   * The machine name of the entity type.
   *
   * @var string
   *
   * @todo: rename this.
   */
  public $entity_type;

  /**
   * {@inheritdoc}
   */
  public function id() {
    return $this->type;
  }

  /**
   * {@inheritdoc}
   */
  public function label() {
    return $this->name;
  }

  /**
   * {@inheritdoc}
   */
  public static function postDelete(
    EntityStorageInterface $storage,
    array $entities
  ) {
    parent::postDelete($storage, $entities);

    // Clear the cache.
    $storage->resetCache(array($entities));
  }

  /**
   * {@inheritdoc}
   */
  public function isLocked() {
    $locked = \Drupal::state()->get('eck_entity.type.locked');
    return isset($locked[$this->id()]) ? $locked[$this->id()] : FALSE;
  }

  /**
   * {@inheritdoc}
   */
  public static function loadMultiple(array $ids = NULL) {
    // Because we use a single class for multiple entity bundles we need to
    // parse all entity types and load the bundles.
    $entity_manager = \Drupal::entityManager();
    $bundles = array();
    foreach (EckEntityType::loadMultiple() as $entity) {
      $bundles = array_merge($bundles, $entity_manager->getStorage($entity->id() . '_type')->loadMultiple($ids));
    }

    return $bundles;
  }

  /**
   * {@inheritdoc}
   */
  public static function load($id) {
    // Because we use a single class for multiple entity bundles we need to
    // parse all entity types and find the id.
    $entity_manager = \Drupal::entityManager();
    $loaded_entity = NULL;
    foreach (EckEntityType::loadMultiple() as $entity) {
      $load = $entity_manager->getStorage($entity->id() . '_type')->load($id);
      $loaded_entity = empty($load) ? $loaded_entity : $load;;
    }

    return $loaded_entity;
  }

}

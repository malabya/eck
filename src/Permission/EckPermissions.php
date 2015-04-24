<?php

/**
 * @file
 * Contains \Drupal\eck\Permission\EckPermissions.
 */

namespace Drupal\eck\Permission;

use Drupal\Core\Routing\UrlGeneratorTrait;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\eck\Entity\EckEntityBundle;
use Drupal\eck\Entity\EckEntityType;

/**
 * Defines dynamic permissions.
 *
 * @ingroup eck
 */
class EckPermissions {
  use StringTranslationTrait;
  use UrlGeneratorTrait;

  /**
   * Returns an array of entity type permissions.
   *
   * @return array
   *   The permissions.
   */
  public function entityTypePermissions() {
    $perms = array();
    // Generate entity permissions for all entity types.
    foreach (EckEntityBundle::loadMultiple() as $eck_type) {
      $perms = array_merge($perms, $this->buildPermissions($eck_type));
    }

    return $perms;
  }

  /**
   * Builds a standard list of entity permissions for a given type.
   *
   * @param EckEntityType $eck_type
   *   The entity type.
   *
   * @return array
   *   An array of permissions.
   */
  public function buildPermissions(EckEntityType $eck_type) {
    $type_id = $eck_type->id();
    $type_params = array('%type_name' => $eck_type->label());

    return array(
      "create $type_id entity" => array(
        'title' => $this->t('%type_name: Create new entity', $type_params),
      ),
      "edit own $type_id entity" => array(
        'title' => $this->t('%type_name: Edit own entity', $type_params),
      ),
      "edit any $type_id entity" => array(
        'title' => $this->t('%type_name: Edit any entity', $type_params),
      ),
      "delete own $type_id entity" => array(
        'title' => $this->t('%type_name: Delete own entity', $type_params),
      ),
      "delete any $type_id entity" => array(
        'title' => $this->t('%type_name: Delete any entity', $type_params),
      ),
      "view own $type_id entity" => array(
        'title' => $this->t('%type_name: View own entity', $type_params),
      ),
      "view any $type_id entity" => array(
        'title' => $this->t('%type_name: View any entity', $type_params),
      ),
    );

  }

}

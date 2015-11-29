<?php

/**
 * @file
 * Contains \Drupal\eck\Permission\EckPermissions.
 */

namespace Drupal\eck\Permission;

use Drupal\Core\Routing\UrlGeneratorTrait;
use Drupal\Core\StringTranslation\StringTranslationTrait;
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
    foreach (EckEntityType::loadMultiple() as $eck_type) {
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

    $create_permission = [
      "create {$type_id} entities" => [
        'title' => $this->t('Create new %type_name entities', $type_params),
      ],
    ];

    $own_permissions = [];
    if ($eck_type->uid) {
      $own_permissions = [
        "edit own {$type_id} entities" => [
          'title' => $this->t('Edit own %type_name entities', $type_params),
        ],
        "delete own {$type_id} entities" => [
          'title' => $this->t('Delete own %type_name entities', $type_params),
        ],
        "view own {$type_id} entities" => [
          'title' => $this->t('View own %type_name entities', $type_params),
        ],
      ];
    }

    $any_permissions = [
      "edit any {$type_id} entities" => [
        'title' => $this->t('Edit any %type_name entities', $type_params),
      ],
      "delete any {$type_id} entities" => [
        'title' => $this->t('Delete any %type_name entities', $type_params),
      ],
      "view any {$type_id} entities" => [
        'title' => $this->t('View any %type_name entities', $type_params),
      ],
    ];

    return array_merge($create_permission, $own_permissions, $any_permissions);
  }

}

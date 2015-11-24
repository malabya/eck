<?php

/**
 * @file
 * Contains \Drupal\eck\Entity\EckEntityRouteProvider.
 */

namespace Drupal\eck\Entity;

use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Entity\Routing\EntityRouteProviderInterface;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;

/**
 * Provides routes for eck entities.
 */
class EckEntityRouteProvider implements EntityRouteProviderInterface {


  /**
   * {@inheritdoc}
   */
  public function getRoutes(EntityTypeInterface $entity_type) {
    $route_collection = new RouteCollection();

    // Get all entity types.
    $eck_types = EckEntityType::loadMultiple();

    foreach ($eck_types as $eck_type) {
      // Route for view.
      $route_view = (
      new Route(
        'admin/structure/eck/entity/' . $eck_type->id . '/{' . $eck_type->id . '}'
      )
      )
        ->addDefaults(
          array(
            '_entity_view' => $eck_type->id,
            '_title' => $eck_type->label,
          )
        )
        ->setRequirement('_entity_access', $eck_type->id . '.view');

      // Add the route.
      $route_collection->add(
        'entity.' . $eck_type->id . '.canonical',
        $route_view
      );

      // Route for edit.
      $route_edit = new Route(
        'admin/structure/eck/entity/' . $eck_type->id . '/{' . $eck_type->id . '}/edit',
        array(
          '_entity_form' => $eck_type->id . '.edit',
          '_title' => 'Edit' . $eck_type->label,
        ),
        array(
          '_entity_access' => $eck_type->id . '.edit',
        )
      );
      // Add the route.
      $route_collection->add(
        'entity.' . $eck_type->id . '.edit_form',
        $route_edit
      );

      // Route for delete.
      $route_delete = new Route(
        'admin/structure/eck/entity/' . $eck_type->id . '/{' . $eck_type->id . '}/delete',
        array(
          '_entity_form' => $eck_type->id . '.delete',
          '_title' => 'Delete' . $eck_type->label,
        ),
        array(
          '_entity_access' => $eck_type->id . '.delete',
        )
      );
      // Add the route.
      $route_collection->add(
        'entity.' . $eck_type->id . '.delete_form',
        $route_delete
      );
    }
    return $route_collection;
  }

}

<?php

/**
 * @file
 * Contains \Drupal\eck\Routing\EckRoutes.
 */

namespace Drupal\eck\Routing;

use Drupal\eck\Entity\EckEntityType;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

/**
 * Defines dynamic routes.
 *
 * @ingroup eck
 */
class EckRoutes {

  /**
   * {@inheritdoc}
   */
  public function routes() {
    $route_collection = new RouteCollection();
    // Get all entity types.
    $eck_types = EckEntityType::loadMultiple();

    foreach ($eck_types as $eck_type) {
      $t_args = array('@entity' => $eck_type->label);

      // Route for list.
      $route_list = new Route(
        'admin/structure/eck/entity/' . $eck_type->id,
        array(
          '_entity_list' => $eck_type->id,
          '_title' => t('@entity list', $t_args),
        ),
        array(
          '_permission' => 'view eck entity',
        )
      );
      // Add the route.
      $route_collection->add('eck.entity.' . $eck_type->id . '.list', $route_list);

      // Route for type list.
      $route_type_list = new Route(
        'admin/structure/eck/entity/' . $eck_type->id . '/types',
        array(
          '_controller' => '\Drupal\Core\Entity\Controller\EntityListController::listing',
          'entity_type' => $eck_type->id . '_type',
          '_title' => t('@entity types', $t_args),
        ),
        array(
          '_permission' => 'administer eck entity bundles',
        )
      );
      // Add the route.
      $route_collection->add('eck.entity.' . $eck_type->id . '_type.list', $route_type_list);

      // Route for type add.
      $route_type_add = new Route(
        'admin/structure/eck/entity/' . $eck_type->id . '/types/add',
        array(
          '_entity_form' => $eck_type->id . '_type.add',
          '_title' => t('Add @entity type', $t_args),
        ),
        array(
          '_permission' => 'administer eck entity bundles',
        )
      );
      // Add the route.
      $route_collection->add('eck.entity.' . $eck_type->id . '_type.add', $route_type_add);

      // Route for type edit.
      $route_type_edit = new Route(
        'admin/structure/eck/entity/' . $eck_type->id . '/types/manage/{' . $eck_type->id . '_type}',
        array(
          '_entity_form' => $eck_type->id . '_type.edit',
          '_title' => t('Edit @entity type', $t_args),
        ),
        array(
          '_permission' => 'administer eck entity bundles',
        )
      );
      // Add the route.
      $route_collection->add('entity.' . $eck_type->id . '_type.edit_form', $route_type_edit);

      // Route for type delete.
      $route_type_delete = new Route(
        'admin/structure/eck/entity/' . $eck_type->id . '/types/manage/{' . $eck_type->id . '_type}/delete',
        array(
          '_entity_form' => $eck_type->id . '_type.delete',
          '_title' => t('Delete @entity type', $t_args),
        ),
        array(
          '_permission' => 'administer eck entity bundles',
        )
      );
      // Add the route.
      $route_collection->add('entity.' . $eck_type->id . '_type.delete_form', $route_type_delete);
    }

    return $route_collection;
  }

}

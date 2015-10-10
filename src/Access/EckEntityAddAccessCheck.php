<?php

/**
 * @file
 * Contains \Drupal\eck\Access\EckEntityAddAccessCheck.
 */

namespace Drupal\eck\Access;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Entity\EntityManagerInterface;
use Drupal\Core\Routing\Access\AccessInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\eck\EckEntityTypeInterface;

/**
 * Determines access for ECK entity add page.
 */
class EckEntityAddAccessCheck implements AccessInterface {

  /**
   * The entity manager.
   *
   * @var \Drupal\Core\Entity\EntityManagerInterface $entity_manager.
   */
  protected $entityManager;

  /**
   * Constructs an EckEntityAddAccessCheck object.
   *
   * @param \Drupal\Core\Entity\EntityManagerInterface $entity_manager
   *   The entity manager.
   */
  public function __construct(EntityManagerInterface $entity_manager) {
    $this->entityManager = $entity_manager;
  }

  /**
   * Checks access to the eck entity add page for the entity bundle type.
   *
   * @param AccountInterface $account
   *   The currently logged in account.
   * @param EckEntityTypeInterface $eck_entity_type
   *   The entity type.
   * @param string $eck_entity_bundle
   *   (optional) The entity type bundle.
   *
   * @return bool|AccessResult|\Drupal\Core\Access\AccessResultInterface
   *   A \Drupal\Core\Access\AccessInterface constant value.
   */
  public function access(AccountInterface $account, EckEntityTypeInterface $eck_entity_type, $eck_entity_bundle = NULL) {
    $access_control_handler = $this->entityManager->getAccessControlHandler($eck_entity_type->id());
    if (!empty($eck_entity_bundle)) {
      return $access_control_handler->createAccess($eck_entity_bundle, $account, array(), TRUE);
    }
    // Get the entity type bundles.
    $bundles = $this->entityManager->getStorage($eck_entity_type->id() . '_type')->loadMultiple();

    // If checking whether an entity of any type may be created.
    foreach ($bundles as $eck_entity_bundle) {
      if (($access = $access_control_handler->createAccess($eck_entity_bundle->id(), $account, array(), TRUE)) && $access->isAllowed()) {
        return $access;
      }
    }

    // No opinion.
    return AccessResult::neutral();
  }

}

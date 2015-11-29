<?php

/**
 * @file
 * Contains \Drupal\eck\EckEntityAccessControlHandler.
 */

namespace Drupal\eck;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;

/**
 * Access controller for the EckEntity entity.
 *
 * @ingroup eck
 *
 * @see \Drupal\eck\Entity\EckEntity.
 */
class EckEntityAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  public function access(EntityInterface $entity, $operation, AccountInterface $account = NULL, $return_as_object = FALSE) {
    $account = $this->prepareUser($account);
    // Checks for bypass permission.
    if ($account->hasPermission('bypass eck entity access')) {
      $result = AccessResult::allowed()->cachePerPermissions();
      return $return_as_object ? $result : $result->isAllowed();
    }

    $result = parent::access($entity, $operation, $account, TRUE)
      ->cachePerPermissions();

    return $return_as_object ? $result : $result->isAllowed();
  }

  /**
   * {@inheritdoc}
   */
  public function createAccess($entity_bundle = NULL, AccountInterface $account = NULL, array $context = array(), $return_as_object = FALSE) {
    $account = $this->prepareUser($account);
    // Checks for bypass permission.
    if ($account->hasPermission('bypass eck entity access') && $account) {
      $result = AccessResult::allowed()->cachePerPermissions();

      return $return_as_object ? $result : $result->isAllowed();
    }

    $result = parent::createAccess($entity_bundle, $account, $context, TRUE)
      ->cachePerPermissions();

    return $return_as_object ? $result : $result->isAllowed();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    $permissions[] = $operation . ' any ' . $entity->getEntityTypeId() . ' entities';
    /** @var \Drupal\eck\Entity\EckEntity $entity */
    if ($entity->getOwnerId() == $account->id()) {
      $permissions[] = $operation . ' own ' . $entity->getEntityTypeId() . ' entities';
    }

    return AccessResult::allowedIfHasPermissions($account, $permissions, 'OR');
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    $permissions = [
      'create ' . $this->entityTypeId . ' entities',
    ];

    if (!empty($entity_bundle)) {
      $permissions[] = 'create ' . $this->entityTypeId . ' entities of bundle ' . $entity_bundle;
    }
    return AccessResult::allowedIfHasPermissions($account, $permissions, 'OR')
      ->cachePerPermissions();
  }

}

<?php

/**
 * @file
 * Contains \Drupal\eck\EckEntityAccessControlHandler.
 */

namespace Drupal\eck;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Language\LanguageInterface;
use Drupal\Core\Session\AccountInterface;

/**
 * Access controller for the comment entity.
 *
 * @ingroup eck
 *
 * @see \Drupal\eck\Entity\EckEntity.
 */
class EckEntityAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  public function access(EntityInterface $entity, $operation, $langcode = LanguageInterface::LANGCODE_DEFAULT, AccountInterface $account = NULL, $return_as_object = FALSE) {
    $account = $this->prepareUser($account);
    // Checks for bypass permission.
    if ($account->hasPermission('bypass eck entity access')) {
      $result = AccessResult::allowed()->cachePerPermissions();
      return $return_as_object ? $result : $result->isAllowed();
    }
    // Check if the user has permission to access eck entities.
    if (!$account->hasPermission('access eck entities')) {
      $result = AccessResult::forbidden()->cachePerPermissions();
      return $return_as_object ? $result : $result->isAllowed();
    }

    $result = parent::access($entity, $operation, $langcode, $account, TRUE)->cachePerPermissions();

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
    // Check if the user has permission to access eck entities.
    if (!$account->hasPermission('access eck entities')) {
      $result = AccessResult::forbidden()->cachePerPermissions();
      return $return_as_object ? $result : $result->isAllowed();
    }

    $result = parent::createAccess($entity_bundle, $account, $context, TRUE)->cachePerPermissions();

    return $return_as_object ? $result : $result->isAllowed();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, $langcode, AccountInterface $account) {
    if ($entity->getOwnerId() == $account->id()) {
      return AccessResult::allowedIfHasPermission($account, $operation . ' own ' . $entity->bundle() . ' entity');
    }

    return AccessResult::allowedIfHasPermission($account, $operation . ' any ' . $entity->bundle() . ' entity');
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIf($account->hasPermission('create ' . $entity_bundle . ' entity'))->cachePerPermissions();
  }

}

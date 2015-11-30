<?php

/**
 * @file
 * Contains \Drupal\eck\Controller\EckEntityTypeListBuilder.
 */

namespace Drupal\eck\Controller;

use Drupal\Core\Config\Entity\ConfigEntityListBuilder;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Url;

/**
 * Provides a listing of ECK entities.
 *
 * @ingroup eck
 */
class EckEntityTypeListBuilder extends ConfigEntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['label'] = $this->t('Entity Type');
    $header['machine_name'] = $this->t('Machine Name');

    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    $row['label'] = $entity->label();
    $row['machine_name'] = $entity->id();

    /** @var \Drupal\Core\Entity\EntityTypeBundleInfo $bundleInfo */
    $bundleInfo = \Drupal::service('entity_type.bundle.info');
    $bundles = $bundleInfo->getBundleInfo($entity->id());

    $bundles_present = TRUE;
    if (empty($bundles) || count($bundles) == 1 && isset($bundles[$entity->id()])) {
      $bundles_present = FALSE;
    }

    if (!$bundles_present) {
      $row['operations']['data']['#links']['add_bundle'] = [
        'title' => $this->t('Add bundle'),
        'url' => new Url('eck.entity.' . $entity->id() . '_type.add'),
      ];
    }

    if ($bundles_present) {
      // Add link to list operation.
      $row['operations']['data']['#links']['add_content'] = [
        'title' => $this->t('Add content'),
        'url' => new Url('eck.entity.add_page', ['eck_entity_type' => $entity->id()]),
      ];
      // Directly link to the add entity page if there is only one bundle.
      if (count($bundles) == 1) {
        $bundle_machine_names = array_keys($bundles);
        $arguments = ['eck_entity_type' => $entity->id(), 'eck_entity_bundle' => reset($bundle_machine_names)];
        $row['operations']['data']['#links']['add_content']['url'] = new Url('eck.entity.add', $arguments);
      }

      $contentExists = (bool) \Drupal::entityQuery($entity->id())->range(0, 1)->execute();
      if ($contentExists) {
        // Add link to list operation.
        $row['operations']['data']['#links']['content_list'] = [
          'title' => $this->t('Content list'),
          'url' => new Url('eck.entity.' . $entity->id() . '.list'),
        ];
      }
    }

    $row['operations']['data']['#links']['bundle_list'] = [
      'title' => $this->t('Bundle list'),
      'url' => new Url('eck.entity.' . $entity->id() . '_type.list'),
    ];

    return array_merge_recursive($row, parent::buildRow($entity));
  }

}

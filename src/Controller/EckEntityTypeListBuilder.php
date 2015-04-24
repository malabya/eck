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
    $row['label'] = $this->getLabel($entity);
    $row['machine_name'] = $entity->id();
    // Add link to list operation.
    $row['operations']['data']['#links']['bundle_list'] = array(
      'title' => $this->t('Bundle list'),
      'weight' => -10,
      'url' => new Url('eck.entity.' . $entity->id() . '_type.list'),
    );

    // Add link to list operation.
    $row['operations']['data']['#links']['add_content'] = array(
      'title' => $this->t('Add content'),
      'weight' => -10,
      'url' => new Url('eck.entity.add_page', array('eck_entity_type' => $entity->id)),
    );
    $url = array_merge_recursive($row, parent::buildRow($entity));

    return array_merge_recursive($row, parent::buildRow($entity));
  }

}

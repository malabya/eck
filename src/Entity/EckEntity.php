<?php

/**
 * @file
 * Contains \Drupal\eck\Entity\EckEntity.
 */

namespace Drupal\eck\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\eck\EckEntityInterface;
use Drupal\user\UserInterface;

/**
 * Defines the ECK entity.
 *
 * @ingroup eck
 */
class EckEntity extends ContentEntityBase implements EckEntityInterface {

  /**
   * {@inheritdoc}
   */
  public static function preCreate(
    EntityStorageInterface $storage_controller,
    array &$values
  ) {
    parent::preCreate($storage_controller, $values);
    $values += array(
      'uid' => \Drupal::currentUser()->id(),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getCreatedTime() {
    return $this->get('created')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwner() {
    return $this->get('uid')->entity;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwnerId() {
    return $this->get('uid')->target_id;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwnerId($uid) {
    $this->set('uid', $uid);

    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwner(UserInterface $account) {
    $this->set('uid', $account->id());

    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    // The primary key field.
    $fields['id'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('ID'))
      ->setDescription(t('The ID of the eck entity.'))
      ->setReadOnly(TRUE);

    // Standard field, universal unique id.
    $fields['uuid'] = BaseFieldDefinition::create('uuid')
      ->setLabel(t('ID'))
      ->setDescription(t('The UUID of the entity.'))
      ->setReadOnly(TRUE);

    $fields['type'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Type'))
      ->setDescription(t('The entity type.'))
      ->setSetting('target_type', $entity_type->id() . '_type')
      ->setReadOnly(TRUE);

    // Title field for the entity.
    $fields['title'] = BaseFieldDefinition::create('string')
      ->setLabel(t('title'))
      ->setDescription(t('The title of the entity.'))
      ->setRequired(TRUE)
      ->setTranslatable(TRUE)
      ->setSettings(
        array(
          'default_value' => '',
          'max_length' => 255,
          'text_processing' => 0,
        )
      )
      ->setDisplayOptions(
        'view',
        array(
          'label' => 'above',
          'type' => 'string',
          'weight' => -6,
        )
      )
      ->setDisplayOptions(
        'form',
        array(
          'type' => 'string',
          'weight' => -6,
        )
      )
      ->setDisplayConfigurable('view', TRUE)
      ->setDisplayConfigurable('form', TRUE);

    // The following base fields are set only if the user selected them. In the
    // future we need to find a better solution for defining this base fields.
    // @todo: Find a dynamic way to add base fields.
    // Owner field of the entity.
    $fields['uid'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Authored by'))
      ->setDescription(t('The username of the entity author.'))
      ->setSetting('target_type', 'user')
      ->setSetting('handler', 'default')
      ->setTranslatable(TRUE)
      ->setDisplayOptions(
        'view',
        array(
          'label' => 'above',
          'type' => 'entity_reference',
          'weight' => -3,
        )
      )
      ->setDisplayConfigurable('view', TRUE);

    $fields['langcode'] = BaseFieldDefinition::create('language')
      ->setLabel(t('Language code'))
      ->setDescription(t('The language code of the entity.'))
      ->setTranslatable(TRUE);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Authored on'))
      ->setTranslatable(TRUE)
      ->setDescription(t('The time that the entity was created.'));

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setTranslatable(TRUE)
      ->setDescription(t('The time that the entity was last edited.'));

    return $fields;
  }

}

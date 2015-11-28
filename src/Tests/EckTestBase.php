<?php

/**
 * @file
 * Contains \Drupal\eck\Tests\EckTestBase
 */

namespace Drupal\eck\Tests;

use Drupal\Core\Url;
use Drupal\simpletest\WebTestBase;

/**
 * Base class for eck tests
 */
abstract class EckTestBase extends WebTestBase {

  /**
   * Modules to install.
   *
   * @var array
   */
  public static $modules = array('node', 'eck');

  public function setUp() {
    parent::setUp();
    $user = $this->createUser([
      'administer eck entity types',
      'administer eck entities',
      'administer eck entity bundles',
      'bypass eck entity access',
    ]);
    $this->drupallogin($user);
  }

  /**
   * Returns an array of the configurable base fields.
   * @return array
   */
  protected function getConfigurableBaseFields() {
    return ['created', 'changed', 'uid', 'title'];
  }

  /**
   * Creates an entity type with a given label and/or enabled base fields.
   *
   * @param array $fields
   *   The fields that should be enabled for this entity type.
   * @param string $label
   *   The name of the entity type.
   *
   * @return array
   *   Information about the created entity type.
   *   - id:    the type's machine name
   *   - label: the type's label.
   */
  protected function createEntityType($fields = [], $label = '') {
    $label = empty($label) ? $this->randomMachineName() : $label;
    $fields = empty($fields) ? $this->getConfigurableBaseFields() : $fields;

    $edit = [
      'label' => $label,
      'id' => $id = strtolower($label),
    ];

    foreach ($fields as $field) {
      $edit[$field] = TRUE;
    }

    $this->drupalPostForm(Url::fromRoute('eck.entity_type.add'), $edit, t('Create entity type'));
    $this->assertRaw(t('Entity type %label has been added.', array('%label' => $label)));

    return ['id' => $id, 'label' => $label];
  }

  /**
   * Adds a bundle for a given entity type.
   *
   * @param $entity_type
   *  The entity type to add the bundle for.
   *
   * @return string
   *  The machine name of the newly created bundle.
   */
  protected function createEntityBundle($entity_type) {
    $label = $this->randomMachineName();
    $bundle = strtolower($label);

    $edit = [
      'name' => $label,
      'type' => $bundle,
    ];
    $this->drupalPostForm("admin/structure/eck/entity/{$entity_type}/types/add", $edit, t('Save bundle'));
    $this->assertRaw(t('The entity bundle %name has been added.', array('%name' => $label)));

    return $bundle;
  }

}

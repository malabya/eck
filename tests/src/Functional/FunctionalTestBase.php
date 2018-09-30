<?php

namespace Drupal\Tests\eck\Functional;

use Drupal\Core\Url;
use Drupal\Tests\BrowserTestBase;

/**
 * Provides common functionality for ECK functional tests.
 */
abstract class FunctionalTestBase extends BrowserTestBase {

  /**
   * {@inheritdoc}
   */
  public static $modules = ['node', 'eck'];

  /**
   * {@inheritdoc}
   */
  public function setUp() {
    parent::setUp();

    $permissions = [
      'administer eck entity types',
      'administer eck entities',
      'administer eck entity bundles',
      'bypass eck entity access',
    ];
    $user = $this->createUser($permissions);
    $this->drupalLogin($user);
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
    $this->assertSession()->responseContains("Entity type <em class=\"placeholder\">$label</em> has been added.");
    return ['id' => $id, 'label' => $label];
  }

  /**
   * Returns an array of the configurable base fields.
   *
   * @return array
   */
  protected function getConfigurableBaseFields() {
    return ['created', 'changed', 'uid', 'title'];
  }

}

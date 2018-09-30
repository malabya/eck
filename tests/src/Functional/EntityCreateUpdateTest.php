<?php

namespace Drupal\Tests\eck\Functional;

use Drupal\Core\Url;

/**
 * Tests if eck entities are correctly created and updated
 *
 * @group eck
 *
 * @codeCoverageIgnore because we don't have to test the tests
 */
class EntityCreateUpdateTest extends FunctionalTestBase {

  public function testEntityCreationDoesNotResultInMismatchedEntityDefinitions() {
    $this->createEntityType([], 'TestType');

    $this->assertNoMismatchedFieldDefinitions();
  }

  public function testIfEntityUpdateDoesNotResultInMismatchedEntityDefinitions() {
    $this->createEntityType([], 'TestType');

    $routeArguments = ['eck_entity_type' => 'testtype'];
    $route = 'entity.eck_entity_type.edit_form';
    $edit = ['created' => FALSE];
    $submitButton = t('Update @type', ['@type' => 'TestType']);
    $this->drupalPostForm(Url::fromRoute($route, $routeArguments), $edit, $submitButton);

    $this->assertNoMismatchedFieldDefinitions();
  }

  private function assertNoMismatchedFieldDefinitions() {
    $this->drupalGet(Url::fromRoute('system.status'));
    $this->assertSession()->responseNotContains('Mismatched entity and/or field definitions');
  }

}

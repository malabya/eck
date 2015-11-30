<?php
/**
 * @file
 *  contains \Drupal\eck\Tests\ActionTest
 */

namespace Drupal\eck\Tests;
use Drupal\Core\Url;

/**
 * Tests if eck's UI elements are working properly.
 *
 * @group eck
 */
class EckUITest extends EckTestBase {

  /**
   * Modules to install.
   *
   * @var array
   */
  public static $modules = ['node', 'eck', 'block'];

  public function setUp() {
    parent::setUp();

    // Place the actions block, to test if the actions are placed correctly.
    $this->drupalPlaceBlock('local_actions_block');
  }

  /**
   * Makes sure the Add entity type actions are properly implemented.
   */
  public function testAddEntityTypeActions() {
    $this->drupalGet(Url::fromRoute('eck.entity_type.list'));
    $this->assertLink(t('Add entity type'));
  }

  /**
   * Makes sure the listing titles of entity type listings are correct.
   */
  public function testListingTitles() {
    $type = $this->createEntityType();
    $bundle = $this->createEntityBundle($type['id']);
    $this->drupalPlaceBlock('page_title_block');

    // Test title of the entity types listing.
    $this->drupalGet(Url::fromRoute('eck.entity_type.list'));
    $this->assertRaw("ECK Entity Types");

    // Test title of the entity bundles listing.
    $this->drupalGet(Url::fromRoute('eck.entity.' . $type['id'] . '_type.list'));
    $this->assertRaw(t('%type bundles', ['%type' => ucfirst($type['label'])]));

    // Test title of the add bundle page.
    $this->drupalGet(Url::fromRoute('eck.entity.' . $type['id'] . '_type.add'));
    $this->assertRaw(t('Add %type bundle', ['%type' => $type['label']]));

    // Test title of the edit bundle page.
    $this->drupalGet(Url::fromRoute('entity.' . $type['id'] . '_type.edit_form', [$type['id'] . '_type' => $bundle['type']]));
    $this->assertRaw(t('Edit %type bundle', ['%type' => $type['label']]));

    // Test title of the delete bundle page.
    $this->drupalGet(Url::fromRoute('entity.' . $type['id'] . '_type.delete_form', [$type['id'] . '_type' => $bundle['type']]));
    $this->assertRaw(t('Are you sure you want to delete the entity bundle %type?', ['%type' => $bundle['name']]));

    // Test title of the entity content listing.
    $this->drupalGet(Url::fromRoute('eck.entity.' . $type['id'] . '.list'));
    $this->assertRaw(t('%type content', ['%type' => ucfirst($type['label'])]));
  }

  /**
   * Makes sure the operations on the entity listing page work as expected.
   */
  public function testEntityListingOperations() {
    $entityManager = \Drupal::entityTypeManager();
    $entity = $entityManager->getDefinition('eck_entity_type');
    $this->drupalGet(Url::fromRoute('eck.entity_type.list'));
    $this->assertText(t('There is no @label yet.', array('@label' => $entity->getLabel())));

    $entityType = $this->createEntityType();
    $this->drupalGet(Url::fromRoute('eck.entity_type.list'));
    $this->assertNoText(t('There is no @label yet.', array('@label' => $entity->getLabel())));
    foreach (['Add content', 'Content list'] as $option) {
      $this->assertNoLink(t($option), t('No %option option is shown when there are no bundles.', ['%option' => t($option)]));
    }
    $this->assertLink(t('Add bundle'));
    $this->assertLink(t('Bundle list'));
    $this->assertLink(t('Edit'));
    $this->assertLink(t('Delete'));

    $bundles[] = $this->createEntityBundle($entityType['id']);
    $this->drupalGet(Url::fromRoute('eck.entity_type.list'));
    $this->assertNoText(t('There is no @label yet.', array('@label' => $entity->getLabel())));
    $this->assertNoLink(t('Content list'), t('No %option option is shown when there is no content.', ['%option' => t('Content list')]));
    $this->assertLink(t('Add content'));
    $this->assertLink(t('Bundle list'));
    $this->assertLink(t('Edit'));
    $this->assertLink(t('Delete'));

    // Since there is only one bundle. The add content link should point
    // directly to the correct add entity form. We should be able to add a new
    // entity directly after clicking the link.
    $this->clickLink(t('Add content'));
    $this->drupalPostForm(NULL, ['title[0][value]' => $this->randomMachineName()], t('Save'));
    // There is now content in the datbase, which means the content list link
    // should also be displayed.
    $this->drupalGet(Url::fromRoute('eck.entity_type.list'));
    $this->assertNoText(t('There is no @label yet.', array('@label' => $entity->getLabel())));
    $this->assertLink(t('Content list'));
    $this->assertLink(t('Add content'));
    $this->assertLink(t('Bundle list'));
    $this->assertLink(t('Edit'));
    $this->assertLink(t('Delete'));

    // If there are multiple bundles, clicking the add Content button should end
    // up with a choice between all available bundles.
    $bundles[] = $this->createEntityBundle($entityType['id']);
    $this->drupalGet(Url::fromRoute('eck.entity_type.list'));
    $this->clickLink(t('Add content'));
    foreach ($bundles as $bundle) {
      $this->assertRaw($bundle['name']);
    }
  }

}

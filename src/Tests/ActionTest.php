<?php
/**
 * @file
 *  contains \Drupal\eck\Tests\ActionTest
 */

namespace Drupal\eck\Tests;
use Drupal\Core\Url;

/**
 * Tests if eck's actions are properly defined.
 *
 * @group eck
 */
class ActionTest extends EckTestBase {

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

}

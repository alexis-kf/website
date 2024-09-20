<?php

namespace Drupal\Tests\viewsreference\Functional;

use Drupal\Tests\BrowserTestBase;

/**
 * Test the Views Reference Setting Plugins.
 *
 * @group viewsreference
 */
class ViewsReferenceSettingsTest extends BrowserTestBase {

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

  /**
   * A user with permission to bypass access content.
   *
   * @var \Drupal\user\UserInterface
   */
  protected $adminUser;

  /**
   * Modules to enable.
   *
   * @var array
   */
  protected static $modules = [
    'viewsreference',
    'viewsreference_test',
    'node',
    'user',
    'block',
    'views_ui',
    'field_ui',
  ];

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();
    $this->adminUser = $this->drupalCreateUser([
      'access content',
      'bypass node access',
      'administer nodes',
      'administer content types',
      'administer node fields',
      'administer node display',
      'administer node form display',
    ]);
    $this->drupalLogin($this->adminUser);

    $this->createDefaultPageWithView();
    $this->createSampleNodes();
  }

  /**
   * Create sample nodes for the test view.
   */
  protected function createSampleNodes() {
    foreach (range(1, 100) as $number) {
      $this->drupalCreateNode([
        'body' => [
          'value' => 'Body for item ' . $number,
          'format' => filter_default_format(),
        ],
      ]);
    }
  }

  /**
   * Create a content item with a reference to a view for testing.
   */
  protected function createDefaultPageWithView() {
    $this->drupalGet('node/add/test_content_type');
    $this->assertSession()->pageTextContains('Views Reference Field');
    $this->submitForm([
      'title[0][value]' => 'Test',
      'field_views_reference_field[0][target_id]' => 'test_view',
    ], 'Save');
    $this->submitForm([
      'title[0][value]' => 'Test',
      'field_views_reference_field[0][target_id]' => 'test_view',
      'field_views_reference_field[0][display_id]' => 'block_1',
    ], 'Save');
  }

  /**
   * Test the pager setting plugin.
   */
  public function testPager() {
    // Check that the pager is the default full.
    $this->drupalGet('node/1');
    $this->assertSession()->elementsCount('css', '.pager__item', 12);

    // Change to mini pager and check that it is overridden.
    $this->drupalGet('node/1/edit');
    $this->submitForm([
      'field_views_reference_field[0][options][pager]' => 'mini',
    ], 'Save');
    $this->drupalGet('node/1');
    $this->assertSession()->elementNotExists('css', '.pager__item');
    $this->assertSession()->pageTextContains('Next ›');
  }

  /**
   * Test the limit of items per page.
   */
  public function testLimit() {
    // Check that the limit shows the default number of elements.
    $this->drupalGet('node/1');
    $this->assertSession()->elementsCount('css', '.views-row', 5);

    // Change to limit 3 per page.
    $this->drupalGet('node/1/edit');
    $this->submitForm([
      'field_views_reference_field[0][options][limit]' => 3,
    ], 'Save');
    $this->drupalGet('node/1');
    $this->assertSession()->elementsCount('css', '.views-row', 3);
  }

  /**
   * Test the offset.
   */
  public function testOffset() {
    // Check that the limit shows the default number of elements.
    $this->drupalGet('node/1');
    $this->assertSession()->elementsCount('css', '.views-row', 5);

    $row_texts = [];
    $rows = $this->getSession()->getPage()->findAll('css', '.views-row');
    foreach ($rows as $row) {
      $row_texts[] = $row->getText();
    }

    // Change to limit 3 per page.
    $this->drupalGet('node/1/edit');
    $this->submitForm([
      'field_views_reference_field[0][options][offset]' => 2,
    ], 'Save');
    $this->drupalGet('node/1');

    $offset_row_texts = [];
    $rows = $this->getSession()->getPage()->findAll('css', '.views-row');
    foreach ($rows as $row) {
      $offset_row_texts[] = $row->getText();
    }

    // Items 3 and 4 in the initial output should
    // match items 1 and 2 after the offset of 2 is applied.
    $this->assertSame(
      array_slice($row_texts, 2, 2),
      array_slice($offset_row_texts, 0, 2),
    );
  }

  /**
   * Test the title include checkbox works.
   */
  public function testTitle() {
    // Check that the title does not show up.
    $this->drupalGet('node/1');
    $this->assertSession()->elementsCount('css', '.viewsreference--view-title', 0);

    // Change to include the title.
    $this->drupalGet('node/1/edit');
    $this->submitForm([
      'field_views_reference_field[0][options][title]' => TRUE,
    ], 'Save');
    $this->drupalGet('node/1');
    $this->assertSession()->elementTextContains('css', '.viewsreference--view-title', 'Test view');
  }

  /**
   * Test contextual filter argument.
   */
  public function testArgument() {
    // Check that the full number of default rows shows.
    $this->drupalGet('node/1');
    $this->assertSession()->elementsCount('css', '.views-row', 5);

    // The contextual filter argument is for the created date,
    // so create one item in an older year for testing.
    $this->drupalCreateNode([
      'title' => 'Test 2022 node',
      'created' => strtotime('February 2022'),
    ]);

    // Change to filter by 2022 only.
    $this->drupalGet('node/1/edit');
    $this->submitForm([
      'field_views_reference_field[0][options][argument]' => 2022,
    ], 'Save');
    $this->drupalGet('node/1');
    $this->assertSession()->pageTextContains('Test 2022 node');
    $this->assertSession()->elementsCount('css', '.views-row', 1);
  }

  /**
   * Test the hide header checkbox.
   */
  public function testHeader() {
    // Check that the view header is rendered.
    $this->drupalGet('node/1');
    $this->assertSession()->pageTextContains('Header text for testing.');

    // Change to hide header.
    $this->drupalGet('node/1/edit');
    $this->submitForm([
      'field_views_reference_field[0][options][header]' => TRUE,
    ], 'Save');
    $this->drupalGet('node/1');
    $this->assertSession()->pageTextNotContains('Header text for testing.');
  }

}

<?php

namespace Drupal\Tests\viewsreference\Functional;

use Drupal\Tests\BrowserTestBase;

/**
 * Test the Views Reference Field without JavaScript.
 *
 * Most coverage is in the FunctionalJavascript test. This
 * covers cases where JavaScript is disabled.
 *
 * @group viewsreference
 */
class ViewsReferenceFieldTest extends BrowserTestBase {

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
  }

  /**
   * Test that a view and display can be selected without JS.
   */
  public function testSelectViewDisplayNoJs() {

    // Get a content item without JS with view ID and display ID.
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
    $this->drupalGet('node/1');
    $this->assertSession()->pageTextContains('Views Reference Field');

    // Change to autocomplete.
    $this->drupalGet('admin/structure/types/manage/test_content_type/form-display');
    $this->submitForm([
      'fields[field_views_reference_field][type]' => 'viewsreference_autocomplete',
    ], 'Save');

    // Try again via autocomplete.
    $this->drupalGet('node/add/test_content_type');
    $this->submitForm([
      'title[0][value]' => 'Test',
      'field_views_reference_field[0][target_id]' => 'Test view (test_view)',
    ], 'Save');
    $this->submitForm([
      'title[0][value]' => 'Test',
      'field_views_reference_field[0][target_id]' => 'Test view (test_view)',
      'field_views_reference_field[0][display_id]' => 'block_1',
    ], 'Save');
    $this->drupalGet('node/2');
    $this->assertSession()->pageTextContains('Views Reference Field');
  }

  /**
   * Test that no display selection results in no view render.
   */
  public function testNoDisplayResultsInNoViewRender() {
    $this->drupalGet('node/add/test_content_type');
    $this->assertSession()->pageTextContains('Views Reference Field');

    // Without JavaScript enabled, the View must be selected first,
    // then the Display ID can be selected. It is therefore
    // possible that the editor abandons a node update leaving
    // only the View ID selected. In reality the form validation
    // will prevent this from happening, but for instance a
    // misconfigured migration might not.
    $node = $this->drupalCreateNode([
      'type' => 'test_content_type',
      'title' => 'Test',
      'field_views_reference_field' => [
        'target_id' => 'test_view',
      ],
    ]);

    // No field should be shown.
    $this->drupalGet('node/' . $node->id());
    $this->assertSession()->pageTextNotContains('Views Reference Field');

    // Make another one with the display ID selected.
    $node_2 = $this->drupalCreateNode([
      'type' => 'test_content_type',
      'title' => 'Test',
      'field_views_reference_field' => [
        'target_id' => 'test_view',
        'display_id' => 'block_1',
      ],
    ]);

    // The field should be shown.
    $this->drupalGet('node/' . $node_2->id());
    $this->assertSession()->pageTextContains('Views Reference Field');
  }

}

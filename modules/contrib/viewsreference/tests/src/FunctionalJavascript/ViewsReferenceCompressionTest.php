<?php

namespace Drupal\Tests\viewsreference\FunctionalJavascript;

use Drupal\Component\Serialization\Json;
use Drupal\Component\Utility\UrlHelper;
use Drupal\FunctionalJavascriptTests\WebDriverTestBase;
use Drupal\Tests\user\Traits\UserCreationTrait;

/**
 * Tests the views reference compression of settings.
 *
 * @group viewsreference
 */
class ViewsReferenceCompressionTest extends WebDriverTestBase {

  use UserCreationTrait;

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
   * {@inheritdoc}
   */
  protected static $modules = [
    'node',
    'viewsreference',
    'views_ui',
    'field_ui',
    'big_pipe',
    'block',
    'test_views_reference_ajax_history',
  ];

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();
    $this->drupalCreateContentType([
      'type' => 'page',
      'name' => 'Basic page',
      'display_submitted' => FALSE,
    ]);
    $this->adminUser = $this->drupalCreateUser([
      'access content',
      'bypass node access',
      'administer nodes',
      'administer content types',
      'administer node fields',
    ]);
    $this->drupalLogin($this->adminUser);
  }

  /**
   * Tests the Views Reference compression.
   */
  public function testViewsReferenceCompression() {

    // Create a content item with a views reference. The easiest way to test
    // this is to ensure ajax history is also enabled, so we piggy-back
    // off of that test module.
    $node = $this->drupalCreateNode([
      'title' => 'Test page with compression',
      'type' => 'test_ajax_history',
      'field_views_reference_history' => [
        'target_id' => 'test_views_ajax_history',
        'display_id' => 'block_1',
      ],
    ]);
    // Create sample content items.
    foreach (range(1, 100) as $count) {
      $this->drupalCreateNode([
        'title' => 'Test page for view ' . $count,
      ]);
    }

    // Enable the limit settings.
    $this->drupalGet('admin/structure/types/manage/test_ajax_history/fields/node.test_ajax_history.field_views_reference_history');
    $this->submitForm([
      'settings[enabled_settings][limit]' => TRUE,
    ], 'Save settings');
    $this->drupalGet('node/' . $node->id() . '/edit');
    $this->click('#edit-field-views-reference-history-0-options');
    $this->submitForm([
      'field_views_reference_history[0][options][limit]' => 20,
    ], 'Save');

    // Test pagination triggers ajax history.
    $this->drupalGet('node/' . $node->id());
    $this->assertSession()->pageTextContains('Test page for view 100');
    $pagination_links = $this->getSession()->getPage()->findAll('css', '.pager__item a');
    foreach ($pagination_links as $link) {
      if (str_contains($link->getText(), '2')) {
        $link->click();
        break;
      }
    }
    $this->assertSession()->waitForText('Test page for view 80');
    $this->assertStringContainsString('page=1', $this->getSession()->getCurrentUrl());

    // Next page.
    $pagination_links = $this->getSession()->getPage()->findAll('css', '.pager__item a');
    foreach ($pagination_links as $link) {
      if (str_contains($link->getText(), '3')) {
        $link->click();
        break;
      }
    }
    $this->assertSession()->waitForText('Test page for view 60');
    $this->assertStringContainsString('page=2', $this->getSession()->getCurrentUrl());

    // By default, Views Ajax History will have the viewsreference configuration
    // in the URL on the second click and onwards if the compression is not in
    // place. This ensures the compression has remained in place.
    $this->assertStringContainsString('viewsreference[compressed]', $this->getSession()->getCurrentUrl());

    // Ensure that the compressed string, once un-compressed, resulted in the
    // next page of results.
    $this->assertSession()->waitForText('Test page for view 40');
    $parsed_url = UrlHelper::parse($this->getSession()->getCurrentUrl());

    // Check that the uncompressed value is still as expected. We do not care
    // about the order of the data, so apply ksort to both to ensure no false
    // negative test results.
    $expected_data = [
      'title' => NULL,
      'pager' => NULL,
      'offset' => NULL,
      'limit' => '20',
      'header' => NULL,
      'argument' => NULL,
    ];
    ksort($expected_data);
    $expected_configuration = [
      'data' => $expected_data,
      'enabled_settings' => [
        'limit' => 'limit',
      ],
      'parent_entity_type' => 'node',
      'parent_entity_id' => '1',
      'parent_field_name' => 'field_views_reference_history',
      'parent_revision_id' => '1',
      'field_item_delta' => 0,
    ];
    $json = UrlHelper::uncompressQueryParameter($parsed_url['query']['viewsreference']['compressed']);
    $parameters = Json::decode($json);
    ksort($parameters['data']);
    $this->assertSame($expected_configuration, $parameters);

    // Check that the other parameters of the view have not been negatively
    // affected: we should now see page 2 in the query parameters.
    $this->assertSame('2', UrlHelper::uncompressQueryParameter($parsed_url['query']['page']));

    // The configured override of 20 per page rather than default 5 should
    // still be respected here after reload of settings from the entity.
    $this->assertSession()->elementsCount('css', '.views-row', 20);
  }

}

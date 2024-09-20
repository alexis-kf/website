<?php

namespace Drupal\Tests\viewsreference\FunctionalJavascript;

use Drupal\FunctionalJavascriptTests\WebDriverTestBase;
use Drupal\Tests\user\Traits\UserCreationTrait;

/**
 * Tests the views reference integration with ajax history.
 *
 * @group viewsreference
 */
class ViewsReferenceAjaxHistoryTest extends WebDriverTestBase {

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
   * Tests the view with ajax history functionality.
   */
  public function testUseViewWithAjaxHistory() {

    // Create a content item with a views reference.
    $node = $this->drupalCreateNode([
      'title' => 'Test page with ajax history',
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

    // Test pagination triggers ajax history.
    $this->drupalGet('node/' . $node->id());
    $this->assertSession()->pageTextContains('Test page for view 100');

    // On page 2, URL is 1 since 0 indexed. We should now be showing
    // results 91 to 95, instead of 96 to 100.
    $pagination_links = $this->getSession()->getPage()->findAll('css', '.pager__item a');
    foreach ($pagination_links as $link) {
      if (str_contains($link->getText(), '2')) {
        $link->click();
        break;
      }
    }
    $this->assertSession()->waitForText('Test page for view 95');
    $this->assertStringContainsString('page=1', $this->getSession()->getCurrentUrl());

    // Next page.
    $pagination_links = $this->getSession()->getPage()->findAll('css', '.pager__item a');
    foreach ($pagination_links as $link) {
      if (str_contains($link->getText(), '3')) {
        $link->click();
        break;
      }
    }
    $this->assertSession()->waitForText('Test page for view 90');
    $this->assertStringContainsString('page=2', $this->getSession()->getCurrentUrl());
    // By default, Views Ajax History will have the viewsreference configuration
    // in the URL on the second click and onwards. This is probably not idea.
    $this->assertStringContainsString('viewsreference', $this->getSession()->getCurrentUrl());

    // Test excluding viewsreference from the URL.
    $view_config = \Drupal::configFactory()->getEditable('views.view.test_views_ajax_history');
    $view_config->set(
      'display.block_1.display_options.display_extenders.ajax_history.exclude_args',
      'viewsreference',
    );
    $view_config->save();
    \Drupal::cache()->invalidate('config:views.view.test_views_ajax_history');

    // Repeat the above testing.
    $this->drupalGet('node/' . $node->id());
    $pagination_links = $this->getSession()->getPage()->findAll('css', '.pager__item a');
    foreach ($pagination_links as $link) {
      if (str_contains($link->getText(), '2')) {
        $link->click();
        break;
      }
    }
    $this->assertSession()->waitForText('Test page for view 95');
    $pagination_links = $this->getSession()->getPage()->findAll('css', '.pager__item a');
    foreach ($pagination_links as $link) {
      if (str_contains($link->getText(), '3')) {
        $link->click();
        break;
      }
    }
    $this->assertSession()->waitForText('Test page for view 90');
    $this->assertStringContainsString('page=2', $this->getSession()->getCurrentUrl());
    $this->assertStringNotContainsString('viewsreference', $this->getSession()->getCurrentUrl());
  }

}

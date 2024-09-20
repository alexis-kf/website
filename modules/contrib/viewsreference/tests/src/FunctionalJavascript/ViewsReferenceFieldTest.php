<?php

namespace Drupal\Tests\viewsreference\FunctionalJavascript;

use Drupal\FunctionalJavascriptTests\WebDriverTestBase;
use Drupal\Tests\user\Traits\UserCreationTrait;

/**
 * Tests the views reference field and widget.
 *
 * @group footnotes
 */
class ViewsReferenceFieldTest extends WebDriverTestBase {

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
      'administer node display',
      'administer node form display',
    ]);
    $this->drupalLogin($this->adminUser);
  }

  /**
   * Tests the field creation process.
   */
  public function testCreateViewsReferenceField() {

    // Create the views reference field on the page content type.
    $this->drupalGet('admin/structure/types/manage/page/fields/add-field');
    $this->submitForm([
      'new_storage_type' => 'reference',
    ], 'Continue');
    $this->submitForm([
      'label' => 'Views reference field',
      'group_field_options_wrapper' => 'viewsreference',
    ], 'Continue');
    $this->assertSession()->pageTextContains('View display plugins to allow');
    $this->assertSession()->pageTextContains('Preselect View Options');
    $this->assertSession()->pageTextContains('Enable extra settings');
    $this->submitForm([
      'settings[preselect_views][content]' => TRUE,
      'settings[preselect_views][content_recent]' => TRUE,
      'settings[enabled_settings][title]' => TRUE,
      'settings[enabled_settings][pager]' => TRUE,
      'settings[enabled_settings][argument]' => TRUE,
      'settings[enabled_settings][offset]' => TRUE,
      'settings[enabled_settings][limit]' => TRUE,
    ], 'Save settings');

    // View the create page form to verify that the settings exist.
    $this->drupalGet('node/add/page');
    $assert_session = $this->assertSession();

    // Run the autocomplete.
    $autocomplete = $assert_session->elementExists('css', '[name="field_views_reference_field[0][target_id]"]');
    $autocomplete->click();
    $autocomplete->setValue('Recent content');
    $autocomplete->keyDown('Recent content');
    $this->assertSession()->assertWaitOnAjaxRequest();
    $this->assertSession()->waitForElementVisible('css', '.ui-autocomplete li');
    $this->click('.ui-autocomplete li a');
    $this->assertSession()->assertWaitOnAjaxRequest();
    $this->assertSession()->waitForElementVisible('css', '[data-drupal-selector="edit-field-views-reference-field-0-options"]');

    // Submit the form.
    $this->submitForm([
      'title[0][value]' => 'Test page',
      'field_views_reference_field[0][target_id]' => 'Recent content (content_recent)',
    ], 'Save');
    $this->submitForm([
      'title[0][value]' => 'Test page',
      'field_views_reference_field[0][target_id]' => 'Recent content (content_recent)',
      'field_views_reference_field[0][display_id]' => 'block_1',
    ], 'Save');

    // Check that the node edit screen contains the views reference options
    // that were selected earlier in the field configuration.
    $this->drupalGet('node/1/edit');
    $assert_session = $this->assertSession();
    $this->click('#edit-field-views-reference-field-0-options');
    $assert_session->pageTextContains('Include View Title');
    $assert_session->pageTextContains('Items per page');
    $assert_session->pageTextContains('Offset results');
    $assert_session->pageTextContains('Argument');
    $assert_session->pageTextContains('Pagination');

    // Check that the select widget also shows up.
    $this->drupalGet('admin/structure/types/manage/page/form-display');
    $this->getSession()->getPage()->selectFieldOption('fields[field_views_reference_field][type]', 'viewsreference_select');
    $this->assertSession()->assertWaitOnAjaxRequest();
    $this->submitForm([], 'Save');
    $this->getSession()->getPage();
    $this->drupalGet('node/1/edit');
    $this->assertSession()->optionExists('field_views_reference_field[0][target_id]', 'content_recent');
    $this->assertSession()->optionExists('field_views_reference_field[0][target_id]', 'content');

    // Quick check on the output that the field shows up along with
    // the selected View.
    $this->drupalGet('node/1');
    $assert_session = $this->assertSession();
    $assert_session->pageTextContains('Views reference field');
    // This just double checks it is the recent content block, in case for some
    // reason something goes wrong displayed the view the editor actually
    // selected.
    $assert_session->elementTextContains('css', '.views-field.views-field-changed .field-content', 'second');

    // Check that the lazy builder still shows the view.
    $this->drupalGet('admin/structure/types/manage/page/display');
    $this->getSession()->getPage()->selectFieldOption('fields[field_views_reference_field][type]', 'viewsreference_lazy_formatter');
    $this->assertSession()->assertWaitOnAjaxRequest();
    $this->submitForm([], 'Save');
    $this->getSession()->getPage();
    $this->drupalGet('node/1');
    $assert_session->pageTextContains('Views reference field');
    $assert_session->elementTextContains('css', '.views-field.views-field-changed .field-content', 'second');

  }

  /**
   * Tests that a view can have brackets in the name.
   */
  public function testViewWithBrackets() {
    // Create the views reference field on the page content type.
    $this->drupalGet('admin/structure/types/manage/page/fields/add-field');
    $this->submitForm([
      'new_storage_type' => 'reference',
    ], 'Continue');
    $this->submitForm([
      'label' => 'Views reference brackets',
      'group_field_options_wrapper' => 'viewsreference',
    ], 'Continue');
    $this->submitForm([
      'settings[preselect_views][content]' => TRUE,
      'settings[preselect_views][content_recent]' => TRUE,
    ], 'Save settings');

    // Change the recent content name to have brackets in it.
    $view_config = \Drupal::configFactory()->getEditable('views.view.content_recent');
    $view_config->set('label', 'Recent (content)');
    $view_config->save();
    \Drupal::cache()->invalidate('config:views.view.content_recent');

    // View the create page form and select the view and display.
    $this->drupalGet('node/add/page');
    $assert_session = $this->assertSession();
    $autocomplete = $assert_session->elementExists('css', '[name="field_views_reference_brackets[0][target_id]"]');
    $autocomplete->click();
    $autocomplete->setValue('Recent (content)');
    $autocomplete->keyDown('Recent (content)');
    $this->assertSession()->assertWaitOnAjaxRequest();
    $this->assertSession()->waitForElementVisible('css', '.ui-autocomplete li');
    $this->click('.ui-autocomplete li a');
    $this->assertSession()->assertWaitOnAjaxRequest();
    $this->assertSession()->waitForElementVisible('css', '[data-drupal-selector="edit-field-views-reference-brackets-0-options"]');

    // Ensure that the view can be selected and the display appears.
    // Submit the form.
    $this->submitForm([
      'title[0][value]' => 'Test page',
      'field_views_reference_brackets[0][target_id]' => 'Recent (content) (content_recent)',
    ], 'Save');
    $this->submitForm([
      'title[0][value]' => 'Test page',
      'field_views_reference_brackets[0][target_id]' => 'Recent (content) (content_recent)',
      'field_views_reference_brackets[0][display_id]' => 'block_1',
    ], 'Save');

    // Check that the view is output on the page.
    $this->getSession()->getPage();
    $this->assertSession()->pageTextContains('Recent content');
    $this->assertSession()->elementExists('css', '.views-field.views-field-title');
  }

}

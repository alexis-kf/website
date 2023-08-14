<?php

namespace Drupal\material_icons\Form;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\CloseModalDialogCommand;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Link;
use Drupal\Core\Url;
use Drupal\editor\Ajax\EditorDialogSave;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Creates an icon dialog form for use in CKEditor.
 *
 * @package Drupal\material_icons\Form
 */
class IconDialog extends FormBase {

  /**
   * The config factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory')
    );
  }

  /**
   * IconDialog constructor.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $configFactory
   *   The config factory.
   */
  public function __construct(ConfigFactoryInterface $configFactory) {
    $this->configFactory = $configFactory;
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'material_icons_dialog';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $settings = $this->configFactory->get('material_icons.settings');
    $form['#attached']['library'][] = 'editor/drupal.editor.dialog';

    $form['notice'] = [
      '#markup' => $this->t('Select the icon and style to be displayed.'),
    ];

    $form['icon'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Icon Name'),
      '#default_value' => '',
      '#required' => TRUE,
      '#description' => $this->t('Name of the Material Design Icon. See @iconsLink for valid icon names, or begin typing for an autocomplete list.', [
        '@iconsLink' => Link::fromTextAndUrl(
          $this->t('the icon list'),
          Url::fromUri('https://material.io/resources/icons', ['attributes' => ['target' => '_blank']])
        )->toString(),
      ]),
      '#autocomplete_route_name' => 'material_icons.autocomplete',
    ];

    $options = [];
    foreach ($settings->get('families') as $type) {
      $options[$type] = ucfirst($type);
    }
    $form['family'] = [
      '#title' => $this->t('Icon Type'),
      '#type' => 'select',
      '#options' => $options,
    ];

    $form['classes'] = [
      '#title' => $this->t('Additional Classes'),
      '#type' => 'textfield',
      '#description' => $this->t('For example, veritical alignment classes: <em>align-text-top</em>'),
    ];

    $form['actions'] = ['#type' => 'actions'];
    $form['actions']['save'] = [
      '#type' => 'submit',
      '#value' => $this->t('Insert Icon'),
      // No regular submit-handler. This form only works via JavaScript.
      '#submit' => [],
      '#ajax' => [
        'callback' => '::submitForm',
        'event' => 'click',
      ],
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $response = new AjaxResponse();

    $values = [
      'settings' => [
        'icon' => $form_state->getValue('icon'),
        'family' => $form_state->getValue('family'),
        'classes' => $form_state->getValue('classes', ''),
      ],
    ];

    $response->addCommand(new EditorDialogSave($values));
    $response->addCommand(new CloseModalDialogCommand());

    return $response;
  }

}

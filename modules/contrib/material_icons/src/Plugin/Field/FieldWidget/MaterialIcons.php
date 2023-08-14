<?php

namespace Drupal\material_icons\Plugin\Field\FieldWidget;

use Drupal\Core\Config\ConfigFactory;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Link;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Plugin implementation of the 'Material Icons' widget.
 *
 * @FieldWidget(
 *   id = "material_icons",
 *   label = @Translation("Material Icons"),
 *   field_types = {
 *     "material_icons"
 *   }
 * )
 */
class MaterialIcons extends WidgetBase implements ContainerFactoryPluginInterface {

  /**
   * Drupal configuration service container.
   *
   * @var \Drupal\Core\Config\ConfigFactory
   */
  protected $configFactory;

  /**
   * {@inheritdoc}
   */
  public function __construct($plugin_id, $plugin_definition, FieldDefinitionInterface $field_definition, array $settings, array $third_party_settings, ConfigFactory $config_factory) {
    parent::__construct($plugin_id, $plugin_definition, $field_definition, $settings, $third_party_settings);
    $this->configFactory = $config_factory;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $plugin_id,
      $plugin_definition,
      $configuration['field_definition'],
      $configuration['settings'],
      $configuration['third_party_settings'],
      $container->get('config.factory')
    );
  }

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return [
      'allow_style' => TRUE,
      'default_style' => '',
      'allow_classes' => TRUE,
    ] + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $element['allow_style'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Allow Style Selection'),
      '#default_value' => $this->getSetting('allow_style'),
    ];

    $element['default_style'] = [
      '#type' => 'select',
      '#options' => $this->getStyleOptions(),
      '#title' => $this->t('Default Style'),
      '#default_value' => $this->getSetting('default_style'),
    ];

    $element['allow_classes'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Allow Additional Classes'),
      '#default_value' => $this->getSetting('allow_classes'),
    ];

    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = [];
    $summary[] = $this->t('Allow Styles: @allow_style', ['@allow_style' => $this->getSetting('allow_style') ? $this->t('Yes') : $this->t('No')]);
    $summary[] = $this->t('Defailt Style: @default_style', ['@default_style' => $this->getSetting('default_style')]);
    $summary[] = $this->t('Allow Additional Classes: @allow_classes', ['@allow_classes' => $this->getSetting('allow_classes') ? $this->t('Yes') : $this->t('No')]);
    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    $cardinality = $this->fieldDefinition->getFieldStorageDefinition()->getCardinality();

    $element['icon'] = [
      '#type' => 'textfield',
      '#title' => $cardinality == 1 ? $this->fieldDefinition->getLabel() : $this->t('Icon Name'),
      '#default_value' => $items[$delta]->get('icon')->getValue(),
      '#required' => $element['#required'],
      '#description' => $this->t('Name of the Material Design Icon. See @iconsLink for valid icon names, or begin typing for an autocomplete list.', [
        '@iconsLink' => Link::fromTextAndUrl(
          $this->t('the icon list'),
          Url::fromUri('https://material.io/resources/icons', ['attributes' => ['target' => '_blank']])
        )->toString(),
      ]),
      '#autocomplete_route_name' => 'material_icons.autocomplete',
    ];

    $element['family'] = [
      '#title' => $this->t('Icon Style'),
      '#type' => 'select',
      '#default_value' => $items[$delta]->get('family')->getValue() ?? $this->getSetting('default_style'),
      '#options' => $this->getStyleOptions(),
      '#disabled' => !$this->getSetting('allow_style'),
    ];

    if ($this->getSetting('allow_classes')) {
      $element['classes'] = [
        '#title' => $this->t('Additional Classes'),
        '#type' => 'textfield',
        '#default_value' => $items[$delta]->get('classes')->getValue(),
        '#description' => $this->t('For example, veritical alignment classes: <em>align-text-top</em>'),
      ];
    }

    return $element;
  }

  /**
   * Helper to produce a list of available icon styles.
   *
   * @return array
   *   The available options.
   */
  protected function getStyleOptions() {
    $settings = $this->configFactory->get('material_icons.settings');
    $options = [];
    foreach ($settings->get('families') as $type) {
      $options[$type] = ucfirst($type);
    }
    return $options;
  }

}

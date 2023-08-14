<?php

namespace Drupal\material_icons\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;

/**
 * Implementation of Material Icon formatter.
 *
 * @FieldFormatter(
 *   id = "material_icons",
 *   label = @Translation("Material Icons"),
 *   field_types = {
 *     "material_icons"
 *   }
 * )
 */
class MaterialIcons extends FormatterBase {

  /**
   * {@inheritDoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $element = [];

    foreach ($items as $delta => $item) {
      $family_value = $item->get('family')->getValue();
      $family = $family_value === 'baseline' ? 'material-icons' : 'material-icons-' . $family_value;
      $element[$delta] = [
        '#theme' => 'material_icon',
        '#icon' => $item->get('icon')->getValue(),
        '#family' => $family,
        '#classes' => $item->get('classes')->getValue(),
      ];
    }
    return $element;
  }

}

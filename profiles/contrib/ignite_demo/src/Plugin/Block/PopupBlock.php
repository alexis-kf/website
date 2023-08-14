<?php

namespace Drupal\ignite_demo\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a newsletter block.
 *
 * @Block(
 *   id = "ignite_demo_popup",
 *   admin_label = @Translation("Demo Pop-up"),
 *   category = @Translation("Ignite Demo")
 * )
 */
class PopupBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [
      '#theme' => 'ignite_demo_popup',
      '#attached' => [
        'library' => [
          'ignite_demo/demo-popup',
        ],
      ],
    ];

    return $build;
  }

}

<?php

namespace Drupal\ignite_features\Commands;

use Drupal\layout_builder\Section;
use Drupal\layout_builder\SectionComponent;
use Drupal\node\Entity\Node;
use Drush\Commands\DrushCommands;

/**
 * A Drush commandfile.
 */
class IgniteFeaturesCommands extends DrushCommands {

  /**
   * Drush command that creates demo pages with every enabled custom block.
   *
   * @usage ignite_features:create_kitchen_sink
   *
   * @command ignite_features:create_kitchen_sink
   * @aliases kitchen-sink
   */
  public function createDemoPage() {
    $node = $node2 = $section = NULL;

    // Create base landing page.
    if (\Drupal::moduleHandler()->moduleExists('ignite_landing')) {
      $node = Node::create(
        [
          'type' => 'landing',
          'title' => 'Kitchen Sink Demo Page',
        ]
      );

      $node->save();
    }

    // Create base layout page.
    if (\Drupal::moduleHandler()->moduleExists('ignite_layout')) {
      $node2 = Node::create(
        [
          'type' => 'layout',
          'title' => 'Kitchen Sink Demo Page (Layout builder)',
        ]
      );
      $node2->save();
      $section = new Section('layout_onecol');
    }

    // Invoke all ignite_demo_block hooks.
    $blocks = \Drupal::moduleHandler()->invokeAll('ignite_demo_block');

    // Add blocks to demo page(s).
    foreach ($blocks as $block) {
      // Add block to landing page.
      if (\Drupal::moduleHandler()->moduleExists('ignite_landing')) {
        $node->get('field_block')->appendItem($block);
        $node->save();
      }

      // Add block to layout page.
      if (\Drupal::moduleHandler()->moduleExists('ignite_layout')) {
        $component = new SectionComponent(
              $block->id(), 'content', [
                'id' => 'block_content:' . $block->uuid(),
                'label' => $block->label(),
                'provider' => 'block_content',
                'label_display' => '0',
              ]
          );
        $section->appendComponent($component);
      }
    }

    // Save landing page.
    if ($node) {
      $node->save();
      $node_url = $node->toUrl('canonical', ['absolute' => TRUE])->toString();
      $this->output()->writeln('Kitchen Sink Demo Page created at URL: ' . $node_url);
    }

    // Save layout builder page.
    if ($node2) {
      $node2->get('layout_builder__layout')->appendItem($section);
      $node2->save();
      $node_url = $node2->toUrl('canonical', ['absolute' => TRUE])->toString();
      $this->output()->writeln('Kitchen Sink Demo Page (Layout Builder) created at URL: ' . $node_url);
    }
  }

}

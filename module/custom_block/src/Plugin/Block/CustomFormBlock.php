<?php

namespace Drupal\custom_block\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'Custom Form Block' block.
 *
 * @Block(
 * id = "custom_form_block",
 * admin_label = @Translation("Form block"),
 * category = @Translation("Custom form block")
 * )
 */
class CustomFormBlock extends BlockBase {
  /**
  * {@inheritdoc}
  */
  public function build() {

    $form = \Drupal::formBuilder()->getForm('Drupal\custom_form\Form\NewForm');
    return $form;
  }
}
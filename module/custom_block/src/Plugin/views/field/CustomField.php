<?php

namespace Drupal\custom_block\Plugin\views\field;

use Drupal\Core\Form\FormStateInterface;
use Drupal\field\Entity\FieldStorageConfig;
use Drupal\views\Plugin\views\field\FieldPluginBase;
use Drupal\views\ResultRow;

/**
 * @ingroup views_field_handlers
 *
 * @ViewsField("text_css_content")
 */
class CustomField extends FieldPluginBase {

  /**
   * {@inheritdoc}
   */
  public function query() {

  }

  /**
   * {@inheritdoc}
   */
  protected function defineOptions() {
    $options = parent::defineOptions();
    $options['text_css_content'] = ['default' => ''];;
    return $options;
  }

  /**
   * {@inheritdoc}
   */
  public function buildOptionsForm(&$form, FormStateInterface $form_state) {
    $form['text_css_content'] = [
      '#type' => 'textfield',
      '#required' => TRUE,
      '#title' => $this->t('Enter custom text'),
      '#description' => $this->t('This is custom text field.'),
      '#default_value' => $this->options['text_css_content'],
    ];
    parent::buildOptionsForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function render(ResultRow $values) {
    $result = "";
    if (!empty($this->view->field['text_css_content'])) {
      $result = $this->view->field['text_css_content']->options['text_css_content'];
    }
    return [
      '#markup' => $result,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function clickSort($order) {
    $this->query->addOrderBy('text_css_content', 'created', $order);
  }

}
<?php

namespace Drupal\custom_block\Form;

use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

/**
 * Defines a confirmation form to confirm deletion of something by id.
 */
class DeleteRecordForm extends ConfirmFormBase {

  /**
   * ID of the item to delete.
   *$form['#attached']['library'][] = 'core/drupal.dialog.aj$form['#attached']['library'][] = 'core/drupal.dialog.ajax';$form['#attached']['library'][] = 'core/drupal.dialog.ajax';ax';
   * @var int
   */
  protected $id;

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, int $id = NULL) {
    $this->id = $id;
    $form['#attached']['library'][] = 'core/drupal.dialog.ajax';
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    if($this->id) {
      $node_storage = \Drupal::entityTypeManager()->getStorage('node');
      $node = $node_storage->load($this->id);
      if($node) {
        $node->delete();
        \Drupal::messenger()->addMessage('Record deleted with id: '.$this->id);
        $url = Url::fromRoute('view.delete_data_view.page_1')->getRouteName();
        $form_state->setRedirect($url);
      }
      else {
        \Drupal::messenger()->addError('Unable to delete record with id: '.$this->id);
        $url = Url::fromRoute('view.delete_data_view.page_1')->getRouteName();
        $form_state->setRedirect($url);
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() : string {
    return "delete_record_form";
  }

  /**
   * {@inheritdoc}
   */
  public function getCancelUrl() {
    $url = Url::fromRoute('view.delete_data_view.page_1')->getRouteName();
    return new Url($url);
  }

  /**
   * {@inheritdoc}
   */
  public function getQuestion() {
    return t('Do you want to delete record id: %id?', ['%id' => $this->id]);
  }

}
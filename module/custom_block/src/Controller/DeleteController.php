<?php

namespace Drupal\custom_block\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\OpenModalDialogCommand;

/**
 * Delete controller.
 */
class DeleteController extends ControllerBase {

  /**
   * .
   */
  public function DeleteRecord($nid) {
    $response = new AjaxResponse();
  	$modal_form = \Drupal::formBuilder()->getForm('Drupal\custom_block\Form\DeleteRecordForm',$nid);
  	$options = [
      'dialogClass' => 'popup-dialog-class',
      'width' => '40%',
    ];
    $response->addCommand(new OpenModalDialogCommand(t('Delete Modal'), $modal_form, $options));

    return $response;

  }

}

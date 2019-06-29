<?php

namespace Drupal\custom_form\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\Node;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\ReplaceCommand;
use Drupal\Core\Ajax\CssCommand;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Ajax\InvokeCommand;

/**
 * Implements an Form form.
 */
class NewForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'new_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $term = \Drupal::entityTypeManager()
    ->getStorage('taxonomy_term')
    ->loadTree('vegetables');
    $city = [];
    foreach($term as $key=>$value){
      $city[$value->tid] = $value->name;
    }
    $form['success'] = [
     '#type' => '#markup',
     '#markup'=> '',
     '#prefix' => '<div id="success-message">',
     '#suffix' => '</div>'
   ];
   $form['error'] = [
     '#type' => '#markup',
     '#markup'=> '',
     '#prefix' => '<div id="error-message">',
     '#suffix' => '</div>'
   ];
   $form['name'] = [
    '#type' => 'textfield',
    '#title' => $this->t('Your name'),
    '#required' => FALSE,
    '#suffix' => '<span id="error-name"></span>',
  ];
  $form['email'] = [
    '#type' => 'email',
    '#title' => $this->t('Your email'),
    '#required' => FALSE,
    '#attributes' => [
      '#id' => 'email',
    ],
    '#suffix' => '<span id="error-email"></span>',
  ];
  $form['dob'] = [
    '#type' => 'date',
    '#title' => $this->t('Your DOB'),
    '#required' => FALSE,
    '#suffix' => '<span id="error-dob"></span>',
  ];
  $form['vegetables'] = [
    '#type' => 'select',
    '#title' => $this->t('Select your vegetables'),
    '#options' => $city,
    '#required' => FALSE,
    '#suffix' => '<span id="error-city"></span>',
  ];
  $form['phone'] = [
    '#type' => 'tel',
    '#title' => $this->t('Your phone number'),
    '#required' => FALSE,
    '#suffix' => '<span id="error-phone"></span><br>',
  ];
    //$form['actions']['#type'] = 'actions';
  $form['submit'] = [
    '#type' => 'submit',
    '#name' => 'submit-email',
    '#value' => 'Save',
    '#ajax' => [
      'callback' => '::ajax_call_submit',
      'wrapper' => 'new-form',
      'effect' => 'fade',
      'event' => 'click',
      'progress' => [
        'type' => 'throbber',
        'message' => $this->t('Saving record ...'),
      ],
    ],
  ];

  return $form;
}

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    // $email = $form_state->getValue('email');
    // if (isset($email) && 2 < strlen($email)) {
    //   if (!\Drupal::service('email.validator')->isValid($email)) {
    //     $form_state->setErrorByNform_stateame('email', t('That is not a valid e-mail address.'));
    //   }
    // }
    // $mobile = $form_state->getValue('phone');
    // if (strlen($mobile) != 10) {
    //   $form_state->setErrorByName('phone', $this->t('Enter valid phone number.'));
    // }
    // else {
    //   $mob_check = \Drupal::entityQuery('node')
    //   ->condition('type', 'from_data')
    //   ->condition('field_mobile', $mobile)
    //   ->execute();
    //   if($mob_check) {
    //     $form_state->setErrorByName('phone', $this->t('Mobile No already exists.'));
    //   }
    // }

  }

  public function ajax_call_submit(array &$form, FormStateInterface $form_state) {
    $response = new AjaxResponse();
    $email = $form_state->getValue('email');
    $css = ['color' => 'red'];
    $flag = TRUE;
    // if(!\Drupal::service('email.validator')->isValid($email)) {
    //   $flag = FALSE;
    //   $error_message = 'That is not a valid e-mail address';
    //   $response->addCommand(new CssCommand('#error-email', $css));
    //   $response->addCommand(new HtmlCommand('#error-email', $error_message));
    //   $response->addCommand(new InvokeCommand('#email', 'val', ['']));
    // }
    // $mobile = $form_state->getValue('phone');
    // if(strlen($mobile) != 10) {
    //   $flag = FALSE;
    //   $error_mobile = 'Enter valid phone number.';
    //   $response->addCommand(new CssCommand('#error-phone', $css));
    //   $response->addCommand(new HtmlCommand('#error-phone', $error_mobile));
    // }
    // else {
    //   $mob_check = \Drupal::entityQuery('node')
    //   ->condition('type', 'from_data')
    //   ->condition('field_mobile', $mobile)
    //   ->execute();
    //   if($mob_check) {
    //     $flag = FALSE;
    //     $error_mobile = 'Mobile No already exists.';
    //     $response->addCommand(new CssCommand('#error-phone', $css));
    //     $response->addCommand(new HtmlCommand('#error-phone', $error_mobile));
    //   }
    // }
    if($flag) {
      $node = Node::create([
        'type' => 'from_data',
        'title' => 'Title of '.$form_state->getValue('name'),
        'field_name' => $form_state->getValue('name'),
        'field_email' => $form_state->getValue('email'),
        'field_dob' => $form_state->getValue('dob'),
        'field_city' => ['target_id'=>$form_state->getValue('vegetables')],
        'field_mobile' => $form_state->getValue('phone'),
      ]); 
      $node->save();
      if($node->id()) {
        $css1 = ['color' => 'green'];
        $msg = '<h2>Record saved</h2>';
        $response->addCommand(new CssCommand('#success-message', $css1));
        $response->addCommand(new HtmlCommand('#success-message', $msg));
        $response->addCommand(new InvokeCommand('.new-form', 'trigger', ['reset']));
      }
    }

      return $response;
    }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
     //print_r('submit form');die;
  //   $node = Node::create([
  //     'type' => 'from_data',
  //     'title' => 'Title of '.$form_state->getValue('name'),
  //     'field_name' => $form_state->getValue('name'),
  //     'field_email' => $form_state->getValue('email'),
  //     'field_dob' => $form_state->getValue('dob'),
  //     'field_city' => ['target_id'=>$form_state->getValue('city')],
  //     'field_mobile' => $form_state->getValue('phone'),
  //     ]); 
  //   $node->save();
  //   if($node->id()) {
  //     \Drupal::messenger()->addMessage('Record saved');
  //   }
  //   else {
  //     \Drupal::messenger()->addError('Try again!');
  //   }
  // }

  }

}

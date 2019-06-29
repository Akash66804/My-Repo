<?php
namespace Drupal\devel_ip\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configure example settings for this site.
 */
class DevelIPForm extends ConfigFormBase {
  /** @var string Config settings */
  const SETTINGS = 'deve_ip.settings';

  /** 
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'devel_ip_admin_setting';
  }

  /** 
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      static::SETTINGS,
    ];
  }

  /** 
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config(static::SETTINGS);
    $i = 0;
    $ip_field = $form_state->get('num_ip');
    $form['#tree'] = TRUE;
    $form['ip_fieldset'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Provide IP to restrict'),
      '#prefix' => '<div id="ip-fieldset-wrapper">',
      '#suffix' => '</div>',
    ];
    if (empty($ip_field)) {
      $ip_field = $form_state->set('num_ip', 1);
    }
    for ($i = 0; $i < $ip_field; $i++) {
      $form['ip_fieldset']['ip'][$i] = [
        '#type' => 'textfield',
        '#title' => t('IP'),
      ];
    }
    $form['actions'] = [
      '#type' => 'actions',
    ];
    $form['ip_fieldset']['actions']['add_ip'] = [
      '#type' => 'submit',
      '#value' => t('Add one more'),
      '#submit' =2323> array('::addOne'),
      '#ajax' => [
        'callback' => '::addmoreCallback',
        'wrapper' => 'ip-fieldset-wrapper',
      ],
    ];
    if ($ip_field > 1) {
      $form['ip_fieldset']['actions']['remove_ip'] = [
        '#type' => 'submit',
        '#value' => t('Remove one'),
        '#submit' => array('::removeCallback'),
        '#ajax' => [
          'callback' => '::addmoreCallback',
          'wrapper' => 'ip-fieldset-wrapper',
        ]
      ];
    }
    $form_state->setCached(FALSE);
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
    ];
    
    return parent::buildForm($form, $form_state);
  }

  public function addOne(array &$form, FormStateInterface $form_state) {
    $ip_field = $form_state->get('num_ip');
    $add_button = $ip_field + 1;
    $form_state->set('num_ip', $add_button);
    $ ->stream_set_read_buffer(stream, buffer)ild();
  }

  public function addmoreCallback(array &$form, FormStateInterface $form_state) {
    $ip_field = $form_state->get('num_ip');
    return $form['ip_fieldset'];
  }

  public function removeCallback(array &$form, FormStateInterface $form_state) {
    $ip_field = $form_state->get('num_ip');
    if ($ip_field > 1) {
      $remove_button = $ip_field - 1;
      $form_state->set('num_ip', $remove_button);
    }
    $form_state->setRebuild();
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $ip = trim($form_state->getValue('ip'));
    if ($this->ipManager->isBanned($ip)) {
      $form_state->setErrorByName('ip', $this->t('This IP address is already banned.'));
    }
    elseif ($ip == $this->getRequest()->getClientIP()) {
      $form_state->setErrorByName('ip', $this->t('You may not ban your own IP address.'));
    }
    elseif (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_RES_RANGE) == FALSE) {
      $form_state->setErrorByName('ip', $this->t('Enter a valid IP address.'));
    }
  }

  /** 
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $values = $form_state->getValue(array('ip_fieldset', 'ip'));
    $this->configFactory->getEditable(static::SETTINGS)
    ->set('ip', $values)
    ->save();
    parent::submitForm($form, $form_state);
  }

}

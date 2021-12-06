<?php

namespace Drupal\node_status_config\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Defines a form that configures forms module settings.
 */
class NodeConfigForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'node_config_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'node_status_config.adminsettings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, Request $request = NULL) {
    $config = $this->config('node_status_config.adminsettings');

    $status_arr = ['pending'=>'Pending','working'=>'Working','progress'=>'Progress','done'=>'Done'];
    $form['node_status'] = array(
      '#title' => t('Node Status'),
      '#type' => 'select',
      '#description' => 'Select the Status of Node.',
      // '#options' => array(t('--- SELECT ---'), t('Pending'), t('Working'), t('Progress'), t('Done')),
      '#options' => $status_arr,
      '#default_value' => $config->get('node_status'),
    );
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    
    $this->config('node_status_config.adminsettings')  
      ->set('node_status', $form_state->getValue('node_status'))  
      ->save();  
    parent::submitForm($form, $form_state);
  }
}

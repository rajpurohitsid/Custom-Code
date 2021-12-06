<?php


namespace Drupal\dependent_dropdown\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Entity\ContentEntityType;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Ajax\AjaxResponse;

/**
 * Form handler for the shtabs config entity edit forms.
 */
class DependentDropdown extends FormBase {
  /**
   * {@inheritdoc}
   */
  public function getFormId() {
  return "dependent_dropdown";
  }


  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

   $countries = []; 
   // $countries = getYourCountiresList  funtion call and get array of country options lile [1 => "India", 2 => "USA"];
     $form['Country'] = [
      '#type' => 'select',
      '#title' => t('Country'),
      '#description' => t('Country'),
      '#required' => TRUE,
      '#options' => $countries,
     // '#default_value' => setInConfigandGEtVAlue,
      '#ajax' => ['callback' => [$this, 'getStates'],  'event' => 'change',
                  'method' => 'html',
                  'wrapper' => 'states-to-update',
                  'progress' => [
                    'type' => 'throbber',
                     'message' => NULL,
                  ],
                ],
    ];
     $states = [];
   // if($default_country != "") { load default states of selected country by get the default value of country (setInConfigandGEtVAlue)
     // $states = $this->getStatesByCountry($default_country);

   // }
   
    $form['state'] = array(
      '#title' => t('State'),
      '#type' => 'select',
      '#description' => t('Select the state'),
      '#options' => $states,
     // '#default_value' => setInConfigandGEtVAlue,
      '#attributes' => ["id" => 'states-to-update'],
      '#multiple' => TRUE,
      '#validated' => TRUE
    );
   $form['submit'] = array(
      '#type' => 'submit',
      '#value' => t('Submit'),
    );
   
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array $form, FormStateInterface $form_state) {
    // In save add 
  }


public function getStates(array &$element, FormStateInterface $form_state) {
    $triggeringElement = $form_state->getTriggeringElement();
    $value = $triggeringElement['#value'];
    $states = $this->getStatesByCountry($value);
    $wrapper_id = $triggeringElement["#ajax"]["wrapper"];
    $renderedField = '';
    foreach ($states as $key => $value) {
      $renderedField .= "<option value='".$key."'>".$value."</option>";
    }
    $response = new AjaxResponse();
    $response->addCommand(new HtmlCommand("#".$wrapper_id, $renderedField));
    return $response;
  }

  public function getStatesByCountry($default_country) {
    //add you logic return states by country
    return $states;
  }
}
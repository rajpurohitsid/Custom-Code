<?php

namespace Drupal\custom_autocomplete\Form;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\Element\EntityAutocomplete;

/**
 * Class MyAutocompleteForm
 * @package Drupal\mymodule\Form
 */
class MyAutocompleteForm extends FormBase
{

  /**
   * {@inheritdoc}
   */
  public function getFormId()
  {
    return 'my_autocomplete_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state)
  {
    $form['article'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Autocomplete Articles'),
      '#autocomplete_route_name' => 'custom_autocomplete.autocomplete',
    ];
    $form['actions'] = ['#type' => 'actions'];
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save'),
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state)
  {
    $article_id = EntityAutocomplete::extractEntityIdFromAutocompleteInput($form_state->getValue('article'));
    \Drupal::messenger()->addMessage('Article ID is ' . $article_id);
  }


}
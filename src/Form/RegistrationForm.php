<?php

namespace Drupal\user_registration\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Database\Database;


class RegistrationForm extends FormBase {
  /**
   * {@inheritdoc}
   */

  public function getFormId() {
    return 'user_registration_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state) {

    $form['message'] = [
      '#type' => 'markup',
      '#markup' => '<div class="result_message"></div>',
    ];
    $form['full_name'] = array(
      '#type' => 'textfield',
      '#title' => t('Enter Full Name:'),
      '#required' => TRUE,
    );
    $form['phone_number'] = array (
      '#type' => 'tel',
      '#title' => t('Enter Contact Number'),
      '#required' => TRUE,
    );
    $form['actions'] = [
       '#type' => 'button',
       '#value' => $this->t('Register'),
       '#ajax' => [
         'callback' => '::setMessage',
       ],
     ];

    return $form;
  }

  public function setMessage(array $form, FormStateInterface $form_state) {

    $response = new AjaxResponse();
   
    $conntion = Database::getConnection();
    
    $field = $form_state->getValues();
      
    $fields["full_name"] = $field["full_name"];
    $fields["phone_number"] = $field["phone_number"];
     
    $conntion->insert('user_custom_details')->fields($fields)->execute();

    $response->addCommand(
      new HtmlCommand(
        '.result_message',
        '<div class="my_top_message"> ' . t('Registration Done!! Registered Values are: ') . ($form_state->getValue('full_name') . $form_state->getValue('phone_number')) . '</div>')
    );

    return $response;

  }


  public function submitForm(array &$form, FormStateInterface $form_state) {
  }

}
<?php
/**
 * @file
 * A form to collect an email address for RSVP
 */
 namespace Drupal\rsvplist\Form;

 use Drupal\Core\Form\FormBase;
 use Drupal\Core\Form\FormStateInterface;

 class RSVPForm extends FormBase {
  /**
   * {@inheritdoc}
   */
   public function getFormID() {
     return 'rsvplist_email_form';
  }
   /**
    * {@inheritdoc}
    */
   public function buildForm(array $form, FormStateInterface $form_state) {
     $node = \Drupal::routeMatch()->getParameter('node');
     //For testing
     //If a node was loaded
     if (!(is_null($node))){
       $nid = $node->id();
     }else{
       //If a node could not a loaded, default to 0;
       $nid = 0;
     }
     $form['email'] = [
       '#type'=>'textfield',
       '#title'=>t('Email address'),
       '#size'=> 25,
       '#description'=>t('We will update your email'),
       '#required'=>TRUE,
     ];
     $form['submit'] = [
       '#type'=>'submit',
       '#value'=>t('RSVP'),
     ];
     $form['nid'] = [
       '#type'=>'hidden',
       '#value'=>$nid,
     ];

     return $form;
   }
   /**
    * {@inheritdoc}
    */
   public function validateForm(array &$form, FormStateInterface $form_state) {
     $value = $form_state->getValue('email');
     if (!(\Drupal::service('email.validator')->isValid($value)) ) {
       $form_state->setErrorByName('email',
         $this->t("It's not that %mail valid. Pls try again",
         ['%mail'=>$value]));
     }
   }
   /**
    * {@inheritdoc}
    */
   public function submitForm(array &$form, FormStateInterface $form_state) {
     /*$submitted_email = $form_state->getValue('email');
     $this->messenger()->addMessage(t('The form is working! You entered @entry.',
     ['@entry' => @$submitted_email]));*/

     try {
//phase 1: initiate variables to save
//get current user ID
       $uid = \Drupal::currentUser()->id();
       $full_user  = \Drupal\user\Entity\User::load(\Drupal::currentUser()->id());

       $nid=$form_state->getValue('nid');
       $email=$form_state->getValue('email');

       $current_time = \Drupal::time()->getRequestTime();
       //end phase 1

       //phase 2: save the values to the db
       $query = \Drupal::database()->insert('rsvplist');
       $query->fields([
         'uid',
         'nid',
         'mail',
         'created',
       ]);
       $query->values([
         $uid,
         $nid,
         $email,
         $current_time,
       ]);
       $query->execute();
       //end phase 2
       //phase 3: display a success message

       \Drupal::messenger()->addMessage(
         t('thx 4 your RSVP')
       );
       //end phase 3
     }
     catch (\Exception $e){
       \Drupal::messenger()->addError(
         t('Unabled to save RSVP settings. Pls try again')
       );
     }
   }
 }

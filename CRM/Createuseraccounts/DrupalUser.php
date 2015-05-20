<?php

class CRM_Createuseraccounts_DrupalUser {

  protected $roles = array();

  protected $contact_id;

  protected $drupal_id;

  public function __construct($contact_id, $roles) {
    $this->roles = $roles;
    $this->contact_id = $contact_id;
  }

  public function assignRolesToUser() {
    $user = user_load($this->drupal_id);
    $roles = user_roles(TRUE);
    $user_roles = $user->roles;
    foreach($this->roles as $rid) {
      if (!isset($user_roles[$rid])) {
        $user_roles[$rid] = $roles[$rid];
      }
    }


    user_save($user, array('roles' => $user_roles));
  }

  public function createDrupalAccount() {
    $drupal_uid = $this->getDurpalUserId($this->contact_id);
    if ($drupal_uid !== false) {
      $this->drupal_id = $drupal_uid;
      return $this->drupal_id;
    }

    try {
      $email = civicrm_api3('Email', 'getsingle', array('contact_id' => $this->contact_id, 'is_primary' => '1'));
    } catch (Exception $ex) {
      CRM_Core_Session::setStatus('No user account created because contact does not have an e-mail address', 'No user account created', 'error');
    }

    $name = $email['email'];

    $roles = $this->roles;
    //$roles[] = DRUPAL_AUTHENTICATED_RID;
    $form_state['input'] = array(
      'name' => $name,
      'mail' => $email['email'],
      'roles' => $roles,
      'op' => 'Create new account',
      'notify' => true,
    );

    $pass = user_password();
    $form_state['input']['pass'] = array('pass1'=>$pass,'pass2'=>$pass);

    $form_state['rebuild'] = FALSE;
    $form_state['programmed'] = TRUE;
    $form_state['complete form'] = FALSE;
    $form_state['method'] = 'post';
    $form_state['build_info']['args'] = array();
    /*
    * if we want to submit this form more than once in a process (e.g. create more than one user)
    * we must force it to validate each time for this form. Otherwise it will not validate
    * subsequent submissions and the manner in which the password is passed in will be invalid
    * */
    $form_state['must_validate'] = TRUE;
    $config = CRM_Core_Config::singleton();

    // we also need to redirect b......
    $config->inCiviCRM = TRUE;

    $form = drupal_retrieve_form('user_register_form', $form_state);
    $form_state['process_input'] = 1;
    $form_state['submitted'] = 1;
    $form['#array_parents'] = array();
    $form['#tree'] = FALSE;
    drupal_process_form('user_register_form', $form, $form_state);

    $config->inCiviCRM = FALSE;

    if (form_get_errors()) {
      CRM_Core_Session::setStatus('No user account created for '.$name, 'No user account created', 'error');
      return FALSE;
    }
    $this->drupal_id = $form_state['user']->uid;

    $ufmatch             = new CRM_Core_DAO_UFMatch();
    $ufmatch->domain_id  = CRM_Core_Config::domainID();
    $ufmatch->uf_id      = $this->drupal_id;
    $ufmatch->contact_id = $this->contact_id;
    $ufmatch->uf_name    = $name;

    if (!$ufmatch->find(TRUE)) {
      $ufmatch->save();
    }


    return $this->drupal_id;
  }

  protected function getDurpalUserId($contact_id) {
    try {
      $domain_id = CRM_Core_Config::domainID();
      $uf = civicrm_api3('UFMatch', 'getsingle', array('contact_id' => $contact_id, 'domain_id' => $domain_id));
      return $uf['uf_id'];
    } catch (Exception $e) {
      //do nothing
    }
    return false;
  }

}
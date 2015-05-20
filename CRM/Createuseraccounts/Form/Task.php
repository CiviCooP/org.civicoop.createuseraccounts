<?php

class CRM_Createuseraccounts_Form_Task extends CRM_Contact_Form_Task {

  function buildQuickForm() {
    $roles = user_roles();
    foreach($roles as $rid => $role) {
      if ($rid == DRUPAL_AUTHENTICATED_RID || $rid == DRUPAL_ANONYMOUS_RID) {
        unset($roles[$rid]);
      }
    }

    $rolesSelect = $this->addElement('advmultiselect', 'roles', ts('Roles'), $roles, array(
      'size' => 5,
      'style' => 'width:250px',
      'class' => 'advmultiselect',
    ));

    $rolesSelect->setButtonAttributes('add', array('value' => ts('Add >>')));
    $rolesSelect->setButtonAttributes('remove', array('value' => ts('<< Remove')));

    $this->addDefaultButtons(ts('Create user accounts'));
  }

  public function postProcess() {

    $params = $this->controller->exportValues();
    $roles = $params['roles'];

    $queue = CRM_Queue_Service::singleton()->create(array(
      'type' => 'Sql',
      'name' => 'org.civicoop.createuseraccounts',
      'reset' => false, //do not flush queue upon creation
    ));

    foreach($this->_contactIds as $cid) {
      //create a task without parameters
      $task = new CRM_Queue_Task(
        array('CRM_Createuseraccounts_Page_Creator', 'CreateUserAccount'), //call back method
        array($cid, $roles)
      );
      //now add this task to the queue
      $queue->createItem($task);
    }

    $url = CRM_Utils_System::url('civicrm/createuseraccounts');
    CRM_Utils_System::redirect($url);

  }

}
<?php

require_once 'CRM/Core/Page.php';

class CRM_Createuseraccounts_Page_Creator extends CRM_Core_Page {
  function run() {
    //retrieve the queue
    $queue = CRM_Queue_Service::singleton()->create(array(
      'type' => 'Sql',
      'name' => 'org.civicoop.createuseraccounts',
      'reset' => false, //do not flush queue upon creation
    ));


    $runner = new CRM_Queue_Runner(array(
      'title' => ts('Create user accounts'), //title fo the queue
      'queue' => $queue, //the queue object
      'errorMode'=> CRM_Queue_Runner::ERROR_ABORT, //abort upon error and keep task in queue
      'onEnd' => array('CRM_Createuseraccounts_Page_Creator', 'onEnd'), //method which is called as soon as the queue is finished
      'onEndUrl' => CRM_Utils_System::url('civicrm', 'reset=1'), //go to page after all tasks are finished
    ));

    $runner->runAllViaWeb(); // does not return
  }

  /**
   * Handle the final step of the queue
   */
  static function onEnd(CRM_Queue_TaskContext $ctx) {
    //set a status message for the user
    CRM_Core_Session::setStatus('User accounts have been created', 'Queue', 'success');
  }

  public static function CreateUserAccount(CRM_Queue_TaskContext $ctx, $contact_id, $roles) {
    $user = new CRM_Createuseraccounts_DrupalUser($contact_id, $roles);
    $user->createDrupalAccount();
    //$user->assignRolesToUser();
    return true;
  }
}

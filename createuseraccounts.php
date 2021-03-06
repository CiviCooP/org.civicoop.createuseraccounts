<?php

require_once 'createuseraccounts.civix.php';

/**
 * Implementation of hook_civicrm_searchTasks
 *
 * Add a task for creating user accounts to the search task list
 *
 * @param $objectName
 * @param $tasks
 */
function createuseraccounts_civicrm_searchTasks( $objectName, &$tasks ) {
  if ($objectName == 'contact') {
    $permission = CRM_Core_Permission::check('administer CiviCRM');
    if (!$permission) {
      return;
    }
    $tasks['org_civicoop_createuseraccount'] = array(
      'title' => ts('Create user accounts'),
      'class' => 'CRM_Createuseraccounts_Form_Task',
    );
  }
}

/**
 * Implementation of hook_civicrm_config
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_config
 */
function createuseraccounts_civicrm_config(&$config) {
  _createuseraccounts_civix_civicrm_config($config);
}

/**
 * Implementation of hook_civicrm_xmlMenu
 *
 * @param $files array(string)
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_xmlMenu
 */
function createuseraccounts_civicrm_xmlMenu(&$files) {
  _createuseraccounts_civix_civicrm_xmlMenu($files);
}

/**
 * Implementation of hook_civicrm_install
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_install
 */
function createuseraccounts_civicrm_install() {
  _createuseraccounts_civix_civicrm_install();
}

/**
 * Implementation of hook_civicrm_uninstall
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_uninstall
 */
function createuseraccounts_civicrm_uninstall() {
  _createuseraccounts_civix_civicrm_uninstall();
}

/**
 * Implementation of hook_civicrm_enable
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function createuseraccounts_civicrm_enable() {
  _createuseraccounts_civix_civicrm_enable();
}

/**
 * Implementation of hook_civicrm_disable
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_disable
 */
function createuseraccounts_civicrm_disable() {
  _createuseraccounts_civix_civicrm_disable();
}

/**
 * Implementation of hook_civicrm_upgrade
 *
 * @param $op string, the type of operation being performed; 'check' or 'enqueue'
 * @param $queue CRM_Queue_Queue, (for 'enqueue') the modifiable list of pending up upgrade tasks
 *
 * @return mixed  based on op. for 'check', returns array(boolean) (TRUE if upgrades are pending)
 *                for 'enqueue', returns void
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_upgrade
 */
function createuseraccounts_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _createuseraccounts_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implementation of hook_civicrm_managed
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_managed
 */
function createuseraccounts_civicrm_managed(&$entities) {
  _createuseraccounts_civix_civicrm_managed($entities);
}

/**
 * Implementation of hook_civicrm_caseTypes
 *
 * Generate a list of case-types
 *
 * Note: This hook only runs in CiviCRM 4.4+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function createuseraccounts_civicrm_caseTypes(&$caseTypes) {
  _createuseraccounts_civix_civicrm_caseTypes($caseTypes);
}

/**
 * Implementation of hook_civicrm_alterSettingsFolders
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_alterSettingsFolders
 */
function createuseraccounts_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _createuseraccounts_civix_civicrm_alterSettingsFolders($metaDataFolders);
}

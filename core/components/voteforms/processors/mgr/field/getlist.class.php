<?php

/**
 * Get a list of Field
 */
class VoteFormFieldGetListProcessor extends modObjectGetListProcessor {
  public $objectType = 'VoteForm';
  public $classKey = 'VoteForm';
  public $defaultSortField = 'index';
  public $defaultSortDirection = 'DESC';
  //public $permission = 'list';


  /**
   * * We doing special check of permission
   * because of our objects is not an instances of modAccessibleObject
   *
   * @return boolean|string
   */
  public function beforeQuery() {
    if (!$this->checkPermissions()) {
      return $this->modx->lexicon('access_denied');
    }
    return true;
  }

}

return 'VoteFormFieldGetListProcessor';
<?php

/**
 * Get an Item
 */
class VoteFormsItemGetProcessor extends modObjectGetProcessor {
  public $objectType = 'VoteFormsItem';
  public $classKey = 'VoteFormsItem';
  public $languageTopics = array('voteforms:default');
  //public $permission = 'view';


  /**
   * We doing special check of permission
   * because of our objects is not an instances of modAccessibleObject
   *
   * @return mixed
   */
  public function process() {
    if (!$this->checkPermissions()) {
      return $this->failure($this->modx->lexicon('access_denied'));
    }

    return parent::process();
  }

}

return 'VoteFormsItemGetProcessor';
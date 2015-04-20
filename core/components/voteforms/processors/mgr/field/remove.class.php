<?php

/**
 * Remove an Field
 */
class VoteFormFieldRemoveProcessor extends modObjectProcessor {
  public $objectType = 'VoteFormField';
  public $classKey = 'VoteFormField';
  public $languageTopics = array('voteforms');
  //public $permission = 'remove';


  /**
   * @return array|string
   */
  public function process() {
    if (!$this->checkPermissions()) {
      return $this->failure($this->modx->lexicon('access_denied'));
    }

    $ids = $this->modx->fromJSON($this->getProperty('ids'));
    if (empty($ids)) {
      return $this->failure($this->modx->lexicon('voteforms_item_err_ns'));
    }

    foreach ($ids as $id) {
      /** @var VoteFormField $object */
      if (!$object = $this->modx->getObject($this->classKey, $id)) {
        return $this->failure($this->modx->lexicon('voteforms_item_err_nf'));
      }

      $object->remove();
    }

    return $this->success();
  }

}

return 'VoteFormFieldRemoveProcessor';
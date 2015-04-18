<?php

/**
 * Enable an Form
 */
class VoteFormEnableProcessor extends modObjectProcessor {
  public $objectType = 'VoteForm';
  public $classKey = 'VoteForm';
  public $languageTopics = array('voteforms');
  //public $permission = 'save';


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
      /** @var VoteForm $object */
      if (!$object = $this->modx->getObject($this->classKey, $id)) {
        return $this->failure($this->modx->lexicon('voteforms_item_err_nf'));
      }

      $object->set('active', true);
      $object->save();
    }

    return $this->success();
  }

}

return 'VoteFormEnableProcessor';

<?php

/**
 * Remove an Forms
 */
class VoteFormThreadRemoveProcessor extends modObjectProcessor {
  public $objectType = 'VoteFormThread';
  public $classKey = 'VoteFormThread';
  public $languageTopics = array('voteforms');
  public $permission = 'remove';


  /**
   * @return array|string
   */
  public function process() {
    if (!$this->checkPermissions()) {
      return $this->failure($this->modx->lexicon('access_denied'));
    }

    $ids = $this->modx->fromJSON($this->getProperty('ids'));
    $forms = $this->modx->fromJSON($this->getProperty('forms'));
    if (empty($ids) and !empty($forms)) {
      $query = $this->modx->newQuery($this->classKey);
      $query->select('id');
      $where = array();
      foreach ($forms as $formid){
        $where[] = array(
          'form' => $formid
        );
      }
      $query->where($where, xPDOQuery::SQL_OR);
      $forms = $this->modx->getCollection($this->classKey, $query);
      foreach ($forms as $form){
        $ids[] = $form->id;
      }
    }
    if (empty($ids)) {
      return $this->failure($this->modx->lexicon('voteforms_item_err_ns'));
    }

    foreach ($ids as $id) {
      /** @var VoteForm $object */
      if (!$object = $this->modx->getObject($this->classKey, $id)) {
        return $this->failure($this->modx->lexicon('voteforms_item_err_nf'));
      }

      $object->remove();
    }

    return $this->success();
  }

}

return 'VoteFormThreadRemoveProcessor';
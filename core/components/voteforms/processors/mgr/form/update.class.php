<?php

/**
 * Update an Form
 */
class VoteFormUpdateProcessor extends modObjectUpdateProcessor {
  public $objectType = 'VoteForm';
  public $classKey = 'VoteForm';
  public $languageTopics = array('voteforms');
  //public $permission = 'save';


  /**
   * We doing special check of permission
   * because of our objects is not an instances of modAccessibleObject
   *
   * @return bool|string
   */
  public function beforeSave() {
    if (!$this->checkPermissions()) {
      return $this->modx->lexicon('access_denied');
    }

    return true;
  }


  /**
   * @return bool
   */
  public function beforeSet() {
    $id = (int)$this->getProperty('id');
    $name = trim($this->getProperty('name'));
    $ratingMax = (int)$this->getProperty('rating_max');

    if (empty($id)) {
      return $this->modx->lexicon('voteforms_item_err_ns');
    }

    if (empty($name)) {
      $this->modx->error->addField('name', $this->modx->lexicon('voteforms_item_err_name'));
    }
    elseif ($this->modx->getCount($this->classKey, array('name' => $name, 'id:!=' => $id))) {
      $this->modx->error->addField('name', $this->modx->lexicon('voteforms_item_err_ae'));
    }

    if(($ratingMax !== $this->object->get('rating_max')) and
      $this->modx->getCount('VoteFormRecord', array('form' => $id))){
      $this->modx->error->addField('rating_max', $this->modx->lexicon('voteforms_form_err_rating_max'));
    }

    return parent::beforeSet();
  }
}

return 'VoteFormUpdateProcessor';

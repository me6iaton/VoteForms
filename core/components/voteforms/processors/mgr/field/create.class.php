<?php

/**
 * Create an Field
 */
class VoteFormFieldCreateProcessor extends modObjectCreateProcessor {
  public $objectType = 'VoteFormField';
  public $classKey = 'VoteFormField';
  public $languageTopics = array('voteforms');
  //public $permission = 'create';


  /**
   * @return bool
   */
  public function beforeSet() {
    $name = trim($this->getProperty('name'));
    $form = (int)$this->getProperty('form');
    $type = trim($this->getProperty('type'));

    if (empty($name)) {
      $this->modx->error->addField('name', $this->modx->lexicon('voteforms_item_err_name'));
    }

    if (empty($form)) {
      $this->modx->error->addField('form', $this->modx->lexicon('voteforms_item_err_form'));
    }

    if (empty($type)) {
      $this->modx->error->addField('type', $this->modx->lexicon('voteforms_item_err_type'));
    }

    return parent::beforeSet();
  }

}

return 'VoteFormFieldCreateProcessor';
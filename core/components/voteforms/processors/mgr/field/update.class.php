<?php

/**
 * Update an Field
 */
class VoteFormFieldUpdateProcessor extends modObjectUpdateProcessor {
  public $objectType = 'VoteFormField';
  public $classKey = 'VoteFormField';
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
    $form = (int)$this->getProperty('form');
    $type = trim($this->getProperty('type'));

    if (empty($id)) {
      return $this->modx->lexicon('voteforms_item_err_ns');
    }

    if (empty($name)) {
      $this->modx->error->addField('name', $this->modx->lexicon('voteforms_item_err_name'));
    }
    elseif ($this->modx->getCount($this->classKey, array('name' => $name, 'id:!=' => $id))) {
      $this->modx->error->addField('name', $this->modx->lexicon('voteforms_item_err_ae'));
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

return 'VoteFormFieldUpdateProcessor';

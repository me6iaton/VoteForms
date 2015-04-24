<?php

/**
 * Create an Record Multiple
 */
class VoteFormRecordCreateMultipleProcessor extends modObjectProcessor {
  public $objectType = 'VoteFormRecord';
  public $classKey = 'VoteFormRecord';
  public $languageTopics = array('voteforms');
  //public $permission = 'create';

  /**
   * {@inheritDoc}
   * @return boolean
   */
  public function initialize()
  {
//    $this->object = $this->modx->newObject($this->classKey);

    return parent::initialize();
  }

  /**
   * @return boolean
   */
  public function beforeSet()
  {
    $form = (int)$this->getProperty('form');
    $fields = $this->getProperty('fields');
    $thread = trim($this->getProperty('thread'));
    $createdby = $this->modx->user->id;

    if (empty($form)) {
      $this->modx->error->addField('form', $this->modx->lexicon('voteforms_err_form'));
    }
    if (empty($fields)) {
      $this->modx->error->addField('fields', $this->modx->lexicon('voteforms_err_fields'));
    }
    if (empty($thread)) {
      $this->modx->error->addField('thread', $this->modx->lexicon('voteforms_err_thread'));
    }
    if (empty($createdby)) {
      $this->modx->error->addField('createdby', $this->modx->lexicon('voteforms_err_createdby'));
    }

    return !$this->hasErrors();
  }

  /**
   * @return boolean
   */
  public function beforeSave()
  {
    if (!$this->checkPermissions()) {
      return $this->modx->lexicon('access_denied');
    }

    return true;
  }

  /**
   * Process the Object create processor
   * {@inheritDoc}
   * @return mixed
   */
  public function process()
  {
    /* Run the beforeSet method before setting the fields, and allow stoppage */
    $canSave = $this->beforeSet();
    if ($canSave !== true) {
      return $this->failure($canSave);
    }

//    $this->object->fromArray($this->getProperties());

    /* run the before save logic */
    $canSave = $this->beforeSave();
    if ($canSave !== true) {
      return $this->failure($canSave);
    }

    /* run object validation */
//    if (!$this->object->validate()) {
//      /** @var modValidator $validator */
//      $validator = $this->object->getValidator();
//      if ($validator->hasMessages()) {
//        foreach ($validator->getMessages() as $message) {
//          $this->addFieldError($message['field'], $this->modx->lexicon($message['message']));
//        }
//      }
//    }

    /* save element */
//    if ($this->object->save() == false) {
//      $this->modx->error->checkValidation($this->object);
//      return $this->failure($this->modx->lexicon($this->objectType . '_err_save'));
//    }

    $this->afterSave();

    $this->logManagerAction();
    return $this->cleanup();
  }

  /**
   * Return the success message
   * @return array
   */
  public function cleanup()
  {
    return $this->success('', $this->object);
  }

  /**
   * @return boolean
   */
  public function afterSave()
  {
    return true;
  }


  /**
   * @param array $criteria
   * @return int
   */
  public function doesAlreadyExist(array $criteria)
  {
    return $this->modx->getCount($this->classKey, $criteria);
  }

  /**
   * Log the removal manager action
   * @return void
   */
  public function logManagerAction()
  {
    $this->modx->logManagerAction($this->objectType . '_create', $this->classKey, $this->object->get($this->primaryKeyField));
  }
}

return 'VoteFormRecordCreateMultipleProcessor';

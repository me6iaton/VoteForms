<?php

/**
 * Create an Record Multiple
 */
class VoteFormRecordMultipleProcessor extends modObjectProcessor {
  public $languageTopics = array('voteforms');
  /* @var VoteForms $VoteForms */
  private $VoteForms;
  /* @var VoteForm $form */
  private $form;
  private $formId;
  private $threadId;
  private $fields;
  //public $permission = 'create';

  /**
   * {@inheritDoc}
   * @return boolean
   */
  public function initialize()
  {
    $this->VoteForms = $this->modx->getService('voteforms', 'VoteForms', $this->modx->getOption('voteforms_core_path', null, $this->modx->getOption('core_path') . 'components/voteforms/') . 'model/voteforms/');
    $this->formId = (int)$this->getProperty('form');
    $this->threadId = (int)$this->getProperty('thread');
    $this->fields = $this->getProperty('fields');
    if (empty($this->formId)) {
      $this->modx->error->addField('form', $this->modx->lexicon('voteforms_err_form'));
    }
    if (empty($this->fields)) {
      $this->modx->error->addField('fields', $this->modx->lexicon('voteforms_err_fields'));
    }
    if (empty($this->threadId)) {
      $this->modx->error->addField('thread', $this->modx->lexicon('voteforms_err_thread'));
    }

    $this->form = $this->modx->getObject('VoteForm', $this->formId);
    if(!$this->form->active){
      return $this->modx->lexicon('voteforms_record_err_active');
    }
    // validation rating_max
    $ratingMax = $this->form->rating_max;
    foreach ($this->fields as $key => $field) {
      if( (int) $field['value'] > (int) $ratingMax){
        return $this->modx->lexicon('voteforms_form_err_rating_max_value');
      }
    }
    return parent::initialize();
  }


  /**
   * Process the Object create processor
   * {@inheritDoc}
   * @return mixed
   */
  public function process()
  {
    $query = $this->modx->newQuery('VoteFormRecord');
    $query->where(array(
      'form' => $this->formId,
      'thread' => $this->threadId,
      'createdby' => $this->modx->user->id
    ));
    $records = $this->form->getMany('Records', $query);

    // update records
    $fieldsStack =  $this->fields;
    /* @var VoteFormRecord $record */
    foreach ($records as $record) {
      foreach ($fieldsStack as $key => $field){
        if($record->field == $field['id']){
          $record->set('integer', $field['value']);
          $record->save();
          unset($fieldsStack[$key]);
        }
      }
    }
    // create new records
    foreach ($fieldsStack as $key => $field){
      $record = $this->modx->newObject('VoteFormRecord');
      $record->set('form', $this->formId);
      $record->set('field',$field['id']);
      $record->set('thread', $this->threadId);
      $record->set('createdby', $this->modx->user->id);
      $record->set('integer', $field['value']);

      /* run object validation */
      if (!$record->validate()) {
        /** @var modValidator $validator */
        $validator = $record->getValidator();
        if ($validator->hasMessages()) {
          foreach ($validator->getMessages() as $message) {
            $this->addFieldError($message['field'], $this->modx->lexicon($message['message']));
          }
        }
      }

      /* save element */
      if ($record->save() == false) {
        $this->modx->error->checkValidation($record);
        return $this->failure($this->modx->lexicon($this->objectType . '_err_save'));
      }
    }

    //  set ratings for VoteFormThread and VoteFormRatingField
    $this->VoteForms->setRatings($this->form, $this->fields, $this->threadId);

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

}

return 'VoteFormRecordMultipleProcessor';

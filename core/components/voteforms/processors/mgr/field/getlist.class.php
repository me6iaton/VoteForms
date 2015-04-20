<?php

/**
 * Get a list of Field
 */
class VoteFormFieldGetListProcessor extends modObjectGetListProcessor {
  public $objectType = 'VoteFormField';
  public $classKey = 'VoteFormField';
  public $defaultSortField = 'index';
  public $defaultSortDirection = 'ASC';
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

  /**
   * @param xPDOQuery $c
   *
   * @return xPDOQuery
   */
  public function prepareQueryBeforeCount(xPDOQuery $c)
  {
    $form = (int)$this->getProperty('form');
    if ($form) {
      $c->where(array(
        'form' => $form
      ));
    }

    return $c;
  }

  /**
   * @param xPDOObject $object
   *
   * @return array
   */
  public function prepareRow(xPDOObject $object)
  {
    $array = $object->toArray();
    $array['actions'] = array();

    // Edit
    $array['actions'][] = array(
      'cls' => '',
      'icon' => 'icon icon-edit',
      'title' => $this->modx->lexicon('voteforms_item_update'),
      //'multiple' => $this->modx->lexicon('voteforms_items_update'),
      'action' => 'updateItem',
      'button' => true,
      'menu' => true,
    );

    // Remove
    $array['actions'][] = array(
      'cls' => '',
      'icon' => 'icon icon-trash-o action-red',
      'title' => $this->modx->lexicon('voteforms_item_remove'),
      'multiple' => $this->modx->lexicon('voteforms_items_remove'),
      'action' => 'removeItem',
      'button' => true,
      'menu' => true,
    );

    return $array;
  }

}

return 'VoteFormFieldGetListProcessor';
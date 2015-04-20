<?php

/**
 * Get a list of Form
 */
class VoteFormGetListProcessor extends modObjectGetListProcessor {
  public $objectType = 'VoteForm';
  public $classKey = 'VoteForm';
  public $defaultSortField = 'id';
  public $defaultSortDirection = 'DESC';
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
  public function prepareQueryBeforeCount(xPDOQuery $c) {
    $query = trim($this->getProperty('query'));
    if ($query) {
      $c->where(array(
        'name:LIKE' => "%{$query}%",
        'OR:description:LIKE' => "%{$query}%",
      ));
    }

    return $c;
  }


  /**
   * @param xPDOObject $object
   *
   * @return array
   */
  public function prepareRow(xPDOObject $object) {
    $array = $object->toArray();
    $array['actions'] = array();

    // Edit Fields
    $array['actions'][] = array(
      'cls' => '',
      'icon' => 'icon icon-external-link action-blue',
      'title' => $this->modx->lexicon('voteforms_item_update_fields'),
      'multiple' => $this->modx->lexicon('voteforms_items_update_fields'),
      'action' => 'updateItemFields',
      'button' => true,
      'menu' => true,
    );
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

    if (!$array['active']) {
      $array['actions'][] = array(
        'cls' => '',
        'icon' => 'icon icon-power-off action-green',
        'title' => $this->modx->lexicon('voteforms_item_enable'),
        'multiple' => $this->modx->lexicon('voteforms_items_enable'),
        'action' => 'enableItem',
        'button' => true,
        'menu' => true,
      );
    }
    else {
      $array['actions'][] = array(
        'cls' => '',
        'icon' => 'icon icon-power-off action-gray',
        'title' => $this->modx->lexicon('voteforms_item_disable'),
        'multiple' => $this->modx->lexicon('voteforms_items_disable'),
        'action' => 'disableItem',
        'button' => true,
        'menu' => true,
      );
    }

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

return 'VoteFormGetListProcessor';
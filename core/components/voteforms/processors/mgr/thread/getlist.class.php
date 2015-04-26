<?php

/**
 * Get a list of Form
 */
class VoteFormThreadGetListProcessor extends modObjectGetListProcessor {
  public $objectType = 'VoteFormThread';
  public $classKey = 'VoteFormThread';
  public $defaultSortField = 'rating';
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
        'OR:resource:=' => $query,
        'OR:form:=' => $query,
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
      'title' => $this->modx->lexicon('voteforms_thread_update_resource'),
      'multiple' => $this->modx->lexicon('voteforms_thread_update_resource'),
      'action' => 'updateResource',
      'button' => true,
      'menu' => true,
    );
    return $array;
  }

}

return 'VoteFormThreadGetListProcessor';
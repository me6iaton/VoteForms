<?php

/**
 * Get a list of Form
 */
class VoteFormRatingFieldGetListProcessor extends modObjectGetListProcessor
{
  public $objectType = 'VoteFormRatingField';
  public $classKey = 'VoteFormRatingField';
  public $defaultSortField = 'thread';
  public $defaultSortDirection = 'ASC';
  //public $permission = 'list';


  /**
   * * We doing special check of permission
   * because of our objects is not an instances of modAccessibleObject
   *
   * @return boolean|string
   */
  public function beforeQuery()
  {
    if (!$this->checkPermissions()) {
      return $this->modx->lexicon('access_denied');
    }

    return true;
  }


  /**
   * Get the data of the query
   * @return array
   */
  public function getData()
  {
    $data = array();
    $limit = intval($this->getProperty('limit'));
    $start = intval($this->getProperty('start'));

    /* query for chunks */
    $c = $this->modx->newQuery($this->classKey);
    $c = $this->prepareQueryBeforeCount($c);
    $data['total'] = $this->modx->getCount($this->classKey, $c);
    $c = $this->prepareQueryAfterCount($c);

    $sortClassKey = $this->getSortClassKey();
    $sortKey = $this->modx->getSelectColumns($sortClassKey, $this->getProperty('sortAlias', $sortClassKey), '', array($this->getProperty('sort')));
    if (empty($sortKey)){
      $sortKey = $this->getProperty('sort');
      if(strstr($sortKey, 'rating_field')){
        $sortKeyArr = explode('_', $sortKey);
        $sortKey = 'FIELD(VoteFormRatingField.field, '. $sortKeyArr[2].') DESC, rating';
      }
    }
    $c->sortby($sortKey, $this->getProperty('dir'));
    if ($limit > 0) {
      $c->limit($limit, $start);
    }

    $data['results'] = $this->modx->getCollection($this->classKey, $c);
    return $data;
  }

  /**
   * Can be used to provide a custom sorting class key for the default sorting columns
   * @return string
   */
  public function getSortClassKey()
  {
    return $this->classKey;
  }

  /**
   * Can be used to insert a row after iteration
   * @param array $list
   * @return array
   */
  public function afterIteration(array $list)
  {
    $listConcat = array();
    $keysConcat = array();
    foreach ($list as $key => $item){
      if(isset($keysConcat[$item['thread']])){
        $listConcat[$keysConcat[$item['thread']]]['rating_field_' . $item['field']] = $item['rating'];
      }else{
        $keysConcat[$item['thread']] = count($listConcat);
        $listConcat[] = array(
          'resource' => $item['resource'],
          'thread' => $item['thread'],
          'rating_field_' . $item['field'] => $item['rating'],
          'actions' => $item['actions']
        );
      }
    }
    return $listConcat;
  }


  /**
   * @param xPDOQuery $c
   *
   * @return xPDOQuery
   */
  public function prepareQueryBeforeCount(xPDOQuery $c)
  {
    $form = trim($this->getProperty('form'));
    if ($form) {
      $c->where(array(
        'form:=' => $form,
      ));
    }
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
  public function prepareRow(xPDOObject $object)
  {
    $array = $object->toArray();
//    $array['actions'] = array();
//
//    // Edit Fields
//    $array['actions'][] = array(
//      'cls' => '',
//      'icon' => 'icon icon-external-link action-blue',
//      'title' => $this->modx->lexicon('voteforms_thread_update_resource'),
//      'multiple' => $this->modx->lexicon('voteforms_thread_update_resource'),
//      'action' => 'updateResource',
//      'button' => true,
//      'menu' => true,
//    );
//    // Remove
//    $array['actions'][] = array(
//      'cls' => '',
//      'icon' => 'icon icon-trash-o action-red',
//      'title' => $this->modx->lexicon('voteforms_clean'),
//      'multiple' => $this->modx->lexicon('voteforms_clean'),
//      'action' => 'removeThreads',
//      'button' => true,
//      'menu' => true,
//    );
    return $array;
  }

}

return 'VoteFormRatingFieldGetListProcessor';
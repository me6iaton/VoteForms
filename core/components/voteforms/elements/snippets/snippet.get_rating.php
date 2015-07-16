<?php
/** @var array $scriptProperties */
/* @var pdoFetch $pdoFetch */
$fqn = $modx->getOption('pdoFetch.class', null, 'pdotools.pdofetch', true);
if ($pdoClass = $modx->loadClass($fqn, '', false, true)) {
  $pdoFetch = new $pdoClass($modx, $scriptProperties);
} elseif ($pdoClass = $modx->loadClass($fqn, MODX_CORE_PATH . 'components/pdotools/model/', false, true)) {
  $pdoFetch = new $pdoClass($modx, $scriptProperties);
} else {
  $modx->log(modX::LOG_LEVEL_ERROR, 'Could not load pdoFetch from "MODX_CORE_PATH/components/pdotools/model/".');
  return false;
}
/** @var VoteForms $VoteForms */
if (!$VoteForms = $modx->getService('voteforms', 'VoteForms', $modx->getOption('voteforms_core_path', null, $modx->getOption('core_path') . 'components/voteforms/') . 'model/voteforms/', $scriptProperties)) {
  return 'Could not load VoteForms class!';
}

$VoteForms->initialize($modx->context->key, $scriptProperties);

// Properties
if (empty($form)) {
  return $modx->lexicon('voteforms_form_err_id');
} else {
  $form = $modx->getOption('form', $scriptProperties);
}
$resourceId = $modx->getOption('resource', $scriptProperties, $modx->resource->id, true);
$thread = $modx->getOption('threadName', $scriptProperties, 'resource-' . $resourceId . '-form-' . $form, true);
$field = $modx->getOption('field', $scriptProperties);
$tpl = $modx->getOption('tpl', $scriptProperties);
$stars = $modx->getOption('stars', $scriptProperties);
if($field){
  $default = array(
    'class' => 'VoteFormThread',
    'where' => array(
      'form' => $form,
      'resource' => $resourceId,
      'name' => $thread,
    ),
    'leftJoin' => array(
      'Form' => array(
        'class' => 'VoteForm',
        'on' => 'VoteFormThread.form = Form.id'
      ),
      'Record'=>array(
        'class' => 'VoteFormRecord',
        'on' => "VoteFormThread.form = Record.form" .
          " AND VoteFormThread.id = Record.thread" .
          " AND {$field} = Record.field"
      ),
      'Field' => array(
        'class' => 'VoteFormField',
        'on' => "{$field} = Field.id"
      ),
    ),
    'groupby' => 'Record.thread',
    'select' => array(
      'VoteFormThread' => '*',
      'Form' => $modx->getSelectColumns('VoteForm', 'Form', 'form.', array(), true),
      'Field' => $modx->getSelectColumns('VoteFormField', 'Field', 'field.', array(), true),
      'Record' => 'ROUND(AVG(`integer`), 2) AS `rating`, COUNT(*) AS users_count',
    ),
    'return' => 'data',
  );
}else{
  $default = array(
    'class' => 'VoteFormThread',
    'where' => array(
      'form' => $form,
      'resource' => $resourceId,
      'name' => $thread,
    ),
    'leftJoin' => array(
      'Form' => array(
        'class' => 'VoteForm',
        'on' => 'VoteFormThread.form = Form.id'
      )),
    'select' => array(
      'VoteFormThread' => '*',
      'Form' => $modx->getSelectColumns('VoteForm', 'Form', 'form.', array(), true),
    ),
    'return' => 'data',
  );
}

$pdoFetch->setConfig($default);
$outputData= $pdoFetch->run();
// if fist init
if(!$outputData){
  $VoteForms->newObjects($modx->resource->id, $form, $thread);
  $outputData = $pdoFetch->run();
}
$outputData = $outputData[0];

if($stars){
  $outputData['stars'] =
    "<div
    data-read-only='true'
    data-thread='{$outputData['id']}'
    data-score='{$outputData['rating']}'
    class='raty read-only'
    ></div>";
}
if($field){
  $outputData['class'] = '';
}else{
  $outputData['class'] = 'vtf-thread-[[+id]]';
}
if (!$outputData['rating']) $outputData['rating'] = 0;

$output = $pdoFetch->getChunk($tpl, $outputData, $pdoFetch->config['fastMode']);
return $output;

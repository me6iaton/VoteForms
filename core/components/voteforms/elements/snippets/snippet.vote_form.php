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
if (!$VoteForms->authenticated) {
  return $modx->lexicon('voteforms_form_err_no_auth');
}
$VoteForms->initialize($modx->context->key, $scriptProperties);

// Properties
if (empty($thread)) {
  $scriptProperties['thread'] = $modx->getOption('thread', $scriptProperties, 'resource-' . $modx->resource->id, true);
}
if(empty($id)){
  return $modx->lexicon('voteforms_form_err_id');
}else{
  $formId = $modx->getOption('id', $scriptProperties);
}
$tplOuter = $modx->getOption('tplOuter', $scriptProperties);
$tplRow = $modx->getOption('tplRow', $scriptProperties);
$sortby = $modx->getOption('sortby', $scriptProperties, 'index');
$sortdir = $modx->getOption('sortbir', $scriptProperties, 'ASC');
//$limit = $modx->getOption('limit', $scriptProperties, 5);
//$outputSeparator = $modx->getOption('outputSeparator', $scriptProperties, "\n");
//$toPlaceholder = $modx->getOption('toPlaceholder', $scriptProperties, false);

// Prepare Voteforms Thread
/** @var TicketThread $thread */
$thread = $modx->getObject('VoteFormThread', array(
  'name' => $scriptProperties['thread'],
  'form' => $formId
));
if (!$thread) {
  $thread = $VoteForms->prepareJquery($modx->resource->id, $formId, $scriptProperties['thread']);
}
$scriptProperties['thread'] = $thread->get('id');

// get fields
$default = array(
  'class' => 'VoteFormField',
  'where' => array(
    'form' => $formId,
    'Form.active' => 1
  ),
  'leftJoin' => array(
    'Form' => array(
      'class' => 'VoteForm',
      'on' => 'VoteFormField.form = Form.id'
    ),
    'Record' => array(
      'class' => 'VoteFormRecord',
      'on' => "VoteFormField.id = Record.field" .
        " AND Form.id = Record.form" .
        " AND Record.thread = {$thread->get('id')}" .
        " AND Record.createdby = {$modx->user->id}"
    ),
  ),
  'select' => array(
    'VoteFormField' => '*',
    'Record' => 'Record.integer as record',
    'Form' => 'Form.rating_max as rating_max',
  ),
  'return' => 'data',
);
$pdoFetch->setConfig($default);
$rows = $pdoFetch->run();
// Processing rows
$ratingMax = null;
$output = array();
if (!empty($rows) && is_array($rows)) {
  foreach ($rows as $k => $row) {
    $ratingMax = $row['rating_max'];
    $output[] = $pdoFetch->getChunk($tplRow, $row, $pdoFetch->config['fastMode']);
  }
}

// Output
if (empty($outputSeparator)) {
  $outputSeparator = "\n";
}
$output = implode($outputSeparator, $output);
$outputData = array_merge(array(
  'output' => $output,
  'rating_max' => $ratingMax
), $scriptProperties);
$output = $pdoFetch->getChunk($tplOuter, $outputData, $pdoFetch->config['fastMode']);
// By default just return output
return $output;

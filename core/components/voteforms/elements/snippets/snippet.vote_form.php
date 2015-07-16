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
$formId = $modx->getOption('id', $scriptProperties);
if (empty($formId)) {
  return $modx->lexicon('voteforms_form_err_id');
}
$resourceId = $modx->getOption('resource', $scriptProperties, $modx->resource->id, true);
$threadName = $modx->getOption('threadName', $scriptProperties, 'resource-' . $resourceId . '-form-' . $formId, true);
$widget = $modx->getOption('widget', $scriptProperties);
$tplOuter = $modx->getOption('tplOuter', $scriptProperties);
$tplRow = $modx->getOption('tplRow', $scriptProperties);
$sortby = $modx->getOption('sortby', $scriptProperties, 'index');
$sortdir = $modx->getOption('sortbir', $scriptProperties, 'ASC');
if ($widget == 'upvote' and $tplRow == "tpl.VoteForms.row") {
  $tplRow = 'tpl.VoteForms.row.upvote';
}

// Prepare Voteforms
/** @var VoteFormThread $thread */
$thread = $modx->getObject('VoteFormThread', array(
  'name' => $threadName,
  'form' => $formId
));
if (!$thread) {
  $thread = $VoteForms->newObjects($resourceId, $formId, $threadName);
}
$threadId = $thread->get('id');

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
      'on' => "Record.field = VoteFormField.id" .
        " AND Record.form = Form.id" .
        " AND Record.thread = {$threadId}" .
        " AND Record.createdby = {$modx->user->id}"
    ),
    'Rating' => array(
      'class' => 'VoteFormRatingField',
      'on' => "Rating.field = VoteFormField.id " .
        " AND Rating.form = Form.id" .
        " AND Rating.thread = {$threadId}"
    ),
  ),
  'select' => array(
    'VoteFormField' => '*',
    'Record' => 'Record.integer as record',
    'Form' => 'Form.rating_max as rating_max',
    'Rating' => 'Rating.rating as rating',
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
    if ($scriptProperties['widget'] == 'upvote') {
      if ($row['record'] == 1) {
        $row['upvoteOn'] = 'upvote-on';
      }else if ($row['record'] == -1) {
        $row['downvoteOn'] = 'downvote-on';
      }
    }
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
  'thread' => $threadId,
  'rating_max' => $ratingMax,
  'output' => $output
), $scriptProperties);
$output = $pdoFetch->getChunk($tplOuter, $outputData, $pdoFetch->config['fastMode']);
// By default just return output
return $output;

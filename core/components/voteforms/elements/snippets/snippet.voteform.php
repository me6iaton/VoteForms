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

if (empty($thread)) {
  $scriptProperties['thread'] = $modx->getOption('thread', $scriptProperties, 'resource-' . $modx->resource->id, true);
}
$formId = $modx->getOption('id', $scriptProperties);
$tplOuter = $modx->getOption('tplOuter', $scriptProperties);
$tplRow = $modx->getOption('tplRow', $scriptProperties);
$sortby = $modx->getOption('sortby', $scriptProperties, 'index');
$sortdir = $modx->getOption('sortbir', $scriptProperties, 'ASC');
//$limit = $modx->getOption('limit', $scriptProperties, 5);
//$outputSeparator = $modx->getOption('outputSeparator', $scriptProperties, "\n");
//$toPlaceholder = $modx->getOption('toPlaceholder', $scriptProperties, false);

$default = array(
  'class' => 'VoteForm',
  'where' => array(
    'VoteForm.id' => $formId
  ),
  'leftJoin' => array(
    'Field' => array(
      'class' => 'VoteFormField',
      'on' => 'VoteForm.id = Field.form'
    )
  ),
  'select' => array(
    'VoteForm' => '*',
    'Field' => 'Field.name as fileds',
  ),
  'return' => 'data',
);

// Merge all properties and run!
$pdoFetch->setConfig($default);
$rows = $pdoFetch->run();

// Processing rows
$output = array();
if (!empty($rows) && is_array($rows)) {
  foreach ($rows as $k => $row) {
    $output[] = $pdoFetch->getChunk($tplRow, $row, $pdoFetch->config['fastMode']);
  }
}
// Build query
//$c = $modx->newQuery('VoteFormsItem');
//$c->sortby($sortby, $sortdir);
//$c->limit($limit);
//$items = $modx->getIterator('VoteFormsItem', $c);

// Iterate through items
//$list = array();
///** @var VoteFormsItem $item */
//foreach ($items as $item) {
//  $list[] = $modx->getChunk($tpl, $item->toArray());
//}

// Output
if (empty($outputSeparator)) {
  $outputSeparator = "\n";
}
$output = implode($outputSeparator, $output);;

// By default just return output
return $output;

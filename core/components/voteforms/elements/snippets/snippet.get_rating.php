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
$resource = $modx->getOption('resource', $scriptProperties, $modx->resource->id, true);
$thread = $modx->getOption('thread', $scriptProperties, 'resource-' . $resource, true);
$tpl = $modx->getOption('tpl', $scriptProperties);
$stars = $modx->getOption('stars', $scriptProperties);

$outputData = $pdoFetch->getArray('VoteFormThread',
  array (
    'form' => $form,
    'resource' => $resource,
    'name' => $thread,
),array(
    'leftJoin'=> array(
      'Form' => array(
        'class' => 'VoteForm',
        'on' => 'VoteFormThread.form = Form.id'
      )),
    'select' => array(
      'VoteFormThread' => '*',
      'Form' => $modx->getSelectColumns('VoteForm', 'Form', 'form.', array(), true),
    )
  ));
$test = $modx->getSelectColumns('VoteForm', 'Form', 'form.', array('id', 'name'), true);
//$modx->getSelectColumns('TicketsSection', 'Section', 'section.', array('content'), true),
$outputData['stars'] = $stars;


$output = $pdoFetch->getChunk($tpl, $outputData, $pdoFetch->config['fastMode']);
return $output;

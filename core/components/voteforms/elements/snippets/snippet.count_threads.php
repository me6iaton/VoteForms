<?php
/** @var array $scriptProperties */
$form = $modx->getOption('form', $scriptProperties);
$field = $modx->getOption('field', $scriptProperties);

if(!$form) return;

/** @var VoteForms $VoteForms */
if (!$VoteForms = $modx->getService('voteforms', 'VoteForms', $modx->getOption('voteforms_core_path', null, $modx->getOption('core_path') . 'components/voteforms/') . 'model/voteforms/', $scriptProperties)) {
  return 'Could not load VoteForms class!';
}

$query = $modx->newQuery('VoteFormRecord');
if ($field) {
  $query->where(array(
    'form' => $form,
    'field' => $field,
    'integer:!=' => 0
  ));
} else {
  $query->where(array(
    'form' => $form,
    'integer:!=' => 0
  ));
}
$query->groupby('thread');

return $modx->getCount('VoteFormRecord', $query);
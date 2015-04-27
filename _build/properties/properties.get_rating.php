<?php

$properties = array();

$tmp = array(
  'form' => array(
    'type' => 'numberfield',
    'value' => '',
  ),
  'resource' => array(
    'type' => 'numberfield',
    'value' => '',
  ),
  'thread' => array(
    'type' => 'textfield',
    'value' => '',
  ),
  'tpl' => array(
    'type' => 'textfield',
    'value' => 'tpl.VoteForms.rating',
  ),
  'stars' => array(
    'type' => 'combo-boolean',
    'value' => true,
  ),
);

foreach ($tmp as $k => $v) {
  $properties[] = array_merge(
array(
      'name' => $k,
      'desc' => PKG_NAME_LOWER . '_prop_' . $k,
      'lexicon' => PKG_NAME_LOWER . ':properties',
    ), $v
  );
}

return $properties;
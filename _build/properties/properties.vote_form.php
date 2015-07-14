<?php

$properties = array();

$tmp = array(
  'id' => array(
    'type' => 'numberfield',
    'value' => '',
  ),
  'thread' => array(
    'type' => 'textfield',
    'value' => '',
  ),
  'tplRow' => array(
    'type' => 'textfield',
    'value' => 'tpl.VoteForms.row',
  ),
  'tplOuter' => array(
    'type' => 'textfield',
    'value' => 'tpl.VoteForms.outer',
  ),
  'sortby' => array(
    'type' => 'textfield',
    'value' => 'index',
  ),
  'sortdir' => array(
    'type' => 'list',
    'options' => array(
      array('text' => 'ASC', 'value' => 'ASC'),
      array('text' => 'DESC', 'value' => 'DESC'),
    ),
    'value' => 'ASC'
  ),
  'widget' => array(
    'type' => 'list',
    'options' => array(
      array('text' => 'raty', 'value' => 'raty'),
      array('text' => 'upvote', 'value' => 'upvote'),
    ),
    'value' => 'raty'
  ),
  'submit' => array(
    'type' => 'combo-boolean',
    'value' => false,
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
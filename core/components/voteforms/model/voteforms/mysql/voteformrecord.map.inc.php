<?php
$xpdo_meta_map['VoteFormRecord']= array (
  'package' => 'voteforms',
  'version' => '1.1',
  'table' => 'voteforms_records',
  'extends' => 'xPDOSimpleObject',
  'fields' => 
  array (
    'form' => 0,
    'field' => 0,
    'thread' => 0,
    'createdby' => 0,
    'integer' => 0,
  ),
  'fieldMeta' => 
  array (
    'form' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'integer',
      'attributes' => 'unsigned',
      'null' => false,
      'default' => 0,
    ),
    'field' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'integer',
      'attributes' => 'unsigned',
      'null' => false,
      'default' => 0,
    ),
    'thread' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'integer',
      'attributes' => 'unsigned',
      'null' => false,
      'default' => 0,
    ),
    'createdby' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'integer',
      'attributes' => 'unsigned',
      'null' => false,
      'default' => 0,
    ),
    'integer' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'integer',
      'attributes' => 'unsigned',
      'null' => false,
      'default' => 0,
    ),
  ),
  'aggregates' => 
  array (
    'Form' => 
    array (
      'class' => 'VoteForm',
      'local' => 'form',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
    'Field' => 
    array (
      'class' => 'VoteFormField',
      'local' => 'field',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
    'Thread' => 
    array (
      'class' => 'VoteFormThread',
      'local' => 'thread',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
  ),
);

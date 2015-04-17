<?php
$xpdo_meta_map['VoteFormField']= array (
  'package' => 'voteforms',
  'version' => '1.1',
  'table' => 'voteforms_fields',
  'extends' => 'xPDOSimpleObject',
  'fields' => 
  array (
    'form' => 0,
    'name' => '',
    'type' => 'integer',
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
    'name' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '100',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'type' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '100',
      'phptype' => 'string',
      'null' => false,
      'default' => 'integer',
    ),
  ),
  'composites' => 
  array (
    'Records' => 
    array (
      'class' => 'VoteFormRecord',
      'local' => 'id',
      'foreign' => 'field',
      'cardinality' => 'many',
      'owner' => 'local',
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
  ),
);

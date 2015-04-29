<?php
$xpdo_meta_map['VoteFormThread']= array (
  'package' => 'voteforms',
  'version' => '1.1',
  'table' => 'voteforms_threads',
  'extends' => 'xPDOSimpleObject',
  'fields' => 
  array (
    'resource' => 0,
    'form' => 0,
    'name' => '',
    'rating' => 0,
    'users_count' => 0,
  ),
  'fieldMeta' => 
  array (
    'resource' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'integer',
      'attributes' => 'unsigned',
      'null' => false,
      'default' => 0,
    ),
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
      'precision' => '255',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'rating' => 
    array (
      'dbtype' => 'float',
      'phptype' => 'float',
      'attributes' => 'unsigned',
      'null' => false,
      'default' => 0,
    ),
    'users_count' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'integer',
      'attributes' => 'unsigned',
      'null' => false,
      'default' => 0,
    ),
  ),
  'composites' => 
  array (
    'Records' => 
    array (
      'class' => 'VoteFormRecord',
      'local' => 'id',
      'foreign' => 'thread',
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

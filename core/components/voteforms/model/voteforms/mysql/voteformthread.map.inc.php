<?php
$xpdo_meta_map['VoteFormThread']= array (
  'package' => 'voteforms',
  'version' => '1.1',
  'table' => 'voteforms_threads',
  'extends' => 'xPDOSimpleObject',
  'fields' => 
  array (
    'form' => 0,
    'name' => '',
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
      'precision' => '255',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
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

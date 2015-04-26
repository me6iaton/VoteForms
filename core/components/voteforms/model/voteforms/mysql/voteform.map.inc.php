<?php
$xpdo_meta_map['VoteForm']= array (
  'package' => 'voteforms',
  'version' => '1.1',
  'table' => 'voteforms',
  'extends' => 'xPDOSimpleObject',
  'fields' => 
  array (
    'name' => '',
    'description' => '',
    'active' => 1,
    'rating_max' => 5,
  ),
  'fieldMeta' => 
  array (
    'name' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '100',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'description' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'text',
      'null' => true,
      'default' => '',
    ),
    'active' => 
    array (
      'dbtype' => 'tinyint',
      'precision' => '1',
      'phptype' => 'boolean',
      'null' => true,
      'default' => 1,
    ),
    'rating_max' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'default' => 5,
    ),
  ),
  'composites' => 
  array (
    'Fields' => 
    array (
      'class' => 'VoteFormField',
      'local' => 'id',
      'foreign' => 'form',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
    'Threads' => 
    array (
      'class' => 'VoteFormThread',
      'local' => 'id',
      'foreign' => 'form',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
    'Records' => 
    array (
      'class' => 'VoteFormRecord',
      'local' => 'id',
      'foreign' => 'form',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
  ),
);

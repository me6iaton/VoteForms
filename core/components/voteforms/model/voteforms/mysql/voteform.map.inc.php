<?php
$xpdo_meta_map['VoteForm']= array (
  'package' => 'voteforms',
  'version' => '1.1',
  'table' => 'voteforms',
  'extends' => 'xPDOSimpleObject',
  'fields' => 
  array (
    'name' => '',
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

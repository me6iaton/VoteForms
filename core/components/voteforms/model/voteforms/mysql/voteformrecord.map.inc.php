<?php
$xpdo_meta_map['VoteFormRecord']= array (
  'package' => 'voteforms',
  'version' => '1.1',
  'table' => 'voteforms_records',
  'extends' => 'xPDOObject',
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
      'index' => 'pk',
    ),
    'field' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'integer',
      'attributes' => 'unsigned',
      'null' => false,
      'default' => 0,
      'index' => 'pk',
    ),
    'thread' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'integer',
      'attributes' => 'unsigned',
      'null' => false,
      'default' => 0,
      'index' => 'pk',
    ),
    'createdby' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'phptype' => 'integer',
      'attributes' => 'unsigned',
      'null' => false,
      'default' => 0,
      'index' => 'pk',
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
  'indexes' => 
  array (
    'unique_key' => 
    array (
      'alias' => 'unique_key',
      'primary' => true,
      'unique' => true,
      'type' => 'BTREE',
      'columns' => 
      array (
        'form' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
        'field' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
        'thread' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
        'createdby' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
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

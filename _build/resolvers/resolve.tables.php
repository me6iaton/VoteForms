<?php

if ($object->xpdo) {
  /** @var modX $modx */
  $modx =& $object->xpdo;

  switch ($options[xPDOTransport::PACKAGE_ACTION]) {
    case xPDOTransport::ACTION_INSTALL:
      $modelPath = $modx->getOption('voteforms_core_path', null, $modx->getOption('core_path') . 'components/voteforms/') . 'model/';
      $modx->addPackage('voteforms', $modelPath);

      $manager = $modx->getManager();
      $objects = array(
        'VoteForm',
        'VoteFormField',
        'VoteFormThread',
        'VoteFormRecord',
      );
      foreach ($objects as $tmp) {
        $manager->createObjectContainer($tmp);
      }
      break;

    case xPDOTransport::ACTION_UPGRADE:
      break;

    case xPDOTransport::ACTION_UNINSTALL:
      break;
  }
}
return true;

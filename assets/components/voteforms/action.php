<?php

if (empty($_REQUEST['action'])) {
  die('Access denied');
} else {
  $action = $_REQUEST['action'];
}

define('MODX_API_MODE', true);
$productionIndex = dirname(dirname(dirname(dirname(__FILE__)))) . '/index.php';
$developmentIndex = dirname(dirname(dirname(dirname(dirname(__FILE__))))) . '/index.php';
if (file_exists($productionIndex)) {
  require_once $productionIndex;
} else {
  require_once $developmentIndex;
}

$modx->getService('error', 'error.modError');
$modx->getRequest();
$modx->setLogLevel(modX::LOG_LEVEL_ERROR);
$modx->setLogTarget('FILE');
$modx->error->message = null;

// Switch context
$ctx = !empty($_REQUEST['ctx']) ? $_REQUEST['ctx'] : 'web';
if ($ctx != 'web') {
  $modx->switchContext($ctx);
}

/** @var VoteForms $VoteForms */
define('MODX_ACTION_MODE', true);
$VoteForms = $modx->getService('voteforms', 'VoteForms', $modx->getOption('voteforms_core_path', null, $modx->getOption('core_path') . 'components/voteforms/') . 'model/voteforms/');
$modx->lexicon->load('voteforms:default');

if (!$VoteForms->authenticated) {
  die($modx->lexicon('voteforms_form_err_no_auth'));
}

$response = $VoteForms->runProcessor($action, $_REQUEST);
if (is_array($response)) {
  $response = $modx->toJSON($response);
}

@session_write_close();
exit($response);
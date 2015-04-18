<?php
$productionConfig = dirname(dirname(dirname(dirname(__FILE__)))) . '/config.core.php';
$developmentConfig = dirname(dirname(dirname(dirname(dirname(__FILE__))))) . '/config.core.php';
if (file_exists($productionConfig)) {
  /** @noinspection PhpIncludeInspection */
  require_once $productionConfig;
} else {
  /** @noinspection PhpIncludeInspection */
  require_once $developmentConfig;
}
/** @noinspection PhpIncludeInspection */
require_once MODX_CORE_PATH . 'config/' . MODX_CONFIG_KEY . '.inc.php';
/** @noinspection PhpIncludeInspection */
require_once MODX_CONNECTORS_PATH . 'index.php';
/** @var VoteForms $VoteForms */
$VoteForms = $modx->getService('voteforms', 'VoteForms', $modx->getOption('voteforms_core_path', null, $modx->getOption('core_path') . 'components/voteforms/') . 'model/voteforms/');
$modx->lexicon->load('voteforms:default');

// handle request
$corePath = $modx->getOption('voteforms_core_path', null, $modx->getOption('core_path') . 'components/voteforms/');
$path = $modx->getOption('processorsPath', $VoteForms->config, $corePath . 'processors/');
$modx->request->handleRequest(array(
  'processors_path' => $path,
  'location' => '',
));
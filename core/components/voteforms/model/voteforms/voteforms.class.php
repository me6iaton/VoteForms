<?php

/**
 * The base class for VoteForms.
 */
class VoteForms
{
  /* @var modX $modx */
  public $modx;
  /* @var pdoTools $pdoTools */
  public $pdoTools;
  public $initialized = array();
  public $authenticated = false;


  /**
   * @param modX $modx
   * @param array $config
   */
  function __construct(modX &$modx, array $config = array())
  {
    $this->modx =& $modx;

    $corePath = $this->modx->getOption('voteforms_core_path', $config, $this->modx->getOption('core_path') . 'components/voteforms/');
    $assetsUrl = $this->modx->getOption('voteforms_assets_url', $config, $this->modx->getOption('assets_url') . 'components/voteforms/');
    $actionUrl = $this->modx->getOption('voteforms_action_url', $config, $assetsUrl . 'action.php');
    $connectorUrl = $assetsUrl . 'connector.php';

    $this->config = array_merge(array(
      'assetsUrl' => $assetsUrl,
      'cssUrl' => $assetsUrl . 'css/',
      'jsUrl' => $assetsUrl . 'js/',
      'imagesUrl' => $assetsUrl . 'images/',

      'connectorUrl' => $connectorUrl,
      'actionUrl' => $actionUrl,

      'corePath' => $corePath,
      'modelPath' => $corePath . 'model/',
      'chunksPath' => $corePath . 'elements/chunks/',
      'templatesPath' => $corePath . 'elements/templates/',
      'chunkSuffix' => '.chunk.tpl',
      'snippetsPath' => $corePath . 'elements/snippets/',
      'processorsPath' => $corePath . 'processors/'
    ), $config);

    $this->modx->addPackage('voteforms', $this->config['modelPath']);
    $this->modx->lexicon->load('voteforms:default');

    $this->authenticated = $this->modx->user->isAuthenticated($this->modx->context->get('key'));
  }

  /**
   * Initializes component into different contexts.
   *
   * @param string $ctx The context to load. Defaults to web.
   * @param array $scriptProperties
   *
   * @return boolean
   */
  public function initialize($ctx = 'web', $scriptProperties = array())
  {
    $this->config = array_merge($this->config, $scriptProperties);
    if (!$this->pdoTools) {
      $this->loadPdoTools();
    }
    $this->pdoTools->setConfig($this->config);
    $this->config['ctx'] = $ctx;
    if (empty($this->initialized[$ctx])) {
      $config_js = array(
        'ctx' => $ctx,
        'actionUrl' => $this->config['actionUrl'],
        'vendorUrl' => $this->config['assetsUrl']. 'vendor/',
        'jsUrl' => $this->config['jsUrl'] . 'web/',
        'cssUrl' => $this->config['cssUrl'] . 'web/',
      );
      $this->modx->regClientStartupScript(
        '<script type="text/javascript">' .
        'VoteFormsConfig=' . $this->modx->toJSON($config_js) .
        '</script>', true);
      $this->initialized[$ctx] = true;
    }
    if (!defined('MODX_API_MODE') || !MODX_API_MODE) {
      $config = $this->makePlaceholders($this->config);
      $css = !empty($this->config['frontend_css'])
        ? $this->config['frontend_css']
        : $this->modx->getOption('voteforms_frontend_css');
      if (!empty($css) && preg_match('/\.css/i', $css)) {
        $this->modx->regClientCSS(str_replace($config['pl'], $config['vl'], $css));
      }
      $js = !empty($this->config['frontend_js'])
        ? $this->config['frontend_js']
        : $this->modx->getOption('voteforms_frontend_js');
      if (!empty($js) && preg_match('/\.js/i', $js)) {
        $this->modx->regClientScript(str_replace($config['pl'], $config['vl'], $js));
      }
    }
    return true;
  }

  /**
   * Loads an instance of pdoTools
   *
   * @return boolean
   */
  public function loadPdoTools()
  {
    if (!is_object($this->pdoTools) || !($this->pdoTools instanceof pdoTools)) {
      /** @var pdoFetch $pdoFetch */
      $fqn = $this->modx->getOption('pdoFetch.class', null, 'pdotools.pdofetch', true);
      if ($pdoClass = $this->modx->loadClass($fqn, '', false, true)) {
        $this->pdoTools = new $pdoClass($this->modx, $this->config);
      } elseif ($pdoClass = $this->modx->loadClass($fqn, MODX_CORE_PATH . 'components/pdotools/model/', false, true)) {
        $this->pdoTools = new $pdoClass($this->modx, $this->config);
      } else {
        $this->modx->log(modX::LOG_LEVEL_ERROR, 'Could not load pdoFetch from "MODX_CORE_PATH/components/pdotools/model/".');
      }
    }
    return !empty($this->pdoTools) && $this->pdoTools instanceof pdoTools;
  }

  /**
   * Method for transform array to placeholders
   *
   * @var array $array With keys and values
   * @var string $prefix Prefix for array keys
   *
   * @return array $array Two nested arrays with placeholders and values
   */
  public function makePlaceholders(array $array = array(), $prefix = '')
  {
    if (!$this->pdoTools) {
      $this->loadPdoTools();
    }

    return $this->pdoTools->makePlaceholders($array, $prefix);
  }

  /**
   * Shorthand for the call of processor
   *
   * @access public
   *
   * @param string $action Path to processor
   * @param array $data Data to be transmitted to the processor
   *
   * @return mixed The result of the processor
   */
  public function runProcessor($action = '', $data = array())
  {
    if (empty($action)) {
      return false;
    }
    if ($this->modx->context->get('key') !== 'mgr') {
      $action = 'web/' . $action;
    }
    /* @var modProcessorResponse $response */
    $response = $this->modx->runProcessor($action, $data, array('processors_path' => $this->config['processorsPath']));
    return $this->prepareResponse($response);
  }

  /**
   * This method returns prepared response
   *
   * @param mixed $response
   *
   * @return array|string $response
   */
  public function prepareResponse($response) {
    if ($response instanceof modProcessorResponse) {
      $output = $response->getResponse();
      $output['message'] = $this->lexicon($output['message']);
      if ($response->isError()) {
        header('HTTP/1.1 400 Bad Request');
      }
    } else {
      header('HTTP/1.1 400 Bad Request');
      $output = array(
        'success' => false,
        'message' => $response
          ? $this->lexicon($response)
          : $this->lexicon('voteforms_err_unknown')
      );
    }
    return $output;
  }

  /**
   * return lexicon message if possibly
   *
   * @param string $message
   * @return string $message
   */
  private function lexicon ($message){
    if ($messageLexicon = $this->modx->lexicon($message)) {
      return $messageLexicon;
    }else{
      return $message;
    }

  }
}
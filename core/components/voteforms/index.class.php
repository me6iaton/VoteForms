<?php

/**
 * Class VoteFormsMainController
 */
abstract class VoteFormsMainController extends modExtraManagerController {
  /** @var VoteForms $VoteForms */
  public $VoteForms;


  /**
   * @return void
   */
  public function initialize() {
    $corePath = $this->modx->getOption('voteforms_core_path', null, $this->modx->getOption('core_path') . 'components/voteforms/');
    require_once $corePath . 'model/voteforms/voteforms.class.php';

    $this->VoteForms = new VoteForms($this->modx);
    $this->addCss($this->VoteForms->config['cssUrl'] . 'mgr/main.css');
    $this->addJavascript($this->VoteForms->config['jsUrl'] . 'mgr/voteforms.js');
    $this->addHtml('
    <script type="text/javascript">
      VoteForms.config = ' . $this->modx->toJSON($this->VoteForms->config) . ';
      VoteForms.config.connector_url = "' . $this->VoteForms->config['connectorUrl'] . '";
    </script>
    ');

    parent::initialize();
  }


  /**
   * @return array
   */
  public function getLanguageTopics() {
    return array('voteforms:default');
  }


  /**
   * @return bool
   */
  public function checkPermissions() {
    return true;
  }
}


/**
 * Class IndexManagerController
 */
class IndexManagerController extends VoteFormsMainController {

  /**
   * @return string
   */
  public static function getDefaultController() {
    return 'home';
  }
}
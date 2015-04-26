<?php

/**
 * The home manager controller for VoteForms.
 *
 */
class VoteFormsHomeManagerController extends VoteFormsMainController {
  /* @var VoteForms $VoteForms */
  public $VoteForms;


  /**
   * @param array $scriptProperties
   */
  public function process(array $scriptProperties = array()) {
  }


  /**
   * @return null|string
   */
  public function getPageTitle() {
    return $this->modx->lexicon('voteforms');
  }


  /**
   * @return void
   */
  public function loadCustomCssJs() {
    $this->addCss($this->VoteForms->config['cssUrl'] . 'mgr/main.css');
    $this->addCss($this->VoteForms->config['cssUrl'] . 'mgr/bootstrap.buttons.css');
    $this->addJavascript($this->VoteForms->config['jsUrl'] . 'mgr/misc/utils.js');
    $this->addJavascript($this->VoteForms->config['jsUrl'] . 'mgr/widgets/forms.grid.js');
    $this->addJavascript($this->VoteForms->config['jsUrl'] . 'mgr/widgets/forms.windows.js');
    $this->addJavascript($this->VoteForms->config['jsUrl'] . 'mgr/widgets/threads.grid.js');
    $this->addJavascript($this->VoteForms->config['jsUrl'] . 'mgr/widgets/fields.grid.js');
    $this->addJavascript($this->VoteForms->config['jsUrl'] . 'mgr/widgets/fields.windows.js');
    $this->addJavascript($this->VoteForms->config['jsUrl'] . 'mgr/widgets/home.panel.js');
    $this->addJavascript($this->VoteForms->config['jsUrl'] . 'mgr/sections/home.js');
    $this->addHtml('<script type="text/javascript">
    Ext.onReady(function() {
      MODx.load({ xtype: "voteforms-page-home"});
    });
    </script>');
  }


  /**
   * @return string
   */
  public function getTemplateFile() {
    return $this->VoteForms->config['templatesPath'] . 'home.tpl';
  }
}
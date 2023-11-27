<?php

/**
 * The home manager controller for easyRedirects.
 *
 */
class easyRedirectsHomeManagerController extends modExtraManagerController
{
    /** @var easyRedirects $easyRedirects */
    public $easyRedirects;


    /**
     *
     */
    public function initialize()
    {
        $this->easyRedirects = $this->modx->getService('easyRedirects', 'easyRedirects', MODX_CORE_PATH . 'components/easyredirects/model/');
        parent::initialize();
    }


    /**
     * @return array
     */
    public function getLanguageTopics()
    {
        return ['easyredirects:default'];
    }


    /**
     * @return bool
     */
    public function checkPermissions()
    {
        return true;
    }


    /**
     * @return null|string
     */
    public function getPageTitle()
    {
        return $this->modx->lexicon('easyredirects');
    }


    /**
     * @return void
     */
    public function loadCustomCssJs()
    {
        $this->addCss($this->easyRedirects->config['cssUrl'] . 'mgr/main.css');
        $this->addJavascript($this->easyRedirects->config['jsUrl'] . 'mgr/easyredirects.js');
        $this->addJavascript($this->easyRedirects->config['jsUrl'] . 'mgr/misc/utils.js');
        $this->addJavascript($this->easyRedirects->config['jsUrl'] . 'mgr/misc/combo.js');
        $this->addJavascript($this->easyRedirects->config['jsUrl'] . 'mgr/widgets/redirects.grid.js');
        $this->addJavascript($this->easyRedirects->config['jsUrl'] . 'mgr/widgets/redirects.windows.js');
        $this->addJavascript($this->easyRedirects->config['jsUrl'] . 'mgr/widgets/import.window.js');
        $this->addJavascript($this->easyRedirects->config['jsUrl'] . 'mgr/widgets/home.panel.js');
        $this->addJavascript($this->easyRedirects->config['jsUrl'] . 'mgr/sections/home.js');

        $this->addHtml('<script type="text/javascript">
        easyRedirects.config = ' . json_encode($this->easyRedirects->config) . ';
        easyRedirects.config.connector_url = "' . $this->easyRedirects->config['connectorUrl'] . '";
        Ext.onReady(function() {
            MODx.load({ xtype: "easyredirects-page-home"});
        });
        </script>');
    }


    /**
     * @return string
     */
    public function getTemplateFile()
    {
        $this->content .= '<div id="easyredirects-panel-home-div"></div>';

        return '';
    }
}
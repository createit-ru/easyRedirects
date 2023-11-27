<?php
if (file_exists(dirname(dirname(dirname(dirname(__FILE__)))) . '/config.core.php')) {
    /** @noinspection PhpIncludeInspection */
    require_once dirname(dirname(dirname(dirname(__FILE__)))) . '/config.core.php';
} else {
    require_once dirname(dirname(dirname(dirname(dirname(__FILE__))))) . '/config.core.php';
}
/** @noinspection PhpIncludeInspection */
require_once MODX_CORE_PATH . 'config/' . MODX_CONFIG_KEY . '.inc.php';
/** @noinspection PhpIncludeInspection */
require_once MODX_CONNECTORS_PATH . 'index.php';
/** @var easyRedirects $easyRedirects */
$easyRedirects = $modx->getService('easyRedirects', 'easyRedirects', MODX_CORE_PATH . 'components/easyredirects/model/');
$modx->lexicon->load('easyredirects:default');

// handle request
$corePath = $modx->getOption('easyredirects_core_path', null, $modx->getOption('core_path') . 'components/easyredirects/');
$path = $modx->getOption('processorsPath', $easyRedirects->config, $corePath . 'processors/');
$modx->getRequest();

/** @var modConnectorRequest $request */
$request = $modx->request;
$request->handleRequest([
    'processors_path' => $path,
    'location' => '',
]);
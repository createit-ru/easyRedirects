<?php

/**
 * @package easyRedirects
 *
 * @var modX $modx
 * @var array $scriptProperties
 * @var modResource $resource
 * @var string $mode
 */

/** @var easyRedirects $easyRedirects */
$corePath = $modx->getOption('easyredirects_core_path', $scriptProperties, $modx->getOption('core_path') . 'components/easyredirects/');
$easyRedirects = $modx->getService('easyRedirects', 'easyRedirects', $corePath . 'model/', $scriptProperties);
if (!($easyRedirects instanceof easyRedirects)) {
    return;
}

switch ($modx->event->name) {
    case 'OnPageNotFound':
        /* handle redirects */
        $search = rawurldecode($_SERVER['REQUEST_URI']);
        $baseUrl = trim($modx->getOption('base_url', null, MODX_BASE_URL));
        if (!empty($baseUrl) && $baseUrl != '/' && $baseUrl != '/' . $modx->context->get('key') . '/') {
            $search = str_replace($baseUrl, '', $search);
        }

        $search = ltrim($search, '/');
        if (!empty($search)) {
            $quotedSearch = $modx->quote($search);

            /** @var xPDOQuery $criteria */
            $criteria = $modx->newQuery('easyRedirect');
            $criteria->where([
                'url' => $search,
                [
                    'context_key:=' => $modx->context->get('key'),
                    'OR:context_key:IS' => null,
                    'OR:context_key:=' => ''
                ],
                'active' => true
            ]);

            /** @var easyRedirect $redirect */
            $redirect = $modx->getObject('easyRedirect', $criteria);

            if (empty($redirect) || !is_object($redirect)) {
                // Внимание.
                // Вторая строчка в where закомментирована по той причине, что:
                // для страницы catalog/category/product подойдет правило "category".
                // хотя по логике оно бы должно работать только на корневую страницу.
                $criteria2 = $modx->newQuery('easyRedirect');
                $criteria2->where([
                    "("
                    . "`easyRedirect`.`url` = " . $quotedSearch
                    //. " OR " . $quotedSearch . " REGEXP `easyRedirect`.`url`"
                    . " OR " . $quotedSearch . " REGEXP CONCAT('^', `easyRedirect`.`url`, '$')"
                    . ")",
                    [
                        'context_key:=' => $modx->context->get('key'),
                        'OR:context_key:IS' => null,
                        'OR:context_key:=' => ''
                    ],
                    'active' => true
                ]);
                $redirect = $modx->getObject('easyRedirect', $criteria2);
            }

            if (!empty($redirect) && is_object($redirect)) {
                /** @var modContext $context */
                $context = $redirect->getOne('Context');
                if (empty($context) || !($context instanceof modContext)) {
                    $context = $modx->context;
                }

                $target = $redirect->get('target');

                if ($target != $modx->resourceIdentifier && $target != $search) {
                    if (strpos($target, '$') !== false) {
                        $url = $redirect->get('url');
                        $target = preg_replace('/' . $url . '/', $target, $search);
                    }
                    if (!strpos($target, '://')) {
                        $target = rtrim($context->getOption('site_url'), '/') . '/' . (($target == '/') ? '' : ltrim($target, '/'));
                    }
                    $modx->log(modX::LOG_LEVEL_INFO, '[easyRedirects] Redirecting request for ' . $search . ' to ' . $target);

                    $redirect->registerTrigger();

                    $options = array('responseCode' => 'HTTP/1.1 301 Moved Permanently');
                    // Comment this line for debug
                    $modx->sendRedirect($target, $options);
                }
            }
        }

        break;

    case 'OnDocFormRender':
        $track = (bool)$modx->getOption('easyredirects_track', null, 0);

        if ($mode == 'upd' && $track) {
            $_SESSION['modx_resource_uri'] = $resource->get('uri');
        }

        break;

    case 'OnDocFormSave':
        /* if uri has changed, add to redirects */
        $track = (bool)$modx->getOption('easyredirects_track', null, 0);

        if ($mode == 'upd' && $track && !empty($_SESSION['modx_resource_uri'])) {
            $contextKey = $resource->get('context_key');
            $newUri = $resource->get('uri');


            $oldUri = $_SESSION['modx_resource_uri'];
            if ($oldUri != $newUri) {
                /* uri changed */
                $redirect = $modx->getObject('easyRedirect', array(
                    'url' => $oldUri,
                    'context_key' => $contextKey,
                    // 'active' => true
                ));
                if (empty($redirect)) {
                    /* no record for old uri */
                    $redirect = $modx->newObject('easyRedirect');
                    $redirect->fromArray(array(
                        'url' => $oldUri,
                        // TODO: по идее лучше сохранять id ресурса, а не uri
                        'target' => $resource->get('uri'),
                        'context_key' => $contextKey,
                        'active' => true,
                    ));

                    if ($redirect->save() == false) {
                        return $modx->error->failure('[easyRedirects] ' . $modx->lexicon('easyredirects_redirect_err_save'));
                    }
                }
            }

            $_SESSION['modx_resource_uri'] = $newUri;
        }

        break;
}

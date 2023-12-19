<?php

class easyRedirectsImportCSVProcessor extends modProcessor {

    public function getLanguageTopics() {
        return array('easyredirects:default');
    }

    public function process() {

        $csvData = $this->getProperty('csv');
        $allData = array();

        $label = trim($this->getProperty('label'));

        // raw data csv
        if(!empty($csvData)) {
            $data = str_getcsv($csvData, "\n");
            foreach($data as $line) {
                $line = str_getcsv($line, ';', '');
                $allData[] = $line;
            }
        }

        $total = count($allData);
        $succeed = $failed = 0;
        foreach($allData as $line) {
            if(count($line) < 2) {
                $failed++;
                continue;
            }

            // figure out context
            $url = $this->cleanupUrl($line[0]);
            $target = $this->cleanupTarget($line[1]);
            $context = ((isset($line[2]) && !empty($line[2])) ? trim($line[2]) : '');
            $responseCode = ((isset($line[3]) && !empty($line[3])) ? trim($line[3]) : '');
            if(!in_array($responseCode, ['301', '302', '307', '308'])) {
                $responseCode = '301';
            }

            // validate url
            if($url === false || $target === false) {
                $failed++;
                $this->modx->log(modX::LOG_LEVEL_ERROR, '[easyRedirects] Failed to import redirect: "'.$url.'" > "'.$target.'"');
                continue;
            }

            // create entry
            $saved = $this->createRedirect($url, $target, $context, $responseCode, $label);
            if(!$saved) {
                $failed++;
                $this->modx->log(modX::LOG_LEVEL_ERROR, '[easyRedirects] Failed to create redirect: "'.$url.'" > "'.$target.'"');
                continue;
            }

            $succeed++;
        }

        if(empty($total)) {
            return $this->failure($this->modx->lexicon('easyredirects_import_failed'));
        }
        // always failure!
        return $this->failure($this->modx->lexicon('easyredirects_import_success', array(
            'total' => $total,
            'succeed' => $succeed,
            'failed' => $failed,
        )));
    }

    private function createRedirect($url, $target, $contextKey, $responseCode, $label) {
        if(empty($url) || empty($target)) {
            return false;
        }

        /** @var easyRedirect $redirect */
        $redirect = $this->modx->newObject('easyRedirect');
        $redirect->fromArray(array(
            'url' => ltrim($url, '/'),
            'target' => ltrim($target, '/'),
            'context_key' => $contextKey,
            'response_code' => $responseCode,
            'label' => $label,
            'createdon' => date('Y-m-d H:i:s'),
            'createdby' => $this->modx->user->isAuthenticated($this->modx->context->key) ? $this->modx->user->id : 0,
        ));

        return $redirect->save();
    }

    /**
     * @param $url
     * @return false|string
     */
    private function cleanupUrl($url)
    {
        $url = trim($url);
        if(strpos($url, '^') === 0) {
            // это регулярка, нужно убрать слеш после ^ и убедиться, что она заканчивается на $
            $url = ltrim($url, '^');
            $url = ltrim($url, '/');
            $url = rtrim($url, '$');
            if(empty($url)) {
                return false;
            }
            $url = '^' . $url . '$';
        } else {
            // это не регулярка, просто уберем начальный слеш, если он есть
            $url = ltrim($url, '/');
        }

        if(empty($url)) {
            return false;
        }
        return $url;
    }

    /**
     * @param $target
     * @return string
     */
    private function cleanupTarget($target)
    {
        $target = trim($target);
        $target = ltrim($target, '/');

        return $target;
    }

}

return 'easyRedirectsImportCSVProcessor';
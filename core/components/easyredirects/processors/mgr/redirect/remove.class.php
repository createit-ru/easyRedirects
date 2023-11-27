<?php

class easyRedirectsRedirectRemoveProcessor extends modObjectProcessor
{
    public $objectType = 'easyRedirect';
    public $classKey = 'easyRedirect';
    public $languageTopics = ['easyredirects'];
    //public $permission = 'remove';


    /**
     * @return array|string
     */
    public function process()
    {
        if (!$this->checkPermissions()) {
            return $this->failure($this->modx->lexicon('access_denied'));
        }

        $ids = $this->modx->fromJSON($this->getProperty('ids'));
        if (empty($ids)) {
            return $this->failure($this->modx->lexicon('easyredirects_redirect_err_ns'));
        }

        foreach ($ids as $id) {
            /** @var easyRedirectsItem $object */
            if (!$object = $this->modx->getObject($this->classKey, $id)) {
                return $this->failure($this->modx->lexicon('easyredirects_redirect_err_nf'));
            }

            $object->remove();
        }

        return $this->success();
    }

}

return 'easyRedirectsRedirectRemoveProcessor';
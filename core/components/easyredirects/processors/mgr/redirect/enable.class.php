<?php

class easyRedirectsRedirectEnableProcessor extends modObjectProcessor
{
    public $objectType = 'easyRedirect';
    public $classKey = 'easyRedirect';
    public $languageTopics = ['easyredirects'];
    //public $permission = 'save';


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

            $now = date('Y-m-d H:i:s');
            $object->set('editedon', $now);
            $object->set('editedby', $this->modx->user->isAuthenticated($this->modx->context->key) ? $this->modx->user->id : 0);

            $object->set('active', true);
            $object->save();
        }

        return $this->success();
    }

}

return 'easyRedirectsRedirectEnableProcessor';

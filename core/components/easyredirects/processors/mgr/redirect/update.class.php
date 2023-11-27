<?php

class easyRedirectsRedirectUpdateProcessor extends modObjectUpdateProcessor
{
    public $objectType = 'easyRedirect';
    public $classKey = 'easyRedirect';
    public $languageTopics = ['easyredirects'];
    //public $permission = 'save';


    /**
     * We doing special check of permission
     * because of our objects is not an instances of modAccessibleObject
     *
     * @return bool|string
     */
    public function beforeSave()
    {
        if (!$this->checkPermissions()) {
            return $this->modx->lexicon('access_denied');
        }

        return true;
    }


    /**
     * @return bool
     */
    public function beforeSet()
    {
        $id = (int)$this->getProperty('id');
        $url = trim($this->getProperty('url'));
        if (empty($id)) {
            return $this->modx->lexicon('easyredirects_redirect_err_ns');
        }

        if (empty($url)) {
            $this->modx->error->addField('name', $this->modx->lexicon('easyredirects_redirect_err_url'));
        } elseif ($this->modx->getCount($this->classKey, ['url' => $url, 'id:!=' => $id])) {
            $this->modx->error->addField('url', $this->modx->lexicon('easyredirects_redirect_err_ae'));
        }

        $now = date('Y-m-d H:i:s');
        $this->setProperties(array(
            'editedon' => $now,
            'editedby' => $this->modx->user->isAuthenticated($this->modx->context->key) ? $this->modx->user->id : 0,
        ));

        return parent::beforeSet();
    }
}

return 'easyRedirectsRedirectUpdateProcessor';

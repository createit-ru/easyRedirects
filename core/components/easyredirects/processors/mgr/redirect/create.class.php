<?php

class easyRedirectsRedirectCreateProcessor extends modObjectCreateProcessor
{
    public $objectType = 'easyRedirect';
    public $classKey = 'easyRedirect';
    public $languageTopics = ['easyredirects'];
    //public $permission = 'create';

    /**
     * @return bool
     */
    public function beforeSet()
    {
        $url = trim($this->getProperty('url'));
        if (empty($url)) {
            $this->modx->error->addField('url', $this->modx->lexicon('easyredirects_redirect_err_url'));
        } elseif ($this->modx->getCount($this->classKey, ['url' => $url])) {
            // TODO: здесь нужна другая проверка, могут быть 2 одинаковых url в разных контекстах, нужно добавить context_key
            $this->modx->error->addField('url', $this->modx->lexicon('easyredirects_redirect_err_ae'));
        }

        $now = date('Y-m-d H:i:s');
        $this->setProperties(array(
            'createdon' => $now,
            'createdby' => $this->modx->user->isAuthenticated($this->modx->context->key) ? $this->modx->user->id : 0,
            'editedon' => null,
            'editedby' => 0
        ));

        return parent::beforeSet();
    }

}

return 'easyRedirectsRedirectCreateProcessor';
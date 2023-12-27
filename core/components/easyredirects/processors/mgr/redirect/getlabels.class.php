<?php

class easyRedirectsGetLabelsProcessor extends modObjectGetListProcessor  {
    public $classKey = 'easyRedirect';
    public $defaultSortField = 'label';
    public $defaultSortDirection  = 'ASC';

    public function getLanguageTopics() {
        return array('easyredirects:default');
    }

    /** {@inheritDoc} */
    public function prepareQueryBeforeCount(xPDOQuery $c) {
        $c->select($this->modx->getSelectColumns('easyRedirect', '', '', ['id', 'label']));
        $c->groupby('label', 'ASC');
        $c->where([
            'label:!=' => '',
            'label:IS NOT NULL'
        ]);
        if ($query = $this->getProperty('query')) {
            $c->where([
                'label:LIKE' => "%$query%"
            ]);
        }
        return $c;
    }

}

return 'easyRedirectsGetLabelsProcessor';
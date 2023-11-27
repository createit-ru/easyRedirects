<?php

class easyRedirectsContextGetListProcessor extends modObjectGetListProcessor
{
    public $classKey = 'modContext';
    public $languageTopics = array('easyredirects:default');
    public $defaultSortField = 'key';
    public $defaultSortDirection = 'ASC';
    public $objectType = 'modContext';

    public function prepareQueryBeforeCount(xPDOQuery $c): xPDOQuery
    {
        $c->where([
            'key:!=' => 'mgr'
        ]);

        $query = $this->getProperty('query');
        if (!empty($query)) {
            $c->andCondition(array(
                'key:LIKE' => '%' . $query . '%'
            ));
        }
        return $c;
    }
}

return 'easyRedirectsContextGetListProcessor';
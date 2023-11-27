<?php

class easyRedirectsResourceGetListProcessor extends modObjectGetListProcessor
{
    public $classKey = 'modResource';
    public $languageTopics = array('easyredirect:default');
    public $defaultSortField = 'pagetitle';
    public $defaultSortDirection = 'ASC';
    public $objectType = 'modResource';

    public function prepareQueryBeforeCount(xPDOQuery $c): xPDOQuery
    {

        $contextKey = $this->getProperty('context_key');
        if (!empty($contextKey)) {
            $c->andCondition(array(
                'context_key' => $contextKey,
            ));
        }

        $query = $this->getProperty('query');
        if (!empty($query)) {
            if(is_numeric($query)) {
                $c->andCondition(array(
                    'id' => $query,
                    'OR:pagetitle:LIKE' => '%' . $query . '%',
                ));
            } else {
                $c->andCondition(array(
                    'pagetitle:LIKE' => '%' . $query . '%',
                ));
            }

        }
        return $c;
    }

    public function prepareRow(xPDOObject $object): array
    {
        $result = $object->toArray();
        $result['pagetitle'] .= ' (' . $object->get('context_key') . ', ' . $object->get('id') . ')';

        // figure out if resource is a site-start resource
        $siteStart = $this->modx->getObject('modSystemSetting', array(
            'key' => 'site_start',
            'value' => $object->get('id'),
        ));
        if (empty($siteStart)) {

            $siteStart = $this->modx->getObject('modContextSetting', array(
                'key' => 'site_start',
                'value' => $object->get('id'),
                'context_key' => $object->get('context_key'),
            ));
        }

        $result['site_start'] = !empty($siteStart) && is_object($siteStart);

        return $result;
    }
}

return 'easyRedirectsResourceGetListProcessor';
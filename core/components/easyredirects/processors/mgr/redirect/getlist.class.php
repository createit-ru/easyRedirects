<?php

class easyRedirectsRedirectGetListProcessor extends modObjectGetListProcessor
{
    public $objectType = 'easyRedirect';
    public $classKey = 'easyRedirect';
    public $defaultSortField = 'url';
    public $defaultSortDirection = 'DESC';
    //public $permission = 'list';


    /**
     * We do a special check of permissions
     * because our objects is not an instances of modAccessibleObject
     *
     * @return boolean|string
     */
    public function beforeQuery()
    {
        if (!$this->checkPermissions()) {
            return $this->modx->lexicon('access_denied');
        }

        return true;
    }


    /**
     * @param xPDOQuery $c
     *
     * @return xPDOQuery
     */
    public function prepareQueryBeforeCount(xPDOQuery $c): xPDOQuery
    {
        $query = trim($this->getProperty('query'));
        if ($query) {
            $c->andCondition([
                'url:LIKE' => "%" . $query . "%",
                'OR:target:LIKE' => "%" . $query . "%",
            ]);
        }

        $contextKey = $this->getProperty('context_key');
        if (!empty($contextKey)) {
            $c->andCondition(array(
                'context_key:LIKE' => '%' . $contextKey . '%',
            ));
        }

        return $c;
    }


    /**
     * @param xPDOObject $object
     *
     * @return array
     */
    public function prepareRow(xPDOObject $object): array
    {
        $array = $object->toArray();
        $array['actions'] = [];

        // Edit
        $array['actions'][] = [
            'cls' => '',
            'icon' => 'icon icon-edit',
            'title' => $this->modx->lexicon('easyredirects_redirect_update'),
            //'multiple' => $this->modx->lexicon('easyredirects_redirects_update'),
            'action' => 'updateRedirect',
            'button' => true,
            'menu' => true,
        ];

        if (!$array['active']) {
            $array['actions'][] = [
                'cls' => '',
                'icon' => 'icon icon-power-off action-green',
                'title' => $this->modx->lexicon('easyredirects_redirect_enable'),
                'multiple' => $this->modx->lexicon('easyredirects_redirects_enable'),
                'action' => 'enableRedirect',
                'button' => true,
                'menu' => true,
            ];
        } else {
            $array['actions'][] = [
                'cls' => '',
                'icon' => 'icon icon-power-off action-gray',
                'title' => $this->modx->lexicon('easyredirects_redirect_disable'),
                'multiple' => $this->modx->lexicon('easyredirects_redirects_disable'),
                'action' => 'disableRedirect',
                'button' => true,
                'menu' => true,
            ];
        }

        // Remove
        $array['actions'][] = [
            'cls' => '',
            'icon' => 'icon icon-trash-o action-red',
            'title' => $this->modx->lexicon('easyredirects_redirect_remove'),
            'multiple' => $this->modx->lexicon('easyredirects_redirects_remove'),
            'action' => 'removeRedirect',
            'button' => true,
            'menu' => true,
        ];

        return $array;
    }

}

return 'easyRedirectsRedirectGetListProcessor';
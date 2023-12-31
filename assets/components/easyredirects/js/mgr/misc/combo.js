easyRedirects.combo.Search = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        xtype: 'twintrigger',
        ctCls: 'x-field-search',
        allowBlank: true,
        msgTarget: 'under',
        emptyText: _('search'),
        name: 'query',
        triggerAction: 'all',
        clearBtnCls: 'x-field-search-clear',
        searchBtnCls: 'x-field-search-go',
        onTrigger1Click: this._triggerSearch,
        onTrigger2Click: this._triggerClear,
    });
    easyRedirects.combo.Search.superclass.constructor.call(this, config);
    this.on('render', function () {
        this.getEl().addKeyListener(Ext.EventObject.ENTER, function () {
            this._triggerSearch();
        }, this);
    });
    this.addEvents('clear', 'search');
};
Ext.extend(easyRedirects.combo.Search, Ext.form.TwinTriggerField, {

    initComponent: function () {
        Ext.form.TwinTriggerField.superclass.initComponent.call(this);
        this.triggerConfig = {
            tag: 'span',
            cls: 'x-field-search-btns',
            cn: [
                {tag: 'div', cls: 'x-form-trigger ' + this.searchBtnCls},
                {tag: 'div', cls: 'x-form-trigger ' + this.clearBtnCls}
            ]
        };
    },

    _triggerSearch: function () {
        this.fireEvent('search', this);
    },

    _triggerClear: function () {
        this.fireEvent('clear', this);
    },

});
Ext.reg('easyredirects-combo-search', easyRedirects.combo.Search);
Ext.reg('easyredirects-field-search', easyRedirects.combo.Search);


easyRedirects.combo.Label = function(config) {
    config = config || {};
    Ext.applyIf(config,{
        id: 'easyredirects-combo-label',
        fieldLabel: _('easyredirects_redirect_label'),
        description: '',
        fields: ['label'],
        valueField: 'label',
        displayField: 'label',
        name: 'parent-cmb',
        hiddenName: 'parent-cmp',
        allowBlank: true,
        url: easyRedirects.config.connector_url,
        baseParams: {
            action: 'mgr/redirect/getlabels',
            combo: 1,
            id: config.value
            //,limit: 0
        },
        pageSize: 20,
        //,typeAhead: true
        editable: true
    });
    easyRedirects.combo.Label.superclass.constructor.call(this,config);
};
Ext.extend(easyRedirects.combo.Label, MODx.combo.ComboBox);
Ext.reg('easyredirects-combo-label', easyRedirects.combo.Label);


/* context list combobox */
easyRedirects.combo.Contexts = function(config) {
    config = config || {};
    Ext.applyIf(config, {
        name: 'context_key',
        hiddenName: 'context_key',
        displayField: 'key',
        valueField: 'key',
        fields: ['key'],
        forceSelection: true,
        typeAhead: true,
        editable: true,
        allowBlank: true,
        autocomplete: true,
        url: easyRedirects.config.connector_url,
        baseParams: {
            action: 'mgr/context/getlist',
            combo: true
        }
    });

    easyRedirects.combo.Contexts.superclass.constructor.call(this, config);
};

Ext.extend(easyRedirects.combo.Contexts, MODx.combo.ComboBox);
Ext.reg('easyredirects-combo-contexts', easyRedirects.combo.Contexts);


/* Response code list combobox */
easyRedirects.combo.ResponseCode = function(config) {
    config = config || {};
    Ext.applyIf(config, {
        name: 'response_code',
        hiddenName: 'response_code',
        displayField: 'title',
        valueField: 'code',
        fields: ['code', 'title'],
        forceSelection: true,
        typeAhead: true,
        editable: false,
        allowBlank: true,
        autocomplete: true,
        store: new Ext.data.ArrayStore({
            id: 0
            ,fields: ['code','title']
            ,data: [
                ['301','301, Moved Permanently'],
                ['302','302, Found'],
                ['307','307, Temporary Redirect'],
                ['308','308, Permanent Redirect']
            ]
        })
        ,mode: 'local'
    });

    easyRedirects.combo.Contexts.superclass.constructor.call(this, config);
};

Ext.extend(easyRedirects.combo.ResponseCode, MODx.combo.ComboBox);
Ext.reg('easyredirects-combo-response-code', easyRedirects.combo.ResponseCode);
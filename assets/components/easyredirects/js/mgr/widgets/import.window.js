easyRedirects.window.Import = function (config) {
    config = config || {};
    if (!config.id) {
        config.id = 'easyredirects-redirect-window-import';
    }
    Ext.applyIf(config, {
        title: _('easyredirects_redirect_import'),
        width: 750,
        autoHeight: true,
        url: easyRedirects.config.connector_url,
        action: 'mgr/exchange/import',
        fields: this.getFields(config),
        keys: [{
            key: Ext.EventObject.ENTER, shift: true, fn: function () {
                this.submit()
            }, scope: this
        }],
        failure: function(form, response) {
            Ext.MessageBox.alert(response.result.message);
        },
        success: function(form, response) {
            Ext.MessageBox.alert(response.result.message);
        }
    });
    easyRedirects.window.Import.superclass.constructor.call(this, config);
}

Ext.extend(easyRedirects.window.Import, MODx.Window, {
    getFields: function (config) {
        return [
            {
                html: 'Вставьте CSV данные в текстовую область.<br />' +
                    'Формат: "url;цель;контекст;код_редиректа". Контекст и код редиректа не являются обязательными полями.<br />' +
                    'Также URL-ссылки должны быть относительными (без адреса сайта или слеша в начале.',
                cls: 'panel-desc',
                style: 'margin-top: 15px;'
            },
            {
                xtype: 'textarea',
                fieldLabel: _('easyredirects_import_csv'),
                height: 380,
                name: 'csv',
                id: config.id + '-csv',
                anchor: '99%',
                allowBlank: true,
            },
            {
                xtype: 'textfield',
                fieldLabel: _('easyredirects_import_label'),
                name: 'label',
                id: config.id + '-label',
                anchor: '99%',
                allowBlank: true,
            }
        ];
    }
});
Ext.reg('easyredirects-redirect-window-import', easyRedirects.window.Import);
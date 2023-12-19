// Базовое окно (от него наследуются окна создания и редактирования)
easyRedirects.window.RedirectWindow = function (config) {
    config = config || {};
    if (!config.id) {
        config.id = 'easyredirects-redirect-window';
    }
    easyRedirects.window.RedirectWindow.superclass.constructor.call(this, config);
}

Ext.extend(easyRedirects.window.RedirectWindow, MODx.Window, {
    getRedirectWindowFields: function (config, record) {
        let result = [];
        if (record) {
            result.push({xtype: 'hidden', name: 'id', id: config.id + '-id'});
        }
        /*result.push( {
            html: _('esf_auto_label_warning'),
            cls: 'panel-desc'
        });*/
        result.push({
            layout: 'column',
            border: false,
            items: [{
                columnWidth: .6,
                border: false,
                layout: 'form',
                items: [
                    {
                        xtype: 'textfield',
                        fieldLabel: _('easyredirects_redirect_url'),
                        name: 'url',
                        id: config.id + '-url',
                        anchor: '99%',
                        allowBlank: false,
                    }, {
                        xtype: 'textfield',
                        fieldLabel: _('easyredirects_redirect_target'),
                        name: 'target',
                        id: config.id + '-target',
                        anchor: '99%',
                        allowBlank: false,
                    }, {
                        layout: 'column',
                        border: false,
                        items: [
                            {
                                columnWidth: .5,
                                border: false,
                                layout: 'form',
                                items: [
                                    {
                                        xtype: 'easyredirects-combo-contexts',
                                        fieldLabel: _('easyredirects_redirect_context_key'),
                                        name: 'context_key',
                                        id: config.id + '-context_key',
                                        anchor: '99%',
                                        allowBlank: true,
                                    }, {
                                        html: _('easyredirects_redirect_context_key_desc'),
                                        style: 'padding-top:4px;color:#696969;'
                                    }, {
                                        xtype: 'xcheckbox',
                                        boxLabel: _('easyredirects_redirect_active'),
                                        name: 'active',
                                        id: config.id + '-active',
                                        checked: true,
                                    }
                                ]
                            },
                            {
                                columnWidth: .5,
                                border: false,
                                layout: 'form',
                                items: [
                                    {
                                        xtype: 'easyredirects-combo-response-code',
                                        fieldLabel: _('easyredirects_redirect_response_code'),
                                        name: 'response_code',
                                        id: config.id + '-response_code',
                                        anchor: '99%',
                                        allowBlank: true
                                    }, {
                                        html: _('easyredirects_redirect_response_code_desc'),
                                        style: 'padding-top:4px;color:#696969;'
                                    }, {
                                        xtype: 'textfield',
                                        fieldLabel: _('easyredirects_redirect_label'),
                                        name: 'label',
                                        id: config.id + '-label',
                                        anchor: '99%',
                                        allowBlank: true
                                    }/*, {
                                        html: _('easyredirects_redirect_label_desc'),
                                        style: 'padding-top:4px;color:#696969;'
                                    }*/
                                ]
                            }
                        ]
                    }
                ]
            }, {
                columnWidth: .4,
                border: false,
                layout: 'form',
                items: [{
                    html:
                        '<strong>Url:</strong><br />' +
                        'Адрес страницы или регулярное выражение. Символы начала ^, конца строки $ и экранирование слеша - обязательны.<br /><br />' +
                        '<strong>Цель:</strong><br />' +
                        'Адрес для редиректа. Если в вашем Url было регулярное выражение, вы можете ссылаться на групповые подстановки $1, $2 и т.д..<br /><br />' +
                        '<strong>Например:</strong><br />' +
                        'url: ^catalog\/category-(.*)$.<br />' +
                        'цель: shop/category/$1 <br /><br />' +
                        '<em>Примечание:</em> слеш в начале адресов использовать не нужно!',
                    cls: 'panel-desc',
                    style: 'margin-top:15px;font-size: 0.9em;line-height: 1.3;'
                }]
            }]
        });

        return result;
    }
});

easyRedirects.window.CreateRedirect = function (config) {
    config = config || {};
    if (!config.id) {
        config.id = 'easyredirects-redirect-window-create';
    }
    Ext.applyIf(config, {
        title: _('easyredirects_redirect_create'),
        width: 750,
        autoHeight: true,
        url: easyRedirects.config.connector_url,
        action: 'mgr/redirect/create',
        fields: this.getRedirectWindowFields(config, null),
        keys: [{
            key: Ext.EventObject.ENTER, shift: true, fn: function () {
                this.submit()
            }, scope: this
        }]
    });
    easyRedirects.window.CreateRedirect.superclass.constructor.call(this, config);
};
Ext.extend(easyRedirects.window.CreateRedirect, easyRedirects.window.RedirectWindow, {});
Ext.reg('easyredirects-redirect-window-create', easyRedirects.window.CreateRedirect);


easyRedirects.window.UpdateRedirect = function (config) {
    config = config || {};
    if (!config.id) {
        config.id = 'easyredirects-redirect-window-update';
    }
    Ext.applyIf(config, {
        title: _('easyredirects_redirect_update'),
        width: 750,
        autoHeight: true,
        url: easyRedirects.config.connector_url,
        action: 'mgr/redirect/update',
        fields: this.getRedirectWindowFields(config, config.record),
        keys: [{
            key: Ext.EventObject.ENTER, shift: true, fn: function () {
                this.submit()
            }, scope: this
        }]
    });
    easyRedirects.window.UpdateRedirect.superclass.constructor.call(this, config);
};
Ext.extend(easyRedirects.window.UpdateRedirect, easyRedirects.window.RedirectWindow, {});
Ext.reg('easyredirects-redirect-window-update', easyRedirects.window.UpdateRedirect);
easyRedirects.panel.Home = function (config) {
    config = config || {};
    Ext.apply(config, {
        baseCls: 'modx-formpanel',
        layout: 'anchor',
        /*
         stateful: true,
         stateId: 'easyredirects-panel-home',
         stateEvents: ['tabchange'],
         getState:function() {return {activeTab:this.items.indexOf(this.getActiveTab())};},
         */
        hideMode: 'offsets',
        items: [{
            xtype: 'modx-header',
            html: _('easyredirects'),
            style: "margin-top:1.25rem;"
        }, {
            xtype: 'modx-tabs',
            defaults: {border: false, autoHeight: true},
            border: true,
            hideMode: 'offsets',
            items: [{
                title: _('easyredirects_redirects'),
                layout: 'anchor',
                items: [{
                    html: _('easyredirects_intro_msg'),
                    cls: 'panel-desc',
                }, {
                    xtype: 'easyredirects-grid-redirects',
                    cls: 'main-wrapper',
                }]
            }]
        }]
    });
    easyRedirects.panel.Home.superclass.constructor.call(this, config);
};
Ext.extend(easyRedirects.panel.Home, MODx.Panel);
Ext.reg('easyredirects-panel-home', easyRedirects.panel.Home);

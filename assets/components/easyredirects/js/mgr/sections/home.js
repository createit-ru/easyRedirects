easyRedirects.page.Home = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        components: [{
            xtype: 'easyredirects-panel-home',
            renderTo: 'easyredirects-panel-home-div'
        }]
    });
    easyRedirects.page.Home.superclass.constructor.call(this, config);
};
Ext.extend(easyRedirects.page.Home, MODx.Component);
Ext.reg('easyredirects-page-home', easyRedirects.page.Home);
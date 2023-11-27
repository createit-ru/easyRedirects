var easyRedirects = function (config) {
    config = config || {};
    easyRedirects.superclass.constructor.call(this, config);
};
Ext.extend(easyRedirects, Ext.Component, {
    page: {}, window: {}, grid: {}, tree: {}, panel: {}, combo: {}, config: {}, view: {}, utils: {}
});
Ext.reg('easyredirects', easyRedirects);

easyRedirects = new easyRedirects();
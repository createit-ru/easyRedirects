easyRedirects.grid.Redirects = function (config) {
    config = config || {};
    if (!config.id) {
        config.id = 'easyredirects-grid-redirects';
    }
    Ext.applyIf(config, {
        url: easyRedirects.config.connector_url,
        fields: this.getFields(config),
        columns: this.getColumns(config),
        tbar: this.getTopBar(config),
        sm: new Ext.grid.CheckboxSelectionModel(),
        baseParams: {
            action: 'mgr/redirect/getlist'
        },
        listeners: {
            rowDblClick: function (grid, rowIndex, e) {
                var row = grid.store.getAt(rowIndex);
                this.updateRedirect(grid, e, row);
            }
        },
        viewConfig: {
            forceFit: true,
            enableRowBody: true,
            autoFill: true,
            showPreview: true,
            scrollOffset: 0,
            getRowClass: function (rec) {
                return !rec.data.active
                    ? 'easyredirects-grid-row-disabled'
                    : '';
            }
        },
        paging: true,
        remoteSort: true,
        autoHeight: true,
    });
    easyRedirects.grid.Redirects.superclass.constructor.call(this, config);

    // Clear selection on grid refresh
    this.store.on('load', function () {
        if (this._getSelectedIds().length) {
            this.getSelectionModel().clearSelections();
        }
    }, this);
};
Ext.extend(easyRedirects.grid.Redirects, MODx.grid.Grid, {
    windows: {},

    getMenu: function (grid, rowIndex) {
        var ids = this._getSelectedIds();

        var row = grid.getStore().getAt(rowIndex);
        var menu = easyRedirects.utils.getMenu(row.data['actions'], this, ids);

        this.addContextMenuItem(menu);
    },

    importRedirects: function (btn, e) {
        let w = MODx.load({
            xtype: 'easyredirects-redirect-window-import',
            id: Ext.id(),
            listeners: {
                success: {
                    fn: function () {
                        this.refresh();
                    }, scope: this
                }
            }
        });
        w.reset();
        w.setValues({});
        w.show(e.target);
    },

    createRedirect: function (btn, e) {
        let w = MODx.load({
            xtype: 'easyredirects-redirect-window-create',
            id: Ext.id(),
            listeners: {
                success: {
                    fn: function () {
                        this.refresh();
                    }, scope: this
                }
            }
        });
        w.reset();
        w.setValues({active: true, response_code: '301'});
        w.show(e.target);
    },

    updateRedirect: function (btn, e, row) {
        if (typeof (row) != 'undefined') {
            this.menu.record = row.data;
        } else if (!this.menu.record) {
            return false;
        }
        var id = this.menu.record.id;

        MODx.Ajax.request({
            url: this.config.url,
            params: {
                action: 'mgr/redirect/get',
                id: id
            },
            listeners: {
                success: {
                    fn: function (r) {
                        var w = MODx.load({
                            xtype: 'easyredirects-redirect-window-update',
                            id: Ext.id(),
                            record: r,
                            listeners: {
                                success: {
                                    fn: function () {
                                        this.refresh();
                                    }, scope: this
                                }
                            }
                        });
                        w.reset();
                        w.setValues(r.object);
                        w.show(e.target);
                    }, scope: this
                }
            }
        });
    },

    removeRedirect: function () {
        var ids = this._getSelectedIds();
        if (!ids.length) {
            return false;
        }
        MODx.msg.confirm({
            title: ids.length > 1
                ? _('easyredirects_redirects_remove')
                : _('easyredirects_redirect_remove'),
            text: ids.length > 1
                ? _('easyredirects_redirects_remove_confirm')
                : _('easyredirects_redirect_remove_confirm'),
            url: this.config.url,
            params: {
                action: 'mgr/redirect/remove',
                ids: Ext.util.JSON.encode(ids),
            },
            listeners: {
                success: {
                    fn: function () {
                        this.refresh();
                    }, scope: this
                }
            }
        });
        return true;
    },

    disableRedirect: function () {
        var ids = this._getSelectedIds();
        if (!ids.length) {
            return false;
        }
        MODx.Ajax.request({
            url: this.config.url,
            params: {
                action: 'mgr/redirect/disable',
                ids: Ext.util.JSON.encode(ids),
            },
            listeners: {
                success: {
                    fn: function () {
                        this.refresh();
                    }, scope: this
                }
            }
        })
    },

    enableRedirect: function () {
        var ids = this._getSelectedIds();
        if (!ids.length) {
            return false;
        }
        MODx.Ajax.request({
            url: this.config.url,
            params: {
                action: 'mgr/redirect/enable',
                ids: Ext.util.JSON.encode(ids),
            },
            listeners: {
                success: {
                    fn: function () {
                        this.refresh();
                    }, scope: this
                }
            }
        })
    },

    getFields: function () {
        return ['id', 'url', 'target', 'context_key', 'response_code', 'triggered', 'triggered_last', 'active', 'label', 'actions'];
    },

    getColumns: function () {
        return [{
            header: _('easyredirects_redirect_id'),
            dataIndex: 'id',
            sortable: true,
            width: 60
        }, {
            header: _('easyredirects_redirect_url'),
            dataIndex: 'url',
            sortable: true,
            width: 250,
        }, {
            header: _('easyredirects_redirect_target'),
            dataIndex: 'target',
            sortable: true,
            width: 250,
        }, {
            header: _('easyredirects_redirect_context_key'),
            dataIndex: 'context_key',
            sortable: true,
            width: 80,
        }, {
            header: _('easyredirects_redirect_response_code'),
            dataIndex: 'response_code',
            sortable: true,
            width: 80,
            hidden: false
        }, {
            header: _('easyredirects_redirect_triggered'),
            dataIndex: 'triggered',
            sortable: true,
            width: 90,
        }, {
            header: _('easyredirects_redirect_triggered_last'),
            dataIndex: 'triggered_last',
            sortable: true,
            width: 120,
        }, {
            header: _('easyredirects_redirect_active'),
            dataIndex: 'active',
            renderer: easyRedirects.utils.renderBoolean,
            sortable: true,
            width: 80,
        }, {
            header: _('easyredirects_redirect_label'),
            dataIndex: 'label',
            sortable: true,
            width: 120,
            hidden: false
        }, {
            header: _('easyredirects_grid_actions'),
            dataIndex: 'actions',
            renderer: easyRedirects.utils.renderActions,
            sortable: false,
            width: 120,
            id: 'actions'
        }];
    },

    getTopBar: function (config) {
        return [
            {
                text: _('easyredirects_redirect_create'),
                cls: 'primary-button',
                handler: this.createRedirect,
                scope: this
            }, {
                text: '<i class="icon icon-upload"></i> ' + _('easyredirects_redirect_import'),
                cls: '',
                handler: this.importRedirects,
                scope: this
            }, '->', {
                xtype: 'easyredirects-combo-label',
                name: 'filter_label',
                id: config.id + '-filter-label',
                width: 200,
                emptyText: _('easyredirects_redirect_label'),
                listeners: {
                    select: {
                        fn: function (field) {
                            this._doLabelSearch(field);
                        },
                        scope: this
                    }
                }
            }, {
                xtype: 'easyredirects-field-search',
                id: config.id + '-search',
                width: 250,
                listeners: {
                    search: {
                        fn: function (field) {
                            this._doSearch(field);
                        }, scope: this
                    },
                    clear: {
                        fn: function (field) {
                            this._clearSearch();
                        }, scope: this
                    },
                }
            }
        ];
    },

    onClick: function (e) {
        var elem = e.getTarget();
        if (elem.nodeName === 'BUTTON') {
            var row = this.getSelectionModel().getSelected();
            if (typeof (row) != 'undefined') {
                var action = elem.getAttribute('action');
                if (action === 'showMenu') {
                    var ri = this.getStore().find('id', row.id);
                    return this._showMenu(this, ri, e);
                } else if (typeof this[action] === 'function') {
                    this.menu.record = row.data;
                    return this[action](this, e);
                }
            }
        }
        return this.processEvent('click', e);
    },

    _getSelectedIds: function () {
        let ids = [];
        let selected = this.getSelectionModel().getSelections();

        for (let i in selected) {
            if (!selected.hasOwnProperty(i)) {
                continue;
            }
            ids.push(selected[i]['id']);
        }

        return ids;
    },

    _doLabelSearch: function (tf) {
        this.getStore().baseParams.label = tf.getValue();
        this.getBottomToolbar().changePage(1);
    },

    _doSearch: function (tf) {
        this.getStore().baseParams.query = tf.getValue();
        this.getBottomToolbar().changePage(1);
    },

    _clearSearch: function () {
        Ext.getCmp(this.config.id + '-filter-label').setValue('');
        Ext.getCmp(this.config.id + '-search').setValue('');

        this.getStore().baseParams.label = '';
        this.getStore().baseParams.query = '';

        this.getBottomToolbar().changePage(1);
    },
});
Ext.reg('easyredirects-grid-redirects', easyRedirects.grid.Redirects);

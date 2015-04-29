VoteForms.grid.Threads = function (config) {
    config = config || {};
    if (!config.id) {
        config.id = 'voteforms-grid-threads';
    }
    Ext.applyIf(config, {
        url: VoteForms.config.connector_url,
        fields: this.getFields(config),
        columns: this.getColumns(config),
        tbar: this.getTopBar(config),
        baseParams: {
            action: 'mgr/thread/getlist'
        },
        listeners: {
            rowDblClick: function (grid, rowIndex, e) {
                var row = grid.store.getAt(rowIndex);
                this.updateResource(grid, e, row);
            }
        },
        viewConfig: {
            forceFit: true,
            enableRowBody: true,
            autoFill: true,
            showPreview: true,
            scrollOffset: 0,
            getRowClass: function (rec, ri, p) {
                return !rec.data.active
                    ? 'voteforms-row-disabled'
                    : '';
            }
        },
        paging: true,
        remoteSort: true,
        autoHeight: true,
    });
    VoteForms.grid.Threads.superclass.constructor.call(this, config);

    // Clear selection on grid refresh
    this.store.on('load', function () {
        if (this._getSelectedIds().length) {
            this.getSelectionModel().clearSelections();
        }
    }, this);
};
Ext.extend(VoteForms.grid.Threads, MODx.grid.Grid, {
    windows: {},

    getMenu: function (grid, rowIndex) {
        var ids = this._getSelectedIds();

        var row = grid.getStore().getAt(rowIndex);
        var menu = VoteForms.utils.getMenu(row.data['actions'], this, ids);

        this.addContextMenuItem(menu);
    },

    updateResource: function (btn, e, row) {
        if (typeof(row) != 'undefined') {
            this.menu.record = row.data;
        }
        else if (!this.menu.record) {
            return false;
        }
        var resourceId = this.menu.record.resource;
        MODx.loadPage('resource/update&id='+resourceId);
    },

    getFields: function (config) {
        return ['id', 'resource', 'form', 'name', 'users_count', 'rating', 'actions'];
    },

    getColumns: function (config) {
        return [{
            header: _('voteforms_item_id'),
            dataIndex: 'id',
            sortable: true,
            width: 50
        }, {
            header: _('voteforms_item_resource'),
            dataIndex: 'resource',
            sortable: true,
            width: 70,
        }, {
            header: _('voteforms_item_form'),
            dataIndex: 'form',
            sortable: true,
            width: 70,
        }, {
            header: _('voteforms_item_name'),
            dataIndex: 'name',
            sortable: true,
            width: 200,
        }, {
            header: _('voteforms_item_users_count'),
            dataIndex: 'users_count',
            sortable: true,
            width: 70,
        }, {
            header: _('voteforms_item_rating'),
            dataIndex: 'rating',
            sortable: true,
            width: 70,
        }, {
            header: _('voteforms_grid_actions'),
            dataIndex: 'actions',
            renderer: VoteForms.utils.renderActions,
            sortable: false,
            width: 70,
            id: 'actions'
        }];
    },

    getTopBar: function (config) {
        return [ '->',
        {
            xtype: 'textfield',
            name: 'query',
            width: 200,
            id: config.id + '-search-field',
            emptyText: _('voteforms_grid_search'),
            listeners: {
                render: {
                    fn: function (tf) {
                        tf.getEl().addKeyListener(Ext.EventObject.ENTER, function () {
                            this._doSearch(tf);
                        }, this);
                    }, scope: this
                }
            }
        }, {
            xtype: 'button',
            id: config.id + '-search-clear',
            text: '<i class="icon icon-times"></i>',
            listeners: {
                click: {fn: this._clearSearch, scope: this}
            }
        }];
    },

    onClick: function (e) {
        var elem = e.getTarget();
        if (elem.nodeName == 'BUTTON') {
            var row = this.getSelectionModel().getSelected();
            if (typeof(row) != 'undefined') {
                var action = elem.getAttribute('action');
                if (action == 'showMenu') {
                    var ri = this.getStore().find('id', row.id);
                    return this._showMenu(this, ri, e);
                }
                else if (typeof this[action] === 'function') {
                    this.menu.record = row.data;
                    return this[action](this, e);
                }
            }
        }
        return this.processEvent('click', e);
    },

    _getSelectedIds: function () {
        var ids = [];
        var selected = this.getSelectionModel().getSelections();

        for (var i in selected) {
            if (!selected.hasOwnProperty(i)) {
                continue;
            }
            ids.push(selected[i]['id']);
        }

        return ids;
    },

    _doSearch: function (tf, nv, ov) {
        this.getStore().baseParams.query = tf.getValue();
        this.getBottomToolbar().changePage(1);
        this.refresh();
    },

    _clearSearch: function (btn, e) {
        this.getStore().baseParams.query = '';
        Ext.getCmp(this.config.id + '-search-field').setValue('');
        this.getBottomToolbar().changePage(1);
        this.refresh();
    }
});
Ext.reg('voteforms-grid-threads', VoteForms.grid.Threads);
VoteForms.grid.RatingsFields = function (config) {
    config = config || {};
    if (!config.id) {
        config.id = 'voteforms-grid-ratingsFields-form' + config.form;
    }
    if (!config.pageSize) {
        config.pageSize = 100;
    }
    Ext.applyIf(config, {
        url: VoteForms.config.connector_url,
        fields: config.fields,
        columns: config.columns,
        baseParams: {
            action: 'mgr/ratingfield/getlist',
            form: config.form,
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
        forceFit: true
    });
    VoteForms.grid.RatingsFields.superclass.constructor.call(this, config);

    // Clear selection on grid refresh
    this.store.on('load', function () {
        if (this._getSelectedIds().length) {
            this.getSelectionModel().clearSelections();
        }
    }, this);
};
Ext.extend(VoteForms.grid.RatingsFields, MODx.grid.Grid, {
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

    removeThreads: function (act, btn, e) {
        var ids = this._getSelectedIds();
        if (!ids.length) {
            return false;
        }
        MODx.msg.confirm({
            title: _('voteforms_clean_remove'),
            text: _('voteforms_clean_confirm'),
            url: this.config.url,
            params: {
                action: 'mgr/thread/remove',
                ids: Ext.util.JSON.encode(ids),
            },
            listeners: {
                success: {
                    fn: function (r) {
                        this.refresh();
                    }, scope: this
                }
            }
        });
        return true;
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

});
Ext.reg('voteforms-grid-ratingsFields', VoteForms.grid.RatingsFields);
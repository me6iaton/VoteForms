VoteForms.window.CreateField = function (config) {
    config = config || {};
    if (!config.id) {
        config.id = 'voteforms-field-window-create';
    }
    Ext.applyIf(config, {
        title: _('voteforms_item_create'),
        width: 550,
        autoHeight: true,
        url: VoteForms.config.connector_url,
        baseParams: {
            action: 'mgr/field/create',
            form: config.form,
            type: this._getType(config)
        },
        fields: this.getFields(config),
        keys: [{
            key: Ext.EventObject.ENTER, shift: true, fn: function () {
                this.submit()
            }, scope: this
        }]
    });
    VoteForms.window.CreateField.superclass.constructor.call(this, config);
};
Ext.extend(VoteForms.window.CreateField, MODx.Window, {

    getFields: function (config) {
        return [{
            xtype: 'textfield',
            fieldLabel: _('voteforms_item_name'),
            name: 'name',
            id: config.id + '-name',
            anchor: '99%',
            allowBlank: false,
        }, {
            xtype: 'numberfield',
            fieldLabel: _('voteforms_item_index'),
            name: 'index',
            id: config.id + '-index',
            anchor: '99%',
            allowBlank: false,
            originalValue: this._getIndex(config)
        }, {
            xtype: 'textarea',
            fieldLabel: _('voteforms_item_description'),
            name: 'description',
            id: config.id + '-description',
            height: 150,
            anchor: '99%'
        }];
    },

    _getIndex: function (config) {
        var grid = Ext.getCmp('voteforms-grid-fields-form' + config.form);
        var index = grid.getStore().getCount() * 100;
        return index
    },

    _getType: function (config) {
        return 'integer'
    }
});
Ext.reg('voteforms-field-window-create', VoteForms.window.CreateField);


VoteForms.window.UpdateField = function (config) {
    config = config || {};
    if (!config.id) {
        config.id = 'voteforms-field-window-update';
    }
    Ext.applyIf(config, {
        title: _('voteforms_item_update'),
        width: 550,
        autoHeight: true,
        url: VoteForms.config.connector_url,
        action: 'mgr/field/update',
        fields: this.getFields(config),
        keys: [{
            key: Ext.EventObject.ENTER, shift: true, fn: function () {
                this.submit()
            }, scope: this
        }]
    });
    VoteForms.window.UpdateField.superclass.constructor.call(this, config);
};
Ext.extend(VoteForms.window.UpdateField, MODx.Window, {

    getFields: function (config) {
        return [{
            xtype: 'hidden',
            name: 'id',
            id: config.id + '-id',
        }, {
            xtype: 'hidden',
            name: 'form',
            id: config.id + '-form',
        }, {
            xtype: 'hidden',
            name: 'type',
            id: config.id + '-type',
        }, {
            xtype: 'textfield',
            fieldLabel: _('voteforms_item_name'),
            name: 'name',
            id: config.id + '-name',
            anchor: '99%',
            allowBlank: false,
        }, {
            xtype: 'numberfield',
            fieldLabel: _('voteforms_item_index'),
            name: 'index',
            id: config.id + '-index',
            anchor: '99%',
            allowBlank: false,
        }, {
            xtype: 'textarea',
            fieldLabel: _('voteforms_item_description'),
            name: 'description',
            id: config.id + '-description',
            anchor: '99%',
            height: 150,
        }];
    },

});
Ext.reg('voteforms-field-window-update', VoteForms.window.UpdateField);
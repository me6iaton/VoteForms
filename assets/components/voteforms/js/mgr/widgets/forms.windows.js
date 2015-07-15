VoteForms.window.CreateItem = function (config) {
    config = config || {};
    if (!config.id) {
        config.id = 'voteforms-form-window-create';
    }
    Ext.applyIf(config, {
        title: _('voteforms_item_create'),
        width: 550,
        autoHeight: true,
        url: VoteForms.config.connector_url,
        action: 'mgr/form/create',
        fields: this.getFields(config),
        keys: [{
            key: Ext.EventObject.ENTER, shift: true, fn: function () {
                this.submit()
            }, scope: this
        }]
    });
    VoteForms.window.CreateItem.superclass.constructor.call(this, config);
};
Ext.extend(VoteForms.window.CreateItem, MODx.Window, {

    getFields: function (config) {
        return [{
            xtype: 'textfield',
            fieldLabel: _('voteforms_item_name'),
            name: 'name',
            id: config.id + '-name',
            anchor: '99%',
            allowBlank: false,
        }, {
            xtype: 'textarea',
            fieldLabel: _('voteforms_item_description'),
            name: 'description',
            id: config.id + '-description',
            height: 150,
            anchor: '99%'
        }, {
            xtype: 'textarea',
            fieldLabel: _('voteforms_form_properties'),
            name: 'properties',
            id: config.id + '-properties',
            anchor: '99%',
            height: 150,
        }, {
            xtype: 'combo',
            fieldLabel: _('voteforms_form_ranking'),
            name: 'ranking',
            hiddenName: 'ranking',
            id: config.id + '-ranking',
            store: new Ext.data.SimpleStore({
                fields: ['type', 'disp'],
                data: [['AVG', _('voteforms_form_ranking_avg')],
                    ['WILSON', _('voteforms_form_ranking_wilson')],
                    ['SUM', _('voteforms_form_ranking_sum')]],
            }),
            triggerAction: 'all',
            mode: 'local',
            displayField: 'disp',
            valueField: 'type',
            editable: false,
            value: 'AVG',
            allowBlank: false,
            selectOnFocus: true
        }, {
            xtype: 'numberfield',
            fieldLabel: _('voteforms_form_rating_max'),
            name: 'rating_max',
            originalValue: 5,
            id: config.id + '-rating_max',
        }, {
            xtype: 'xcheckbox',
            boxLabel: _('voteforms_item_active'),
            name: 'active',
            id: config.id + '-active',
            checked: true,
        }];
    }

});
Ext.reg('voteforms-form-window-create', VoteForms.window.CreateItem);


VoteForms.window.UpdateItem = function (config) {
    config = config || {};
    if (!config.id) {
        config.id = 'voteforms-form-window-update';
    }
    Ext.applyIf(config, {
        title: _('voteforms_item_update'),
        width: 550,
        autoHeight: true,
        url: VoteForms.config.connector_url,
        action: 'mgr/form/update',
        fields: this.getFields(config),
        keys: [{
            key: Ext.EventObject.ENTER, shift: true, fn: function () {
                this.submit()
            }, scope: this
        }]
    });
    VoteForms.window.UpdateItem.superclass.constructor.call(this, config);
};
Ext.extend(VoteForms.window.UpdateItem, MODx.Window, {

    getFields: function (config) {
        return [{
            xtype: 'hidden',
            name: 'id',
            id: config.id + '-id',
        }, {
            xtype: 'textfield',
            fieldLabel: _('voteforms_item_name'),
            name: 'name',
            id: config.id + '-name',
            anchor: '99%',
            allowBlank: false,
        }, {
            xtype: 'textarea',
            fieldLabel: _('voteforms_item_description'),
            name: 'description',
            id: config.id + '-description',
            anchor: '99%',
            height: 150,
        }, {
            xtype: 'textarea',
            fieldLabel: _('voteforms_form_properties'),
            name: 'properties',
            id: config.id + '-properties',
            anchor: '99%',
            height: 150,
        }, {
            xtype: 'combo',
            fieldLabel: _('voteforms_form_ranking'),
            name: 'ranking',
            hiddenName: 'ranking',
            id: config.id + '-ranking',
            store: new Ext.data.SimpleStore({
                fields: ['type', 'disp'],
                data: [['AVG', _('voteforms_form_ranking_avg')],
                    ['WILSON', _('voteforms_form_ranking_wilson')],
                    ['SUM', _('voteforms_form_ranking_sum')]],
            }),
            triggerAction: 'all',
            mode: 'local',
            displayField: 'disp',
            valueField: 'type',
            editable: false,
            value: 'AVG',
            allowBlank: false,
            selectOnFocus: true,
            readOnly: true,
            cls: 'x-item-disabled',
        }, {
            xtype: 'numberfield',
            fieldLabel: _('voteforms_form_rating_max'),
            name: 'rating_max',
            id: config.id + '-rating_max',
            readOnly: true,
            cls: 'x-item-disabled',
        }, {
            xtype: 'xcheckbox',
            boxLabel: _('voteforms_item_active'),
            name: 'active',
            id: config.id + '-active',
        }];
    }

});
Ext.reg('voteforms-form-window-update', VoteForms.window.UpdateItem);
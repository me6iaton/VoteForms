VoteForms.panel.Home = function (config) {
    config = config || {};
    Ext.apply(config, {
        baseCls: 'modx-formpanel',
        layout: 'anchor',
        /*
         stateful: true,
         stateId: 'voteforms-panel-home',
         stateEvents: ['tabchange'],
         getState:function() {return {activeTab:this.items.indexOf(this.getActiveTab())};},
         */
        hideMode: 'offsets',
        items: [{
            html: '<h2>' + _('voteforms') + '</h2>',
            cls: '',
            style: {margin: '15px 0'}
        }, {
            xtype: 'modx-tabs',
            id: 'voteforms-panel-home-tabs',
            defaults: {border: false, autoHeight: true},
            border: true,
            hideMode: 'offsets',
            items: [{
                title: _('voteforms_threads'),
                layout: 'anchor',
                items: [{
                    xtype: 'voteforms-grid-threads',
                    cls: 'main-wrapper',
                }]
            },{
                title: _('voteforms_forms'),
                layout: 'anchor',
                items: [{
                    xtype: 'voteforms-grid-forms',
                    cls: 'main-wrapper',
                }, {
                    html: _('voteforms_intro_msg'),
                    cls: 'panel-desc',
                }]
            }],
            listeners: {
                added: function (){
                    var self = this;
                    Ext.Ajax.request({
                        url: VoteForms.config.connectorUrl,
                        params: {
                            action: 'mgr/field/getlist',
                        },
                        success: function (response, opts) {
                           var fields = Ext.decode(response.responseText).results;
                           var configs = makeConfigsForms(fields);
                           getForms(configs);
                        },
                        failure: function (response, opts) {
                            var responseError = Ext.decode(response.responseText);
                            console.error(responseError)
                        }
                    });
                    var getForms = function (configs) {
                        Ext.Ajax.request({
                            url: VoteForms.config.connectorUrl,
                            params: {
                                action: 'mgr/form/getlist',
                                dir: 'ASC'
                            },
                            success: function (response, opts) {
                                var forms = Ext.decode(response.responseText).results;
                                forms.forEach(function (form) {
                                    self.add({
                                        title: form.name,
                                        layout: 'anchor',
                                        items: [{
                                            xtype: 'voteforms-grid-ratingsFields',
                                            cls: 'main-wrapper',
                                            form: form.id,
                                            columns: configs[form.id].columns,
                                            fields: configs[form.id].fields,
                                        }],
                                        listeners: {}
                                    });
                                });
                            },
                            failure: function (response, opts) {
                                var responseError = Ext.decode(response.responseText);
                                console.error(responseError)
                            }
                        });
                    };
                    var makeConfigsForms = function (fields) {
                        var configs = [];
                        fields.forEach(function(field){
                            if (!configs[field.form]){
                                configs[field.form] = {};
                                configs[field.form].columns = [{
                                    header: _('voteforms_item_resource'),
                                    dataIndex: 'resource',
                                    sortable: true,
                                },{
                                    header: _('voteforms_item_thread'),
                                    dataIndex: 'thread',
                                    sortable: true,
                                //},{
                                //    header: _('voteforms_grid_actions'),
                                //    dataIndex: 'actions',
                                //    renderer: VoteForms.utils.renderActions,
                                //    sortable: false,
                                //    width: 70,
                                //    id: 'actions'
                                }];
                                configs[field.form].fields = [
                                    'resource',
                                    'thread',
                                    'actions'
                                ];
                            }
                            configs[field.form].columns.push({
                                header: field.name,
                                dataIndex: 'rating_field_' + field.id,
                                sortable: true,
                            });
                            configs[field.form].fields.push('rating_field_' + field.id)
                        });
                        return configs;
                    }
                }
            }
        }]
    });
    VoteForms.panel.Home.superclass.constructor.call(this, config);
};
Ext.extend(VoteForms.panel.Home, MODx.Panel);
Ext.reg('voteforms-panel-home', VoteForms.panel.Home);

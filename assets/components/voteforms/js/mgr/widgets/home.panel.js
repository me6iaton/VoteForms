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
            }]
        }]
    });
    VoteForms.panel.Home.superclass.constructor.call(this, config);
};
Ext.extend(VoteForms.panel.Home, MODx.Panel);
Ext.reg('voteforms-panel-home', VoteForms.panel.Home);
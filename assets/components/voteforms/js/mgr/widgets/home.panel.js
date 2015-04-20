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
                title: _('voteforms_items'),
                layout: 'anchor',
                items: [{
                    html: _('voteforms_intro_msg'),
                    cls: 'panel-desc',
                }, {
                    xtype: 'voteforms-grid-forms',
                    cls: 'main-wrapper',
                }, {
                    xtype: 'voteforms-grid-items',
                    cls: 'main-wrapper',
                }]
            }]
        }]
    });
    VoteForms.panel.Home.superclass.constructor.call(this, config);
};
Ext.extend(VoteForms.panel.Home, MODx.Panel);
Ext.reg('voteforms-panel-home', VoteForms.panel.Home);

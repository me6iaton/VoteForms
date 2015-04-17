VoteForms.page.Home = function (config) {
    config = config || {};
    Ext.applyIf(config, {
        components: [{
            xtype: 'voteforms-panel-home', renderTo: 'voteforms-panel-home-div'
        }]
    });
    VoteForms.page.Home.superclass.constructor.call(this, config);
};
Ext.extend(VoteForms.page.Home, MODx.Component);
Ext.reg('voteforms-page-home', VoteForms.page.Home);
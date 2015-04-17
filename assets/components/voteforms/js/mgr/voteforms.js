var VoteForms = function (config) {
    config = config || {};
    VoteForms.superclass.constructor.call(this, config);
};
Ext.extend(VoteForms, Ext.Component, {
    page: {}, window: {}, grid: {}, tree: {}, panel: {}, combo: {}, config: {}, view: {}, utils: {}
});
Ext.reg('voteforms', VoteForms);

VoteForms = new VoteForms();

//{block name="backend/order/controller/detail"}
//{$smarty.block.parent}
Ext.define('Shopware.apps.Order.controller.Detail.BucoAllowMultipleDocuments', {
    override: 'Shopware.apps.Order.controller.Detail',

    bucoOriginalDocumentType: null,

    /**
     * Trying to created a new document for an existing one will cause a confirmation dialog to pop up. We don't want
     * this. We don't want to overwriting it. We want to create another one of the same type. If this is allowed
     * for the desired type, we suppress the confirmation by nulling the document type ID we wanna create. As a result
     * the original Shopware logic can't determine if there is an exiting document of this type.
     *
     * The next decoration will restore the document type ID.
     *
     * @param [Ext.data.Model]          The record of the detail page (Shopware.apps.Order.model.Order)
     * @param [Ext.data.Model]          The configuration record of the document form (Shopware.apps.Order.model.Configuration)
     * @param [Ext.container.Container] me
     */
    onCreateDocument: function(order, config, panel) {
        var me = this,
            bucoMultipleAllowedDocIdsByShopId = {$bucoMultipleAllowedDocIdsByShopId|@json_encode nofilter},
            docType = !Number.isNaN(config.get('documentType')) ? config.get('documentType') : me.bucoOriginalDocumentType,
            shopId = order.get('shopId');

        try {
            if(bucoMultipleAllowedDocIdsByShopId[shopId].includes(docType)) {
                me.bucoOriginalDocumentType = docType;
                config.set('documentType', Number.NaN);
            }
        }
        catch (e) {}

        me.callParent(arguments);
    },

    /**
     * We just nulled the document type ID to suppress the confirmation message. Now the need the ID back again. So, we're
     * restoring it.
     *
     * @param [Ext.data.Model]          The record of the detail page (Shopware.apps.Order.model.Order)
     * @param [Ext.data.Model]          The configuration record of the document form (Shopware.apps.Order.model.Configuration)
     * @param [Ext.container.Container] The panel
     */
    createDocument: function(order, config, panel) {
        var me = this;

        if(Number.isNaN(config.get('documentType')) && Number.isInteger(me.bucoOriginalDocumentType)) {
            config.set('documentType', me.bucoOriginalDocumentType);
            delete me.bucoOriginalDocumentType;
        }

        me.callParent(arguments);
    }
});
//{/block}

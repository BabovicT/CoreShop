/*
 * CoreShop.
 *
 * This source file is subject to the GNU General Public License version 3 (GPLv3)
 * For the full copyright and license information, please view the LICENSE.md and gpl-3.0.txt
 * files that are distributed with this source code.
 *
 * @copyright  Copyright (c) CoreShop GmbH (https://www.coreshop.org)
 * @license    https://www.coreshop.org/license     GNU General Public License version 3 (GPLv3)
 *
 */

pimcore.registerNS('coreshop.order.order.create.step.address');
coreshop.order.order.create.step.address = Class.create(coreshop.order.order.create.abstractStep, {
    addressStore: null,

    initStep: function () {
        var modelName = 'CoreShopCreateOrderAddress';
        if (!Ext.ClassManager.isCreated(modelName)) {
            Ext.define(modelName, {
                extend: 'Ext.data.Model',
                idProperty: 'o_id'
            });
        }

        this.addressStore = new Ext.data.JsonStore({
            data: this.creationPanel.customerDetail.addresses,
            model: modelName
        });

    },

    isValid: function (parent) {
        return true;
    },

    reset: function() {
        this.panel.getForm().reset();
    },

    getPriority: function () {
        return 40;
    },

    setPreviewData: function(data) {
        this.addressStore.setData(data.customer.addresses);
    },

    getValues: function (parent) {
        return this.panel.getForm().getFieldValues();
    },

    getPanel: function () {
        this.panel = Ext.create('Ext.form.Panel', {
            layout: 'hbox',
            items: [
                this.getAddressPanelForType('shipping'),
                this.getAddressPanelForType('invoice')
            ]
        });

        return this.panel;
    },

    getIconCls: function() {
        return 'coreshop_icon_address';
    },

    getName: function () {
        return t('coreshop_order_create_address');
    },

    getAddressPanelForType: function (type) {
        var key = 'addressPanel' + type;
        var addressKey = 'address' + type;

        if (!this[key]) {
            var addressDetailPanelKey = 'addressDetailPanel' + type;

            this[addressDetailPanelKey] = Ext.create('Ext.panel.Panel', {});

            this[key] = Ext.create('Ext.panel.Panel', {
                flex: 1,
                padding: 10,
                items: [
                    {
                        xtype: 'combo',
                        fieldLabel: t('coreshop_address_' + type),
                        labelWidth: 150,
                        itemId: 'address',
                        name: type + 'Address',
                        store: this.addressStore,
                        editable: false,
                        triggerAction: 'all',
                        queryMode: 'local',
                        width: 500,
                        displayField: 'name',
                        valueField: 'o_id',
                        displayTpl: Ext.create('Ext.XTemplate', '<tpl for=".">', '{firstname} {lastname}, {postcode} {city}, {street} {number}', '</tpl>'),
                        listConfig: {
                            itemTpl: Ext.create('Ext.XTemplate', '', '{firstname} {lastname}, {postcode} {city}, {street} {number}', '')
                        },
                        listeners: {
                            change: function (combo, value) {
                                var address = this.addressStore.getById(value);

                                this[addressDetailPanelKey].removeAll();

                                if (address) {
                                    this[addressDetailPanelKey].add(this.getAddressPanelForAddress(address.data));

                                    this[addressKey] = address.data;
                                }

                                this.eventManager.fireEvent('preview');
                            }.bind(this)
                        }
                    },
                    {
                        xtype: 'button',
                        iconCls: 'pimcore_icon_add',
                        text: t('coreshop_address_create'),
                        handler: function () {
                            new coreshop.order.order.create.address(
                                {
                                    prefix: 'address.',
                                    params: {
                                        customer: this.creationPanel.customerId
                                    }
                                }, function(id) {
                                    this[key].down('#address').setValue(id);

                                    this.eventManager.fireEvent('preview');
                                }.bind(this)
                            ).show();
                        }.bind(this)
                    },
                    this[addressDetailPanelKey]
                ]
            });
        }

        return this[key];
    },

    getAddressPanelForAddress : function (address) {
        var country = pimcore.globalmanager.get('coreshop_countries').getById(address.country);

        var panel = {
            xtype: 'panel',
            dockedItems: [{
                xtype: 'toolbar',
                dock: 'top',
                items: [
                    '->',
                    {
                        iconCls: 'coreshop_icon_open',
                        text: t('open'),
                        handler: function () {
                            pimcore.helpers.openObject(address.o_id);
                        }.bind(this)
                    }
                ]
            }],
            layout: {
                type: 'hbox',
                align: 'stretch'
            },
            height: 220,
            items: [
                {
                    xtype: 'panel',
                    bodyPadding: 5,
                    html: (address.firstname ? address.firstname : '') + ' ' + (address.lastname ? address.lastname : '') + '<br/>' +
                    (address.company ? address.company + '<br/>' : '') +
                    (address.street ? address.street : '') + ' ' + (address.nr ? address.nr : '') + '<br/>' +
                    (address.zip ? address.zip : '') + ' ' + (address.city ? address.city : '') + '<br/>' +
                    (country ? country.get('name') : ''),
                    flex: 1
                }
            ]
        };

        if (pimcore.settings.google_maps_api_key) {
            panel.items.push({
                xtype: 'panel',
                html: '<img src="https://maps.googleapis.com/maps/api/staticmap?zoom=13&size=200x200&maptype=roadmap'
                + '&center=' + address.street + '+' + address.nr + '+' + address.zip + '+' + address.city + '+' + (country ? country.get('name') : '')
                + '&markers=color:blue|' + address.street + '+' + address.nr + '+' + address.zip + '+' + address.city + '+' + (country ? country.get('name') : '')
                + '&key=' + pimcore.settings.google_maps_api_key
                + '" />',
                flex: 1,
                bodyPadding: 5
            });
        }

        return panel;
    }
});

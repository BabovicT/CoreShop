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

pimcore.registerNS('coreshop.index.getters.classificationstore');

coreshop.index.getters.classificationstore = Class.create(coreshop.index.getters.abstract, {

    getLayout: function (record) {
        return [
            {
                xtype: 'textfield',
                fieldLabel: t('coreshop_index_field_classificationstore'),
                name: 'classificationStoreField',
                length: 255,
                value: record.data.getterConfig ? record.data.getterConfig.classificationStoreField : null,
                allowBlank: false
            }
        ];
    }

});

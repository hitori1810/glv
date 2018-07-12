/*********************************************************************************
 * By installing or using this file, you are confirming on behalf of the entity
 * subscribed to the SugarCRM Inc. product ("Company") that Company is bound by
 * the SugarCRM Inc. Master Subscription Agreement (“MSA”), which is viewable at:
 * http://www.sugarcrm.com/master-subscription-agreement
 *
 * If Company is not bound by the MSA, then by installing or using this file
 * you are agreeing unconditionally that Company will be bound by the MSA and
 * certifying that you have authority to bind Company accordingly.
 *
 * Copyright (C) 2004-2013 SugarCRM Inc.  All rights reserved.
 ********************************************************************************/

({
    unformat: function(value) {
        return value;
    },
    format: function(value, fieldName) {
        value = {
            street: this.model.get(this.name),
            city: this.model.get(this.formatFieldName('city')),
            postalcode: this.model.get(this.formatFieldName('postalcode')),
            state: this.model.get(this.formatFieldName('state')),
            country: this.model.get(this.formatFieldName('country'))
        };
        return value;
    },
    bindDomChange: function() {
        var self = this;
        var model = this.model;
        var fieldName = this.name;
        var street = this.$('.address_street');
        var city = this.$('.address_city');
        var country = this.$('.address_country');
        var postalcode = this.$('.address_postalcode');
        var state = this.$('.address_state');
        street.on('change', function() {
            model.set(fieldName, self.unformat(street.val()));
        });
        city.on('change', function() {
            model.set(self.formatFieldName('city'), self.unformat(city.val()));
        });
        postalcode.on('change', function() {
            model.set(self.formatFieldName('postalcode'), self.unformat(postalcode.val()));
        });
        state.on('change', function() {
            model.set(self.formatFieldName('state'), self.unformat(state.val()));
        });
        country.on('change', function() {
            model.set(self.formatFieldName('country'), self.unformat(country.val()));
        });
    },
    formatFieldName: function(attribute) {
        var endFieldName = '';
        var arrFieldName = this.name.split('_');
        if (arrFieldName[arrFieldName.length - 1] == 'c') {
            endFieldName = '_c';
            arrFieldName.pop();
        }
        if (arrFieldName[arrFieldName.length - 1] == 'street') arrFieldName.pop();
        var rootFieldName = arrFieldName.join('_');
        return rootFieldName + "_" + attribute + endFieldName;
    }
})
import FormFields from './components/fieldtypes/FormFieldsFieldtype.vue';

Statamic.booting(() => {
    Statamic.$components.register('form_fields-fieldtype', FormFields);

});
import AppForm from '../app-components/Form/AppForm';

Vue.component('wood-specie-form', {
    mixins: [AppForm],
    data: function() {
        return {
            form: {
                calculation_period:  '' ,
                main_harvesting_age:  '' ,
                timber_harvesting_age:  '' ,
                title:  '' ,

            }
        }
    }

});

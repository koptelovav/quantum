require('./bootstrap');

window.Vue = require('vue');

Vue.component('test-component', require('./components/TestComponent.vue').default);

const app = new Vue({
    el: '#app',
});

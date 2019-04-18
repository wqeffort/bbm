
require('./bootstrap');

window.Vue = require('vue');

// 引入路由管理
import VueRouter from 'vue-router';
Vue.use(VueRouter);

// 引入MintUI
import MintUI from 'mint-ui'
Vue.use(MintUI);
import 'mint-ui/lib/style.css'





import router from './routes';

new Vue({
    el: '#app',
    router: router
});

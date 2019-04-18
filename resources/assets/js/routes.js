import VueRouter from 'vue-router';

let routes = [
    {
        path: '/',
        component: require('./components/pages/Home')
    }
];

export default new VueRouter({
    // mode: 'history', // 超级大坑坑.nginx上使用了监听 无法使用try_files $uri $uri/ /index.html;
    routes: routes
})

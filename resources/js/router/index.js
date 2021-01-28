import Vue from 'vue';
import VueRouter from 'vue-router'
import Layouts from '../enums/LayoutEnums';
import DashboardPage from '../pages/app/dashboard';
import CampaignPage from '../pages/app/campaign';
import SettingsPage from '../pages/app/settings';

Vue.use(VueRouter);

const index = [
    {
        name: 'dashboard',
        path: '/',
        component: DashboardPage,
        meta: {
            layout: Layouts.APP_LAYOUT
        }
    },
    {
        name: 'campaigns',
        path: '/campaigns',
        component: CampaignPage,
        meta: {
            layout: Layouts.APP_LAYOUT
        }
    },
    {
        name: 'settings',
        path: '/settings',
        component: SettingsPage,
        meta: {
            layout: Layouts.APP_LAYOUT
        }
    }
];

export default new VueRouter({
    base: '/',
    mode: 'history',
    routes: index,
    linkActiveClass: 'active'
});

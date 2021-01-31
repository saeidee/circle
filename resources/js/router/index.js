import Vue from 'vue';
import VueRouter from 'vue-router'
import Layouts from '../enums/LayoutEnums';
import CampaignPage from '../pages/app/campaign';
import SettingsPage from '../pages/app/settings';
import DashboardPage from '../pages/app/dashboard';
import CreateCampaign from '../pages/app/campaign/Create';

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
        name: 'campaign-create',
        path: '/campaigns/create',
        component: CreateCampaign,
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
    routes: index
});

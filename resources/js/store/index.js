import Vue from 'vue';
import Vuex from 'vuex';
import { Mutation, Action } from './types';
import RouteEnums from '../enums/RouteEnums';

Vue.use(Vuex);

export default new Vuex.Store({
    state: {
        campaign: {
            campaign: '',
            subject: '',
            from: { name: '', email: '' },
            to: [{ name: '', email: '' }],
            replyTo: { name: '', email: '' },
            content: { type: '', value: '' }
        },
        campaigns: []
    },

    mutations: {
        /**
         * @name setCampaigns
         * @param state
         * @param campaigns
         */
        [Mutation.SET_CAMPAIGNS](state, campaigns) {
            state.campaigns = campaigns;
        }
    },

    actions: {
        /**
         * @name fetchPredefinedRequirements
         * @param {function} [commit]
         */
        async [Action.FETCH_CAMPAIGNS]({ commit }) {
            const { data } = await window.axios.get(RouteEnums.CAMPAIGNS_URL);

            commit(Mutation.SET_CAMPAIGNS, data.data);
        }
    }
});

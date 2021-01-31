import Vue from 'vue';
import Vuex from 'vuex';
import { Mutation, Action } from './types';
import ApiRouteEnums from '../enums/ApiRouteEnums';
import CampaignEnums from '../enums/CampaignEnums';

Vue.use(Vuex);

export default new Vuex.Store({
    state: {
        campaign: {
            name: '',
            subject: '',
            from: { name: '', email: '' },
            to: [{ name: '', email: '' }],
            replyTo: { name: '', email: '' },
            content: { type: CampaignEnums.TEXT_TYPE, value: '' }
        },
        campaigns: {
            data: [],
            meta: {}
        }
    },

    mutations: {
        /**
         * @name setCampaigns
         * @param {object} state
         * @param {object} campaigns
         */
        [Mutation.SET_CAMPAIGNS](state, campaigns) {
            state.campaigns = campaigns;
        },

        /**
         * @name setName
         * @param {object} state
         * @param {string} name
         */
        [Mutation.SET_NAME](state, name) {
            state.campaign.name = name;
        },

        /**
         * @name setSubject
         * @param {object} state
         * @param {string} subject
         */
        [Mutation.SET_SUBJECT](state, subject) {
            state.campaign.subject = subject;
        },

        /**
         * @name setFrom
         * @param {object} state
         * @param {object} from
         */
        [Mutation.SET_FROM](state, from) {
            state.campaign.from = from;
        },

        /**
         * @name setReplyTo
         * @param {object} state
         * @param {object} replyTo
         */
        [Mutation.SET_REPLAY_TO](state, replyTo) {
            state.campaign.replyTo = replyTo;
        },

        /**
         * @name setTo
         * @param {object} state
         * @param {array} recipients
         */
        [Mutation.SET_TO](state, recipients) {
            state.campaign.to = recipients;
        },

        /**
         * @name setContent
         * @param {object} state
         * @param {object} content
         */
        [Mutation.SET_CONTENT](state, content) {
            state.campaign.content = content;
        }
    },

    actions: {
        /**
         * @name fetchCampaigns
         * @param {function} [commit]
         * @param {number} page
         */
        async [Action.FETCH_CAMPAIGNS]({ commit }, page = 1) {
            const { data } = await window.axios.get(`${ApiRouteEnums.CAMPAIGNS_URL}?page=${page}`);

            commit(Mutation.SET_CAMPAIGNS, data);
        }
    }
});

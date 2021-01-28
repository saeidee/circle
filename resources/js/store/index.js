import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

export default new Vuex.Store({
    state: {
        projects: [],
        project: {'id': '', 'title': '', 'description': ''}
    },

    mutations: {
        setProjects(state, projects) {
            state.projects = projects;
        },

        deleteProjects({projects}, id) {
            projects.data.splice(projects.data.indexOf(projects.data.find(project => project.id === id)), 1);
        },
    },

    actions: {
        fetchProjects(context) {
            axios.get('/dashboard/projects')
                .then(response => {
                    context.commit('setProjects', response.data)
                });
        },

        fetchNextProjects(context) {
            axios.get(this.state.projects.next_page_url)
                .then(response => {
                    context.commit('setProjects', response.data)
                });
        },

        fetchPreviousProjects(context) {
            axios.get(this.state.projects.prev_page_url)
                .then(response => {
                    context.commit('setProjects', response.data)
                });
        },
    }
});

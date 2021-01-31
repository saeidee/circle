<template>
    <div class="mb-5 w-100">
        <dashboard
            :failed-count="stats.failed"
            :queued-count="stats.queued"
            :sent-count="stats.sent"/>
    </div>
</template>

<script>
    import Dashboard from '../../../components/Dashboard';
    import ApiRouteEnums from '../../../enums/ApiRouteEnums';

    export default {
        name: 'DashboardPage',

        components: {
            Dashboard
        },

        data () {
            return {
                stats: {
                    failed: 0,
                    queued: 0,
                    sent: 0
                }
            };
        },

        beforeMount() {
            this.fetchStats();
        },

        methods: {
            /**
             * @return {void}
             */
            async fetchStats () {
                const { data } = await window.axios.get(ApiRouteEnums.STATS_URL);
                this.stats = data;
            }
        }
    }
</script>

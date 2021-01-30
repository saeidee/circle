<template>
    <div class="mb-5 w-100">
        <create-campaign :campaigns="campaigns"/>
    </div>
</template>

<script>
    import { mapState, mapActions } from 'vuex';
    import CreateCampaign from '../../../components/CreateCampaign';

    export default {
        name: 'CreateCampaignPage',

        components: {
            CreateCampaign
        },

        computed: {
            ...mapState(['campaigns'])
        },

        methods: {
            ...mapActions(['fetchCampaigns'])
        }
    }
</script>

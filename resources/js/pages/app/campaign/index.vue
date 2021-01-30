<template>
    <div class="mb-5 w-100">
        <campaigns :campaigns="campaigns"/>
    </div>
</template>

<script>
import { mapState, mapActions } from 'vuex';
import Campaigns from '../../../components/Campaigns';

export default {
    name: 'CampaignsPage',

    components: {
        Campaigns
    },

    computed: {
        ...mapState(['campaigns'])
    },

    beforeMount() {
        this.fetchCampaigns()
    },

    methods: {
        ...mapActions(['fetchCampaigns'])
    }
}
</script>

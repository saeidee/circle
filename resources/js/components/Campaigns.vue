<template>
    <div>
        <div class="w-100">
            <div class="d-flex justify-content-sm-between">
                <h4 class="mb-0 font-weight-bold">Campaigns</h4>
                <div class="mb-0 font-weight-bold">
                    <b-button variant="link" :to="{ name: 'campaign-create' }">
                        <b-icon icon="plus"/> New Campaign
                    </b-button>
                </div>
            </div>
        </div>
        <div class="my-5 rounded-items base-shadow">
            <b-table hover :items="preparedCampaigns" :fields="fields"></b-table>
        </div>
        <b-pagination
            :value="paginationMeta.current_page"
            :total-rows="paginationMeta.total"
            :per-page="paginationMeta.per_page"
            @change="page => $emit('pageChanged', page)"
        ></b-pagination>
    </div>
</template>

<script>
    import CampaignEnums from '../enums/CampaignEnums';

    export default {
        name: 'Campaigns',

        props: {
            /**
             * @property {array} campaigns
             */
            campaigns: {
                type: Array,
                required: true
            },

            /**
             * @property {Object} paginationMeta
             */
            paginationMeta: {
                type: Object,
                required: true
            }
        },

        computed: {
            /**
             * @return {array}
             */
            preparedCampaigns () {
                return this.campaigns.map(campaign => {
                    return { ...campaign, _cellVariants: { status: this.getStatusIndicator(campaign.status) }}
                })
            }
        },

        data () {
            return {
                fields: [
                    {
                        key: 'uuid',
                        label: 'Uuid'
                    },
                    {
                        key: 'name',
                        label: 'Campaign'
                    },
                    {
                        key: 'type',
                        label: 'Type'
                    },
                    {
                        key: 'status',
                        label: 'Status',
                    },
                    {
                        key: 'provider',
                        label: 'Provider'
                    }
                ]
            };
        },

        methods: {
            /**
             * @param {string} status
             * @return {string}
             */
            getStatusIndicator (status) {
                switch (status) {
                    case CampaignEnums.QUEUED:
                        return 'warning';
                    case CampaignEnums.FAILED:
                        return 'danger';
                    default:
                        return 'success';
                }
            }
        }
    }
</script>

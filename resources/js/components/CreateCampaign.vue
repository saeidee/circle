<template>
    <div>
        <div class="w-100">
            <div class="d-flex align-items-baseline">
                <h4 class="mb-0 font-weight-bold">Create Campaign</h4>
            </div>
        </div>
        <div class="my-5 rounded-items base-shadow">

        </div>
    </div>
</template>

<script>
    export default {
        name: 'CreateCampaign',

        props: {
            /**
             * @property {string} name
             */
            name: {
                type: String,
                required: true
            }
        },

        computed: {

        },

        data () {
            return {

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

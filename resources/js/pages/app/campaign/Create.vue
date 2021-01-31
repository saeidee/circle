<template>
    <div class="mb-5 w-100">
        <create-campaign
            :name="campaign.name"
            :subject="campaign.subject"
            :from="campaign.from"
            :reply-to="campaign.replyTo"
            :to="campaign.to"
            :content="campaign.content"
            @setName="setName"
            @setSubject="setSubject"
            @setFrom="setFrom"
            @setReplyTo="setReplyTo"
            @setTo="setTo"
            @setContent="setContent"
            @create="createCampaign"
        />
    </div>
</template>

<script>
    import { mapState, mapMutations } from 'vuex';
    import ApiRouteEnums from '../../../enums/ApiRouteEnums';
    import CampaignEnums from '../../../enums/CampaignEnums';
    import CreateCampaign from '../../../components/CreateCampaign';

    const EMPTY = '';

    export default {
        name: 'CreateCampaignPage',

        components: {
            CreateCampaign
        },

        computed: {
            ...mapState(['campaign'])
        },

        data () {
            return {
                emptyContact: { name: '', email: '' }
            };
        },

        methods: {
            ...mapMutations([
                'setName',
                'setSubject',
                'setFrom',
                'setReplyTo',
                'setTo',
                'setContent'
            ]),

            /**
             * @return void
             */
            async createCampaign () {
                try {
                    await window.axios.post(
                        ApiRouteEnums.CREATE_CAMPAIGNS_URL,
                        {...this.campaign, campaign: this.campaign.name}
                    );

                    this.$bvToast.toast(
                        'Successfully campaign created',
                        {
                            title: 'Success',
                            variant: 'success',
                            solid: true
                        }
                    );

                    this.resetCampaignData();
                } catch (error) {
                    this.$bvToast.toast(
                        'Something went wrong please try again.',
                        {
                            title: 'Warning',
                            variant: 'danger',
                            solid: true
                        }
                    );
                }
            },

            /**
             * @return {void}
             */
            resetCampaignData () {
                this.setName(EMPTY);
                this.setSubject(EMPTY);
                this.setFrom(this.emptyContact);
                this.setReplyTo(this.emptyContact);
                this.setTo([this.emptyContact]);
                this.setContent({ value: EMPTY, type: CampaignEnums.TEXT_TYPE });
            }
        }
    }
</script>

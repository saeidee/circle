<template>
    <div>
        <div class="w-100">
            <div class="d-flex align-items-baseline">
                <h4 class="mb-0 font-weight-bold">Dashboard</h4>
            </div>
        </div>
        <div class="my-5 rounded-items base-shadow">
            <b-row no-gutters>
                <b-col
                    v-for="(stat, index) in preparedStats"
                    :key="index"
                    md="4">
                    <b-card
                        style="max-width: 25rem;"
                        class="mb-2">
                        <b-card-text class="mt-3">
                            <b-card-title>
                                <b-icon :icon="stat.icon" :scale="stat.scale" :variant="stat.variant"></b-icon>
                                <span class="ml-2">{{ stat.title }}</span>
                            </b-card-title>
                            <span class="font-bold">{{ stat.count }}</span>
                        </b-card-text>
                    </b-card>
                </b-col>
            </b-row>
            <hr/>
            <h4> Circuits </h4>
            <b-row no-gutters>
                <b-col
                    v-for="(stat, index) in preparedCircuitStat"
                    :key="index"
                    md="4">
                    <b-card
                        style="max-width: 25rem;"
                        class="mb-2">
                        <b-card-text class="mt-3">
                            <b-card-title>
                                <b-icon :icon="stat.icon" :scale="stat.scale" :variant="stat.variant"></b-icon>
                                <span class="ml-2">{{ stat.title }}</span>
                            </b-card-title>
                        </b-card-text>
                    </b-card>
                </b-col>
            </b-row>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'Dashboard',

        props: {
            /**
             * @property {Number} queuedCount
             */
            queuedCount: {
                type: Number,
                required: true
            },

            /**
             * @property {Number} sentCount
             */
            sentCount: {
                type: Number,
                required: true
            },

            /**
             * @property {Number} failedCount
             */
            failedCount: {
                type: Number,
                required: true
            },

            /**
             * @property {Number} sendGridCircuit
             */
            sendGridCircuit: {
                type: Boolean,
                required: true
            },

            /**
             * @property {Number} mailJetCircuit
             */
            mailJetCircuit: {
                type: Boolean,
                required: true
            },
        },

        computed: {
            /**
             * @return {array}
             */
            preparedStats () {
                return [
                    {
                        title: 'Sent',
                        variant: 'success',
                        icon: 'check',
                        scale: 2,
                        count: this.sentCount
                    },
                    {
                        title: 'Queued',
                        variant: 'warning',
                        scale: 1,
                        icon: 'exclamation-triangle-fill',
                        count: this.queuedCount
                    },
                    {
                        title: 'Failed',
                        variant: 'danger',
                        scale: 1,
                        icon: 'exclamation-circle-fill',
                        count: this.failedCount
                    }
                ]
            },

            /**
             * @return {array}
             */
            preparedCircuitStat () {
                return [
                    {
                        title: 'SendGrid',
                        variant: this.sendGridCircuit ? 'danger' : 'success',
                        scale: 1,
                        icon: this.sendGridCircuit ? 'dash-circle-fill' : 'circle-fill',
                    },
                    {
                        title: 'Mail Jet',
                        variant: this.mailJetCircuit ? 'danger' : 'success',
                        scale: 1,
                        icon: this.mailJetCircuit ? 'dash-circle-fill' : 'circle-fill',
                    }
                ]
            }
        }
    }
</script>

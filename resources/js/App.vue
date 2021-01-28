<template>
    <div>
        <component :is="layout"/>
    </div>
</template>

<script>
    import AppLayout from './layouts/App'

    export default {
        name: 'App',

        components: {
            AppLayout
        },

        computed: {
            /**
             * @return {string}
             */
            layout () {
                return this.$route.meta.layout;
            }
        }
    }
</script>

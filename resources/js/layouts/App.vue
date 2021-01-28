<template>
    <div>
        <div class="container-fluid">
            <div class="row">
                <sidebar></sidebar>

                <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-0">
                    <navbar></navbar>

                    <div
                        class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center py-5 px-5 w-100">
                        <router-view/>
                    </div>
                </main>
            </div>
        </div>
    </div>
</template>

<script>
    import sidebar from './Sidebar';
    import navbar from './Navbar';

    export default {
        components: { sidebar, navbar },

        name: 'App-Layout'
    }
</script>

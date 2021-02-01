<template>
    <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-white sidebar collapse">
        <div class="sidebar-sticky">
            <div class="bg-white shadow-sm position-sticky">
                <div class="d-flex justify-content-between py-3 align-items-center pl-3">
                    <div class="px-2">
                        <h3 class="font-weight-bold p-0 m-0">
                            <router-link class="mx-1" :to="{ name: 'dashboard' }">Circle</router-link>
                        </h3>
                    </div>
                </div>
            </div>
            <ul class="nav flex-column pt-4 px-3">
                <li class="nav-item">
                    <router-link
                        :to="{ name: 'dashboard' }"
                        class="nav-link rounded d-flex align-items-center line-height-0 py-2-5">
                        <b-icon icon="bar-chart-fill"></b-icon>
                        <span class="ml-3">Dashboard</span>
                    </router-link>
                </li>

                <li class="nav-item">
                    <router-link
                        :to="{ name: 'campaigns' }"
                        class="nav-link rounded d-flex align-items-center line-height-0 py-2-5">
                        <b-icon icon="envelope"></b-icon>
                        <span class="ml-3">Campaigns</span>
                    </router-link>
                </li>
            </ul>
        </div>
    </nav>
</template>

<style scoped>
    body {
        font-size: .875rem;
    }
    .py-2-5{
        padding: .85rem;
    }
    .sidebar {
        position: fixed;
        top: 0;
        bottom: 0;
        left: 0;
        z-index: 100;
        padding: 0;
    }

    @media (max-width: 767.98px) {
        .sidebar {
            top: 5rem;
        }
    }

    .sidebar-sticky {
        position: relative;
        top: 0;
        height: 100vh;
        overflow-x: hidden;
        overflow-y: auto; /* Scrollable contents if viewport is shorter than content. */
    }

    @supports ((position: -webkit-sticky) or (position: sticky)) {
        .sidebar-sticky {
            position: -webkit-sticky;
            position: sticky;
        }
    }

    .sidebar .nav-link {
        font-weight: 600;
        color: #5e5e5e;
        font-size: 1rem;
    }

    .sidebar .nav-link.active {
        color: #007bff;
    }
</style>

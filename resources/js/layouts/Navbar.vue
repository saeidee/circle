<template>
    <div class="bg-white shadow-sm position-sticky top-0 nav-height text-dark z-index">
        <div class="d-flex justify-content-between h-100 align-items-center">
            <div class="d-flex border-left px-4">
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'Navbar'
    }
</script>

<style scoped>
    .nav-height {
        height: 62px;
    }

    .top-0 {
        top: 0;
    }

    .z-index {
        z-index: 1;
    }

    .size-5 {
        height: 1rem;
        width: 1rem;
    }

    .footer-alignment {
        padding-left: 72px;
    }
</style>

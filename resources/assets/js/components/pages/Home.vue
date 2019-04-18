<template>
    <div>
        <top-menu></top-menu>
    </div>
    <div>
        <foot-bar></foot-bar>
    </div>
</template>

<script>

    import TopMenu from '../tools/TopMenu.vue';
    import FootBar from '../tools/FootBar.vue';
    export default {
        components : {
            TopMenu,
            FootBar
        }
    }
</script>

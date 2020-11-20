<template>
    <NewsFeed />
</template>

<script>
import NewsFeed from '../../NewsFeed.vue';

export default {
    name: "HomePage",
    components: {
        NewsFeed
    }
}
</script>

<style lang="scss" scoped>
    h1 {
        margin-top: 50px;
        text-align: center;
    }
</style>
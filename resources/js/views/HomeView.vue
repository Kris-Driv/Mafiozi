<template>
    <div class="home-view">
        <NewsFeed />
    </div>
</template>

<script>
import NewsFeed from '../components/NewsFeed.vue';

export default {
    name: "HomeView",
    data() {
        return {test: true}
    },
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
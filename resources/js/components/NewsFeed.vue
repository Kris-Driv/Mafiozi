<template>
    <div class="news-feed">
        <header><h3 id="big-title">Mafiozi</h3></header>
    </div>
</template>

<script>
export default {
    name: "NewsFeed"
}
</script>

<style lang="scss" scoped>
.news-feed {
    width: 100%;
    height: 80vh;

    #big-title {
        font-family: 'Josefin Sans', sans-serif;
        color: #fff;
        text-shadow: 0 3px 4px #0f0f0f;
        font-size: 3em;
        letter-spacing: 15px;
        font-weight: 100;
    }
}
</style>
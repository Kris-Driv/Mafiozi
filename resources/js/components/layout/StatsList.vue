<template>
    <div class="stats-list">
        <ul class="progress-list">
            <li class="progress-list__item">
                <div class="health">
                    <span class="symbol"><i class="fa fa-heart" aria-hidden="true"></i></span>
                    <span class="value">{{ this.$store.state.stats.health.value || '...' }}</span>
                </div>
            </li>
            <li class="progress-list__item">
                <div class="energy">
                    <span class="symbol"><i class="fa fa-bolt" aria-hidden="true"></i></span>
                    <span class="value">{{ this.$store.state.stats.energy.value || '...' }}</span>
                </div>
            </li> 
            <li class="progress-list__item">
                <div class="xp">
                    <span class="symbol"><i class="fa fa-certificate" aria-hidden="true"></i></span>
                    <span class="value">{{ this.$store.state.stats.xp.value }}</span>
                </div>
            </li> 
        </ul>
    </div>
</template>

<script>
import ProgressCircle from '../ProgressCircle.vue';

export default {
    name: "StatsList",
    components: {
        ProgressCircle
    },
}
</script>

<style lang="scss" scoped>
.stats-list {
    width: 100%;
    height: 100%;
    display: flex;
    flex-flow: column;
    justify-content: flex-start;
    align-items: center;
}
.value {
    float: right;
}
.symbol {
    float: left;
}
.progress-list {
    width: 90%;

    &__item {
        list-style: none;
        padding-top: 5px;
        padding-bottom: 5px;
        min-height: 30px;
        border-bottom: 1px solid #fff;
    }
}
</style>